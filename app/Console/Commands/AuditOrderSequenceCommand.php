<?php

namespace App\Console\Commands;

use App\Models\UserOrderSet;
use Illuminate\Console\Command;

class AuditOrderSequenceCommand extends Command
{
    protected $signature = 'orders:audit-sequence
                            {--user_id= : Only audit this user ID}
                            {--set_id= : Only audit this user_order_sets ID}
                            {--show=25 : Maximum mismatched sets to display}';

    protected $description = 'Audit historical order sequence mismatches (e.g. package 16-25 before 1-15)';

    public function handle(): int
    {
        $userId = $this->option('user_id');
        $setId = $this->option('set_id');
        $showLimit = max(1, (int) $this->option('show'));

        $query = UserOrderSet::query()
            ->with([
                'user:id,username,phone,email',
                'orders' => function ($q) {
                    $q->select('id', 'user_order_set_id', 'product_package_item_id', 'created_at')
                        ->orderBy('created_at', 'asc')
                        ->orderBy('id', 'asc');
                },
                'orders.productPackageItem:id,product_package_id',
            ]);

        if (!empty($userId)) {
            $query->where('user_id', (int) $userId);
        }

        if (!empty($setId)) {
            $query->where('id', (int) $setId);
        }

        $totalSets = 0;
        $checkedSets = 0;
        $mismatchCount = 0;
        $rows = [];

        $query->orderBy('id')->chunkById(200, function ($sets) use (&$totalSets, &$checkedSets, &$mismatchCount, &$rows, $showLimit) {
            foreach ($sets as $set) {
                $totalSets++;

                $orders = $set->orders;
                if ($orders->count() <= 1) {
                    continue;
                }

                $packageIds = $orders
                    ->map(function ($order) {
                        return optional($order->productPackageItem)->product_package_id;
                    })
                    ->filter()
                    ->values();

                if ($packageIds->count() <= 1) {
                    continue;
                }

                $checkedSets++;

                $expected = $packageIds->sort()->values();
                $isMismatch = $packageIds->all() !== $expected->all();

                if ($isMismatch) {
                    $mismatchCount++;

                    if (count($rows) < $showLimit) {
                        $rows[] = [
                            'set_id' => $set->id,
                            'user_id' => $set->user_id,
                            'user' => $set->user->username ?? $set->user->phone ?? $set->user->email ?? ('User#' . $set->user_id),
                            'orders' => $orders->count(),
                            'actual' => $this->compactList($packageIds->all()),
                            'expected' => $this->compactList($expected->all()),
                        ];
                    }
                }
            }
        });

        $this->newLine();
        $this->info('Order sequence audit completed.');
        $this->line('Total user order sets scanned: ' . $totalSets);
        $this->line('Sets with 2+ sequence candidates: ' . $checkedSets);
        $this->line('Mismatched sets found: ' . $mismatchCount);

        if (!empty($rows)) {
            $this->newLine();
            $this->warn('Showing first ' . count($rows) . ' mismatched sets:');
            $this->table(
                ['Set ID', 'User ID', 'User', 'Orders', 'Actual Package Order', 'Expected Order'],
                array_map(function ($row) {
                    return [
                        $row['set_id'],
                        $row['user_id'],
                        $row['user'],
                        $row['orders'],
                        $row['actual'],
                        $row['expected'],
                    ];
                }, $rows)
            );
        }

        if ($mismatchCount === 0) {
            $this->info('No historical sequence mismatch detected.');
        } else {
            $this->comment('Tip: run with --set_id=<id> for a specific case.');
        }

        return self::SUCCESS;
    }

    private function compactList(array $values): string
    {
        if (empty($values)) {
            return '-';
        }

        $stringValues = array_map(static fn($value) => (string) $value, $values);

        if (count($stringValues) > 12) {
            $head = array_slice($stringValues, 0, 6);
            $tail = array_slice($stringValues, -4);
            return implode(',', $head) . ',...,' . implode(',', $tail);
        }

        return implode(',', $stringValues);
    }
}
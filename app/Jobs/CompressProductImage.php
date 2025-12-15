<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CompressProductImage implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $productId) {}

    public function handle(): void
    {
        $product = Product::find($this->productId);

        if (!$product || !$product->image) {
            return;
        }

        // Call the helper to generate compressed image (async, won't block frontend)
        optimize_image_path($product->image, 600, 50);
    }
}

<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SessionRevocationService
{
    public function revokeForUser(User $user): void
    {
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();

        $user->forceFill([
            'remember_token' => Str::random(60),
        ])->save();
    }
}

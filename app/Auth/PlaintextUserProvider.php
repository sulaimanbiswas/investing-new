<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PlaintextUserProvider extends EloquentUserProvider
{
    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct(HasherContract $hasher, $model)
    {
        parent::__construct($hasher, $model);
    }

    /**
     * Validate a user against the given credentials.
     * 
     * Supports both plaintext passwords (new) and hashed passwords (old/legacy).
     * This provides backward compatibility during migration.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plainPassword = $credentials['password'];
        $storedPassword = $user->getAuthPassword();

        // Debug logging
        Log::debug('Auth attempt', [
            'user_id' => $user->id,
            'username' => $user->username,
            'password_length' => strlen($plainPassword),
            'stored_password_length' => strlen($storedPassword ?? ''),
            'is_bcrypt' => strpos($storedPassword ?? '', '$2') === 0,
            'stored_password_preview' => substr($storedPassword ?? '', 0, 20),
        ]);

        // Handle null stored password
        if (empty($storedPassword)) {
            Log::debug('Auth failed: no stored password');
            return false;
        }

        // First try plaintext comparison (new system)
        if ($plainPassword === $storedPassword) {
            Log::debug('Auth success: plaintext match');
            return true;
        }

        // If plaintext fails, try hash comparison for backward compatibility (old system)
        // Check if stored password looks like a bcrypt hash (starts with $2)
        if (strpos($storedPassword, '$2') === 0) {
            // It's a hashed password, use Hash::check for backward compatibility
            if (Hash::check($plainPassword, $storedPassword)) {
                Log::debug('Auth success: hash match');
                // Successfully authenticated with old hash
                // Optionally: Update password to plaintext for next login
                // $user->update(['password' => $plainPassword]);
                return true;
            }
        }

        Log::debug('Auth failed: no match found');
        return false;
    }
}

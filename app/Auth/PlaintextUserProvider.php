<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

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
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plainPassword = $credentials['password'];
        $storedPassword = $user->getAuthPassword();

        // Handle null stored password
        if (empty($storedPassword)) {
            return false;
        }

        return $plainPassword === $storedPassword;
    }

    /**
     * Disable Laravel's automatic rehash-on-login behavior for plaintext passwords.
     */
    public function rehashPasswordIfRequired(UserContract $user, array $credentials, bool $force = false): void
    {
        // Intentionally no-op: this app stores passwords in plaintext by requirement.
    }
}

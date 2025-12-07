<?php

namespace Database\States;

use App\Enums\Role;
use App\Models\User;

class UserState
{
    public function __invoke()
    {
        if (! User::query()->where('email', 'dennis.koch@bluedom.ch')->exists()) {
            User::create([
                'name' => 'Dennis Koch',
                'email' => 'dennis.koch@bluedom.ch',
                'role' => Role::Admin,
                'password' => '$2y$12$n2M9BZF0E70JULXymFWDC.XZcPazYzMECMbLh4tL2Od6tJvYfQDZK',
                'email_verified_at' => now(),
            ]);
        }
    }
}

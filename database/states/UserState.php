<?php

namespace Database\States;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserState
{
    public function __invoke()
    {
        if (! User::query()->where('email', 'dennis.koch@bluedom.ch')->exists()) {
            User::create([
                'name' => 'Dennis',
                'email' => 'dennis.koch@bluedom.ch',
                'password' => Hash::make('vNULVCyBG3DLCZDjMQtQ37JkGYfM6DyBiKznV67L'),
            ]);
        }
    }
}

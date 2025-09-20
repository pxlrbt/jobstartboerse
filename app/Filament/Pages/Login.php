<?php

namespace App\Filament\Pages;

class Login extends \Filament\Auth\Pages\Login
{
    public function mount(): void
    {
        parent::mount();

        if (! app()->isProduction()) {
            $this->form->fill([
                'email' => 'admin@jobstartboerse.de',
                'password' => 'password',
                'remember' => true,
            ]);
        }
    }
}

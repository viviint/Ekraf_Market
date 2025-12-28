<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Register;

class CustomRegister extends Register
{
    protected function getRedirectUrl(): string
    {
        return route('home');
    }
}

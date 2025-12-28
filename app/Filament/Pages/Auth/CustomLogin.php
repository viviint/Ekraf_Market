<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login;
use Filament\Facades\Filament;

class CustomLogin extends Login
{
    protected function getRedirectUrl(): string
    {
        $user = Filament::auth()->user();

        if ($user && $user->role === 'admin') {
            return Filament::getPanel('admin')->getUrl();
        }

        return route('home');
    }
}

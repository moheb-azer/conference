<?php

use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return redirect()->route('filament.user.auth.login');
});

<?php

namespace App\Http\Middleware;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Filament\Pages\Dashboard;
use Illuminate\Support\Facades\Auth;

class RedirectToUserPanelMiddleware
{
    public function handle(Request $request, Closure $next)
    {
//        $user =auth()->user()->is_admin;
//        dd($user);
        if (auth()->check() && !auth()->user()->is_admin) {

            return redirect()->to(Dashboard::getUrl(panel: 'user'));
        }
        return $next($request);
    }
}

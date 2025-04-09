<?php

namespace App\Http\Middleware;
use App\Models\Member;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Filament\Pages\Dashboard;
use Illuminate\Support\Facades\Auth;

class RedirectToCreateProfileMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Member::query()->where('user_id',Auth::id())->where('hasLogin', Auth::id())->first();
        if($request->routeIs('filament.user.auth.logout')){
            return $next($request);
        }elseif(
            auth()->check() &&
            !$user &&
            !$request->routeIs('filament.user.resources.members.create')
        ) {
            return redirect()->route('filament.user.resources.members.create');
        }
        return $next($request);
    }
}

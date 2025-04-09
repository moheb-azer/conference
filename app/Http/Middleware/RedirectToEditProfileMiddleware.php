<?php

namespace App\Http\Middleware;
use App\Models\Member;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Filament\Pages\Dashboard;
use Illuminate\Support\Facades\Auth;

class RedirectToEditProfileMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $member = Member::query()->where('user_id',Auth::id())->where('hasLogin', Auth::id())->first();
        if(
            auth()->check() &&
            $member &&
            $request->routeIs('filament.user.resources.members.create',Auth::id())
        ) {
            return redirect('user/members/'.$member->id);
        }
        elseif (
            auth()->check() &&
            $member &&
            $request->routeIs('filament.user.resources.members.index')
        ){
            return redirect('user/members/create');
        }
        return $next($request);
    }
}

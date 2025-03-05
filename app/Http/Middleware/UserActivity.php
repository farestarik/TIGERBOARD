<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $expiresAt = Carbon::now()->addMinutes(3); // keep online for 3 min
            Cache::put(Auth::user()->id . '_is_online', true, $expiresAt);
            // last seen
            if (Schema::hasColumn('users', 'last_seen')) {
                User::where('id', Auth::user()->id)->update(['last_seen' => (new \DateTime())->format("Y-m-d H:i:s")]);
            }
        }

        return $next($request);
    }
}
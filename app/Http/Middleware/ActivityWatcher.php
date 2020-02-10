<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ActivityWatcher
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
        $user_id = Auth::user()->id;
        $user_ip = $request->ip();
        $link_uri = $request->url();
        $post_data = $request->isMethod('post') ? json_encode($request->all()) : NULL;

        $logActivity = DB::table('users_log')->insert([
                                'user_id' => $user_id,
                                'user_ip' => $user_ip,
                                'link_uri' => $link_uri,
                                'post_data' => $post_data,
                            ]);

        return $next($request);
    }
}

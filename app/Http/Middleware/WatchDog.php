<?php

namespace App\Http\Middleware;

use Closure;
use App\System\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class WatchDog
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
        $response = $next($request);

        $subject_id = $request->user()->id;
        $is_admin = Auth::guard('web')->check();

        $user_id = $is_admin ? $subject_id : NULL;
        $client_id = $is_admin ? NULL : $subject_id;
        $user_type = $is_admin ? 'admin' : 'client';
        $user_ip = $request->ip();
        $link_uri = $request->url();
        $post_data = $request->isMethod('post') ? json_encode($request->except('_token')) : NULL;
        $action = Route::currentRouteName();
        $status = $response->getStatusCode();

        $logActivity = ActivityLog::create([
                                'user_id' => $user_id,
                                'client_id' => $client_id,
                                'user_type' => $user_type,
                                'user_ip' => $user_ip,
                                'link_uri' => $link_uri,
                                'post_data' => $post_data,
                                'action' => $action,
                                'status' => $status,
                            ]);
        return $response;

    }
}

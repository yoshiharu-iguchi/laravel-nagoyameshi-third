<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Subscribed
{
    /**
     * 受信リクエストの処理
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

    if ($user instanceof \App\Models\Admin) {
        return redirect()->route('admin.home');
    }

    if (! $user || ! method_exists($user, 'subscribed') || ! $user->subscribed('premium_plan')) {
    return redirect()->route('subscription.create');

    }

        return $next($request);
    }
}
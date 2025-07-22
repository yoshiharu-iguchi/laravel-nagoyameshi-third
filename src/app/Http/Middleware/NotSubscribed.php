<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotSubscribed
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

    // ログインしていて、subscribed() メソッドを持ち、有料プランに加入している場合
    if ($user && method_exists($user, 'subscribed') && $user->subscribed('premium_plan')) {
        // すでに加入済み → 編集ページにリダイレクト
        return redirect('subscription/edit');
    }

        return $next($request);
    }
}
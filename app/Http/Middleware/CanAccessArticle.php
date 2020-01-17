<?php

namespace App\Http\Middleware;

use Closure;

class CanAccessArticle
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
        $user = $request->user();
        $articleId = $request->route()->article;

        
        // echo '로그인 아이디 '.$user;
        // echo "<br> 게시글 작성자 아이디" . $articleId;
        // dd($request->route());
        // exit;
        //
        // if (!($user->hasRole('admin') or $user->id == $articleId)) {
        if (!($user->hasRole('admin') or $user->hasRole('user'))) {
            //TODO if문 수정
            flash()->error(trans('errors.forbidden') . ' : ' . trans('errors.forbidden_description'));

            return back();
        }

        return $next($request);
    }
}

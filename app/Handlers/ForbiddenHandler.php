<?php

namespace App\Handlers;

use Artesaos\Defender\Exceptions\ForbiddenException;
use Artesaos\Defender\Contracts\ForbiddenHandler as ForbiddenHandlerContract;
use Flash;
use JWTAuth;

class ForbiddenHandler implements ForbiddenHandlerContract
{
    public function handle()
    {
        if (!request()->ajax()) {
            
            if (strpos(request()->server('HTTP_REFERER'), 'login') !== FALSE) {
               
               if ($user = auth()->user()) {
                    try {
                        JWTAuth::invalidate($user->jwt_token);
                    } catch(\Exception $e) {}

                    $user->jwt_token = '';
                    $user->save();
                }

                auth()->guard()->logout();
                request()->session()->flush();
                request()->session()->regenerate();
                flash('Você não possuí as permissões necessárias para acessar este recurso', 'error');
                return redirect('/login');
            }

            flash('Você não possuí as permissões necessárias para acessar este recurso', 'error');
            return redirect('/');

        } else {
            return response()->json([
                'error' => 'Você não possuí as permissões necessárias para acessar este recurso'
            ], 403);
        }
    }
}

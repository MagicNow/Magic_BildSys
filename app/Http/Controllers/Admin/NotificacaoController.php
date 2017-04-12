<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 10/04/2017
 * Time: 13:07
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AppBaseController;
use App\Models\Notificacao;
use Illuminate\Http\Request;

class NotificacaoController extends AppBaseController
{
    /**
     * Update the specified Notification in storage.
     *
     * @param  int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function updateNotification($id)
    {
        $notification = Notificacao::find($id);
        if($notification){
            $notification->read_at = date('Y-m-d H:i:s');
            $notification->update();
        }
        return response()->json(['success'=>true]);
    }
}
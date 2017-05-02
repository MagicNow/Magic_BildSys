<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notificacao;
use App\Repositories\Admin\ValidationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

class HomeController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.home');
    }

    public static function verifyNotifications(){
        $notifications = Notificacao::where('notifiable_id', '=', \Auth::user()->id)
            ->whereNull('read_at')
            ->get();

        foreach($notifications as $notification){
            $data = json_decode($notification->data);
            foreach ($data as $key => $value) {
                $notification[$key] = $value;
            }
        }
        return $notifications;
    }

    public function validaCnpj(Request $request){
        $validator = ValidationRepository::validaCnpj($request->numero,$request->cpf);

        $validator->validate();

        // verifica se já não existe o documento com outra pessoa
        $documentoUnico = ValidationRepository::CnpjUnico($request->numero);
        if(!$documentoUnico){
            return response()->json(['success'=>false,'erro'=>'CNPJ já cadastrado na base!'],422);
        }
        return response()->json(['success'=>true]);
    }
}

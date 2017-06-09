<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PerfilSaveRequest;
use App\Repositories\Admin\UserRepository;
use Illuminate\Support\Facades\Hash;
use Laracasts\Flash\Flash;

class PerfilController extends AppBaseController
{
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        return view('perfil.index', ['user' => $request->user()]);
    }

    public function save(PerfilSaveRequest $request)
    {
        $user = $this->userRepository->find(auth()->id());

        if( $request->has('current_password') &&
            !Hash::check($request->current_password, $user->password)
        ) {
            Flash::error('A senha atual informada está incorreta!');

            return back()->withInput();
        }

        $request->merge(array_merge($user->toArray(), $request->all()));

        $this->userRepository->update($request->all(), auth()->id());

        Flash::success('Seus dados foram atualizados com sucesso!');
        return redirect('/perfil');
    }
}

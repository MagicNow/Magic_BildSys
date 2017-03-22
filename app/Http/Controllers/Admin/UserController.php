<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Repositories\Admin\UserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(UserDataTable $userDataTable)
    {
        return $userDataTable->render('admin.users.index');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created Usuário in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();
        $input['active'] = intval($request->get('active'));

        $user = $this->userRepository->create($input);

        Flash::success('Usuário '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Usuário '.trans('common.not-found'));

            return redirect(route('users.index'));
        }

        return view('admin.users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Usuário '.trans('common.not-found'));

            return redirect(route('users.index'));
        }

        return view('admin.users.edit')->with('user', $user);
    }

    /**
     * Update the specified Usuário in storage.
     *
     * @param  int              $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Usuário '.trans('common.not-found'));

            return redirect(route('users.index'));
        }
        $input = $request->all();
        $input['active'] = intval($request->get('active'));

        $user = $this->userRepository->update($input, $id);

        Flash::success('Usuário '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified Usuário from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Usuário '.trans('common.not-found'));

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('Usuário '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('users.index'));
    }
}

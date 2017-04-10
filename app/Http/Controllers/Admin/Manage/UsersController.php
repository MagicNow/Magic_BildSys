<?php

namespace App\Http\Controllers\Admin\Manage;

use App\DataTables\UserDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Repositories\Admin\UserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;
use App\Models\User;
use Defender;

/**
 * Class UsersController
 * @package App\Http\Controllers\Admin\Manage
 */
class UsersController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    /**
     * UsersController constructor.
     * @param UserRepository $userRepo
     */
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
        $filters = User::$filters;
        return $userDataTable->render('admin.manage.users.index', compact('filters'));
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.manage.users.create');
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

        Flash::success('Usuário ' . trans('common.saved') . ' ' . trans('common.successfully') . '.');

        return redirect(route('manage.users'));
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
            Flash::error('Usuário ' . trans('common.not-found'));

            return redirect(route('manage.users'));
        }

        $roles = \DB::table("roles")->orderBy('name')->pluck('name', 'name');
        $permissions = \DB::table("permissions")->orderBy('readable_name')->pluck('readable_name', 'name');

        $roles->prepend('------ Selecione ------', 0);
        $permissions->prepend('------ Selecione ------', 0);

        return view('admin.manage.users.show')->with('user', $user)
            ->with('roles', $roles)
            ->with('permissions', $permissions);
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
            Flash::error('Usuário ' . trans('common.not-found'));

            return redirect(route('manage.users'));
        }

        return view('admin.manage.users.edit')->with('user', $user);
    }

    /**
     * Update the specified Usuário in storage.
     *
     * @param  int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Usuário ' . trans('common.not-found'));

            return redirect(route('manage.users'));
        }
        $input = $request->all();
        $input['active'] = intval($request->get('active'));

        $user = $this->userRepository->update($input, $id);

        Flash::success('Usuário ' . trans('common.updated') . ' ' . trans('common.successfully') . '.');

        return redirect(route('manage.users'));
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
            Flash::error('Usuário ' . trans('common.not-found'));

            return redirect(route('manage.users'));
        }

        $this->userRepository->delete($id);

        Flash::success('Usuário ' . trans('common.deleted') . ' ' . trans('common.successfully') . '.');

        return redirect(route('manage.users'));
    }

    /**
     * Deactivate the specified Usuário from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function deactivate($id)
    {
        return $this->changeStatus($id, 0);
    }

    /**
     * Deactivate the specified Usuário from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function activate($id)
    {
        return $this->changeStatus($id, 1);
    }

    /**
     * Centered the business logic for change status of users
     *
     * @param $id
     * @param $status
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function changeStatus($id, $status)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Usuário ' . trans('common.not-found'));

            return redirect(route('manage.users'));
        }

        $this->userRepository->update(['active' => $status], $id);

        Flash::success('Usuário ' . ($status ? trans('common.activated') : trans('common.deactivated')) . ' ' . trans('common.successfully') . '.');

        return redirect(route('manage.users'));
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addRole(Request $request, $id)
    {

        $user = User::find($id);
        $roles = $request->get('roles');
        $role = Defender::findRole($roles);
        $user->attachRole($role);

        Flash::success("Papel adicionado com sucesso.");

        return redirect()->back();
    }

    /**
     * @param $user
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeRole($user, $id)
    {

        $user = User::find($user);
        $user->detachRole([$id]);

        Flash::success("Papel removido com sucesso.");

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPermission(Request $request, $id)
    {

        $user = User::find($id);
        $permissions = $request->get('permissions');
        $permited = $request->get('permited');
        $permission = Defender::findPermission($permissions);
        $user->attachPermission($permission, [
            'value' => $permited ? '1' : '0' // true = has the permission, false = doesn't have the permission,
        ]);

        if ($permited) {
            Flash::success("Permissão adicionada com sucesso.");
        } else {
            Flash::success("Bloqueio adicionado com sucesso.");
        }

        return redirect()->back();
    }

    /**
     * @param $user
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removePermission($user, $id)
    {

        $user = User::find($user);
        $user->detachPermission([$id]);
        Flash::success("Permissão removida com sucesso.");

        return redirect()->back();

    }

    public function busca(Request $request){
        return User::select([
            'id',
            'name'
        ])
            ->where('name','like', '%'.$request->q.'%')->paginate();
    }

}

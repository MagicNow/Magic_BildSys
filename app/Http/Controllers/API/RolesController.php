<?php

namespace App\Http\Controllers\API;

use App\Repositories\Admin\RoleRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Validator;
use Defender;
use DB;

class RolesController extends AppBaseController
{
	/**
	 * @var RoleRepository
	 */
	protected $roleRepository;

	/**
	 * @param RoleRepository $roleRepository
	 */
	function __construct(RoleRepository $roleRepository)
	{
		$this->roleRepository = $roleRepository;
	}

	/**
	 * Returns all roles - paginated
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index()
	{
		$roles = $this->roleRepository->with(['permissions'=>function($query){
			$query->orderBy('name', 'ASC');
			$query->orderBy('readable_name', 'ASC');
		}])->paginate(10);

		return response()->json($roles, 200);
	}

	/**
	 * Get a role by id
	 *
	 * @param $id role_id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
		$role = $this->roleRepository->findById($id);

		return response()->json($role, 200);
	}

	/**
	 * Create a new role
	 *
	 * @param Request $request
	 * @return array
	 */
	public function store(Request $request)
	{
		$role = $request->get('role');

		$permissions = [];
		if (isset($role['permissions']) AND count($role['permissions']) > 0) {
			foreach($role['permissions'] as $permission) {
				$permissions[] = Defender::findPermission($permission['name']);
			}
		}

		if (!$role['name']) {
			return response()->json(['error' => "O campo Papel é obrigatório."], 401);
		}


		if (!$role['name']) {
			return response()->json(['error' => "O campo Papel já existe."], 401);
		}

		try {
			if (isset($role['id']) AND $role['id'] > 0) {
				$this->validate($request, [
					'role.name' => 'required|unique:roles,name,' . $role['id'],
				]);

				$newRole = $this->roleRepository->update(['name' => $role['name']], $role['id']);

			} else {

				$validator = Validator::make($role, [
					'name' => 'required|unique:roles,name',
				],[
					'name.unique' => 'Este Papel já está cadastrado.',
				]);

				if (!$validator->fails()) {

					$newRole = $this->roleRepository->create(['name' => $role['name']]);

				} else {
					$errors = $validator->errors()->all();
					$errors = implode("\n", $errors);
					return response()->json(['error' => $errors], 401);
				}
			}

			$Role = Defender::findRole($newRole->name);
			if (isset($role['id']) and $role['id'] > 0) {
				DB::table("permission_role")->where('role_id', $role['id'])->delete();
			}

			foreach($permissions as $permission) {
				$Role->attachPermission($permission);
			}

			return response()->json(['success' => 'Ação efetuada com sucesso'], 201);

		} catch (\Exception $e) {
			return response()->json(['error' => "Ação não efetuada\nError: " . $e->getMessage()], 401);
		}
	}
}

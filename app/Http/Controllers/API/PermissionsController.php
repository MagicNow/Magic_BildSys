<?php

namespace app\Http\Controllers\API;

use App\Repositories\Admin\PermissionRepository as DataRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Validator;

class PermissionsController extends AppBaseController
{
    /**
     * @var RoleRepository
     */
    protected $repository;

    /**
     * @param RoleRepository $roleRepository
     */
    public function __construct(DataRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Returns all roles - paginated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = $this->repository->paginate(10);

        return response()->json($data, 200);
    }

    /**
     * Get a role by id
     *
     * @param $id role_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = $this->repository->findById($id);

        return response()->json($data, 200);
    }

    /**
     * Create a new role
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $data = $request->get('permission');

        try {
            if (isset($data['id']) and $data['id'] > 0) {

                $validator = Validator::make($data, [
                    'name' => 'required|unique:permissions,name,' . $data['id'],
                    'readable_name' => 'required',
                ], [
                    'name.unique' => 'Este Papel já está cadastrado.',
                ]);

                if (!$validator->fails()) {
                    $newModel = $this->repository->update([
                        'name' => $data['name'],
                        'readable_name' => $data['readable_name']
                    ], $data['id']);
                } else {
                    $errors = $validator->errors()->all();
                    $errors = implode("\n", $errors);
                    return response()->json(['error' => $errors], 401);
                }

            } else {
                $validator = Validator::make($data, [
                    'name' => 'required|unique:permissions,name',
                    'readable_name' => 'required',
                ], [
                    'name.unique' => 'Este Papel já está cadastrado.',
                ]);

                if (!$validator->fails()) {
                    $newModel = $this->repository->create([
                        'name' => $data['name'],
                        'readable_name' => $data['readable_name']
                    ]);
                } else {
                    $errors = $validator->errors()->all();
                    $errors = implode("\n", $errors);
                    return response()->json(['error' => $errors], 401);
                }
            }

        } catch (\Exception $e) {
            return response()->json(['error' => "Ação não efetuada\nError: " . $e->getMessage()], 401);
        }

        return response()->json(['success' => 'Ação efetuada com sucesso'], 201);
    }
}

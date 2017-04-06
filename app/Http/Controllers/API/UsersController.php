<?php namespace App\Http\Controllers\API;

use App\Repositories\Admin\UserRepository;
use App\Http\Controllers\AppBaseController;

class UsersController extends AppBaseController
{
    /**
     * @var UserRepository
     */
    protected $UserRepository;

    /**
     * @param UserRepository $UserRepository
     */
    function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    /**
     * Returns all users - paginated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = $this->UserRepository->paginate(10);

        return response()->json($users, 200);
    }

    /**
     * Get a role by id
     *
     * @param $id role_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = $this->UserRepository->findById($id);

        return response()->json($user, 200);
    }

}

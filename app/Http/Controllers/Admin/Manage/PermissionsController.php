<?php namespace App\Http\Controllers\Admin\Manage;

use Artesaos\Defender\Contracts\Repositories\PermissionRepository;
use App\Http\Controllers\Admin\BaseController;

/**
 * Class PermissionsController
 *
 * @author Andreo Vieira <andreoav@gmail.com>
 * @package Artesaos\Defender\Controllers
 */
class PermissionsController extends BaseController
{
	/**
	 * @var PermissionRepository
	 */
	protected $permissionsRepository;

	/**
	 * @param PermissionRepository $permissionsRepository
	 */
	function __construct(PermissionRepository $permissionsRepository)
	{
		$this->permissionsRepository = $permissionsRepository;
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$permissions = $this->permissionsRepository->paginate(10);

		return view('admin.manage.permissions.index', compact('permissions'));
	}
}

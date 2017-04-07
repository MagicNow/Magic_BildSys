<?php namespace App\Http\Controllers\Admin\Manage;

use Artesaos\Defender\Contracts\Repositories\RoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;


/**
 * Class RolesController
 *
 * @author Andre Vieira <andreoav@gmail.com>
 * @package Artesaos\Dashboard\Controllers
 */
class RolesController extends BaseController
{
	/**
	 * @var RoleRepository
	 */
	protected $rolesRepository;

	/**
	 * @param RoleRepository $rolesRepository
	 */
	function __construct(RoleRepository $rolesRepository)
	{
		$this->rolesRepository = $rolesRepository;
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$permissions = \DB::table("permissions")->orderBy('readable_name')->pluck('readable_name', 'name');
		$permissions->prepend('------ Selecione ------', 0);

		return view('admin.manage.roles.index', compact('permissions'));
	}
}

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
		$permissionsObj = \DB::table("permissions")->orderBy('name')->get();
        $permissions = [];
        $group = '';
		foreach ($permissionsObj as $perm){
            $nameArray = explode('.',$perm->name);
            $name = str_replace('_',' ', strtoupper($nameArray[0]));
            if($name != $group){
                $group = $name;
            }
            $permissions[$group][$perm->name] = $perm->readable_name;
        }
		$permissions[''] = '------ Selecione ------';

		return view('admin.manage.roles.index', compact('permissions'));
	}
}

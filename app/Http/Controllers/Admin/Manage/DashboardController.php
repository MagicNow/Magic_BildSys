<?php namespace App\Http\Controllers\Admin\Manage;

use App\Http\Controllers\Admin\BaseController;

class DashboardController extends BaseController
{

    public function index()
    {
        return view('admin.manage.index');
    }

}

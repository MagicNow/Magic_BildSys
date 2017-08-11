<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notificacao;
use App\Repositories\Admin\ValidationRepository;
use App\Repositories\ImportacaoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

class HomeController extends AppBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.home');
    }

}

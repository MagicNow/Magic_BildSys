<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 04/04/2017
 * Time: 11:47
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AppBaseController;
use App\Models\Modulo;
use App\Repositories\Admin\SpreadsheetRepository;
use Symfony\Component\HttpFoundation\Request;

class ImportController extends AppBaseController
{
    public function index(){

        $modulos = Modulo::pluck('nome','id')->toArray();
        return view('admin.import.index', compact('modulos'));
    }

    public function import(Request $request){
        SpreadsheetRepository::Spreadsheet($request->file, $request->modulo_id);
    }

}
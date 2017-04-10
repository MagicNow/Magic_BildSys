<?php

namespace App\Http\Controllers;

use App\DataTables\RetroalimentacaoObraDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateRetroalimentacaoObraRequest;
use App\Http\Requests\UpdateRetroalimentacaoObraRequest;
use App\Models\Obra;
use App\Repositories\RetroalimentacaoObraRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Response;

class RetroalimentacaoObraController extends AppBaseController
{
    /** @var  RetroalimentacaoObraRepository */
    private $retroalimentacaoObraRepository;

    public function __construct(RetroalimentacaoObraRepository $retroalimentacaoObraRepo)
    {
        $this->retroalimentacaoObraRepository = $retroalimentacaoObraRepo;
    }


    /**
     * Show the form for creating a new RetroalimentacaoObra.
     *
     * @return Response
     */
    public function create()
    {
        $obras = Obra::pluck('nome','id')->toArray();
        return view('retroalimentacao_obras.create',compact('obras'));
    }

    /**
     * Store a newly created RetroalimentacaoObra in storage.
     *
     * @param CreateRetroalimentacaoObraRequest $request
     *
     * @return Response
     */
    public function store(CreateRetroalimentacaoObraRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();
        $retroalimentacaoObra = $this->retroalimentacaoObraRepository->create($input);

        Flash::success('Retroalimentação inserida '.trans('common.successfully').'.');

        return redirect($request->anterior);
    }
}

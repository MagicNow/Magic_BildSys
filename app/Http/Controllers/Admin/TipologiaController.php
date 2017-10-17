<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TipologiaDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateTipologiaRequest;
use App\Http\Requests\Admin\UpdateTipologiaRequest;
use App\Repositories\Admin\TipologiaRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;

class TipologiaController extends AppBaseController
{
    /** @var  TipologiaRepository */
    private $tipologiaRepository;    

    public function __construct(TipologiaRepository $tipologiaRepo)
    {
        $this->tipologiaRepository = $tipologiaRepo;        
    }

    /**
     * Display a listing of the Tipologia.
     *
     * @param TipologiaDataTable $tipologiaDataTable
     * @return Response
     */
    public function index(TipologiaDataTable $tipologiaDataTable)
    {
        return $tipologiaDataTable->render('admin.tipologia.index');
    }

    /**
     * Show the form for creating a new Tipologia.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.tipologia.create');
    }

    /**
     * Store a newly created Tipo de Q.C. Avulso in storage.
     *
     * @param CreateTipologiaRequest $request
     *
     * @return Response
     */
    public function store(CreateTipologiaRequest $request)
    {
        $input = $request->all();

        $tipologia = $this->tipologiaRepository->create($input);

        Flash::success('Tipo de Q.C. Avulso '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.tipologia.index'));
    }

    /**
     * Display the specified Tipologia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tipologia = $this->tipologiaRepository->findWithoutFail($id);

        if (empty($tipologia)) {
            Flash::error('Tipo de Q.C. Avulso '.trans('common.not-found'));

            return redirect(route('admin.tipologia.index'));
        }

        return view('admin.tipologia.show')->with('tipologia', $tipologia);
    }

    /**
     * Show the form for editing the specified Tipologia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tipologia = $this->tipologiaRepository->findWithoutFail($id);

        if (empty($tipologia)) {
            Flash::error('Tipo de Q.C. Avulso '.trans('common.not-found'));

            return redirect(route('admin.tipologia.index'));
        }    
        
        return view('admin.tipologia.edit', compact('tipologia'));
    }

    /**
     * Update the specified Tipo de Q.C. Avulso in storage.
     *
     * @param  int              $id
     * @param UpdateTipologiaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipologiaRequest $request)
    {
        $tipologia = $this->tipologiaRepository->findWithoutFail($id);

        if (empty($tipologia)) {
            Flash::error('Tipo de Q.C. Avulso '.trans('common.not-found'));

            return redirect(route('admin.tipologia.index'));
        }       

        $tipologia = $this->tipologiaRepository->update($request->all(), $id);

        Flash::success('Tipo de Q.C. Avulso '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.tipologia.index'));
    }

    /**
     * Remove the specified Tipo de Q.C. Avulso from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tipologia = $this->tipologiaRepository->findWithoutFail($id);

        if (empty($tipologia)) {
            Flash::error('Tipo de Q.C. Avulso '.trans('common.not-found'));
            return redirect(route('admin.tipologia.index'));
        }
		
        $this->tipologiaRepository->delete($id);

        Flash::success('Tipo de Q.C. Avulso '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.tipologia.index'));
    }
}

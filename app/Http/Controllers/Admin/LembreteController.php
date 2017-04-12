<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LembreteDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateLembreteRequest;
use App\Http\Requests\Admin\UpdateLembreteRequest;
use App\Models\LembreteTipo;
use App\Repositories\Admin\LembreteRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;

class LembreteController extends AppBaseController
{
    /** @var  LembreteRepository */
    private $lembreteRepository;

    public function __construct(LembreteRepository $lembreteRepo)
    {
        $this->lembreteRepository = $lembreteRepo;
    }

    /**
     * Display a listing of the Lembrete.
     *
     * @param LembreteDataTable $lembreteDataTable
     * @return Response
     */
    public function index(LembreteDataTable $lembreteDataTable)
    {
        return $lembreteDataTable->render('admin.lembretes.index');
    }

    /**
     * Show the form for creating a new Lembrete.
     *
     * @return Response
     */
    public function create()
    {
        $lembrete_tipos = LembreteTipo::pluck('nome','id')->toArray();
        return view('admin.lembretes.create', compact('lembrete_tipos'));
    }

    /**
     * Store a newly created Lembrete in storage.
     *
     * @param CreateLembreteRequest $request
     *
     * @return Response
     */
    public function store(CreateLembreteRequest $request)
    {
        $input = $request->all();

        $lembrete = $this->lembreteRepository->create($input);

        Flash::success('Lembrete '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.lembretes.index'));
    }

    /**
     * Display the specified Lembrete.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $lembrete = $this->lembreteRepository->findWithoutFail($id);

        if (empty($lembrete)) {
            Flash::error('Lembrete '.trans('common.not-found'));

            return redirect(route('admin.lembretes.index'));
        }

        return view('admin.lembretes.show')->with('lembrete', $lembrete);
    }

    /**
     * Show the form for editing the specified Lembrete.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $lembrete = $this->lembreteRepository->findWithoutFail($id);

        if (empty($lembrete)) {
            Flash::error('Lembrete '.trans('common.not-found'));

            return redirect(route('admin.lembretes.index'));
        }

        return view('admin.lembretes.edit')->with('lembrete', $lembrete);
    }

    /**
     * Update the specified Lembrete in storage.
     *
     * @param  int              $id
     * @param UpdateLembreteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLembreteRequest $request)
    {
        $lembrete = $this->lembreteRepository->findWithoutFail($id);

        if (empty($lembrete)) {
            Flash::error('Lembrete '.trans('common.not-found'));

            return redirect(route('admin.lembretes.index'));
        }

        $lembrete = $this->lembreteRepository->update($request->all(), $id);

        Flash::success('Lembrete '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.lembretes.index'));
    }

    /**
     * Remove the specified Lembrete from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $lembrete = $this->lembreteRepository->findWithoutFail($id);

        if (empty($lembrete)) {
            Flash::error('Lembrete '.trans('common.not-found'));

            return redirect(route('admin.lembretes.index'));
        }

        $this->lembreteRepository->delete($id);

        Flash::success('Lembrete '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.lembretes.index'));
    }

    public function busca(Request $request){
        return InsumoGrupo::select([
            'id',
            'name'
        ])
            ->where('name','like', '%'.$request->q.'%')->paginate();
    }
}

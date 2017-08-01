<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LembreteDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateLembreteRequest;
use App\Http\Requests\Admin\UpdateLembreteRequest;
use App\Models\InsumoGrupo;
use App\Models\Lembrete;
use App\Models\LembreteTipo;
use App\Repositories\Admin\LembreteRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $insumo_grupos = InsumoGrupo::pluck('nome','id')->toArray();
        return view('admin.lembretes.create', compact('lembrete_tipos','insumo_grupos'));
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

        if($input['dias_prazo_minimo'] < 0){
            Flash::error('O prazo mínimo não pode ser negativo.');
            return redirect('/admin/lembretes/create')->withInput($input);
        }

        if($input['dias_prazo_maximo'] < 0){
            Flash::error('O prazo máximo não pode ser negativo.');
            return redirect('/admin/lembretes/create')->withInput($input);
        }

        if($input['dias_prazo_maximo'] < $input['dias_prazo_minimo']){
            Flash::error('O prazo máximo não pode menor que o prazo mínimo.');
            return redirect('/admin/lembretes/create')->withInput($input);
        }

        $input['user_id'] = Auth::id();

        $lembreteTipo = LembreteTipo::find($input['lembrete_tipo_id']);
        if(!$input['dias_prazo_minimo']){
            $input['dias_prazo_minimo'] = $lembreteTipo->dias_prazo_minimo;
        }
        if(!$input['dias_prazo_maximo']){
            $input['dias_prazo_maximo'] = $lembreteTipo->dias_prazo_maximo;
        }

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
        $lembrete_tipos = LembreteTipo::pluck('nome','id')->toArray();
        $insumo_grupos = InsumoGrupo::pluck('nome','id')->toArray();
        $lembrete = $this->lembreteRepository->findWithoutFail($id);

        if (empty($lembrete)) {
            Flash::error('Lembrete '.trans('common.not-found'));

            return redirect(route('admin.lembretes.index'));
        }

        return view('admin.lembretes.edit', compact('lembrete','lembrete_tipos','insumo_grupos'));
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
        $input = $request->all();

        if($input['dias_prazo_minimo'] < 0){
            Flash::error('O prazo mínimo não pode ser negativo');
            return redirect('/admin/lembretes/'. $id .'/edit')->withInput($input);
        }

        if($input['dias_prazo_maximo'] < 0){
            Flash::error('O prazo máximo não pode ser negativo');
            return redirect('/admin/lembretes/'. $id .'/edit')->withInput($input);
        }

        if($input['dias_prazo_maximo'] < $input['dias_prazo_minimo']){
            Flash::error('O prazo máximo não pode menor que o prazo mínimo.');
            return redirect('/admin/lembretes/'. $id .'/edit')->withInput($input);
        }

        $lembreteTipo = LembreteTipo::find($input['lembrete_tipo_id']);
        if(!$input['dias_prazo_minimo']){
            $input['dias_prazo_minimo'] = $lembreteTipo->dias_prazo_minimo;
        }
        if(!$input['dias_prazo_maximo']){
            $input['dias_prazo_maximo'] = $lembreteTipo->dias_prazo_maximo;
        }

        $lembrete = $this->lembreteRepository->update($input, $id);

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
            'nome'
        ])
            ->where('nome','like', '%'.$request->q.'%')->paginate();
    }

    public function lembreteDataMinima(Request $request){
        $lembrete = Lembrete::where('lembrete_tipo_id', $request->lembrete_tipo_id)
                            ->where('insumo_grupo_id', $request->insumo_grupo_id)
                            ->first();

        if($lembrete){
            $lembrete->dias_prazo_minimo = $request->dias_prazo_minimo;
            $lembrete->nome = $request->nome;
            $lembrete->user_id = Auth::id();
            $lembrete->save();
        }else{
            $lembrete = new Lembrete([
                'lembrete_tipo_id' => $request->lembrete_tipo_id,
                'insumo_grupo_id' => $request->insumo_grupo_id,
                'dias_prazo_minimo' => $request->dias_prazo_minimo,
                'nome' => $request->nome,
                'user_id' => Auth::id()
            ]);
            $lembrete->save();
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\QcAvulsoCarteiraDataTable;
use App\DataTables\QcAvulsoCarteiraPorObrasDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateQcAvulsoCarteiraRequest;
use App\Http\Requests\Admin\UpdateQcAvulsoCarteiraRequest;
use App\Models\QcAvulsoCarteiraUser;
use App\Models\TipoEqualizacaoTecnica;
use App\Repositories\Admin\QcAvulsoCarteiraRepository;
use App\Repositories\CodeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use Response;
use DB;
use App\Repositories\Admin\UserRepository;
use App\Repositories\Admin\TipoEqualizacaoTecnicaRepository;
use App\Repositories\Admin\ObraRepository;

class QcAvulsoCarteiraController extends AppBaseController
{
    /** @var  QcAvulsoCarteiraRepository */
    private $carteiraRepository;

    private $userRepository;

    public function __construct(QcAvulsoCarteiraRepository $carteiraRepo, UserRepository $userRepository)
    {
        $this->carteiraRepository = $carteiraRepo;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the QcAvulsoCarteira.
     *
     * @param QcAvulsoCarteiraDataTable $carteiraDataTable
     * @return Response
     */
    public function index(QcAvulsoCarteiraDataTable $carteiraDataTable)
    {
        return $carteiraDataTable->render('admin.qc_avulso_carteiras.index');
    }

    /**
     * Show the form for creating a new QcAvulsoCarteira.
     *
     * @return Response
     */
    public function create()
    {
        $relacionadoUsers = [];

        $usuarios = $this->userRepository->getUsersByType(2)->pluck('name', 'id')->toArray(); // suprimentos

        return view('admin.qc_avulso_carteiras.create', compact('relacionadoUsers'), compact('usuarios'));
    }

    /**
     * Store a newly created Carteira de Q.C. Avulso in storage.
     *
     * @param CreateQcAvulsoCarteiraRequest $request
     *
     * @return Response
     */
    public function store(CreateQcAvulsoCarteiraRequest $request)
    {
        $input = $request->all();

        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $carteira = $this->carteiraRepository->create($input);

        Flash::success('Carteira de Q.C. Avulso '.trans('common.saved').' '.trans('common.successfully').'.');

        return redirect(route('admin.qc_avulso_carteiras.index'));
    }

    /**
     * Display the specified QcAvulsoCarteira.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carteira = $this->carteiraRepository->findWithoutFail($id);

        if (empty($carteira)) {
            Flash::error('Carteira de Q.C. Avulso '.trans('common.not-found'));

            return redirect(route('admin.qc_avulso_carteiras.index'));
        }

        return view('admin.qc_avulso_carteiras.show')->with('carteira', $carteira);
    }

    /**
     * Show the form for editing the specified QcAvulsoCarteira.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carteira = $this->carteiraRepository->findWithoutFail($id);

        if (empty($carteira)) {
            Flash::error('Carteira de Q.C. Avulso '.trans('common.not-found'));

            return redirect(route('admin.qc_avulso_carteiras.index'));
        }

        $relacionadoUsers = $carteira->users;
        $carteiraUsers = $relacionadoUsers->pluck('id')->all();
        $relacionadoUsers = $relacionadoUsers->pluck('name', 'id')->all();

        $usuarios = $this->userRepository->getUsersByType(2)->pluck('name', 'id')->toArray(); // suprimentos


        return view('admin.qc_avulso_carteiras.edit', compact('carteira', 'relacionadoUsers', 'carteiraUsers', 'usuarios'));
    }

    /**
     * Update the specified Carteira de Q.C. Avulso in storage.
     *
     * @param  int              $id
     * @param UpdateQcAvulsoCarteiraRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQcAvulsoCarteiraRequest $request)
    {
        $carteira = $this->carteiraRepository->findWithoutFail($id);

        if (empty($carteira)) {
            Flash::error('Carteira de Q.C. Avulso '.trans('common.not-found'));

            return redirect(route('admin.qc_avulso_carteiras.index'));
        }

        if($request->logo){
            $destinationPath = CodeRepository::saveFile($request->logo, 'carteiras/' . $carteira->id);
            $carteira->logo = Storage::url($destinationPath);
            $carteira->save();
        }

        $input = $request->except('logo');
        foreach ($input as $item => $value){
            if($value == ''){
                $input[$item] = null;
            }
        }

        $carteira = $this->carteiraRepository->update($input, $id);

        $carteira->update();

        Flash::success('Carteira de Q.C. Avulso '.trans('common.updated').' '.trans('common.successfully').'.');

        return redirect(route('admin.qc_avulso_carteiras.index'));
    }

    /**
     * Remove the specified Carteira de Q.C. Avulso from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carteira = $this->carteiraRepository->findWithoutFail($id);

        if (empty($carteira)) {
            Flash::error('Carteira de Q.C. Avulso '.trans('common.not-found'));

            return redirect(route('admin.qc_avulso_carteiras.index'));
        }

        if(count($carteira->ordemDeCompras)){
            Flash::error('A carteira não pode ser removida, pois tem ordens de compra.');

            return redirect(route('admin.qc_avulso_carteiras.index'));
        }

        $this->carteiraRepository->delete($id);

        Flash::success('Carteira de Q.C. Avulso '.trans('common.deleted').' '.trans('common.successfully').'.');

        return redirect(route('admin.qc_avulso_carteiras.index'));
    }

    public function listaPorObras(
        QcAvulsoCarteiraPorObrasDataTable $dt,
        ObraRepository $obraRepo,
        QcAvulsoCarteiraRepository $carteiraRepo
    ) {
        $obras = $obraRepo
            ->findByUser(auth()->id())
            ->pluck('nome', 'id');

        $carteiras = $carteiraRepo->todasComObraVinculada()
            ->pluck('nome', 'id');

        return $dt->render(
            'admin.qc_avulso_carteiras.lista_por_obras',
            compact('obras', 'carteiras')
        );
    }
}

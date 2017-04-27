@extends('layouts.front')
@section('styles')
    <style type="text/css">

        #totalInsumos h5{
            font-weight: bold;
            color: #4a4a4a;
            font-size: 13px;
            margin: 0 10px;
            opacity: 0.5;
            text-transform: uppercase;
        }
        #totalInsumos h4{
            font-weight: bold;
            margin: 0 10px;
            color: #4a4a4a;
            font-size: 22px;
        }
        #totalInsumos{
            margin-bottom: 20px;
        }
        .tooltip-inner {
             max-width: 500px;
             text-align: left !important;
         }
    </style>
@stop
@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-10">
                    <span class="pull-left title">
                        <h3>
                            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                             <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </button>
                            <span>Lista de OC/Insumos aprovados</span>
                        </h3>
                    </span>
                </div>
                <div class="col-md-2 text-right">
                    <label>Qtd por página</label>
                    {!! Form::select('qtd-por-pagina',[5=>'5',10=>'10',20=>'20',50=>'50',100=>'100',1000=>'1000'],Request::get('qtd-por-pagina','10'),['class'=>'form-control','onchange'=>"document.location='?qtd-por-pagina='+this.value;"]) !!}
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th class="text-center">Obra</th>
                <th class="text-center">O.C.</th>
                <th class="text-center">Codigo Est.</th>
                <th class="text-center">Insumo</th>
                <th class="text-center">Qtd.</th>
                <th class="text-center">SLA</th>
                <th class="text-center">Ação</th>
            </tr>
            </thead>
            <tbody>
        @foreach($itens as $item)
        <tr>
            <td class="text-center">{{ $item->obra->nome }}</td>
            <td class="text-center">
                <a href="{{ url('/ordens-de-compra/detalhes/'.$item->ordem_de_compra_id) }}"
                                       class="btn btn-link btn-block" >{{ $item->ordem_de_compra_id }}</a>
            </td>
            <td class="text-center">
                <strong  data-toggle="tooltip" data-placement="top" data-html="true"
                         title="{{ $item->grupo->codigo .' '. $item->grupo->nome . ' <br> ' .
                                    $item->subgrupo1->codigo .' '.$item->subgrupo1->nome . ' <br> ' .
                                    $item->subgrupo2->codigo .' '.$item->subgrupo2->nome . ' <br> ' .
                                    $item->subgrupo3->codigo .' '.$item->subgrupo3->nome . ' <br> ' .
                                    $item->servico->codigo .' '.$item->servico->nome  }}">
                    {{ $item->codigo_insumo }}
                </strong>
            </td>
            <td class="text-center">{{ $item->insumo->nome }}</td>
            <td class="text-center"><strong>{{ $item->qtd }}</strong></td>
            <td class="text-center"><strong>{{ $item->sla?$item->sla.' dias':'' }}</strong></td>
            <td class="text-center">
                {!! Form::checkbox('ordem_de_compra_itens[]',$item->id, null) !!}
            </td>

    </tr>
    @endforeach
    </tbody>
    </table>
    <div class="pg text-center">
            {{ $itens->appends(['qtd-por-pagina' => Request::get('qtd-por-pagina',10)])->links() }}
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@stop
@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <span class="pull-left title">
                   <h3>
                       <button type="button" class="btn btn-link" onclick="history.go(-1);">
                           <i class="fa fa-arrow-left" aria-hidden="true"></i>
                       </button>
                       <span>{{$cronogramaFisico->tarefa}}</span>
                   </h3>
                </span>
            </div>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>


        @include('adminlte-templates::common.errors')
        <style type="text/css">
            #carrinho ul{
                list-style-type: none;
                padding: 0px;
            }
            #carrinho ul li{
                background-color: #ffffff;
                border: solid 1px #dddddd;
                padding: 18px;
                margin-bottom: 12px;
                font-size: 16px;
                font-weight: 500;
                color: #9b9b9b;
            }
            #carrinho ul li .label-bloco{
                font-size: 13px;
                font-weight: bold;
                color: #4a4a4a;
                line-height: 15px;
                margin-bottom: 0px;
                padding-bottom: 0px;
            }
            .label-bloco-limitado{
                width: 72px;
            }
            @media (min-width: 769px){
                .label-bloco-limitado{
                    margin-top: -5px;
                }
            }
            .inputfile {
                width: 0.1px;
                height: 0.1px;
                opacity: 0;
                overflow: hidden;
                position: absolute;
                z-index: -1;
            }
            @media (min-width: 1215px){
                .margem-botao{
                    margin-top: -15px;
                }
            }

            .label-input-file{
                text-transform: none;
            }
            .dados-extras{
                background-color: #fff;
                margin-top: 20px;
            }
            .li-aberto{
                height: auto !important;
            }
            .col-xs-12, .col-xs-6, .col-xs-5, .col-xs-1{
                margin-bottom: 5px;
            }
            .btn-xs{
                overflow: hidden;
            }
            .tooltip-inner {
                max-width: 500px;
                 text-align: left !important;
            }
            /*.tooltip-ajuste {*/
                /*max-width: 500px;*/
            /*}*/

        </style>
        <!-- Obra Id Field -->
        <div class="form-group col-md-6">
            {!! Form::label('obra_id', 'Obra:') !!}
            <p class="form-control">{!! $cronogramaFisico->obra->nome !!}</p>
        </div>
		

        <!-- Resumo Field -->
        <div class="form-group col-md-6">
            {!! Form::label('resumo', 'Resumo:') !!}
            <p class="form-control">{!! strtoupper($cronogramaFisico->resumo) !!}</p>
        </div>
		
		<!-- Custo Field -->
        <div class="form-group col-md-6">
            {!! Form::label('custo', 'Custo:') !!}
            <p class="form-control">{!! "R$ ".$cronogramaFisico->custo !!}</p>
        </div>
		
		<!-- Torre Field -->
        <div class="form-group col-md-6">
            {!! Form::label('torre', 'Torre:') !!}
            <p class="form-control">{!! $cronogramaFisico->torre !!}</p>
        </div>
		
		<!-- Pavimento Field -->
        <div class="form-group col-md-6">
            {!! Form::label('pavimento', 'Pavimento:') !!}
            <p class="form-control">{!! $cronogramaFisico->pavimento !!}</p>
        </div>
		
		

        <!-- Tarefa Field -->
        <div class="form-group col-md-6">
            {!! Form::label('tarefa', 'Tarefa:') !!}
            <p class="form-control">{!! $cronogramaFisico->tarefa !!}</p>
        </div>

		<!-- Critica Field -->
        <div class="form-group col-md-6">
            {!! Form::label('critica', 'Critica:') !!}
            <p class="form-control">{!! $cronogramaFisico->critica !!}</p>
        </div>
		
		<!-- Concluida Field -->
        <div class="form-group col-md-6">
            {!! Form::label('conclusao', 'Concluída:') !!}
            <p class="form-control">{!! $cronogramaFisico->concluida."%" !!}</p>
        </div>
        
		<!-- Data Field -->
        <div class="form-group col-md-6">
            {!! Form::label('data', 'Data Início:') !!}
            <p class="form-control">{!! $cronogramaFisico->data_inicio ? with(new\Carbon\Carbon($cronogramaFisico->data))->format('d/m/Y') : '' !!}</p>
        </div>

        <!-- Data Fim Field -->
        <div class="form-group col-md-6">
            {!! Form::label('data_termino', 'Data Fim:') !!}
            <p class="form-control">{!! $cronogramaFisico->data_termino ? with(new\Carbon\Carbon($cronogramaFisico->data_termino))->format('d/m/Y') : '' !!}</p>
        </div>
        
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('div.alert').not('.alert-important').delay(3000).fadeOut(350);

	</script>
@endsection


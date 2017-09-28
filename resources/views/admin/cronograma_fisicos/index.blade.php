@extends('layouts.app')

@section('content')
    <section class="content-header">
		<div class="modal-header">
			<div class="col-md-12">
				<div class="col-md-9">
					<h3 class="pull-left title"><a href="#" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> Cronograma Físicos</h3>						
				</div>
			</div>
		</div>
	</section>
    <div class="content">
        <div class="clearfix"></div>
		<div class="clearfix"></div>
		<div class="box">
			<div class="box-body">
				<div class="row">						
					<div class="js-datatable-filter-form pull-left form-group col-sm-3">
						<h4>Obra</h4>
						<select name="obra" id="obra" class="select2">
							<option value="">-- Selecione a Obra --</option>
							@foreach($obras as $k => $v)
								<option value="{{ $k }}">{{ $v }}</option>
							@endforeach

						</select>
					</div>	
					
					<div class="js-datatable-filter-form pull-left form-group col-sm-3">
						<h4>Tipo de Planejamento</h4>
						<select name="template" id="template" class="select2">
							<option value="">-- Selecione o Planejamento --</option>
							@foreach($templates as $k => $v)
								<option value="{{ $k }}">{{ $v }}</option>
							@endforeach

						</select>
					</div>
					
					<div class="col-sm-2">
						<h4>Mês Referência</h4>
						{!!
						  Form::select(
							'mes_id',["07/2017","08/2017","09/2017"],null,['class' => 'form-control select2 js-filter']
						  )
						!!}
					</div>
				</div>
			</div>
		</div>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.cronograma_fisicos.table')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">

        $(function () {

            $('#obra').on('change', function (event) {
                window.LaravelDataTables["dataTableBuilder"].draw();
            });
			
			$('#template').on('change', function (event) {
                window.LaravelDataTables["dataTableBuilder"].draw();
            });

            $('#dataTableBuilder').on('preXhr.dt', function ( e, settings, data ) {

                $('.js-datatable-filter-form :input').each(function () {

                    if($(this).attr('type')=='checkbox'){
                        if(data[$(this).prop('name')]==undefined){
                            data[$(this).prop('name')] = [];
                        }
                        if($(this).is(':checked')){
                            data[$(this).prop('name')].push($(this).val());
                        }

                    }else{
                        data[$(this).prop('name')] = $(this).val();
                    }
                });
            });
        });
    </script>
@stop

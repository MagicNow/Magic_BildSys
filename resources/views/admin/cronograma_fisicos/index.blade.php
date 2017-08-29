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
					<div class="col-sm-3">
						<h4>Obra</h4>
						{!!
						  Form::select(
							'obra_id',$obras,null,['class' => 'form-control select2 js-filter']
						  )
						!!}
					</div>
					<div class="col-sm-3">
						<h4>Tipo</h4>
						{!!
						  Form::select(
							'template_id',$templates,null,['class' => 'form-control select2 js-filter']
						  )
						!!}
					</div>
					<div class="col-sm-2">
						<h4>Ano</h4>
						{!!
						  Form::select(
							'ano_id',["2017","2018","2019","2020"],null,['class' => 'form-control select2 js-filter']
						  )
						!!}
					</div>
					<div class="col-sm-2">
						<h4>Mês</h4>
						{!!
						  Form::select(
							'mes_id',["Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],null,['class' => 'form-control select2 js-filter']
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
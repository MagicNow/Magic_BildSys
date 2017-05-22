@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.compradorInsumos.deletebloco', 'method'=>'GET']) !!}

                    <div class="col-md-12 loading">
                        <h3>Deletar insumos em bloco</h3>
                        <div class="col-md-12 thumbnail">
                            <div class="col-md-12">
                                <div class="caption">
                                    <div class="card-description">
                                        <!-- User Field -->
                                        <div class="form-group col-sm-12">
                                            {!! Form::label('usuario_id', 'Usuários:') !!}
                                            {!! Form::select('usuario_id', [''=>'-']+$users, null, ['class' => 'form-control select2', 'id'=>'usuario_id', 'required'=>'required', 'onchange'=>'GrupoInsumoUsuario(this.value)']) !!}
                                        </div>

                                        <!-- Grupo de insumos Field -->
                                        <div class="form-group col-sm-12">
                                            {!! Form::label('grupo_insumo_id', 'Grupo de insumos:') !!}
                                            {!! Form::select('grupo_insumo_id', [''=>'-'], null, ['class' => 'form-control select2', 'id'=>'grupo_insumo_id', 'required'=>'required']) !!}
                                        </div>

                                        <div id="insumos" class="col-md-12"></div>

                                        <!-- Submit Field -->
                                        <div class="form-group col-sm-12">
                                            {{--{!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [--}}
                                                {{--'type' => 'button',--}}
                                                {{--'class' => 'btn btn-danger pull-right',--}}
                                                {{--'onclick' => "DeleteBloco();",--}}
                                                {{--'title' => ucfirst(trans('common.delete'))--}}
                                            {{--]) !!}--}}
                                            {!! Form::button( '<i class="glyphicon glyphicon-trash"></i> '. ucfirst( trans('common.delete') ), ['class' => 'btn btn-danger pull-right', 'type'=>'button', 'onclick' => "DeleteBloco();"]) !!}
                                            <a href="{!! route('admin.compradorInsumos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        function GrupoInsumoUsuario(id){
            console.log(id);
            var rota = "{{url('/admin/compradorInsumos/delete-bloco/view/delete')}}/";
            $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            $.ajax({
                url: rota + id
            }).done(function(retorno) {
                options = '<option value="">Selecione</option>';
                $('#grupo_insumo_id').html(options);
                $.each(retorno,function(index, value){
                    options += '<option value="'+index+'">'+value+'</option>';
                });
                $('#grupo_insumo_id').html(options);
                $('.overlay').remove();
                $('#grupo_insumo_id').attr('disabled',false);
            }).fail(function() {
                $('.overlay').remove();
            });
        }

        function DeleteBloco() {
            swal({
                    title: "Remover este item?",
                    text: "Deseja deletar todos os insumos relacionado ao grupo de insumos informado?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, remover!",
                    cancelButtonText: "Não",
                    closeOnConfirm: false
                },
                function(){
                    startLoading();
                    var usuario_id = $('#usuario_id').val();
                    var grupo_insumo_id = $('#grupo_insumo_id').val();
                    $.ajax("{{ url('/admin/compradorInsumos/delete-bloco/view/delete') }}",
                    {
                        data: {
                            'usuario_id' : usuario_id,
                            'grupo_insumo_id': grupo_insumo_id
                        },
                        type: "GET"
                    }).done(function (retorno) {
                        stopLoading();
                        if(retorno.success){
                            swal({
                                title: "Removidos",
                                text: "",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonText: "Ok",
                                closeOnConfirm: true
                            },function(){
                                window.location = "{{route('admin.compradorInsumos.index')}}";
                            });
                        }else{
                            error = 'Não foi possível remover o item, verifique se você esqueceu de marcar algum campo!';
                            swal('Oops', error, 'error');
                        }
                    }).fail(function (retorno) {
                        stopLoading();
                        error = 'Não foi possível remover o item, verifique se você esqueceu de marcar algum campo!';
                        swal("Oops", error, "error");
                    });
                });
        }
    </script>
@stop

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h3 class="pull-left title">
            <a href="#" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
            Insumos da máscara padrão
        </h3>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.mascara_padrao_insumos.table')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">


        function deleteInsumo(id){
            swal({
                title: "{{ ucfirst( trans('common.are-you-sure') ) }}?",
                text: "{{ ucfirst( trans('common.you-will-not-be-able-to-recover-this-registry') ) }}!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ ucfirst( trans('common.yes') ) }}, {{ ucfirst( trans('common.delete') ) }}!",
                cancelButtonText: "{{ ucfirst( trans('common.cancel') ) }}",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    url : '/admin/mascara_padrao_insumos/' + id,
                    type :'DELETE',
                }).done(function (retorno){
                        if(retorno.success){
                            swal.close();
                            window.LaravelDataTables["dataTableBuilder"].draw(false);
                        }
                }).fail(function (){
                    swal('Ops...', 'Não foi possível deletar o insumo', 'error');
                });
            });
        }
    </script>
@endsection


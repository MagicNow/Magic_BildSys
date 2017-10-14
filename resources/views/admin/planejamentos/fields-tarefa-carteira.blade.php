<!-- Obras Field -->
<div class="form-group col-sm-12">
    {!! Form::label('obra_id', 'Obras:') !!}
    {!! Form::select('obra_id', [''=>'-']+$obras, Request::get('obra_id'), ['class' => 'form-control', 'id'=>'obra_id', 'required'=>'required','onchange'=>'selectPlanejamento(this.value);']) !!}
</div>

<!-- Planejamentos de insumo Field -->
<div class="form-group col-sm-12">
    {!! Form::label('planejamento_id', 'Tarefa:') !!}
    {!! Form::select('planejamento_id', [''=>'-'], null, ['class' => 'form-control select2', 'id'=>'planejamento_id', 'required'=>'required']) !!}
</div>

<!-- Grupo de insumos Field -->
<div class="form-group col-sm-12">
    {!! Form::label('qc_avulso_carteira_id', 'Carteira Q.C. Avulso:') !!}
    {!! Form::select('qc_avulso_carteira_id', $qc_avulso_carteiras, null, ['class' => 'form-control select2','multiple','required']) !!}
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.planejamentos.index') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
        var nao_relacionado = 0;
        function selectPlanejamento(id){
            var rota = "{{url('/admin/planejamentos/planejamentoOrcamentos/planejamento')}}/";
            if(id){

                $("#nao_relacionados").removeClass('hide');
                {{--$("#nao_relacionados").attr("href", "{{url('/admin/planejamentos/planejamentoOrcamentos/sem-planejamento/view')}}/"+id)--}}

                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    options = '<option value="">Selecione</option>';
                    $('#planejamento_id').html(options);
                    $.each(retorno,function(index, value){
                        options += '<option value="'+index+'">'+value+'</option>';
                    });
                    $('#planejamento_id').html(options);
                    $('.overlay').remove();
                    $('#planejamento_id').attr('disabled',false);
                    $('#planejamento_id').trigger('change');
                }).fail(function() {
                    $('.overlay').remove();
                });

            } else {

                $("#nao_relacionados").addClass('hide');
            }
        }

        @if(Request::get('obra_id'))
        $(function(){
            selectPlanejamento({{Request::get('obra_id')}});
            {{--orcamento({{Request::get('obra_id')}});--}}
            //                                selectGrupoInsumo();
        });
        @endif

    </script>
@stop
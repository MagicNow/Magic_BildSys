<!-- Cnpj Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cnpj', 'Cnpj:') !!}
    {!! Form::text('cnpj', null, ['class' => 'form-control cnpj',
        'onblur'=>'validaCnpj(1)',
        'required'=>'required',
        'maxlength'=>'255',
        'id'=>'numero1']) !!}
</div>

<!-- Nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control']) !!}
</div>

<!-- Situacao Cnpj Field -->
{{--<div class="form-group col-sm-6">--}}
    {{--{!! Form::label('situacao_cnpj', 'Situacao Cnpj:') !!}--}}
    {{--{!! Form::text('situacao_cnpj', null, ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- Inscricao Estadual Field -->
<div class="form-group col-sm-6">
    {!! Form::label('inscricao_estadual', 'Inscricao Estadual:') !!}
    {!! Form::number('inscricao_estadual', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Site Field -->
<div class="form-group col-sm-6">
    {!! Form::label('site', 'Site:') !!}
    {!! Form::text('site', null, ['class' => 'form-control']) !!}
</div>

<!-- Telefone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('telefone', 'Telefone:') !!}
    {!! Form::text('telefone', null, ['class' => 'form-control telefone']) !!}
</div>

<!-- Cep Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cep', 'Cep:') !!}
    {!! Form::text('cep', null, ['class' => 'form-control cep', 'onkeyup'=>'buscacep(this.value)']) !!}
</div>

<!-- Tipo Logradouro Field -->
{{--<div class="form-group col-sm-6">--}}
    {{--{!! Form::label('tipo_logradouro', 'Tipo Logradouro:') !!}--}}
    {{--{!! Form::text('tipo_logradouro', null, ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- Logradouro Field -->
<div class="form-group col-sm-6">
    {!! Form::label('logradouro', 'Logradouro:') !!}
    {!! Form::text('logradouro', null, ['class' => 'form-control']) !!}
</div>

<!-- Cidade Id Field -->
{{--<div class="form-group col-sm-6">--}}
    {{--{!! Form::label('cidade_id', 'Cidade Id:') !!}--}}
    {{--{!! Form::text('cidade_id', null, ['class' => 'form-control']) !!}--}}
{{--</div>--}}

<!-- Municipio Field -->
<div class="form-group col-sm-6">
    {!! Form::label('municipio', 'Municipio:') !!}
    {!! Form::text('municipio', null, ['class' => 'form-control']) !!}
</div>

<!-- Estado Field -->
<div class="form-group col-sm-6">
    {!! Form::label('estado', 'Estado:') !!}
    {!! Form::text('estado', null, ['class' => 'form-control']) !!}
</div>

<!-- Numero Field -->
<div class="form-group col-sm-6">
    {!! Form::label('numero', 'Numero:') !!}
    {!! Form::text('numero', null, ['class' => 'form-control']) !!}
</div>

<!-- Complemento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('complemento', 'Complemento:') !!}
    {!! Form::text('complemento', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
  <div class="checkbox">
    <label>
      {!! Form::checkbox('is_user', '1') !!}
      É usuário
    </label>
  </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success btn-flat btn-lg pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.fornecedores.index') !!}" class="btn btn-danger btn-flat btn-lg"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
        //CEP
        function buscacep(valor){
            if(valor.length==9){
                startLoading();
                valor = valor.replace('-','');
                $.ajax({
                            url: '/fornecedores/buscacep/'+valor,
                            dataType: 'json',
                            crossDomain:true
                        })
                        .done(function(json) {
                            stopLoading();
                            if(json.cidade){
                                $('input[name="logradouro"]').val(json.logradouro+', ');
                                $('input[name="estado"]').val(json.uf);
                                $('input[name="municipio"]').val(json.cidade);
//                                $('input[name="cidade_id"]').val(json.cidade);
//                                $('input[name="bairro"]').val(json.bairro);
//                                $('input[name="endereco"]').focus();
                            }else{
                                swal('CEP não encontrado');
                            }
                        })
                        .fail(function() {
                            stopLoading();
                            alert('CEP não encontrado!');
                        });
            }
        }

        //CNPJ
        function validaCnpj(qual) {
            if($('#numero'+qual).val()!=''){
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: "/admin/valida-documento",
                    data: {
                        numero: $('#numero'+qual).val(),
                        cpf: 1
                    }
                }).done(function(retorno) {
                    if(retorno.importado == 1){
                        swal({
                                    title: retorno.msg,
                                    text: "",
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonText: "Ok",
                                    closeOnConfirm: false
                                },
                                function(){
                                    @if(!\Illuminate\Support\Facades\Request::get('modal'))
                                    document.location='/fornecedores/'+ retorno.fornecedor.id;
                                    @else
                                        parent.novoObjeto = retorno.fornecedor;
                                        setTimeout(function () {
                                            eval('parent.'+parent.funcaoPosCreate);
                                            parent.$.colorbox.close();
                                        }, 10);
                                    @endif
                                });

                    }
                    $('.overlay').remove();
                }).fail(function(retorno) {
                    if(retorno.responseJSON.erro){
                        swal({
                            title: retorno.responseJSON.erro,
                            text: "",
                            type: "error",
                            showCancelButton: false,
                            confirmButtonText: "Ok",
                            closeOnConfirm: false
                        },
                        function(){
                            swal.close();
                            $('#numero' + qual).val('');
                            $('#numero' + qual).focus();
                        });
                    }else {
                        numero = $('#numero' + qual).val();
                        resposta = !numero.length ? 'Nulo' : 'Inválido';

                        swal({
                            title: 'Número ' + resposta,
                            text: "Este registro não será salvo enquanto o mesmo não for um número válido",
                            type: "error",
                            showCancelButton: false,
                            confirmButtonText: "Ok",
                            closeOnConfirm: true
                        },
                        function(){
                            swal.close();
                            $('#numero' + qual).val('');
                            $('#numero' + qual).focus();
                        });
                    }
                    $('.overlay').remove();
                });
            }

        }
    </script>
@endsection

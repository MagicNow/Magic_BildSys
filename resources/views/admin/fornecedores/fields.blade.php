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


<!-- Logradouro Field -->
<div class="form-group col-sm-6">
    {!! Form::label('logradouro', 'Logradouro:') !!}
    {!! Form::text('logradouro', null, ['class' => 'form-control']) !!}
</div>

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

<!-- Nome sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome_socio', 'Nome sócio ou procurador:') !!}
    {!! Form::text('nome_socio', null, ['class' => 'form-control']) !!}
</div>

<!-- Nacionalidade sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nacionalidade_socio', 'Nacionalidade sócio ou procurador:') !!}
    {!! Form::text('nacionalidade_socio', null, ['class' => 'form-control']) !!}
</div>

<!-- Estado civil sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('estado_civil_socio', 'Estado civil sócio ou procurador:') !!}
    {!! Form::text('estado_civil_socio', null, ['class' => 'form-control']) !!}
</div>

<!-- Profissão sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('profissao_socio', 'Profissão sócio ou procurador:') !!}
    {!! Form::text('profissao_socio', null, ['class' => 'form-control']) !!}
</div>

<!-- RG sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rg_socio', 'RG sócio ou procurador:') !!}
    {!! Form::text('rg_socio', null, ['class' => 'form-control rg']) !!}
</div>

<!-- CPF sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cpf_socio', 'CPF sócio ou procurador:') !!}
    {!! Form::text('cpf_socio', null, ['class' => 'form-control cpf']) !!}
</div>

<!-- Endereço sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('endereco_socio', 'Endereço sócio ou procurador:') !!}
    {!! Form::text('endereco_socio', null, ['class' => 'form-control']) !!}
</div>

<!-- Cidade sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cidade_socio', 'Cidade sócio ou procurador:') !!}
    {!! Form::text('cidade_socio', null, ['class' => 'form-control']) !!}
</div>

<!-- Estado sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('estado_socio', 'Estado sócio ou procurador:') !!}
    {!! Form::text('estado_socio', null, ['class' => 'form-control']) !!}
</div>

<!-- CEP sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cep_socio', 'CEP sócio ou procurador:') !!}
    {!! Form::text('cep_socio', null, ['class' => 'form-control cep']) !!}
</div>

<!-- Telefone sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('telefone_socio', 'Telefone sócio ou procurador:') !!}
    {!! Form::text('telefone_socio', null, ['class' => 'form-control telefone']) !!}
</div>

<!-- Celular sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('celular_socio', 'Celular sócio ou procurador:') !!}
    {!! Form::text('celular_socio', null, ['class' => 'form-control telefone']) !!}
</div>

<!-- Email sócio ou procurador Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_socio', 'Email sócio ou procurador:') !!}
    {!! Form::email('email_socio', null, ['class' => 'form-control']) !!}
</div>

<!-- Nome do vendedor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome_vendedor', 'Nome do vendedor:') !!}
    {!! Form::text('nome_vendedor', null, ['class' => 'form-control']) !!}
</div>

<!-- Email do vendedor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_vendedor', 'Email do vendedor:') !!}
    {!! Form::email('email_vendedor', null, ['class' => 'form-control']) !!}
</div>

<!-- Telefone do vendedor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('telefone_vendedor', 'Telefone do vendedor:') !!}
    {!! Form::text('telefone_vendedor', null, ['class' => 'form-control telefone']) !!}
</div>

<div class="form-group col-sm-6">
  <div class="checkbox">
    <label>
      {!! Form::checkbox('is_user', '1') !!}
      É usuário
    </label>
  </div>
</div>

<div class="col-md-12">
    <h3>Serviços prestados pelo fornecedor:</h3>
</div>
<div class="form-group col-md-12">
    {!! Form::label('servicos', 'Serviços:') !!}
    {!! Form::select('servicos[]', $servicos, (!isset($fornecedores)? null : $servicos_fornecedor),
    ['class' => 'form-control select2', 'required'=>'required', 'multiple'=>'multiple']) !!}
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
                    url: "/valida-documento",
                    data: {
                        numero: $('#numero'+qual).val(),
                        cnpj: 1
                    }
                }).done(function(retorno) {
                    if(retorno.importado == 1){
                        swal({
                                    title: retorno.msg,
                                    text: "Clique em continuar para preencher os campos que não foram importado do MEGA!",
                                    type: "info",
                                    showCancelButton: false,
                                    confirmButtonText: "Continuar",
                                    showLoaderOnConfirm: true,
                                    closeOnConfirm: false
                                },
                                function(){
                                    @if(!\Illuminate\Support\Facades\Request::get('modal'))
                                    document.location='/fornecedores/'+ retorno.fornecedor.id +'/edit';
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

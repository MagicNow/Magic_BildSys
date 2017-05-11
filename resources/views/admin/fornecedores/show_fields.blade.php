<!-- Codigo Mega Field -->
<div class="form-group col-md-6">
    {!! Form::label('codigo_mega', 'Codigo Mega:') !!}
    <p class="form-control">{!! $fornecedores->codigo_mega !!}</p>
</div>

<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $fornecedores->nome !!}</p>
</div>

<!-- Inscricao Estadual Field -->
<div class="form-group col-md-6">
    {!! Form::label('inscricao_estadual', 'Inscricao Estadual:') !!}
    <p class="form-control">{!! $fornecedores->inscricao_estadual !!}</p>
</div>

<!-- Email Field -->
<div class="form-group col-md-6">
    {!! Form::label('email', 'Email:') !!}
    <p class="form-control">{!! $fornecedores->email !!}</p>
</div>

<!-- Site Field -->
<div class="form-group col-md-6">
    {!! Form::label('site', 'Site:') !!}
    <p class="form-control">{!! $fornecedores->site !!}</p>
</div>

<!-- Telefone Field -->
<div class="form-group col-md-6">
    {!! Form::label('telefone', 'Telefone:') !!}
    <p class="form-control">{!! $fornecedores->telefone !!}</p>
</div>

<!-- Cnpj Field -->
<div class="form-group col-md-6">
    {!! Form::label('cnpj', 'Cnpj:') !!}
    <p class="form-control">{!! $fornecedores->cnpj !!}</p>
</div>

<!-- Logradouro Field -->
<div class="form-group col-md-6">
    {!! Form::label('logradouro', 'Logradouro:') !!}
    <p class="form-control">{!! $fornecedores->logradouro !!}</p>
</div>

<!-- Municipio Field -->
<div class="form-group col-md-6">
    {!! Form::label('municipio', 'Municipio:') !!}
    <p class="form-control">{!! $fornecedores->municipio !!}</p>
</div>

<!-- Estado Field -->
<div class="form-group col-md-6">
    {!! Form::label('estado', 'Estado:') !!}
    <p class="form-control">{!! $fornecedores->estado !!}</p>
</div>

<!-- Numero Field -->
<div class="form-group col-md-6">
    {!! Form::label('numero', 'Numero:') !!}
    <p class="form-control">{!! $fornecedores->numero !!}</p>
</div>

<!-- Complemento Field -->
<div class="form-group col-md-6">
    {!! Form::label('complemento', 'Complemento:') !!}
    <p class="form-control">{!! $fornecedores->complemento !!}</p>
</div>

<div class="form-group col-sm-12">
  <div class="checkbox">
    <label style="padding-left: 0px; ">
      {!! Form::checkbox('is_user', '1', $fornecedores->is_user, ['readonly', 'disabled']) !!}
      É usuário
    </label>
  </div>
</div>

@if(isset($servicos))
    <div class="col-md-12">
    <h3>Serviços prestados pelo fornecedor:</h3>
    </div>
    <?php $qtd = 1; ?>
    @foreach($servicos as $servico)
        <div class="col-md-12">
            <ul class="list-group">
                <li class="list-group-item">{{$qtd++}} - {{$servico->nome}}</li>
            </ul>
        </div>
    @endforeach
@endif


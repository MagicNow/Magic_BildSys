<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $obra->nome !!}</p>
</div>

<!-- logo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('logo', 'Logo:') !!}
    <p class="form-control">
        @if(@isset($obra))
            @if($obra->logo)
                <a href="{{$obra->logo}}" class="colorbox">Ver logo</a>
            @endif
        @endif
    </p>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('obraUsers', 'Usuários nesta obra:') !!}
    @if($obra->users)
        @php $usuarios = ''; @endphp
        @foreach($obra->users as $users)
            @php $usuarios .= $users->name . ' - '; @endphp
        @endforeach
        {!! Form::textarea('nome', substr($usuarios, 0, -3), ['class' => 'form-control', 'rows' => '3', 'disabled']) !!}
    @else
        <p class="form-control"></p>
    @endif
</div>

<!-- Cidade Id Field -->
<div class="form-group col-sm-12">
    {!! Form::label('cidade_id', 'Cidade:') !!}
    <p class="form-control">{{ @isset($obra->cidade) ? $obra->cidade->nome_completo . ' - ' . $obra->cidade->uf : null }}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('regional_id', 'Regional:') !!}
    <p class="form-control">{{ @isset($obra->regional_id) ? $obra->regional->nome : null }}</p>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('padrao_empreendimento_id', 'Padrão de empreendimento:') !!}
    <p class="form-control">{{ @isset($obra->padrao_empreendimento_id) ? $obra->padrao_empreendimento->nome : null }}</p>
</div>

<!-- area_terreno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_terreno', 'Área do terreno:') !!}
    <p class="form-control">{!! $obra->area_terreno !!}</p>
</div>

<!-- area_privativa Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_privativa', 'Área privativa:') !!}
    <p class="form-control">{!! $obra->area_privativa !!}</p>
</div>

<!-- area_construida Field -->
<div class="form-group col-sm-6">
    {!! Form::label('area_construida', 'Área construída:') !!}
    <p class="form-control">{!! $obra->area_construida !!}</p>
</div>

<!-- eficiencia_projeto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eficiencia_projeto', 'Eficiencia do projeto:') !!}
    <p class="form-control">{!! $obra->eficiencia_projeto !!}</p>
</div>

<!-- num_apartamentos Field -->
<div class="form-group col-sm-6">
    {!! Form::label('num_apartamentos', 'Número de apartamentos:') !!}
    <p class="form-control">{!! $obra->num_apartamentos !!}</p>
</div>

<!-- num_pavimento_tipo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('num_pavimento_tipo', 'Número pavimento tipo:') !!}
    <p class="form-control">{!! $obra->num_pavimento_tipo !!}</p>
</div>

<!-- num_torres Field -->
<div class="form-group col-sm-12">
    {!! Form::label('num_torres', 'Torres:') !!}
    <ul class="list-group" id="torres">
        @if(isset($obra))
            @foreach($obra->torres as $torre)
                <li class="list-group-item" id="torre{{ $torre->id }}">
                    {{ $torre->nome }}
                </li>
            @endforeach
        @endif
    </ul>
</div>

<!-- data_inicio Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_inicio', 'Data de início:') !!}
    <p class="form-control">{{$obra->data_inicio ? $obra->data_inicio->format('Y-m-d') : null}}</p>
</div>

<!-- data_cliente Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_cliente', 'Data cliente:') !!}
    <p class="form-control">{{$obra->data_cliente ? $obra->data_cliente->format('Y-m-d') : null}}</p>
</div>

<!-- indice_bild_pre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('indice_bild_pre', 'Índice BILD - Pré:') !!}
    <p class="form-control">{!! $obra->indice_bild_pre !!}</p>
</div>

<!-- indice_bild_oi Field -->
<div class="form-group col-sm-6">
    {!! Form::label('indice_bild_oi', 'Índice BILD - OI:') !!}
    <p class="form-control">{!! $obra->indice_bild_oi !!}</p>
</div>

<!-- razao_social Field -->
<div class="form-group col-sm-6">
    {!! Form::label('razao_social', 'Razão social:') !!}
    <p class="form-control">{!! $obra->razao_social !!}</p>
</div>

<!-- cnpj Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cnpj', 'CNPJ:') !!}
    <p class="form-control">{!! $obra->cnpj !!}</p>
</div>

<!-- inscricao_estadual Field -->
<div class="form-group col-sm-6">
    {!! Form::label('inscricao_estadual', 'Inscrição estadual:') !!}
    <p class="form-control">{!! $obra->inscricao_estadual !!}</p>
</div>

<!-- endereco_faturamento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('endereco_faturamento', 'Endereço de faturamento:') !!}
    <p class="form-control">{!! $obra->endereco_faturamento !!}</p>
</div>

<!-- endereco_obra Field -->
<div class="form-group col-sm-6">
    {!! Form::label('endereco_obra', 'Endereço da obra:') !!}
    <p class="form-control">{!! $obra->endereco_obra !!}</p>
</div>

<!-- entrega_nota_fisca_e_boleto Field -->
<div class="form-group col-sm-6">
    {!! Form::label('entrega_nota_fisca_e_boleto', 'Entrega de nota fiscal e boleto:') !!}
    <p class="form-control">{!! $obra->entrega_nota_fisca_e_boleto !!}</p>
</div>

<!-- adm_obra_nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('adm_obra_nome', 'Administrativo de obra - Nome:') !!}
    <p class="form-control">{!! $obra->adm_obra_nome !!}</p>
</div>

<!-- adm_obra_email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('adm_obra_email', 'Administrativo de obra - Email:') !!}
    <p class="form-control">{!! $obra->adm_obra_email !!}</p>
</div>

<!-- adm_obra_telefone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('adm_obra_telefone', 'Administrativo de obra - Telefone:') !!}
    <p class="form-control">{!! $obra->adm_obra_telefone !!}</p>
</div>

<!-- eng_obra_nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eng_obra_nome', 'Engenheiro obra - Nome:') !!}
    <p class="form-control">{!! $obra->eng_obra_nome !!}</p>
</div>

<!-- eng_obra_email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eng_obra_email', 'Engenheiro obra - Email:') !!}
    <p class="form-control">{!! $obra->eng_obra_email !!}</p>
</div>

<!-- eng_obra_telefone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('eng_obra_telefone', 'Engenheiro obra - Telefone:') !!}
    <p class="form-control">{!! $obra->eng_obra_telefone !!}</p>
</div>

<!-- horario_entrega_na_obra Field -->
<div class="form-group col-sm-6">
    {!! Form::label('horario_entrega_na_obra', 'Horário de entrega na obra:') !!}
    <p class="form-control">{!! $obra->horario_entrega_na_obra !!}</p>
</div>

<!-- referencias_bancarias Field -->
<div class="form-group col-sm-12">
    {!! Form::label('referencias_bancarias', 'Referências bancárias:') !!}
    <p class="form-control">{!! $obra->referencias_bancarias !!}</p>
</div>

<!-- referencias_comerciais Field -->
<div class="form-group col-sm-12">
    {!! Form::label('referencias_comerciais', 'Referências comerciais:') !!}
    {!! Form::textarea('referencias_comerciais', $obra->referencias_comerciais, ['class' => 'form-control', 'rows' => '5', 'disabled']) !!}
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Cadastrada em:') !!}
    <p class="form-control">{!! $obra->created_at->format('d/m/Y H:i') !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Alterada em:') !!}
    <p class="form-control">{!! $obra->updated_at->format('d/m/Y H:i') !!}</p>
</div>

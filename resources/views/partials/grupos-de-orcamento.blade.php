<div class="row">
    @if(isset($insumo))
        <input type="hidden"
            id="grupos_de_orcamento_insumo_id"
            value="{{ $insumo }}">
    @endif

    <!-- Grupos de insumo Field -->
        <div class="form-group col-sm-12 col-20">
            {!! Form::label('obra', 'Obra:') !!}
            {!!
              Form::select(
                'obra_id',
                $obras,
                null,
                ['class' => 'form-control select2 js-filter', 'id'=>'obra_id', 'onchange' => 'selectgrupo('. \App\Models\Grupo::where('codigo', '01')->whereNull('grupo_id')->first()->id.', \'subgrupo1_id\', \'grupos\');']
              )
            !!}
        </div>
        <!-- SubGrupos1 de insumo Field -->
        <div class="form-group col-sm-12 col-20">
            {!! Form::label('subgrupo1_id', 'SubGrupo-1:') !!}
            {!! Form::select('subgrupo1_id', [''=>'-'], null, ['class' => 'form-control select2 js-filter', 'id'=>'subgrupo1_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo2_id\', \'grupos\');']) !!}
        </div>

        <!-- SubGrupos2 de insumo Field -->
        <div class="form-group col-sm-12 col-20">
            {!! Form::label('subgrupo2_id', 'SubGrupo-2:') !!}
            {!! Form::select('subgrupo2_id', [''=>'-'], null, ['class' => 'form-control select2 js-filter', 'id'=>'subgrupo2_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo3_id\', \'grupos\');']) !!}
        </div>

        <!-- SubGrupos3 de insumo Field -->
        <div class="form-group col-sm-12 col-20">
            {!! Form::label('subgrupo3_id', 'SubGrupo-3:') !!}
            {!! Form::select('subgrupo3_id', [''=>'-'], null, ['class' => 'form-control select2 js-filter', 'id'=>'subgrupo3_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'servico_id\', \'servicos\');']) !!}
        </div>

        <!-- SubGrupos4 de insumo Field -->
        <div class="form-group col-sm-12 col-20">
            {!! Form::label('servico_id', 'Serviço:') !!}
            {!! Form::select('servico_id', [''=>'-'], null, ['class' => 'form-control select2 js-filter', 'id'=>'servico_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, null, \'servicos\');']) !!}
        </div>
</div>

@section('scripts')
  <script src="{{ asset('js/grupos-de-orcamento.js') }}"></script>
@append

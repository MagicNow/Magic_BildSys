<div class="row">
  <div class="form-group col-sm-6"
    style="{{ isset($full) && $full ? 'width:100%' : 'width:20%' }}">
    {!! Form::label('grupo_id', 'Grupo') !!}
    {!!
      Form::select(
        'grupo_id',
        $grupos,
        null,
        [
          'class'            => 'form-control select2 js-group-selector js-filter',
          'data-input-type'  => 'grupos',
          'data-input-order' => 1
        ]
      )
    !!}
  </div>
  <div class="form-group col-sm-6"
    style="{{ isset($full) && $full ? 'width:100%' : 'width:20%' }}">
    {!! Form::label('subgrupo1_id', 'Subgrupo 1') !!}
    {!!
      Form::select(
        'subgrupo1_id',
        [],
        null,
        [
          'class'            => 'form-control select2 js-group-selector js-filter',
          'data-input-type'  => 'grupos',
          'data-input-order' => 2,
          'disabled'         => 'disabled',
        ]
      )
    !!}
  </div>
  <div class="form-group col-sm-6"
    style="{{ isset($full) && $full ? 'width:100%' : 'width:20%' }}">
    {!! Form::label('subgrupo2_id', 'Subgrupo 2') !!}
    {!!
      Form::select(
        'subgrupo2_id',
        [],
        null,
        [
          'class'            => 'form-control select2 js-group-selector js-filter',
          'data-input-type'  => 'grupos',
          'disabled'         => 'disabled',
          'data-input-order' => 3
        ]
      )
    !!}
  </div>
  <div class="form-group col-sm-6"
    style="{{ isset($full) && $full ? 'width:100%' : 'width:20%' }}">
    {!! Form::label('subgrupo3_id', 'Subgrupo 3') !!}
    {!!
      Form::select(
        'subgrupo3_id',
        [],
        null,
        [
          'class'            => 'form-control select2 js-group-selector js-filter',
          'data-input-type'  => 'grupos',
          'disabled'         => 'disabled',
          'data-input-order' => 4
        ]
      )
    !!}
  </div>
  <div class="form-group col-sm-6"
    style="{{ isset($full) && $full ? 'width:100%' : 'width:20%' }}">
    {!! Form::label('servico_id', 'Serviço') !!}
    {!!
      Form::select(
        'servico_id',
        [],
        null,
        [
          'class'            => 'form-control select2 js-group-selector js-filter',
          'data-input-type'  => 'servicos',
          'disabled'         => 'disabled',
          'data-input-order' => 5
        ]
      )
    !!}
  </div>
</div>

@section('scripts')
  <script src="{{ asset('js/grupos-de-orcamento.js') }}"></script>
@append

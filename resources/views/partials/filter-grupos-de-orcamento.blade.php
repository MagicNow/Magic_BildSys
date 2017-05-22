<div class="row">
  <div class="form-group col-sm-6" style="width:20%">
    <div class="js-datatable-filter-form">
      {!! Form::label('grupo_id', 'Grupo') !!}
      {!!
        Form::select(
          'grupo_id',
          $grupos,
          null,
          [
            'class'    => 'form-control select2',
            'id'       => 'grupo_id',
          ]
        )
      !!}
    </div>
  </div>
  <div class="form-group col-sm-6" style="width:20%">
    <div class="js-datatable-filter-form">
      {!! Form::label('subgrupo1_id', 'Subgrupo 1') !!}
      {!!
        Form::select(
          'subgrupo1_id',
          [],
          null,
          [
            'class'    => 'form-control select2',
            'id'       => 'subgrupo1_id',
            'disabled' => 'disabled',
          ]
        )
      !!}
    </div>
  </div>
  <div class="form-group col-sm-6" style="width:20%">
    <div class="js-datatable-filter-form">
      {!! Form::label('subgrupo2_id', 'Subgrupo 2') !!}
      {!!
        Form::select(
          'subgrupo2_id',
          [],
          null,
          [
            'class'    => 'form-control select2',
            'id'       => 'subgrupo2_id',
            'disabled' => 'disabled',
          ]
        )
      !!}
    </div>
  </div>
  <div class="form-group col-sm-6" style="width:20%">
    <div class="js-datatable-filter-form">
      {!! Form::label('subgrupo3_id', 'Subgrupo 3') !!}
      {!!
        Form::select(
          'subgrupo3_id',
          [],
          null,
          [
            'class'    => 'form-control select2',
            'id'       => 'subgrupo3_id',
            'disabled' => 'disabled',
          ]
        )
    !!}
    </div>
  </div>
  <div class="form-group col-sm-6" style="width:20%">
    <div class="js-datatable-filter-form">
      {!! Form::label('servico_id', 'Serviço') !!}
      {!!
        Form::select(
          'servico_id',
          [],
          null,
          [
            'class'    => 'form-control select2',
            'id'       => 'servico_id',
            'disabled' => 'disabled',
          ]
        )
      !!}
    </div>
  </div>
</div>

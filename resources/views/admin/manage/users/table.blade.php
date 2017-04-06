<div class="form-group col-md-12">
    {!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'], true) !!}
</div>
@section('scripts')
    {!! $dataTable->scripts() !!}
@endsection
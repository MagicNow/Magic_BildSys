<div class="table-responsive">
    {!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'],true) !!}
</div>

@section('scripts')
    {!! $dataTable->scripts() !!}
@endsection
{!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'],true) !!}

@section('scripts')
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}
@endsection
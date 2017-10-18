{!! $dataTable->table(['width' => '100%','class'=>'table table-striped'],true) !!}

@section('scripts')
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endsection

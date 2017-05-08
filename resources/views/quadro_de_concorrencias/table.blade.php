{!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'],true) !!}

@section('scripts')
    @parent
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    <script type="text/javascript" src="{{ asset('js/qc.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endsection
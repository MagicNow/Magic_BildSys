<div class="table-responsive">
    {!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'],true) !!}
</div>

@section('scripts')
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endsection
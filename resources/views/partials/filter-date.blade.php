<div class="form-group" data-toggle="buttons">
    @if(isset($no_buttons) && !$no_buttons)
        <div class="btn-group btn-group-justified">
            <div class="btn-group">
                <label class="btn btn-primary btn-sm btn-flat zoom-1 fs19">
                    {!! Form::radio('days', 30, false, ['class' => 'js-filter visuallyhidden']) !!}
                    30 dias
                </label>
            </div>
            <div class="btn-group">
                <label class="btn btn-primary btn-sm btn-flat zoom-1 fs19">
                    {!! Form::radio('days', 15, false, ['class' => 'js-filter visuallyhidden']) !!}
                    15 dias
                </label>
            </div>
            <div class="btn-group">
                <label class="btn btn-primary btn-sm btn-flat zoom-1 fs19">
                    {!! Form::radio('days', 7, false, ['class' => 'js-filter visuallyhidden']) !!}
                    7 dias
                </label>
            </div>
            <div class="btn-group">
                <label class="btn btn-primary btn-sm btn-flat zoom-1 fs19">
                    {!! Form::radio('days', 0, false, ['class' => 'js-filter visuallyhidden']) !!}
                    Hoje
                </label>
            </div>
        </div>
    </div>
@endif
<div class="input-group with-middle">
    <input type="text" name="data_start" class="form-control datepicker js-filter">
    <span class="input-group-addon addon-middle">até</span>
    <input type="text" name="data_end" class="form-control datepicker js-filter">
</div>

@section('scripts')
    @parent
    <script src="{{ asset('/js/filter-date.js') }}"></script>
@stop

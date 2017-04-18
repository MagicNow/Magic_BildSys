@extends('layouts.front')

@section('content')
<div class="container">
    <div class="row" id="app">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-4"><tile-grafico></tile-grafico></div>
                <div class="col-md-4"><tile-grafico></tile-grafico></div>
                <div class="col-md-4"><tile-grafico></tile-grafico></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4"><tile></tile></div>
                <div class="col-md-4"><tile></tile></div>
                <div class="col-md-4"><tile></tile></div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('scripts')
    <script>
        const app = new Vue({
            el: '#app'
        });
    </script>
@endsection
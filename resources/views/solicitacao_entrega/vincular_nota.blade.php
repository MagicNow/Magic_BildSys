@extends('layouts.front')

@section('content')
<div class="content-header">
    <h1 class="content-header-title">
        <button type="button" class="btn btn-link" onclick="history.go(-1);">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
        </button>
        Solicitação de Entrega #{{ $entrega->id }}
    </h1>
</div>
<div class="content">

</div>
@endsection

@extends('layouts.printable')


@section('content')

    @foreach($item as $item)

        <div class="col-xs-12 col-sm-3 col-md-3 text-center">

            {!! QrCode::size(200)->generate('{"requisicao_item_id": '.$item->id.', "qtd_lida":'.$item->qtde.'}') !!}

            <br>

            <h5>{{ $item->insumo }}</h5>
            <b>Qtde:</b>  {{ $item->qtde }} <br>

            <b>Obra:</b> {{ $item->nome }} <br>
            <b>Torre:</b> {{ $item->torre }} / <b>Pavimento:</b> {{ $item->pavimento }} <br>
            <b>Trecho:</b> {{ $item->trecho }} / <b>Andar:</b> {{ $item->andar }} <br>
            <b>Apartamento:</b> {{ $item->apartamento }} / <b>Comodo:</b> {{ $item->comodo }}

        </div>

    @endforeach

@endsection
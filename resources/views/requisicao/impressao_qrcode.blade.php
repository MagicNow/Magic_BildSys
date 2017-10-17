@extends('layouts.printable')


@section('content')

    @if($request->query('all') == 'true')

        @foreach($item as $item)

            <div class="col-xs-12 col-sm-3 col-md-3 text-center">

                {!! QrCode::size(200)->generate(''.$item->insumo.' -  '.$item->qtde.'<br>'.$item->nome.'<br>'.$item->pavimento.' -  '.$item->andar.' Andar<br>'.$item->apartamento.' -  '.$item->comodo.'<br>"Dados QR Code: "{"requisicao_item_id": '.$item->id.', "qtd_lida":'.$item->qtde.'}') !!}

                <br>

                <h5>{{ $item->insumo }}</h5>
                <b>Qtde:</b>  {{ $item->qtde }} <br>

                <b>Obra:</b> {{ $item->nome }} <br>
                <b>Torre:</b> {{ $item->torre }} / <b>Pavimento:</b> {{ $item->pavimento }} <br>
                <b>Trecho:</b> {{ $item->trecho }} / <b>Andar:</b> {{ $item->andar }} <br>
                <b>Apartamento:</b> {{ $item->apartamento }} / <b>Comodo:</b> {{ $item->comodo }}

            </div>

        @endforeach

    @else

        @if($request->query('qtde_qrcodes') == 1)

            <div class="col-xs-12 col-sm-3 col-md-3 text-center">

                {!! QrCode::size(200)->generate(''.$item->insumo.' -  '.$item->qtde.'<br>'.$item->nome.'<br>'.$item->pavimento.' -  '.$item->andar.' Andar<br>'.$item->apartamento.' -  '.$item->comodo.'<br>"Dados QR Code: "{"requisicao_item_id": '.$item->id.', "qtd_lida":'.$item->qtde.'}') !!}

                <br>

                <h5>{{ $item->insumo }}</h5>
                <b>Qtde:</b>  {{ $item->qtde }} <br>

                <b>Obra:</b> {{ $item->nome }} <br>
                <b>Torre:</b> {{ $item->torre }} / <b>Pavimento:</b> {{ $item->pavimento }} <br>
                <b>Trecho:</b> {{ $item->trecho }} / <b>Andar:</b> {{ $item->andar }} <br>
                <b>Apartamento:</b> {{ $item->apartamento }} / <b>Comodo:</b> {{ $item->comodo }}

            </div>

        @elseif($request->query('qtde_qrcodes') > 1)

            @for ($i = 1; $i <= $request->query('qtde_qrcodes'); $i++)

                <div class="col-xs-12 col-sm-3 col-md-3 text-center">

                    {!! QrCode::size(200)->generate(''.$item->insumo.' -  '.$item->qtde / $request->query('qtde_qrcodes').'<br>'.$item->nome.'<br>'.$item->pavimento.' -  '.$item->andar.' Andar<br>'.$item->apartamento.' -  '.$item->comodo.'<br>"Dados QR Code: "{"requisicao_item_id": '.$item->id.', "qtd_lida":'.$item->qtde / $request->query('qtde_qrcodes').'}') !!}

                    <br>

                    <h5>{{ $item->insumo }}</h5>
                    <b>Qtde:</b>  {{ $item->qtde / $request->query('qtde_qrcodes') }} <br>

                    <b>Obra:</b> {{ $item->nome }} <br>
                    <b>Torre:</b> {{ $item->torre }} / <b>Pavimento:</b> {{ $item->pavimento }} <br>
                    <b>Trecho:</b> {{ $item->trecho }} / <b>Andar:</b> {{ $item->andar }} <br>

                    @if($item->apartamento)
                        <b>Apartamento:</b> {{ $item->apartamento }} / <b>Comodo:</b> {{ $item->comodo }}
                    @endif

                </div>

            @endfor

        @endif

    @endif

@endsection
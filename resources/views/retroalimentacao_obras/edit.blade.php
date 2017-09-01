@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Retroalimentação de Obras
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($retroalimentacaoObra, ['route' => ['retroalimentacaoObras.update', $retroalimentacaoObra->id], 'method' => 'patch']) !!}

                        @include('retroalimentacao_obras.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>

       @if($historico->count())
       <section class="content-header">
           <h3>
               Histórico de Andamentos
           </h3>
       </section>
       <div class="box box-primary">
           <div class="box-body">

               <table class="table">
                   <thead>
                        <tr>
                            <th>Data Criação</th>
                            <th>Usuário Origem</th>
                            <th>Usuário Destino</th>
                            <th>Status Origem</th>
                            <th>Status Destino</th>
                            <th>Andamento</th>
                        </tr>
                   </thead>

                   <tbody>
                        @foreach($historico as $v)
                            <tr>
                                <td>{{ $v->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $v->userOrigem->name }}</td>
                                <td>{{ $v->userDestino ? $v->userDestino->name : '-' }}</td>
                                <th>{{ $v->statusOrigem ? $v->statusOrigem->nome : '-' }}</th>
                                <th>{{ $v->statusDestino->nome }}</th>
                                <td align="left">{!! nl2br($v->andamento)  !!}</td>
                           </tr>
                        @endforeach
                   </tbody>
               </table>

           </div>
       </div>
       @endif

   </div>
@endsection
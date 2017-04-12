@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Validações de colunas</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            @include('flash::message')
            <div class="panel-body"></div>
            <!-- INICIO form -->
            {!! Form::open(['route' => 'admin.planejamento.save', 'method'=>'post']) !!}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Colunas importadas</th>
                            <th>Relação</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($retorno['cabecalho'] as $column => $indice)
                            <tr>
                                <th scope="row">{{$column}}
                                </th>
                                <td>
                                    {!! Form::select($indice, [''=>'Escolha' ]+$colunasbd, null, ['class' => 'form-control']) !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                </table>
            </div>
            <div class="form-group pull-right">
                <button type="submit" class="btn btn-warning">Processar</button>
            </div>
            <!-- FIM form -->
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function($) {
            var $selects = $('select');
            $selects.on('change', function() {
                var $select = $(this),
                        $options = $selects.not($select).find('option'),
                        selectedText = $select.children('option:selected').val();

                var $optionsToDisable = $options.filter(function() {
                    return $(this).val() == selectedText;
                });

                $optionsToDisable.prop('disabled', true);
            });
        });
    </script>
@stop
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Validações de colunas</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">

            <div class="panel-body"></div>
            <!-- INICIO form -->
            {!! Form::open(['route' => 'admin.orcamentos.save', 'method'=>'post']) !!}
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
        var masterList = [];
        var selectedList = [];
        $(function() {


            Array.prototype.equals = function (array) {
                // if the other array is a falsy value, return
                if (!array)
                    return false;

                // compare lengths - can save a lot of time
                if (this.length != array.length)
                    return false;

                for (var i = 0, l=this.length; i < l; i++) {
                    // Check if we have nested arrays
                    if (this[i] instanceof Array && array[i] instanceof Array) {
                        // recurse into the nested arrays
                        if (!this[i].equals(array[i]))
                            return false;
                    }
                    else if (this[i] != array[i]) {
                        // Warning - two different object instances will never be equal: {x:20} != {x:20}
                        return false;
                    }
                }
                return true;
            }

            function createMasterList() {
                masterList = [];
                $('select').children('option').each(function() {
                    masterList.push($(this).val());
                });
                masterList.shift(); //remove blank value
            }

            createMasterList(); //used to check if all dropdown values have been selected

            function updateSelectedList() {
                selectedList = [];
                var selectedValue;
                $('select').each(function() {
                    selectedValue = $(this).find('option:selected').val();
                    if (selectedValue != "" && $.inArray(selectedValue, selectedList) == "-1") {
                        selectedList.push(selectedValue);
                    }
                });
            }

            //disable the dropdown items that have already been selected
            function disableAlreadySelected() {
                $('option').each(function() {
                    if ($.inArray(this.value, selectedList) != "-1") {
                        if(!$(this).is(':checked')){
                            $(this).attr("disabled", true);
                        }
                    } else {
                        $(this).attr("disabled", false);
                    }
                });
            }

            $('select').on('change', function() {
                setTimeout(function() {
                    updateSelectedList();
                    disableAlreadySelected();
                }, 10);
            });

        });
    </script>
@stop
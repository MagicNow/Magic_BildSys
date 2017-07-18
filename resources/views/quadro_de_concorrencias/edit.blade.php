@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Quadro de concorrência {{ $quadroDeConcorrencia->id }}
            <small class="label label-default pull-right margin10">
                <i class="fa fa-clock-o"
                   aria-hidden="true"></i> {{ $quadroDeConcorrencia->created_at->format('d/m/Y H:i') }}
                <i class="fa fa-user" aria-hidden="true"></i> {{ $quadroDeConcorrencia->user->name }}
            </small>
            <small class="label label-info pull-right margin10">
                <i class="fa fa-circle" aria-hidden="true" style="color:{{ $quadroDeConcorrencia->status->cor }}"></i>
                {{ $quadroDeConcorrencia->status->nome }}
            </small>
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-warning">
            <div class="box-body">
                <div class="row">
                {!! Form::model($quadroDeConcorrencia, ['route' => ['quadroDeConcorrencias.update', $quadroDeConcorrencia->id], 'method' => 'patch']) !!}

                @include('quadro_de_concorrencias.fields')

                <!-- Submit Field -->
                    <div class="form-group col-sm-9 pull-right text-right">

                        {!! Form::button( '<i class="fa fa-save"></i> Salvar e voltar', [
                            'class' => 'btn btn-success btn-lg btn-flat',
                            'type'=>'submit']) !!}

                        {!! Form::button( '<i class="fa fa-save"></i> Salvar', [
                            'class' => 'btn btn-success btn-lg btn-flat',
                            'value' => '1',
                            'name' => 'manter',
                            'style' => 'margin-left:10px',
                            'type'=>'submit']) !!}

                        {!! Form::button( '<i class="fa fa-check-square"></i> Enviar para validação', [
                            'class' => 'btn btn-success btn-lg btn-flat',
                            'value' => '1',
                            'name' => 'fechar_qc',
                            'style' => 'margin-left:10px',
                            'type'=>'submit']) !!}

                        {{--{!! Form::button( '<i class="fa fa-check-square"></i> Abrir concorrência', [--}}
                            {{--'class' => 'btn btn-success btn-lg btn-flat',--}}
                            {{--'value' => '1',--}}
                            {{--'name' => 'abrir_concorrencia',--}}
                            {{--'style' => 'margin-left:10px',--}}
                            {{--'type'=>'submit']) !!}--}}


                    </div>

                    {!! Form::close() !!}

                    @if(collect(\Illuminate\Support\Facades\Request::segments())->last()=='criar' )
                        <div class="form-group col-sm-6 pull-left">
                        {!! Form::open(['route' => ['quadroDeConcorrencias.destroy', $quadroDeConcorrencia->id], 'id'=>'formDelete'.$quadroDeConcorrencia->id, 'method' => 'delete']) !!}
                        {!! Form::button('<i class="fa fa-times"></i> cancelar', [
                                'type' => 'button',
                                'class' => 'btn btn-default btn-flat btn-lg',
                                'onclick' => "confirmDelete('formDelete".$quadroDeConcorrencia->id."');",
                                'title' => ucfirst(trans('common.delete'))
                            ]) !!}
                        {!! Form::close() !!}
                        </div>
                    @else
                        <div class="form-group col-sm-3 pull-left">
                            <a href="{!! route('quadroDeConcorrencias.index') !!}"
                               class="btn btn-default btn-flat btn-lg"><i class="fa fa-times"></i>
                                {{ ucfirst( trans('common.cancel') )}}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
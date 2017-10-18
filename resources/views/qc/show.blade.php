@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Q.C.
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('qc.timeline')
        {!! Form::open(['route' => ['qc.update', $qc->id], 'files' => true, 'method' => 'patch']) !!}
        <div class="box box-primary">
            <div class="box-body forp">
                <div class="row" style="padding-left: 20px">
                    @include('qc.show_fields')
                </div>
            </div>
            <div class="box-footer">
                <a href="{!! route('qc.index') !!}"
                    class="btn btn-warning">
                   <i class="fa fa-arrow-left"></i>
                   {{ ucfirst(trans('common.back')) }}
                </a>
                <div class="pull-right">
                    @if($qc->isEditable())
                        <a class="btn btn-success" href="{{ route('qc.edit', $qc->id) }}">
                            <i class="fa fa-floppy-o"></i> Editar Q. C.
                        </a>
                    @endif
                    @if($qc->canClose())
                        <button class="btn btn-info"
                            type="button"
                            id="fechar-qc"
                            data-id="{{ $qc->id }}">
                            <i class="fa fa-check-square"></i> Fechar Q.C.
                        </button>
                    @endif
                    @if($qc->canCancel())
                        <button class="btn btn-danger" type="button" onclick="cancelarQC({{ $qc->id }})">
                          <i class="fa fa-fw fa-times"></i>Cancelar
                        </button>
                    @endif
                    @if(isset($workflowAprovacao) && $qc->isEditable($workflowAprovacao))
                          <button type="submit" class="btn btn-success btn-flat">
                              <i class="fa fa-floppy-o"></i> Salvar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

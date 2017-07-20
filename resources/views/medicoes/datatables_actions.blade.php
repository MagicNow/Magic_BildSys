{!! Form::open(['route' => ['medicoes.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('medicoes.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @if(request()->segment(count(request()->segments()))!='edit' && $medicao_servico_finalizado)
    <span class="pull-right">

            @if(!is_null($aprovado))
            @if($aprovado)
                <button type="button" disabled="disabled"
                        class="btn btn-success btn-xs btn-flat">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </button>
            @else
                <button type="button" disabled="disabled"
                        class="btn btn-danger btn-xs btn-flat">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
            @endif
        @else
            <?php
            $workflowAprovacao = \App\Repositories\WorkflowAprovacaoRepository::verificaAprovacoes('Medicao', $id, Auth::user());
            ?>
            @if($workflowAprovacao['podeAprovar'])
                @if($workflowAprovacao['iraAprovar'])
                    <div class="btn-group" role="group" id="blocoItemAprovaReprova{{ $id }}" aria-label="...">
                            <button type="button" onclick="workflowAprovaReprova({{ $id }},'Medicao',1,'blocoItemAprovaReprova{{ $id }}','Medição {{ $id }}',0, '', '', false);"
                                    class="btn btn-success btn-xs btn-flat"
                                    title="Aprovar este item">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                            <button type="button" onclick="workflowAprovaReprova({{ $id }},'Medicao',0, 'blocoItemAprovaReprova{{ $id }}','Medição {{ $id }}',0, '', '', false);"
                                    class="btn btn-danger btn-xs btn-flat"
                                    title="Reprovar este item">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                @else
                    @if($workflowAprovacao['jaAprovou'])
                        @if($workflowAprovacao['aprovacao'])
                            <span class="btn-lg btn-flat text-success" title="Aprovado por você">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </span>
                        @else
                            <span class="text-danger btn-xs btn-flat" title="Reprovado por você">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </span>
                        @endif
                    @else
                        {{--Não Aprovou ainda, pode aprovar, mas por algum motivo não irá aprovar no momento--}}
                        <button type="button" title="{{ $workflowAprovacao['msg'] }}"
                                onclick="swal('{{ $workflowAprovacao['msg'] }}','','info');"
                                class="btn btn-default btn-xs btn-flat">
                                <i class="fa fa-info" aria-hidden="true"></i>
                            </button>
                    @endif
                @endif
            @endif
        @endif
        </span>
    @endif
    @if(request()->segment(count(request()->segments()))=='edit')
    <a href="{{ route('medicoes.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

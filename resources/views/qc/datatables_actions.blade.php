{!! Form::open(['route' => ['qc.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}

@if($status == 'Ativo' || $status == 'Inativo')
    <div class="btn-group" style="margin-top: 10px;">
        <!-- Rounded switch -->
        <label class="switch" title="{{$status == 'Ativo' ? 'Inativar' : 'Ativar'}}" data-toggle="tooltip" data-placement="top">
            <input type="checkbox" {{$status == 'Ativo' ? 'checked' : null}} onclick="ativarDesativarCatalogo({{$id}});">
            <span class="slider round"></span>
        </label>
    </div>
@endif

<div class='btn-group'>
    <a href="{{ route('qc.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @shield('catalogo_acordos.edit')
    <a href="{{ route('qc.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    @endshield
    @shield('catalogo_acordos.delete')
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}
    @endshield
    @shield('qc.anexos.list')
        @php
        # Check if exists folder and files in folder
        $dir = storage_path('app/public/qc/' . $id);
        if (file_exists($dir) && $handle = opendir($dir)) {
            while (($file = readdir($handle)) !== false){
                if (!in_array($file, array('.', '..')) && !is_dir($dir.$file)) {
                    @endphp
                    <a href="{{ route('qc.anexos', $id) }}" title="{{ ucfirst( trans('common.attachments') )}}" class='btn btn-warning btn-xs'>
                        <i class="glyphicon glyphicon-paperclip"></i>
                    </a>
                    @php
                    break;
                }
            }
        }
        @endphp
    @endshield
</div>
{!! Form::close() !!}

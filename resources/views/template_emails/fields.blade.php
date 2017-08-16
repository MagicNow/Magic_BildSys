{!! Form::hidden('chave', $templateEmail->chave, ['class' => 'form-control']) !!}


        <!-- Nome Field -->
<div class="form-group col-sm-12">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control']) !!}
</div>

<!-- Texto Field -->
<div class="form-group col-sm-12">
    {!! Form::label('template', 'Template:') !!}

            <!-- Info modal -->

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                       aria-expanded="false" aria-controls="collapseOne">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> Tags disponíveis para o template
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">

                    </ul>

                    <h5>Para que ao gerar um email os dados reais sejam carregados, é necessário usar estas tags onde
                        o sistema irá substituir automaticamente.
                    </h5>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h3 class="box-title">TAGS</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <ul class="list-group">
                                        <?php
                                        if (isset($templateEmail)) {
                                            if( strlen(trim($templateEmail->tags)) ){
                                                $tags = json_decode($templateEmail->tags);
                                            }
                                        }
                                        ?>
                                        @if(count($tags))
                                                @foreach($tags as $tag)
                                            <li class="list-group-item">
                                                <span class="label label-primary selecionavel">
                                                    {{ $tag->tag }}
                                                </span> &nbsp;
                                                {{ $tag->nome }}
                                            </li>
                                            @endforeach
                                        @endif
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
    {!! Form::textarea('template', null, ['class' => 'form-control htmleditor']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('templateEmails.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

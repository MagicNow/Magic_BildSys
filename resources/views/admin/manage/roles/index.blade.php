@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Dashboard
            <small>Controle de Acesso</small>
        </h1>
        <ol class="breadcrumb">
            <li ><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><a href="/admin/manage"> Controle de Acesso</a></li>
        </ol>
    </section>
    <div class="content" id='app'>
        <div class="row">
            <!-- Roles list -->
            <div class="col-sm-5" id="roles-list-container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span class="glyphicon glyphicon-align-justify"></span>
                            Perfis
                        </h3>
                    </div>
                    <div class="panel-body" v-show="roles.length > 0">
                        <table class="table table-bordered">
                            <thead>
                            <th>Id</th>
                            <th>Perfil</th>
                            <th>Ações</th>
                            </thead>
                            <tbody>
                            <tr v-for="role in roles">
                                <td>@{{ role.id }}</td>
                                <td>@{{ role.name }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="#" v-on:click="editRole(role)" class="btn btn-primary btn-xs">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer" v-show="roles.length > 0">
                        @include('vendor.pagination.vue-pagination')
                    </div>

                    <div class="panel-body" v-show="roles.length === 0">
                        <span class="text-danger text-center">
                            <strong>Não há papéis registrados</strong>
                        </span>
                    </div>
                </div>
            </div>
            <!--/Roles list -->
            <!-- Add a new role -->
            <div class="col-sm-7" id="add-role-container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Adicionar Novo Perfil
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-success" v-show="success">@{{ success }}</div>
                        <div class="alert alert-danger" v-show="error">@{{ error }}</div>

                        <form name="addRoleForm" id="addRoleForm" v-on:submit="saveRole($event)">
                            <div class="form-group">
                                <label for="role-name">Perfil</label>
                                <input type="text" id="role-name" class="form-control" v-model="role.name" />
                            </div>

                            <div class="form-group clearfix">
                                <label class="col-md-3 control-label">Permissão*</label>
                                <div class="col-md-7">
                                    {{ Form::select('permissions', $permissions, null, ['class' => 'form-control select2','multiple'=>'multiple','size'=>30, 'id' => 'permissions']) }}
                                </div>
                                <div class="col-md-2">
                                    <button type="button" v-on:click="addPermission()" class="btn btn-info">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <table class="table table-striped">
                                    <tr v-for="permission in role.permissions">
                                        {{--<td>@{{ permission.name }}</td>--}}
                                        <td class="text-left">@{{ permission.readable_name }}</td>
                                        <td>
                                            <button type="button" v-on:click="removePermission(permission)" class="btn btn-danger btn-xs">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <button type="submit"
                                    class="btn btn-success ladda-button"
                                    data-style="expand-right"
                                    v-bind:disabled="!role.name">
                                <span class="ladda-label">
                                    Salvar
                                </span>
                            </button>
                            <button type="button"
                                    class="btn btn-warning ladda-button"
                                    data-style="expand-right"
                                    v-on:click="resetRole"
                                    v-bind:disabled="!role.name">
                                <span class="ladda-label">
                                    Redefinir
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!--/Add a new role -->
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        new Vue({
            el: '#app',
            data: {
                roles: [],
                success: '',
                error: '',
                role: {
                    name:null,
                    permissions: []
                },
                pagination: {
                    total: 0,
                    per_page: 10,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4
            },
            computed: {
                isActived: function () {
                    return this.pagination.current_page;
                },
                pagesNumber: function () {
                    if (!this.pagination.to) {
                        return [];
                    }
                    var from = this.pagination.current_page - this.offset;
                    if (from < 1) {
                        from = 1;
                    }
                    var to = from + (this.offset * 2);
                    if (to >= this.pagination.last_page) {
                        to = this.pagination.last_page;
                    }
                    var pagesArray = [];
                    while (from <= to) {
                        pagesArray.push(from);
                        from++;
                    }
                    return pagesArray;
                }
            },
            methods: {
                exists: function(permissionObj) {
                    var keyNames = this.role.permissions.map(function(item) { return item["name"]; });
                    return $.inArray( permissionObj.name, keyNames );
                },
                addPermission: function() {
                    var $items  = $("#permissions option:selected");

                    if ($items.length == 0) {
                        return;
                    }

                    for (var $i in $items) {

                        if (typeof $items[$i] !== 'object' || typeof $items[$i].value == 'undefined') {
                            continue;
                        }

                        var $obj = {
                            'name': $items[$i].value,
                            'readable_name': $items[$i].innerHTML
                        };

                        var $exists = this.exists($obj);
                        if ($exists == -1) {
                            this.role.permissions.push($obj);
                        }

                    }
                },
                removePermission: function(permission) {
                    this.role.permissions = this.role.permissions.filter(function(item) {
                        return item != permission;
                    });
                },
                loadRoles: function (page) {
                    this.success = '';
                    this.error = '';

                    startLoading();
                    this.$http.get('/api/roles', {
                        params: { page: page }
                    }).then(function(resp) {
                        if(typeof resp.data == 'object') {
                            this.roles      = resp.data.data;
                            this.pagination = resp.data;
                        } else if (typeof resp.data =='string') {
                            var response=jQuery.parseJSON(resp.data);
                            this.roles      = response.data;
                            this.pagination = response;

                        }
                        this.resetSelect();
                        stopLoading();
                    });
                },
                resetSelect: function() {
                    $('#permissions').val(null).trigger("change");
                    $("#permissions option:selected").prop("selected", false);
                },
                resetRole: function() {
                    this.role = {name: null, permissions: []};
                    this.success = '';
                    this.error = '';
                    this.resetSelect();
                },
                saveRole: function(event) {
                    self = this;

                    self.error = '';
                    self.success = '';

                    startLoading();

                    event.preventDefault();
                    self.$http.post('/api/roles/store', {
                        role: self.role
                    }).then(function(resp) {
                        self.loadRoles(self.pagination.current_page);
                        self.resetRole();
                        self.success = resp.body.success;
                        stopLoading();
                    }, function(error_resp){
                        self.error = error_resp.body.error;
                        stopLoading();
                    });
                },
                editRole: function(role) {
                    self.error = '';
                    self.success = '';
                    this.role = role;
                },
                changePage: function (page) {
                    this.pagination.current_page = page;
                    this.loadRoles(page);
                }
            },
            created: function() {
                this.loadRoles(1);
            }
        });
    </script>
@endsection

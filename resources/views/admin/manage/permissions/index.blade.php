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
            <div class="col-sm-8" id="roles-list-container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span class="glyphicon glyphicon-align-justify"></span>
                            Papéis
                        </h3>
                    </div>
                    <div class="panel-body" v-show="permissions.length > 0">
                        <table class="table table-bordered">
                            <thead>
                            <th>Id</th>
                            <th>Permissão</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                            </thead>
                            <tbody>
                            <tr v-for="permission in permissions">
                                <td>@{{ permission.id }}</td>
                                <td>@{{ permission.name }}</td>
                                <td>@{{ permission.readable_name }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="#" v-on:click="editPermission(permission)" class="btn btn-primary btn-xs">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer" v-show="permissions.length > 0">
                        @include('vendor.pagination.vue-pagination')
                    </div>

                    <div class="panel-body" v-show="permissions.length === 0">
                        <span class="text-danger text-center">
                            <strong>Não há papéis registrados</strong>
                        </span>
                    </div>
                </div>
            </div>
            <!--/Roles list -->
            <!-- Add a new role -->
            <div class="col-sm-4" id="add-permission-container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Adicionar Nova Permissão
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-success" v-show="success">@{{ success }}</div>
                        <div class="alert alert-danger" v-show="error">@{{ error }}</div>

                        <form name="addPermissionForm" id="addPermissionForm" v-on:submit="savePermission($event)">
                            <div class="form-group">
                                <label for="role-name">Permissão</label>
                                <input type="text" id="role-name" class="form-control" v-model="permission.name" />
                            </div>
                            <div class="form-group">
                                <label for="role-name">Descrição</label>
                                <input type="text" id="role-name" class="form-control" v-model="permission.readable_name" />
                            </div>
                            <button type="submit"
                                    class="btn btn-success ladda-button"
                                    data-style="expand-right"
                                    v-bind:disabled="!permission.name || !permission.readable_name">
                                <span class="ladda-label">
                                    Adicionar
                                </span>
                            </button>
                            <button type="button"
                                    class="btn btn-warning ladda-button"
                                    data-style="expand-right"
                                    v-on:click="resetPermission"
                                    v-bind:disabled="!permission.name && !permission.readable_name">
                                <span class="ladda-label">
                                    Resetar
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

@section('last-scripts')
    <script>

        new Vue({
            el: '#app',
            data: {
                permissions: [],
                success: '',
                error: '',
                permission: {name:null, readable_name: null},
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
                loadData: function (page) {
                    this.success = '';
                    this.error = '';
                    loading(true);
                    this.$http.get('/api/permissions', {
                        params: { page: page }
                    }).then(function(resp) {
                        if(typeof resp.data == 'object') {
                            this.permissions = resp.data.data;
                            this.pagination = resp.data;
                        } else if (typeof resp.data =='string') {
                            var response=jQuery.parseJSON(resp.data);
                            this.permissions = response.data;
                            this.pagination = response;
                        }
                        loading(false)
                    });
                },
                resetPermission: function() {
                    this.permission = {name: null, readable_name: null};
                    this.success = '';
                    this.error = '';
                },
                savePermission: function(event) {
                    self = this;

                    self.error = '';
                    self.success = '';

                    event.preventDefault();
                    loading(true);
                    self.$http.post('/api/permissions/store', {
                        permission: self.permission
                    }).then(function(resp) {
                        self.loadData(self.pagination.current_page);
                        self.resetPermission();
                        self.success = resp.body.success;
                        loading(false);
                    }, function(error_resp){
                        self.error = error_resp.body.error;
                        loading(false);
                    });
                },
                editPermission: function(permission) {
                    self.error = '';
                    self.success = '';
                    this.permission = permission;
                },
                changePage: function (page) {
                    this.pagination.current_page = page;
                    this.loadData(page);
                }
            },
            created: function() {
                this.loadData(1);
            }
        });
    </script>
@endsection

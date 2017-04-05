# BILD SYS

SYS - Será um sistema que fará toda a gerência de cronograma, previsão orçamentária e gastos reais de cada empreendimento do cliente Bild.
O sistema funcionará em PHP com MySQL, sendo implantado pelos profissionais e na infraestrutura do próprio cliente.
O sistema conterá 6 módulos principais, sendo eles:

* Compras;

* Contratos/Aprovações;

* Medição de Mão de Obra/Contrato;

* Gerencial Dashboard/Relatório;

* Controle de obra;

* Pré orçamento / OI / OI+OD;

O sistema SYS será um sistema que integrará informações do ERP MEGA, porém mantendo o mínimo de dependência. Além disso, teremos um módulo de medição na obra em formato adaptativo ou app mobile, para informar os dados da obra no momento exato e no andar que está sendo avaliado.



----------------------------------------------------

Generators

##Custom Table Name

You can also specify your own custom table name by,


```
php artisan infyom:scaffold $MODEL_NAME --tableName=custom_table_name
```

## Generate From Table

```
php artisan infyom:scaffold $MODEL_NAME --fromTable --tableName=$TABLE_NAME
```

## Skip File Generation

The Generator also gives the flexibility to choose what you want to generate or what you want to skip. While using generator command, you can specify skip option to skip files which will not be generated.


```
php artisan infyom:api_scaffold Post --skip=routes,migration,model
```
You can specify any file from the following list:

migration
model
controllers
api_controller
scaffold_controller
scaffold_requests
routes
api_routes
scaffold_routes
views
tests
menu
dump-autoload
Custom Primary Key Name

By default, Generator takes the primary key as id field. But is also gives you the flexibility to use your own primary key field name via --primary option.


```
php artisan infyom:scaffold $MODEL_NAME --primary=custom_name_id
```

## Prefix option

Sometimes, you don't want to directly generate the files into configured folder but in a subfolder of it. Like, admin and that subfolder should be created with namespaces in all generated files. Then you can use --prefix=admin option.


```
php artisan infyom:scaffold $MODEL_NAME --prefix=admin
```
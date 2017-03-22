# Starter System l3

Com generators

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
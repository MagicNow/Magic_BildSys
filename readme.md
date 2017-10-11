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


## Requisitos

<table>
  <tr>
    <td>Tecnologia</td>
    <td>Requisito e versão</td>
  </tr>
  <tr>
    <td>Sistema Operacional</td>
    <td>Ubuntu Server 16.04 LTS</td>
  </tr>
  <tr>
    <td>Servidor Web</td>
    <td>Nginx 1.10.3</td>
  </tr>
  <tr>
    <td>Banco de Dados (SGBD)</td>
    <td>MySQL Community 5.7</td>
  </tr>
  <tr>
    <td>Linguagem de Programação</td>
    <td>PHP 7.1
Módulos: calendar, Core, ctype, curl, date, dom, exif, fileinfo, filter, ftp, gd, gettext, hash, iconv, imagick, json, libxml, mbstring, mcrypt, mysqli, mysqlnd, oci8, odbc, openssl, pcntl, pcre, PDO, pdo_dblib, pdo_mysql, PDO_ODBC, pdo_sqlite, Phar, posix, readline, Reflection, session, shmop, SimpleXML, soap, sockets, SPL, sqlite3, standard, sysvmsg, sysvsem, sysvshm, tokenizer, wddx, xml, xmlreader, xmlwriter, xsl, Zend OPcache, zip, zlib, Zend OPcache</td>
  </tr>
  <tr>
    <td>Gerenciador de Versões</td>
    <td>GIT</td>
  </tr>
  <tr>
    <td>Gerenciador de Pacotes</td>
    <td>Composer</td>
  </tr>
</table>

## Instalação

Para instalar o sistema basta clonar o projeto dentro do diretório do seu servidor web (Geralmente localizado em /var/www):


```
git clone https://bitbucket.org/fcmorenotecnologia/bild-sys.git
```

### Banco de dados

Crie o banco de dados à ser utilizado no sistema, onde deve ter o charset **UTF8** com o collation **utf8_general_ci**

Após o banco criado, pegue o arquivo .env.example e copie para um novo, .env, o qual possui todas as configurações necessárias.

Troque todas as informações necessárias, como banco, banco do Mega, dados de e-mail (SMTP).

Após alterada todas as informações, rode o comando para criar as tabelas e preencher o banco com os dados iniciais.

```
php artisan migrate
php artisan db:seed
```

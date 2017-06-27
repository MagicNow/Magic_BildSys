require('laravel-elixir-vue-2');
var gulp        = require('gulp');
var bower       = require('gulp-bower');
var elixir      = require('laravel-elixir');
var browserSync = require('browser-sync').create();

gulp.task('bower', function() {
    return bower();
});

var vendors = '../../vendor/';

var paths = {
    'jquery': vendors + '/jquery/dist',
    'bootstrap': vendors + '/bootstrap/dist',
    'bootswatch': vendors + '/bootswatch/lumen',
    'adminlte': vendors + '/adminlte/dist',
    'ionicons': vendors + '/ionicons',
    'icheck': vendors + '/iCheck',
    'fontawesome': vendors + '/font-awesome-bower',
    'colorbox': vendors + '/jquery-colorbox',
    'dataTables': vendors + '/datatables/media',
    'dataTablesBootstrap3Plugin': vendors + '/datatables-bootstrap3-plugin/media',
    'flag': vendors + '/flag-sprites/dist',
    'metisMenu': vendors + '/metisMenu/dist',
    'dt_responsive_js': vendors + '/datatables.net-responsive/js/dataTables.responsive.js',
    'dt_responsive_css': vendors + '/datatables.net-responsive-dt/css/responsive.dataTables.css',
    'summernote': vendors + '/summernote/dist',
    'select2': vendors + '/select2/dist',
    'jqueryui':  vendors + '/jquery-ui',
    'justifiedGallery':  vendors + '/Justified-Gallery/dist/',
    'mustache':  vendors + '/mustache/',
    'select2':  vendors + '/select2/dist/',
    'select2theme': vendors + '/select2-bootstrap-theme/dist/',
    'modernizr':  vendors + '/modernizr/',
    'bootstrapdatepicker':  vendors + '/bootstrap-datepicker/dist/',
    'jquerymaskplugin':  vendors + '/jquery-mask-plugin/dist/',
    'sweetalert':  vendors + '/sweetalert/dist/',
    'fullcalendar':  vendors + '/fullcalendar/dist/',
    'moment':  vendors + '/moment/',
    'bootstrap3_typeahead':  vendors + '/bootstrap-3-typeahead/',
    'bootstrap_calendar':  vendors + '/bootstrap-calendar/',
    'underscore':  vendors + '/underscore/',
    'vue':  vendors + '/vue/dist/',
    'vue_resource':  vendors + '/vue-resource/dist/',
    'chartjs':  vendors + '/chart.js/dist/',
    'jquery_maskmoney':  vendors + '/jquery-maskmoney/dist/',
    'slugify':  vendors + '/slug/',
    'slim_scroll':  vendors + '/jquery-slimscroll/',
    'querystring':  vendors + '/querystring/'
};

elixir.config.sourcemaps = false;

elixir(function(mix) {

    // Run bower install
    mix.task('bower');

    // Copy fonts straight to public
    mix.copy('resources/vendor/bootstrap/dist/fonts/**', 'public/fonts');
    mix.copy('resources/vendor/font-awesome-bower/fonts/**', 'public/fonts');
    mix.copy('resources/vendor/summernote/dist/font/**', 'public/css/font');
    mix.copy('resources/assets/fonts/**', 'public/css/font');
    mix.copy('resources/vendor/ionicons/fonts/**', 'public/fonts');

    // Copy images straight to public
    mix.copy('resources/vendor/jquery-colorbox/example3/images/**', 'public/css/images');
    mix.copy('resources/vendor/jquery-ui/themes/base/images/**', 'public/css/images');


    mix.copy('resources/vendor/iCheck/skins/square/green.png', 'public/css');
    mix.copy('resources/vendor/iCheck/skins/square/green@2x.png', 'public/css');


    mix.copy('resources/assets/js/datatables/Portuguese-Brasil.json', 'public/vendor/datatables/Portuguese-Brasil.json');


    // Copy flag resources
    mix.copy('resources/vendor/flag-sprites/dist/css/flag-sprites.min.css', 'public/css/flags.css');
    mix.copy('resources/vendor/flag-sprites/dist/img/flags.png', 'public/img/flags.png');

    mix.copy('resources/vendor/bootstrap-calendar/tmpls/**', 'public/tmpls');


    Elixir.webpack.mergeConfig({
        module: {
            loaders: [{
                test: /\.jsx?$/,
                loader: 'babel',
                exclude: /node_modules(?!\/(vue-tables-2|vue-pagination-2))\//
            }]
        }
    });

    mix.sass('app.scss')
        .webpack('app.js')
        .copy('node_modules/bootstrap-sass/assets/fonts/bootstrap/','public/fonts/bootstrap');

    // Merge Site CSSs.
    mix.styles([
        paths.bootstrap + '/css/bootstrap.css',
        paths.bootstrap + '/css/bootstrap-theme.css',
        paths.fontawesome + '/css/font-awesome.css',
        // paths.bootswatch + '/bootstrap.css',
        paths.colorbox + '/example3/colorbox.css',
        paths.summernote + '/summernote.css',
        paths.justifiedGallery + '/css/justifiedGallery.css',
        paths.select2 + 'css/select2.css',
        paths.bootstrapdatepicker + 'css/bootstrap-datepicker.css',
        paths.sweetalert + 'sweetalert.css',
        paths.fullcalendar + 'fullcalendar.css',
        'site.css'
    ], 'public/css/site.css');

    // Merge Site scripts.
    mix.scripts([
        paths.jquery + '/jquery.js',
        paths.bootstrap + '/js/bootstrap.js',
        paths.colorbox + '/jquery.colorbox.js',
        paths.summernote + '/summernote.js',
        paths.justifiedGallery + '/js/jquery.justifiedGallery.js',
        paths.mustache + '/mustache.js',
        paths.select2 + 'js/select2.js',
        paths.select2 + 'js/i18n/pt-BR.js',
        paths.modernizr + 'modernizr.js',
        paths.bootstrapdatepicker + 'js/bootstrap-datepicker.js',
        paths.bootstrapdatepicker + 'locales/bootstrap-datepicker.pt-BR.min.js',
        paths.jquerymaskplugin + 'jquery.mask.js',
        paths.sweetalert + 'sweetalert.min.js',
        paths.moment + 'moment.js',
        paths.moment + '/locale/pt-br.js',
        // paths.fullcalendar + 'fullcalendar.js',
        // paths.fullcalendar + 'lang/pt-br.js',
        'site.js'
    ], 'public/js/site.js');

    // Merge Admin CSSs.
    mix.styles([
        paths.bootstrap + '/css/bootstrap.css',
        // paths.bootstrap + '/css/bootstrap-theme.css',
        paths.fontawesome + '/css/font-awesome.css',
        // paths.bootswatch + '/bootstrap.css',
        paths.adminlte + '/css/AdminLTE.css',
        paths.adminlte + '/css/skins/skin-yellow-light.css',
        paths.ionicons + '/css/ionicons.css',
        paths.colorbox + '/example3/colorbox.css',
        paths.dataTables + '/css/dataTables.bootstrap.css',
        paths.dt_responsive_css,
        paths.dataTablesBootstrap3Plugin + '/css/datatables-bootstrap3.css',
        // paths.metisMenu + '/metisMenu.css',
        paths.summernote + '/summernote.css',
        paths.select2 + '/css/select2.css',
        paths.select2theme + 'select2-bootstrap.css',
        paths.jqueryui + '/themes/base/minified/jquery-ui.min.css',
        paths.sweetalert + 'sweetalert.css',
        paths.icheck + '/skins/square/green.css',
        paths.bootstrap_calendar + '/css/calendar.css',
        '../js/datatables/css/buttons.dataTables.min.css',
        'orange.css',
        'admin.css',
    ], 'public/css/admin.css');

    // Merge Admin scripts.
    mix.scripts([
        paths.jquery + '/jquery.js',
        paths.jqueryui + '/ui/jquery-ui.js',
        paths.bootstrap + '/js/bootstrap.js',
        paths.adminlte + '/js/app.js',
        paths.colorbox + '/jquery.colorbox.js',
        paths.icheck + '/icheck.js',
        paths.dataTables + '/js/jquery.dataTables.js',
        paths.dt_responsive_js,
        paths.dataTables + '/js/dataTables.bootstrap.js',
        paths.dataTablesBootstrap3Plugin + '/js/datatables-bootstrap3.js',
        paths.metisMenu + '/metisMenu.js',
        paths.summernote + '/summernote.js',
        paths.select2 + 'js/select2.js',
        paths.select2 + 'js/i18n/pt-BR.js',
        paths.sweetalert + 'sweetalert.min.js',
        paths.jquerymaskplugin + 'jquery.mask.js',
        // paths.bootstrap3_typeahead + 'bootstrap3-typeahead.js',
        paths.underscore + '/underscore.js',
        paths.bootstrap_calendar + '/js/calendar.js',
        paths.bootstrap_calendar + '/js/language/pt-BR.js',
        paths.jquerymaskplugin + 'jquery.mask.js',
        paths.chartjs + 'Chart.min.js',
        paths.jquery_maskmoney + 'jquery.maskMoney.js',
        paths.slugify + 'slug.js',
        paths.slim_scroll + 'jquery.slimscroll.js',
        paths.querystring + 'querystring.min.js',
        // paths.vue + 'vue.js',
        // paths.vue_resource + 'vue-resource.js',
        'bootstrap-dataTables-paging.js',
        'dataTables.bootstrap.js',
        'datatables.fnReloadAjax.js',
        'datatables/buttons.colVis.min.js',
        'datatables/buttons.server-side.js',
        'datatables/dataTables.buttons.min.js',
        'datatables/buttons.html5.min.js',
        'datatables/buttons.flash.min.js',
        'admin.js',
        'filtros-dinamicos.js',
        'workflow.js',
        'qc-informar-valores.js',
        'sweetalert-helper.js',
        'flat-color-generator.js',
          'qc-avaliar.js',
        'error-list.js',
        'is-mobile.js',
        'notifications.js',
    ], 'public/js/admin.js');

    mix.scripts([
        'quadro-de-concorrencia.js',
    ], 'public/js/qc.js');

    mix.scripts([
        'insumo-activation.js',
    ], 'public/js/insumo-activation.js');

    mix.scripts([
        'insumo-grupo-activation.js',
    ], 'public/js/insumo-grupo-activation.js');

    mix.scripts([
        'grupos-de-orcamento.js',
    ], 'public/js/grupos-de-orcamento.js');

    mix.scripts([
        'general-filters.js',
    ], 'public/js/general-filters.js');

    mix.scripts([
        'filter-date.js',
    ], 'public/js/filter-date.js');

    mix.scripts([
        'contrato-actions.js',
    ], 'public/js/contrato-actions.js');

    mix.scripts([
        'carrinho.js',
    ], 'public/js/carrinho.js');

});

gulp.task('browser-sync', function() {
    browserSync.init({
        proxy: "bild-sys.dev"
    });
});

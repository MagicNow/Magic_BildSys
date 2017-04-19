
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

// Vue.component('example', require('./components/Example.vue'));

// Vue.component('generic-grid',require('./components/generic-grid.vue'));
require('chart.js');
require('hchs-vue-charts');
Vue.use(VueCharts);

Vue.component('generic-paginator',require('./components/generic-paginator.vue'));

import Tabela from './components/tabela.vue'
import Tile from './components/tile.vue'
import TileGrafico from './components/tile-grafico.vue'

Vue.component('tabela', Tabela);
Vue.component('tile', Tile);
Vue.component('tile-grafico', TileGrafico);
require('../css/app.css');
import Vue from 'vue'

import App from '~/components/App.vue';

Vue.component('hello-world', () => import('~/components/HelloWorld.vue'));

new Vue({
  el: "#app",
  render: h => h(App)
});


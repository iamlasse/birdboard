require('./bootstrap');

import Vue from 'vue';

import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import EchoApp from './App.vue'

Vue.prototype.$route = (...args) => route(...args).url()

Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);

const app = document.getElementById('app');

Vue.mixin({
    methods: {
      error(field, errorBag = 'default') {
        if (!this.$page.errors.hasOwnProperty(errorBag)) {
          return null;
        }
  
        if (this.$page.errors[errorBag].hasOwnProperty(field)) {
          return this.$page.errors[errorBag][field][0];
        }
  
        return null;
      }
    }
  });

new Vue({   
    // render: h => h(App), 
    render: (h) =>
        h(EchoApp),
}).$mount(app);

import './bootstrap';
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@mdi/font/css/materialdesignicons.css'

import Vue from 'vue';
import VeeValidate, { Validator } from 'vee-validate';
//import es from 'vee-validate/dist/locale/es';
import es from 'vuetify/es5/locale/es'
import VueSignaturePad from 'vue-signature-pad';

import pluginAlerts from "./plugins/alerts";
import multiFilters from "./plugins/multiFilters";
import store from './store'
import router from './router'
import VueYoutube from 'vue-youtube';
import LazyTube from "vue-lazytube";

import Vuetify from 'vuetify';
Vue.use(Vuetify,{
    theme:{
        primary: '#0074a6', //'#0064ff',   //'#01c0c8', // #E53935
        secondary: '#ffffff',
        modalHeaders: '#0074a6',
        accent: '#f4f4f4', //'#eeeeee',
        redvitalAzul: '#009bad',
        redvitalVerde: '#86b43c',
        home: '#333333',
        nav_hc: '#457aff',
        mymessage: '#ffffff',
        othermessage: '#357CFF',
        iconfont: 'md'
    },
    lang: {
        locales: { es },
        current: 'es'
    }
})

Vue.use(VueYoutube)
Vue.use(LazyTube);
Vue.use(pluginAlerts);
Vue.use(VeeValidate);
Vue.use(VueSignaturePad);
Vue.use(multiFilters);

//Validator.localize("es", es);

import '../stylus/main.styl'
import 'vuetify/dist/vuetify.min.css'


import App from './views/App.vue';


const app = new Vue({
    el: '#app',
    store,
    router,
    component: App,
    render: (h) => h(App),
});

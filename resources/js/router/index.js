import Vue from 'vue';
import Router from 'vue-router';
import store from './../store';


Vue.use(Router);

import home from './modules/home.js';
import auth from './modules/auth.js';
import password from './modules/password.js';
import redvital from './modules/redvital.js';
import operador from './modules/operador.js';
import generarpassword from './modules/generarPassword.js';
import consentimiento from './modules/consentimientoInformado';


const routes = [
  home,
  auth,
  redvital,
  password,
  generarpassword,
  operador,
  consentimiento
]

const router =  new Router({
  // mode: 'history', // Require service support
  mode: 'history',
  routes
});

router.beforeEach(async (to, from, next) => {
  if( (to.path.includes('/gestion')) | (to.path.includes('/firmar-consentimiento')) | (to.path.includes('/recuperar/password/')) | (to.path.includes('/operador')) |
  (to.path.includes('/generar/password/')) ){
    next()
  }else if(to.path !== '/login'){
    if(localStorage.getItem('_token')){
      await axios.get('/api/auth/user')
          .then((res) => {
            store.commit("setUser", res.data.user)
            next()
          })
          .catch((res) => {
              localStorage.removeItem('_token');
              next('login')
          })
    }
    else next('login');
  }else if(to.path === '/login' && localStorage.getItem('_token')){
    next('/')
  }else{
    next();
  }
});

export default router;

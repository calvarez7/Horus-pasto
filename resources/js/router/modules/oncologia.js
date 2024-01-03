import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('oncologia.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/oncologia',
    component: () => import('./../../views/modules/oncologia/OncologiaLayout.vue'),
    name: 'oncologia',
    children: [{
            path: '/',
            component: () => import('./../../views/modules/oncologia/EsquemaCreate.vue'),
        },
        {
            path: 'ordenado',
            component: () => import('./../../views/modules/oncologia/Ordenado.vue'),
        },
        {
             path: 'enfermera',
            component: () => import('./../../views/modules/oncologia/Enfermera.vue'),
         },
         {
              path: 'notasenfermeria',
             component: () => import('./../../views/modules/oncologia/NotasEnfermeria.vue'),
          },
        {
            path: 'autorizacion',
            component: () => import('./../../views/modules/oncologia/Autorizacion.vue'),
        }
    ]
}

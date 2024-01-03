import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('teleconcepto.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/teleconcepto',
    component: () => import('./../../views/modules/teleconcepto/TeleConceptoLayout.vue'),
    name: 'Teleorientacion',
    children: [{
        path: '/',
        component: () => import('./../../views/modules/teleconcepto/TeleConceptoCreate.vue'),
    },
    {
        path: 'pendientes',
        component: () => import('./../../views/modules/teleconcepto/TeleConceptoPending.vue'),
        name: 'pendientes'
    },
        {
            path: 'girs',
            component: () => import('./../../views/modules/teleconcepto/TeleConceptoGirs.vue'),
            name: 'girs'
        },
    {
        path: 'solucionados',
        component: () => import('./../../views/modules/teleconcepto/TeleConceptoSolved.vue'),
        name: 'solucionados',
    },
    {
        path: 'reportes',
        component: () => import('./../../views/modules/teleconcepto/TeleConceptoReporte.vue'),
        name: 'reportes',
    }]
}

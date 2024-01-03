import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('medicamentos.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/dispensacion',
    component: () =>
        import('./../../views/modules/medicamentos/MedicamentoLayout.vue'),
    name: 'medicamentos',
    children: [{
            path: '/',
            component: () =>
                // import('./../../views/modules/medicamentos/Medicamentos.vue')
                import('./../../views/modules/medicamentos/EntregaMedicamentos.vue')
        },
        {
            path: '/medicamentos',
            component: () =>
                // import('./../../views/modules/medicamentos/Medicamentos.vue')
                import('./../../views/modules/medicamentos/Medicamentos.vue')
        },
        {
            path: '/medicamentos/firmas',
            component: () =>
                import('./../../views/modules/medicamentos/FirmaOrdenes.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('farmacoterapuetico.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/medicamentos/farmacoterapuetico',
            component: () =>
                import('./../../views/modules/medicamentos/Farmacoterapuetico.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('alertas.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/medicamentos/alertas',
            component: () =>
                import('./../../views/modules/medicamentos/AlertasFarmacologicas.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('alertas.segumiento.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/medicamentos/alertas/seguimiento',
            component: () =>
                import('./../../views/modules/medicamentos/AlertasSegumiento.vue')
        }
    ]
}

import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('contratacion.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/manual-tarifario',
    component: () =>
        import('./../../views/modules/manual_tarifario/ManualLayout.vue'),
    name: 'manual_tarifario',
    children: [{
            path: '/',
            component: () =>
                import('./../../views/modules/manual_tarifario/PrestadoresTarifario.vue')
        },
        // {
        //     path: '/manual-tarifario/codigo-propios',
        //     component: () =>
        //         import('./../../views/modules/manual_tarifario/CodigoPropio.vue')
        // },
        {
            path: '/manual-tarifario/contratos',
            component: () =>
                import('./../../views/modules/manual_tarifario/HomeTarifario.vue')
        },
        {
            path: '/manual-tarifario/cup',
            component: () =>
                import('./../../views/modules/manual_tarifario/CupTarifario.vue')
        },
        {
            path: '/manual-tarifario/codigos-sumi',
            component: () =>
                import('./../../views/modules/admin/CodSumiMedicamentos.vue')
        },
        {
            path: '/manual-tarifario/cups',
            component: () =>
                import('./../../views/modules/admin/Cups.vue')
        },
    ]
}
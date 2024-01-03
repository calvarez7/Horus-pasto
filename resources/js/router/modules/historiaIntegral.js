import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('medico.enter')
        if (auth) next();
        else next(from);
    }),
    path: '/historiaIntegral',
    component: () =>
        import('./../../views/modules/historiaIntegral/HistoriaIntegralLayout.vue'),
    name: 'historiaIntegral',
    children: [{
            path: '/',
            component: () =>
                import('./../../views/modules/historiaIntegral/PanelMedico.vue')
        },
        {
        path: '/historiaIntegral/parametrizacion',
        component: () =>
            import('./../../views/modules/historiaIntegral/Parametrizacion.vue')
        },
    ]
}

import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('fias.entrar')
        if(auth) next();
        else next(from);
    }),
    path: '/fias',
    component: () =>
        import('./../../views/modules/fias/fiasLayout.vue'),
    name: 'fias',
    children: [
        {
            path: '/riesgoCardiovascular',
            component: () =>
                import('./../../views/modules/fias/riesgoCardiovascular.vue')
        },
        {
            path: '/cancer',
            component: () =>
                import('./../../views/modules/fias/cancer.vue')
        },
        {
            path: '/hemofilia',
            component: () =>
                import('./../../views/modules/fias/hemofilia.vue')
        },
        {
            path: '/artritis',
            component: () =>
                import('./../../views/modules/fias/artritis.vue')
        },
        {
            path: '/huerfanas',
            component: () =>
                import('./../../views/modules/fias/huerfanas.vue')
        },
        {
            path: '/transplantes',
            component: () =>
                import('./../../views/modules/fias/transplantes.vue')
        },
        {
            path: '/cac',
            component: () =>
                import('./../../views/modules/fias/ReporteCuentaAltoCosto.vue')
        },
        {
            path: '/reporte',
            component: () =>
                import('./../../views/modules/fias/FiasReport.vue')
        },
        {
            path: '/indicadores',
            component: () =>
                import('./../../views/modules/fias/Indicadores.vue')
        },
    ]
}

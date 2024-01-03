import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('202.entrar')
        if(auth) next();
        else next(from);
    }),
    path: '/202',
    component: () =>
        import('./../../views/modules/rips/RipsLayout.vue'),
    name: '202',
    children: [{
        path: '/',
        component: () =>
            import('./../../views/modules/rips/202Validador.vue')
        },
        {
            path: 'registros',
            component: () =>
                import('./../../views/modules/rips/202Registros.vue')
        },
        {
            path: 'reportes',
            component: () =>
                import('./../../views/modules/rips/202Reportes.vue')
        },
    ]
}

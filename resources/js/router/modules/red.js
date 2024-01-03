import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('red.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/red',
    component: () =>
        import('./../../views/modules/seguimientored/RedLayout'),
    name: 'red',
    children: [{
        path: '/',
        component: () =>
            import('./../../views/modules/seguimientored/Oportunidad')
        },
        {
            path: 'facturacion',
            component: () =>
                import('./../../views/modules/seguimientored/Facturacion')
        },
        {
            path: 'ejecucion',
            component: () =>
                import('./../../views/modules/seguimientored/Ejecucion')
        },
        {
            path: 'Seguimiento',
            component: () =>
                import('./../../views/modules/seguimientored/SeguimientoRed')
        },
    ]
}

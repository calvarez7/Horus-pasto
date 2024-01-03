import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('any')
        if(auth) next();
        else next(from);
    }),
    path: '/covi',
    component: () =>
        import('./../../views/modules/covi/CoviLayout.vue'),
    name: 'Covi',
    children: [
        {
            path: '/covi/ingreso',
            component: () =>
                import('./../../views/modules/covi/Registro.vue')
        },
        {
            path: '/covi/admision',
            component: () =>
                import('./../../views/modules/covi/Admision.vue')
        },
        {
            path: '/covi/seguimiento',
            component: () =>
                import('./../../views/modules/covi/Seguimiento.vue')
        },
        {
            path: '/covi/alta',
            component: () =>
                import('./../../views/modules/covi/Alta.vue')
        },

        {
            path: '/covi/reporte',
            component: () =>
                import('./../../views/modules/covi/Reporte.vue')
        }
    ]
}

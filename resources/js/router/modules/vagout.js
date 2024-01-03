import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('vagout.enter')
        if (auth) next();
        else next(from);
    }),
    path: '/vagout',
    component: () =>
        import('./../../views/modules/vagout/VagoutLayout.vue'),
        name: 'vagout',
    children: [
        {
            path: '/vagout',
            component: () =>
                import('./../../views/modules/vagout/Vagout.vue')
        },
        {
            path: '/vagout/facturas',
            component: () =>
                import('./../../views/modules/vagout/VagoutFacturas.vue')
        },
        {
            path: '/vagout/productos',
            component: () =>
                import('./../../views/modules/vagout/VagoutProductos.vue')
        },
        {
            path: '/vagout/Reportes',
            component: () =>
                import('./../../views/modules/vagout/VagoutReportes.vue')
        },
        {
            path: '/vagout/ingredientes',
            component: () =>
                import('./../../views/modules/vagout/VagoutIngredientes.vue')
        },
        {
            path: '/vagout/menus',
            component: () =>
                import('./../../views/modules/vagout/VagoutMenu.vue')
        },
    ]
}

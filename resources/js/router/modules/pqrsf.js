import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('any')
        if (auth) next();
        else next(from);
    }),
    path: '/pqrsf',
    component: () =>
        import('./../../views/modules/pqrsf/PqrsfLayout.vue'),
    name: 'pqrsf',
    children: [{
            path: '/',
            component: () =>
                import('./../../views/modules/pqrsf/PqrsfLayout.vue')
        },
        {
            path: '/pqrsf/formulario',
            component: () =>
                import('./../../views/modules/pqrsf/PqrsfFormulario.vue')
        },
        {
            path: '/pqrsf/gestion-externa',
            component: () =>
                import('./../../views/modules/pqrsf/gestion_externa/PqrsfGestion.vue'),
            children: [{
                    path: 'pendientes',
                    component: () =>
                        import('./../../views/modules/pqrsf/gestion_externa/PqrsfPendiente.vue')
                },
                {
                    path: 'asignados',
                    component: () =>
                        import('./../../views/modules/pqrsf/gestion_externa/PqrsfAsignado.vue')
                },
                {
                    path: 'presolucionados',
                    component: () =>
                        import('./../../views/modules/pqrsf/gestion_externa/PqrsfPresolucionado.vue')
                },
                {
                    path: 'solucionados',
                    component: () =>
                        import('./../../views/modules/pqrsf/gestion_externa/PqrsfSolucionado.vue')
                }
            ]
        },
        {
            path: '/pqrsf/gestion-interna',
            component: () =>
                import('./../../views/modules/pqrsf/gestion_interna/PqrsfGestion.vue'),
            children: [{
                    path: 'pendientes',
                    component: () =>
                        import('./../../views/modules/pqrsf/gestion_interna/PqrsfPendiente.vue')
                },
                {
                    path: 'asignados',
                    component: () =>
                        import('./../../views/modules/pqrsf/gestion_interna/PqrsfAsignado.vue')
                },
                {
                    path: 'presolucionados',
                    component: () =>
                        import('./../../views/modules/pqrsf/gestion_interna/PqrsfPresolucionado.vue')
                },
                {
                    path: 'solucionados',
                    component: () =>
                        import('./../../views/modules/pqrsf/gestion_interna/PqrsfSolucionado.vue')
                }
            ]
        },
        {
            path: '/pqrsf/gestion',
            component: () =>
                import('./../../views/modules/pqrsf/gestion/PqrsfGestion.vue'),
            children: [
                {
                    path: 'asignados',
                    component: () =>
                        import('./../../views/modules/pqrsf/gestion/PqrsfAsignado.vue')
                },
                {
                    path: 'solucionados',
                    component: () =>
                        import('./../../views/modules/pqrsf/gestion/PqrsfSolucionado.vue')
                }
            ]
        },
    ]
}

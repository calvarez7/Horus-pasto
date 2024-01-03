import store from '../../store/index';

export default {
    path: '/sarlaft',
    component: () =>
        import('./../../views/modules/sarlaft/SarlaftLayout.vue'),
    name: 'sarlaft',
    children: [{
        beforeEnter: (async (to, from, next) => {
            let auth = await store.getters.can('sarlaft.enter')
            if(auth) next();
            else next(from);
        }),
        path: '/',
        component: () =>
            import('./../../views/modules/sarlaft/SarlaftFormulario.vue')
    },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('revisionSarlaft.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/sarlaft/revision',
            component: () =>
                import('./../../views/modules/sarlaft/revision/SarlaftRevision.vue'),
            children: [
                {
                    path: 'pendientes',
                    component: () =>
                        import('./../../views/modules/sarlaft/revision/SarlaftPendientes.vue')
                },
                {
                    path: 'revisados',
                    component: () =>
                        import('./../../views/modules/sarlaft/revision/SarlaftRevisados.vue')
                }
            ]
        },
    ]
}

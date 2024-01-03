import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('rips.entrar')
        if(auth) next();
        else next(from);
    }),
    path: '/rips',
    component: () =>
        import('./../../views/modules/rips/RipsLayout.vue'),
    name: 'rips',
    children: [{
            path: '/',
            component: () =>
                import('./../../views/modules/rips/validador.vue')
        },
        {
            path: 'radicados',
            component: () =>
                import('./../../views/modules/rips/RipsRadicados.vue')
        },
        {
            path: 'resolucion',
            component: () =>
                import('./../../views/modules/rips/RipsResolucion1552.vue')
        },
        {
            path: 'pendientes',
            component: () =>
                import('./../../views/modules/rips/RipsPendientes.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('rips.reporte.entrar')
                if(auth) next();
                else next(from);
            }),
            path: 'reportes',
            component: () =>
                import('./../../views/modules/rips/RipsReportes.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('rips.reporte.entrar')
                if(auth) next();
                else next(from);
            }),
            path: 'descargas',
            component: () =>
                import('./../../views/modules/rips/RipsDescarga.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('rips.reps')
                if(auth) next();
                else next(from);
            }),
            path: 'reps',
            component: () =>
                import('./../../views/modules/rips/reps.vue')
        },
    ]
}

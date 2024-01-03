import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('any')
        if(auth) next();
        else next(from);
    }),
    path: '/evento',
    component: () =>
        import('./../../views/modules/eventos/EventoLayout.vue'),
    name: 'evento',
    children: [
        {
        path: '/evento/reporte',
        component: () =>
            import('./../../views/modules/eventos/EventoReporte.vue')
        },
        {
        path: '/evento/analisis',
        component: () =>
            import('./../../views/modules/eventos/EventoAnalisis.vue')
        },
        {
        path: '/evento/informes',
        component: () =>
            import('./../../views/modules/eventos/EventoInforme.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('evento.admin')
                if(auth) next();
                else next(from);
            }),
            path: '/evento/admin',
            component: () =>
                import('./../../views/modules/eventos/EventoAdmin.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('any')
                if(auth) next();
                else next(from);
            }),
            path: '/evento/asignados',
            component: () =>
                import('./../../views/modules/eventos/EventoAsignado.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('any')
                if(auth) next();
                else next(from);
            }),
            path: '/evento/planmejora',
            component: () =>
                import('./../../views/modules/eventos/EventoPlanMejora.vue')
        }
    ]
}

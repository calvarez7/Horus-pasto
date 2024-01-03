import store from '../../store/index';

export default {
    // beforeEnter: (async (to, from, next) => {
    //     let auth = await store.getters.can('transcripcion.enter')
    //     if(auth) next();
    //     else next(from);
    // }),
    path: '/auditoria',
    component: () =>
        import('./../../views/modules/auditoria/AuditoriaLayout.vue'),
    name: 'auditoria',
    children: [{
        path: '/auditoria/home',
        component: () =>
            // import('./../../views/modules/auditoria/Auditoria.vue')
            import('./../../views/modules/auditoria/AuditoriaMedicamento.vue')
        },{
        path: '/auditoria/global',
        component: () =>
            // import('./../../views/modules/auditoria/Auditoria.vue')
            import('./../../views/modules/auditoria/AuditoriaMedicamentoGlobal.vue')
        },{
            path: '/auditoria/service',
            component: () =>
                // import('./../../views/modules/auditoria/AuditoriaService.vue')
                import('./../../views/modules/auditoria/AuditoriaServicio.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('auditoria.historico.enter')
                if (auth) next();
                else next(from);
            }),
            path: '/auditoria/historico',
            component: () =>
                import('./../../views/modules/auditoria/Historico.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('auditoria.laboratorio.enter')
                if (auth) next();
                else next(from);
            }),
            path: '/auditoria/laboratorio',
            component: () =>
                import('./../../views/modules/auditoria/Laboratorio.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('auditoria.historico.enter')
                if (auth) next();
                else next(from);
            }),
            path: '/auditoria/consentimientos',
            component: () =>
                import('./../../views/modules/auditoria/consentimiento.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('auditoria.historicoImagenologia.enter')
                if (auth) next();
                else next(from);
            }),
            path: '/auditoria/historicoImagennologia',
            component: () =>
                import('./../../views/modules/auditoria/Historicoimagenologia.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('auditoria.incapacidades.enter')
                if (auth) next();
                else next(from);
            }),
            path: '/auditoria/incapacidad',
            component: () =>
                import('./../../views/modules/auditoria/Incapacidades.vue')
        }, {
            path: '/auditoria/entidades',
            component: () =>
                import('./../../views/modules/auditoria/HistoricoEntidades.vue')
        }]
}

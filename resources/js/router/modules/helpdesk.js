import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('any')
        if(auth) next();
        else next(from);
    }),
    path: '/helpdesk',
    component: () =>
        import('./../../views/modules/helpdesk/HelpdeskLayout.vue'),
    name: 'helpdesk',
    children: [
        {
        path: '/',
        component: () =>
            import('./../../views/modules/helpdesk/HelpdeskReport.vue')
        },
        {
            path: '/helpdesk/misolicitud/*',
            component: () =>
                import('./../../views/modules/helpdesk/HelpdeskMiSolicitud.vue')
        },
        {
            path: '/helpdesk/solicitudarea/*',
            component: () =>
                import('./../../views/modules/helpdesk/HelpdeskSolicitudArea.vue')
        },
        {
            path: '/helpdesk/miasignada/*',
            component: () =>
                import('./../../views/modules/helpdesk/HelpdeskMiAsignada.vue')
        },
        {
            path: '/helpdesk/informe',
            component: () =>
                import('./../../views/modules/helpdesk/HelpdeskInforme.vue')
        },
        {
            path: '/helpdesk/admin',
            component: () =>
                import('./../../views/modules/helpdesk/HelpdeskAdmin.vue')
        }
    ]
}
import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('solicitudes.entrar')
        if (auth) next();
        else next(from);
    }),
    path: '/solicitudes',
    component: () =>
        import('./../../views/modules/solicitudes/SolicitudesLayout.vue'),
    name: 'solicitudes',
    children: [{
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('solicitudes.radicar')
                if (auth) next();
                else next(from);
            }),
            path: '/solicitudes/radicar',
            component: () =>
                import('./../../views/modules/solicitudes/SolicitudesFormulario.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('solicitudes.gestion')
                if (auth) next();
                else next(from);
            }),
            path: '/solicitudes/gestion',
            component: () =>
                import('./../../views/modules/solicitudes/SolicitudesGestion.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('solicitudes.admin')
                if (auth) next();
                else next(from);
            }),
            path: '/solicitudes/admin',
            component: () =>
                import('./../../views/modules/solicitudes/SolicitudesAdmin.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('any')
                if (auth) next();
                else next(from);
            }),
            path: '/solicitudes/asignadas',
            component: () =>
                import('./../../views/modules/solicitudes/SolicitudesAsignadas.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('solicitudes.informe')
                if (auth) next();
                else next(from);
            }),
            path: '/solicitudes/informe',
            component: () =>
                import('./../../views/modules/solicitudes/SolicitudesInforme.vue')
        },
    ]
}
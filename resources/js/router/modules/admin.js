import store from '../../store/index';

export default {
    path: '/admin',
    component: () =>
        import('./../../views/modules/admin/Admin.vue'),
    name: 'admin',
    children: [
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('configuraciones.enter')
                if(auth) next();
                else next(from);
            }),
            path: 'configuraciones',
            component: () =>
                import('./../../views/modules/admin/configuraciones.vue'),
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/',
            component: () =>
                import('./../../views/modules/admin/Admin.vue'),
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admintutelas.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/tutelas',
            component: () =>
                import('./../../views/modules/tutelas/TutelasAdmin.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('adminhelp.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/helpdesks',
            component: () =>
                import('./../../views/modules/helpdesk/HelpdeskAdmin.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.users.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/users',
            component: () =>
                import('./../../views/modules/admin/Users.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.roles.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/roles',
            component: () =>
                import('./../../views/modules/admin/Roles.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.tipos-agenda.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/tipos-agenda',
            component: () =>
                import('./../../views/modules/admin/TipoAgenda.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.colegios.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/colegios',
            component: () =>
                import('./../../views/modules/admin/Colegios.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('detallearticulos.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/detallearticulos',
            component: () =>
                import('./../../views/modules/admin/Detallearticulos.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.sedes.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/sedes',
            component: () =>
                import('./../../views/modules/admin/Sede.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.cups.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/cups',
            component: () =>
                import('./../../views/modules/admin/Cups.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.recomendacionescups.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/recomendaciones-cup',
            component: () =>
                import('./../../views/modules/admin/RecomendacionCups.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.tipo-servicio.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/tipos-servicio',
            component: () =>
                import('./../../views/modules/admin/TipoFamilia.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.grupos.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/grupos',
            component: () =>
                import('./../../views/modules/admin/GrupoMedicamentos.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.codigos-sumi.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/codigos-sumi',
            component: () =>
                import('./../../views/modules/admin/CodSumiMedicamentos.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.bodega-trasaccion.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/transacciones',
            component: () =>
                import('./../../views/modules/admin/BodegaTransaccion.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.esquemas.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/esquemas',
            component: () =>
                import('./../../views/modules/admin/Esquemas.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.bodegas.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/bodegas',
            component: () =>
                import('./../../views/modules/admin/Bodegas.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.entidades.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/entidades',
            component: () =>
                import('./../../views/modules/admin/Entidades.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.proveedores.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/proveedores',
            component: () =>
                import('./../../views/modules/admin/Proveedores.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.paquetes.servicios')
                if(auth) next();
                else next(from);
            }),
            path: '/admin/paquetes/servicios',
            component: () =>
                import('./../../views/modules/admin/PaqueteServicios.vue')
        },
    ]
}

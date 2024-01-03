import store from '../../store/index';
export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('any')
        if (auth) next();
        else next(from);
    }),
    path: '/aseguramiento',
    component: () =>
        import('./../../views/modules/aseguramiento/AseguramientoLayout.vue'),
    name: 'aseguramiento',
    children: [
        {
            path: '/aseguramiento/validacion',
            component: () =>
                import('../../views/modules/aseguramiento/ValidacionDerechos.vue')
        },
        {
            path: '/aseguramiento/remisionProgramas',
            component: () =>
                import('../../views/modules/aseguramiento/remisionProgramas.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.paciente.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/aseguramiento/pacientes',
            component: () =>
                import('../../views/modules/aseguramiento/Paciente.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('afiliaciones.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/aseguramiento/afiliaciones/entrada',
            component: () =>
                import('../../views/modules/aseguramiento/Afiliaciones.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('afiliaciones.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/aseguramiento/afiliaciones/salida',
            component: () =>
                import('../../views/modules/aseguramiento/PortabilidadSalida.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('parametrizacionAseguramiento.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/aseguramiento/parametrizacionAseguramiento',
            component: () =>
                import('../../views/modules/aseguramiento/parametrizacionAseguramiento.vue')
        },
    ]
}

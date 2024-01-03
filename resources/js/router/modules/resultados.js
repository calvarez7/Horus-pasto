import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('any')
        if(auth) next();
        else next(from);
    }),
    path: '/resultados',
    component: () =>
        import('./../../views/modules/resultados/ResultadosLayout.vue'),
    name: 'Resultados',
    children: [
        {
            path: '/resultados/registro',
            component: () =>
                import('./../../views/modules/resultados/Registro.vue')
        },
        {
            path: '/resultados/registroCovi',
            component: () =>
                import('./../../views/modules/resultados/RegistroCovi.vue')
        },
        {
            path: '/resultados/consulta',
            component: () =>
                import('./../../views/modules/resultados/Consulta.vue')
        },
        {
            path: '/resultados/admision',
            component: () =>
                import('./../../views/modules/resultados/Admision.vue')
        },
        {
            path: '/resultados/seguimientofinal',
            component: () =>
                import('./../../views/modules/resultados/Seguimientofinal.vue')
        },
        {
            path: '/resultados/alta',
            component: () =>
                import('./../../views/modules/resultados/Alta.vue')
        },
        {
            path: '/resultados/reporte',
            component: () =>
                import('./../../views/modules/resultados/Reporte.vue')
        }
        // {
        //     path: '/domiciliaria/admon',
        //     component: () =>
        //         import('./../../views/modules/domiciliaria/DomiciliariaAdmon.vue')
        // },
        // {
        //     path: '/domiciliaria/Hitorico',
        //     component: () =>
        //         import('./../../views/modules/domiciliaria/DomiciliariaHistorico.vue')
        // }
    ]
}

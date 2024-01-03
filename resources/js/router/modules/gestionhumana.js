import store from '../../store/index';

export default {
    path: '/gestionhumana',
    component: () =>
        import('./../../views/modules/gestionhumana/HumanaLayout.vue'),
    name: 'Portal Gestion Humana',
    children: [{
        beforeEnter: (async (to, from, next) => {
            let auth = await store.getters.can('gestionhumana.enter')
            if(auth) next();
            else next(from);
        }),
        path: '/',
        component: () =>
            import('./../../views/modules/gestionhumana/Hojasdevida.vue')
    },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('perfildecargo.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/gestionhumana/cargos',
            component: () =>
                import('./../../views/modules/gestionhumana/PerfilCargos.vue'),
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('contratos.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/gestionhumana/contratacion',
            component: () =>
                import('./../../views/modules/gestionhumana/Contratos.vue'),
        },
        // {
        //     beforeEnter: (async (to, from, next) => {
        //         let auth = await store.getters.can('contratistas.enter')
        //         if(auth) next();
        //         else next(from);
        //     }),
        //     path: '/gestionhumana/contratistas',
        //     component: () =>
        //         import('./../../views/modules/gestionhumana/Contratistas.vue'),
        // },
        // {
        //     beforeEnter: (async (to, from, next) => {
        //         let auth = await store.getters.can('novedadeslideres.enter')
        //         if(auth) next();
        //         else next(from);
        //     }),
        //     path: '/gestionhumana/lideres',
        //     component: () =>
        //         import('./../../views/modules/gestionhumana/NovedadesLideres.vue'),
        // },
        // {
        //     beforeEnter: (async (to, from, next) => {
        //         let auth = await store.getters.can('empleadosautogestion.enter')
        //         if(auth) next();
        //         else next(from);
        //     }),
        //     path: '/gestionhumana/empleados',
        //     component: () =>
        //         import('./../../views/modules/gestionhumana/EmpleadosAutogestion.vue'),
        // },
    ]
}

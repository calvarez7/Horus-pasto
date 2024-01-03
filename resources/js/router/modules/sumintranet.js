import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('any')
        if (auth) next();
        else next(from);
    }),
    path: '/sumintranet',
    component: () =>
        import('./../../views/modules/sumintranet/SumintranetLayout.vue'),
    name: 'Sumintranet',
    children: [{
            path: '/sumintranet',
            component: () =>
                import('./../../views/modules/sumintranet/home.vue')
        },
        {
            path: '/sumintranet/hojadevida',
            component: () =>
                import('./../../views/modules/sumintranet/Hojadevida.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('enter.adminsuminranet')
                if(auth) next();
                else next(from);
            }),
            path: '/adminsuminranet',
            component: () =>
                import('./../../views/modules/sumintranet/adminsuminranet.vue')
        },
        {
            path: '/directorio',
            component: () =>
                import('./../../views/modules/sumintranet/directorio.vue')
        },
        {
            path: '/certificados',
            component: () =>
                import('./../../views/modules/sumintranet/Certificados.vue')
        },
        {
            path: '/gestionDocumental',
            component: () =>
                import('./../../views/modules/sumintranet/documentacion.vue')
        },
    ]
}

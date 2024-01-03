import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('imagenologia.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/imagenologia',
    component: () =>
        import('./../../views/modules/imagenologia/ImagenologiaLayout.vue'),
    name: 'imagenologia',
    children: [
        {
        path: '/imagenologia/admision',
        component: () =>
            import('./../../views/modules/imagenologia/ImagenologiaAdmision.vue')
        },
        {
        path: '/imagenologia/gestion',
        component: () =>
            import('./../../views/modules/imagenologia/ImagenologiaGestion.vue')
        },
        {
        path: '/imagenologia/historico',
        component: () =>
            import('./../../views/modules/imagenologia/ImagenologiaHistorico.vue')
        },
        {
        path: '/imagenologia/admin',
        component: () =>
            import('./../../views/modules/imagenologia/ImagenologiaAdmin.vue')
        }
    ]
}
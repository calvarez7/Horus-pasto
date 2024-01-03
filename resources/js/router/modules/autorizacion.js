import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('autorizacion.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/autorizacion',
    component: () =>
        import('./../../views/modules/autorizacion/AutorizacionLayout.vue'),
    name: 'autorizacion',
    children: [
        {
            path: '/',
            component: () =>
                import('./../../views/modules/autorizacion/AutorizacionMedicamentos.vue')
        },
        {
            path: '/autorizacion/servicios',
            component: () =>
                import('./../../views/modules/autorizacion/AutorizacionServicios.vue')
        },
        {
            path: '/autorizacion/propios',
            component: () =>
                import('./../../views/modules/autorizacion/AutorizacionServiciosPropios.vue')
        },
        {
            path: '/autorizacion/esquemas',
            component: () =>
                import('./../../views/modules/autorizacion/AutorizacionEsquemasOncologicos.vue')
        },
        {
            path: '/autorizacion/portabilidad',
            component: () =>
                import('./../../views/modules/autorizacion/AutorizacionPortabilidad.vue')
        },
    ]
}
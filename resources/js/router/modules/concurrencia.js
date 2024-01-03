import store from '../../store/index';

export default {
    // beforeEnter: (async (to, from, next) => {
    //     let auth = await store.getters.can('registro.caracterizacion')
    //     if(auth) next();
    //     else next(from);
    // }),
    path: '/concurrencia',
    component: () =>
        import('./../../views/modules/concurrencia/ConcurrenciaLayout.vue'),
    name: 'Concurrencia',
    children: [
        {
            path: '/concurrencia/ingreso',
            component: () =>
                import('./../../views/modules/concurrencia/RegistroConcurrencia.vue')
        },
        {
            path: '/concurrencia/seguimiento',
            component: () =>
                import('./../../views/modules/concurrencia/SeguimientoConcurrencia.vue')
        },
        {
            path: '/concurrencia/alta',
            component: () =>
                import('./../../views/modules/concurrencia/AltaConcurrencia.vue')
        },
        {
            path: '/concurrencia/censo',
            component: () =>
                import('./../../views/modules/concurrencia/CensoConcurrencia.vue')
        },
        {
            path: '/concurrencia/reporte',
            component: () =>
                import('./../../views/modules/concurrencia/ReporteConcurrencia.vue')
        }
    ]
}

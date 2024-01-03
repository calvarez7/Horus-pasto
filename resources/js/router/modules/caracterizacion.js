import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('registro.caracterizacion')
        if(auth) next();
        else next(from);
    }),
    path: '/caracterizacion',
    component: () =>
        import('./../../views/modules/caracterizacion/CaracterizacionLayout.vue'),
    name: 'Caracterizacion',
    children: [
        {
            path: '/caracterizacion/registro',
            component: () =>
                import('./../../views/modules/caracterizacion/Registro.vue')
        },
        {
            path: '/caracterizacion/reporte',
            component: () =>
                import('./../../views/modules/caracterizacion/Reporte.vue')
        }
    ]
}

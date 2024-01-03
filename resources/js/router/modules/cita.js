import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('citas.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/citas',
    component: () =>
        import('./../../views/modules/citas/CitasLayout.vue'),
    name: 'citas',
    children: [{
        path: '/citas/asignacion',
        component: () =>
            import('./../../views/modules/citas/AsignacionCita.vue')
         },{
            path: 'cobros/activos',
            component: () => import('./../../views/modules/citas/CobroOrdenes.vue'),
        },{
            path: 'cobros/historico',
            component: () => import('./../../views/modules/citas/CobroHistorico.vue'),
        },
        {
            path: '/citas/parametrizacion',
            component: () => import('./../../views/modules/citas/ParametrizacionCita.vue'),
        }]
}

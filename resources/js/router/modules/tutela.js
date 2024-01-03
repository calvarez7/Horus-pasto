import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('tutelas.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/tutelas',
    component: () =>
        import('./../../views/modules/tutelas/TutelasLayout.vue'),
    name: 'tutelas',
    children: [{
        path: '/',
        component: () =>
            import('./../../views/modules/tutelas/TutelasAsignadas.vue')
    },
    {
        path: '/tutelas/asignadas',
        component: () =>
            import('./../../views/modules/tutelas/Tutelas.vue')
    },
    {
        path: '/tutelas/reporte',
        component: () =>
            import('./../../views/modules/tutelas/ReporteTutelas.vue')
    },
    {
        path: '/tutelas/admintutelas',
        component: () =>
            import('./../../views/modules/tutelas/TutelasAdmin.vue')
    }]
}

import store from '../../store/index';

export default {
    path: '/agenda',
    component: () =>
        import('./../../views/modules/agendamiento/Agendamiento.vue'),
    name: 'agendamiento',
    children: [{
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('agendas.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/',
            component: () =>
                import('./../../views/modules/agendamiento/Agenda.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('agendamiento-medico.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/agenda/agendamiento-medico',
            component: () =>
                import('./../../views/modules/agendamiento/AgendamientoMedico.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('medicos.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/agenda/medicos',
            component: () =>
                import('./../../views/modules/agendamiento/Medicos.vue')
        },
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('reporteAgenda.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/agenda/reporte-agenda',
            component: () =>
                import('./../../views/modules/agendamiento/ReporteAgenda.vue')
        },
    ]
}

import store from '../../store/index';
export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('reportes.enter')
        if (auth) next();
        else next(from);
    }),
    path: '/reportes',
    component: () =>
        import('./../../views/modules/reportes/ReporteLayout.vue'),
    name: 'reportes',
    children: [{
            path: '/reportes/cobros',
            component: () =>
                import('../../views/modules/reportes/Reportecobros.vue')
        },
        {
            path: '/reportes/conteos',
            component: () =>
                import('../../views/modules/reportes/ReporteConteos.vue')
        },
        {
            path: '/reportes/medicamentos',
            component: () =>
                import('../../views/modules/reportes/ReporteMedicamentos.vue')
        },
        {
            path: '/reportes/resoluciones',
            component: () =>
                import('../../views/modules/reportes/ReporteResoluciones.vue')
        },
        {
            path: '/reportes/reportesPqrsf',
            component: () =>
                import('../../views/modules/reportes/ReportePqrsf.vue')
        },
        {
            path: '/reportes/agendas',
            component: () =>
                import('../../views/modules/reportes/ReporteAgendas.vue')
        },
        {
            path: '/reportes/pacientes',
            component: () =>
                import('../../views/modules/reportes/ReportePacientes.vue')
        },
        {
            path: '/reportes/incapacidades',
            component: () =>
                import('../../views/modules/reportes/ReporteIncapacidades.vue')
        },
        {
            path: '/reportes/estadistica',
            component: () =>
                import('../../views/modules/reportes/Estadistica.vue')
        },
        {
            path: '/reportes/entregaMedicamentos',
            component: () =>
                import('../../views/modules/reportes/ReporteEntregaMedicamentos.vue')
        },
        {
            path: '/reportes/citas',
            component: () =>
                import('../../views/modules/reportes/ReporteCitas')
        }
    ]
}

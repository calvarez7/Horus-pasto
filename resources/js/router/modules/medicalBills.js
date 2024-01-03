import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('cuentasmedicas.entrar')
        if(auth) next();
        else next(from);
    }),
    path: '/cuentasmedicas',
    component: () =>
        import('./../../views/modules/medicalBills/MedicalBillsLayout.vue'),
    name: 'cuentasmedicas',
    children: [{
        beforeEnter: (async (to, from, next) => {
            let auth = await store.getters.can('cuentasmedicas.asignar')
            if(auth) next();
            else next(from);
        }),
        path: '/cuentasmedicas/asignar',
        component: () =>
            import('./../../views/modules/medicalBills/MedicalBillsAdmon.vue')
    },
    {
        beforeEnter: (async (to, from, next) => {
            let auth = await store.getters.can('cuentasmedicas.auditoria')
            if(auth) next();
            else next(from);
        }),
        path: '/cuentasmedicas/auditoria',
        component: () =>
            import('./../../views/modules/medicalBills/MedicalBillsGestion.vue'),
        children: [{
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('cuentasmedicas.asignadas')
                if(auth) next();
                else next(from);
            }),
            path: 'asignadas',
            component: () =>
                import('./../../views/modules/medicalBills/MedicalBillsAsignados.vue')
            },
            {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('cuentasmedicas.auditadas')
                if(auth) next();
                else next(from);
            }),
            path: 'auditadas',
            component: () =>
                import('./../../views/modules/medicalBills/MedicalBillsAuditadas.vue')
            },
            {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('cuentasmedicas.prestador')
                if(auth) next();
                else next(from);
            }),
            path: 'prestador',
            component: () =>
                import('./../../views/modules/medicalBills/MedicalBillsPrestador.vue')
            },
            {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('cuentasmedicas.auditoriafinal')
                if(auth) next();
                else next(from);
            }),
            path: 'auditoriafinal',
            component: () =>
                import('./../../views/modules/medicalBills/MedicalBillsAuditoriaFinal.vue')
            },
        ]   
    },
    {
        beforeEnter: (async (to, from, next) => {
            let auth = await store.getters.can('cuentasmedicas.facturas')
            if(auth) next();
            else next(from);
        }),
        path: '/cuentasmedicas/facturas',
        component: () =>
            import('./../../views/modules/medicalBills/MedicalBillsFacturas.vue')
    },
    {
        beforeEnter: (async (to, from, next) => {
            let auth = await store.getters.can('cuentasmedicas.informe')
            if(auth) next();
            else next(from);
        }),
        path: '/cuentasmedicas/informe',
        component: () =>
            import('./../../views/modules/medicalBills/MedicalBillsInforme.vue')
    },
    {
        beforeEnter: (async (to, from, next) => {
            let auth = await store.getters.can('cuentasmedicas.tesoreria')
            if(auth) next();
            else next(from);
        }),
        path: '/cuentasmedicas/tesoreria',
        component: () =>
            import('./../../views/modules/medicalBills/MedicalBillsTesoreria.vue')
    },
    {
        beforeEnter: (async (to, from, next) => {
            let auth = await store.getters.can('cuentasmedicas.admin')
            if(auth) next();
            else next(from);
        }),
        path: '/cuentasmedicas/admin',
        component: () =>
            import('./../../views/modules/medicalBills/MedicalBillsAdmin.vue')
    }
    ]
}

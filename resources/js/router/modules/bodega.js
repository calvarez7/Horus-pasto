import store from "../../store/index";

export default {
    beforeEnter: async (to, from, next) => {
        let auth = await store.getters.can("bodegas.enter");
        if (auth) next();
        else next(from);
    },
    path: "/bodegas",
    component: () => import("./../../views/modules/bodegas/BodegaLayout.vue"),
    name: "bodegas",
    redirect: '/bodegas/solicitud',
    children: [
        {
            path: "reposicion-inventario",
            component: () =>
                import("./../../views/modules/bodegas/min_max.vue")
        },
        {
            path: "solicitud",
            component: () =>
                import("./../../views/modules/bodegas/BodegaSolicitud.vue")
        },
        {
            path: "auditoria",
            component: () =>
                import("./../../views/modules/bodegas/BodegaAuditoria.vue")
        },
        {
            path: "movimientos",
            component: () =>
                import("./../../views/modules/bodegas/BodegaMovimientos.vue")
        },
        {
            path: "solicitud_compras",
            component: () =>
                import("./../../views/modules/bodegas/BodegaCompras.vue")
        },
        {
            path: "inventario",
            component: () =>
                import(
                    "./../../views/modules/bodegas/MedicamentosInventario.vue"
                )
        },
        {
            path: "entradamercancia",
            component: () =>
                import("./../../views/modules/bodegas/EntradaMercancia.vue")
        },
        {
            path: "solicitud_traslados",
            component: () =>
                import("./../../views/modules/bodegas/SolicitudTraslados.vue")
        },
        {
            path: "gestion_traslados",
            component: () =>
                import("./../../views/modules/bodegas/GestionTraslados.vue")
        },
        {
            path: "kardex",
            component: () =>
                import("./../../views/modules/bodegas/Kardex.vue")
        },
        {
            path: "historico",
            redirect: 'historico/dispensado',
            component: () =>
                import(
                    "./../../views/modules/bodegas/BodegaMovimientoHistorial.vue"
                ),
            children: [
                {
                    path: "dispensado",
                    component: () =>
                        import(
                            "./../../views/modules/bodegas/BodegaHistorialDispensado.vue"
                        )
                }
            ]
        },
        {
            path: 'conteo_inventario',
            component: () =>
                import('./../../views/modules/bodegas/ConteoInventario.vue')
        },
        {
            path: 'firma_orden',
            component: () =>
                import('./../../views/modules/bodegas/FirmaOrden.vue')
        },
    ]
};

import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('referencia.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/referencia',
    component: () => import('./../../views/modules/referencia/ReferenciaLayout.vue'),
    name: 'referencia',
    children: [{
        path: '/',
        component: () => import('./../../views/modules/referencia/ReferenciaCreate.vue'),
    },
    {
        path: 'pendientes',
        redirect: 'pendientes/anexo_2',
        component: () => import('./../../views/modules/referencia/ReferenciaPending.vue'),
        name: 'pendientes',
        children: [{
            path: 'anexo_2',
            component: () => import('./../../views/modules/referencia/Anexo2Pending.vue'),
        },
        {
            path: 'anexo_3',
            component: () => import('./../../views/modules/referencia/Anexo3Pending.vue'),
        },
        {
            path: 'anexo_9',
            component: () => import('./../../views/modules/referencia/Anexo9Pending.vue'),
        }],
    },
    {
        path: 'en_proceso',
        redirect: 'en_proceso/anexo_2',
        component: () => import('./../../views/modules/referencia/ReferenciaInProcess.vue'),
        name: 'en_proceso',
        children: [{
            path: 'anexo_2',
            component: () => import('./../../views/modules/referencia/Anexo2InProcess.vue'),
        },
        {
            path: 'anexo_3',
            component: () => import('./../../views/modules/referencia/Anexo3InProcess.vue'),
        },
        {
            path: 'anexo_9',
            component: () => import('./../../views/modules/referencia/Anexo9InProcess.vue'),
        },
        {
            path: 'gestion/:bitacora',
            component: () => import('./../../views/modules/referencia/BitacoraReferencia.vue'),
        }],
    },
    {
        path: 'proceso_interno',
        component: () => import('./../../views/modules/referencia/ReferenciaInternalProcess.vue'),
        name: 'proceso_interno',
    },
    {
        path: 'seguimiento',
        component: () => import('./../../views/modules/referencia/Seguimientofinal.vue'),
        name: 'seguimiento',
    },
    {
        path: 'alta',
        component: () => import('./../../views/modules/referencia/Alta.vue'),
        name: 'alta',
    },
    {
        path: 'reporte',
        component: () => import('./../../views/modules/referencia/ReferenciaReporte.vue'),
        name: 'reporte',
    },
    {
        path: 'reporteConcurrencia',
        component: () => import('./../../views/modules/referencia/ConcurrenciaReporte.vue'),
        name: 'reporteConcurrencia',
    }]
}

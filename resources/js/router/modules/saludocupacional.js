import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('saludocupacional.enter')
        if(auth) next();
        else next(from);
    }),
        path: '/saludocupacional',
        component: () => import('./../../views/modules/saludocupacional/SaludocupacionalLayout.vue'),
        name: 'salud ocupacional',
        children: [
            {
                path: '/saludocupacional/citaOcupacional',
                component: () => import('./../../views/modules/saludocupacional/Asignar_cita_ocupacional.vue'),
            },
            {
                path: '/saludocupacional/certificado',
                component: () => import('./../../views/modules/saludocupacional/Certificado_salud_ocupacional.vue'),
            },
            {
                path: '/saludocupacional/historial',
                component: () => import('./../../views/modules/saludocupacional/Historial_salud_ocupacional.vue'),
            },
            {
                path: '/saludocupacional/reporte',
                component: () => import('./../../views/modules/saludocupacional/Reporte.vue'),
            }
        ]
}

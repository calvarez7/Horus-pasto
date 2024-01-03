import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('any')
        if(auth) next();
        else next(from);
    }),
        path: '/gestionriesgo',
        component: () => import('./../../views/modules/gestionRiesgo/GestionRiesgoLayout.vue'),
        name: 'gestion del riesgo',
        children: [
            {
                path: '/gestionriesgo/GestionRiesgoPendientes.vue',
                component: () => import('./../../views/modules/gestionRiesgo/GestionRiesgoPendientes.vue'),
            },
            {
                path: '/gestionriesgo/demandaInducida.vue',
                component: () => import('./../../views/modules/gestionRiesgo/demandaInducida.vue'),
            },
        ]
}

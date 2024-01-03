import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('panel-medico.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/medico',
    component: () =>
        import('./../../views/modules/medico/MedicoLayout.vue'),
    name: 'medico',
    children: [{
        path: '/',
        component: () =>
            import('./../../views/modules/medico/Medico.vue')
    }, ]
}

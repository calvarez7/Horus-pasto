import store from '../../store/index';
export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('transcripcion.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/transcripcion',
    component: () =>
        import('./../../views/modules/transcripcion/TranscripcionLayout.vue'),
    name: 'transcripcion',
    children: [{
        path: '/',
        component: () =>
            import('./../../views/modules/transcripcion/Transcripcion.vue')
    }, ]
}
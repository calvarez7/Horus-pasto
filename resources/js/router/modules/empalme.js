import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('empalme.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/empalme',
    component: () =>
        import('./../../views/modules/empalme/EmpalmeLayout.vue'),
    name: 'Empalme',
    children: [
        {
            path: '/empalme/registro',
            component: () =>
                import('./../../views/modules/empalme/Registro.vue')
        }
    ]
}

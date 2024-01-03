import store from '../../store/index';

export default {
    path:'/desarrollo',
    component: () => import('./../../views/modules/desarrollo/DesarrolloLayout.vue'),
    name: 'desarrollo',
    children: [
        {
            beforeEnter: (async (to, from, next) => {
                let auth = await store.getters.can('admin.enter')
                if(auth) next();
                else next(from);
            }),
            path: '/',
            component: () =>
                import('./../../views/modules/desarrollo/Desarrollo.vue'),
        }
    ]
}

import store from '../../store/index';

export default {
    beforeEnter: (async (to, from, next) => {
        let auth = await store.getters.can('domiciliaria.enter')
        if(auth) next();
        else next(from);
    }),
    path: '/domiciliaria',
    component: () =>
        import('./../../views/modules/domiciliaria/DomiciliariaLayout.vue'),
    name: 'domiciliaria',
    children: [
        {
            path: '/domiciliaria/remision',
            component: () =>
                import('./../../views/modules/domiciliaria/DomiciliariaRemision.vue')
        },
        {
            path: '/domiciliaria/admision',
            component: () =>
                import('./../../views/modules/domiciliaria/DomiciliariaAdmision.vue')
        },
        {
            path: '/domiciliaria/seguimiento',
            component: () =>
                import('./../../views/modules/domiciliaria/DomiciliariaSeguimiento.vue')
        },
        {
            path: '/domiciliaria/alta',
            component: () =>
                import('./../../views/modules/domiciliaria/DomiciliariaAlta.vue')
        },
        {
            path: '/domiciliaria/Hitorico',
            component: () =>
                import('./../../views/modules/domiciliaria/DomiciliariaHistorico.vue')
        }
    ]
}

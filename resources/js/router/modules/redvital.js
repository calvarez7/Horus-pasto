import Redvital from '../../views/modules/redvital/Redvital.vue'

const redvital = {
    path: '/gestion',
    component: Redvital,
    children: [{
            path: '/',
            component: () =>
                import('./../../views/modules/redvital/Home.vue')
        }, {
            path: 'pqrsf',
            component: () =>
                import('./../../views/modules/redvital/Pqrsf.vue')
        },
        {
            path: 'citas',
            component: () =>
                import('./../../views/modules/redvital/Citas.vue')
        },
        {
            path: 'radicacion',
            component: () =>
                import('./../../views/modules/redvital/Radicacion.vue')
        }
    ]
}

export default redvital;

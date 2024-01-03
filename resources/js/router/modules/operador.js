import Operador from '../../views/modules/operador/index.vue'

const operador = {
    path: '/operador',
    component: Operador,
    children: [{
            path: '/',
            component: () =>
                import('./../../views/modules/operador/operador.vue')
        }
    ]
}

export default operador;

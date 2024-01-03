import store from '../../store/index';

export default {
    path: '/historias',
    component: () =>
        import('./../../views/modules/medico/HistoriaClinicaLayout.vue'),
    name: 'historiaclinica',
    children: [{
            path: '/historias',
            component: () =>
                import('./../../views/modules/medico/HistoriaClinica.vue'),
            children: [
                {
                    path: '/historias/historiaclinica/motivoconsulta',
                    component: () =>
                        import('./../../views/modules/historias/historiaclinica/MotivoConsulta.vue')
                },
                {
                    path: '/historias/historiaclinica/gineco',
                    component: () =>
                        import('./../../views/modules/historias/historiaclinica/Gineco.vue')
                },
                {
                    path: '/historias/historiaclinica/stylelive',
                    component: () =>
                        import('./../../views/modules/historias/historiaclinica/Stylelife.vue')
                },
                {
                    path: '/historias/historiaclinica/patologias',
                    component: () =>
                        import('./../../views/modules/historias/historiaclinica/Patologias.vue')
                },
                {
                    path: '/historias/historiaclinica/examensistema',
                    component: () =>
                        import('./../../views/modules/historias/historiaclinica/ExamenSistema.vue')
                },
                {
                    path: '/historias/historiaclinica/diagnostico',
                    component: () =>
                        import('./../../views/modules/historias/historiaclinica/Diagnostico.vue')
                },
                {
                    path: '/historias/historiaclinica/conducta',
                    component: () =>
                        import('./../../views/modules/historias/historiaclinica/Conducta.vue')
                },
                {
                    path: '/historias/historiaclinica/rcvm',
                    component: () =>
                        import('./../../views/modules/historias/historiaclinica/rcvm.vue')
                },
                // {
                //     path: '/historias/historiaclinica/imagenologia',
                //     component: () =>
                //         import('./../../views/modules/historias/historiaclinica/Imagenologia.vue')
                // },
                // HISTORIA CLINICA ONCOLOGICA

                {
                    path: '/historias/historia_oncologica/motivoOncologico',
                    component: () =>
                        import('./../../views/modules/historias/historia_oncologica/MotivoOncologico.vue')
                },
                {
                    path: '/historias/historia_oncologica/descripcionPatologia',
                    component: () =>
                        import('./../../views/modules/historias/historia_oncologica/DescripcionPatologia.vue')
                },

                {
                    path: '/historias/historia_oncologica/patologias',
                    component: () =>
                        import('./../../views/modules/historias/historia_oncologica/PatologiasOncologia.vue')
                },
                {
                    path: '/historias/historia_oncologica/examensistema',
                    component: () =>
                        import('./../../views/modules/historias/historia_oncologica/ExamenSistemaOncologia.vue')
                },
                {
                    path: '/historias/historia_oncologica/diagnostico',
                    component: () =>
                        import('./../../views/modules/historias/historia_oncologica/DiagnosticoOncologia.vue')
                },
                {
                    path: '/historias/historia_oncologica/conducta',
                    component: () =>
                        import('./../../views/modules/historias/historia_oncologica/ConductaOncologia.vue')
                },
                // HISTORIA CLINICA ENFERMERA ONCOLOGICA

                {
                    path: '/historias/historia_enfermeria/motivoEnfermeria',
                    component: () =>
                        import('./../../views/modules/historias/historia_enfermeria/MotivoEnfermeria.vue')
                },
                {
                    path: '/historias/historia_enfermeria/patologias',
                    component: () =>
                        import('./../../views/modules/historias/historia_enfermeria/PatologiasEnfermeria.vue')
                },
                {
                    path: '/historias/historia_enfermeria/examensistema',
                    component: () =>
                        import('./../../views/modules/historias/historia_enfermeria/ExamenSistemaEnfermeria.vue')
                },

                // HISTORIA OFTAMOLOGIA

                {
                    path: '/historias/historia_oftamologia/oftamologia',
                    component: () =>
                        import('./../../views/modules/historias/historia_oftamologia/Oftamologia.vue')
                },

                // HISTORIA SALUD OCUPACIONAL

                {
                    path: '/historias/historia_salud_ocupacional/psicologia',
                    component: () =>
                        import('./../../views/modules/historias/historia_salud_ocupacional/Psicologia.vue')
                },
                {
                    path: '/historias/historia_salud_ocupacional/voz',
                    component: () =>
                        import('./../../views/modules/historias/historia_salud_ocupacional/Voz.vue')
                },
                {
                    path: '/historias/historia_salud_ocupacional/visiometria',
                    component: () =>
                        import('./../../views/modules/historias/historia_salud_ocupacional/Visiometria.vue')
                },
                {
                    path: '/historias/historia_salud_ocupacional/Consultamedica',
                    component: () =>
                        import('./../../views/modules/historias/historia_salud_ocupacional/ConsultaMedica.vue')
                }
            ]
        },

    ]
}

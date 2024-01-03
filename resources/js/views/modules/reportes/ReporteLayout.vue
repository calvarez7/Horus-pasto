<template>
    <v-layout justify-center>
        <v-flex shrink xs12>
            <RouterView />
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "TutelasLayout",
        mounted() {
            this.setMenu();
        },
        methods: {
            setMenu() {
                this.$store.commit("setMenu", {
                    title: "Módulo Reportes",
                    items: [
                        { name: "Estadistica", path: "/reportes/estadistica", can: 'estadistica.sumi' },
                        { name: "Entrega medicamentos", path: "/reportes/entregaMedicamentos", can: 'reportes.entregaMedicamentos' },
                        { name: "Reporte citas", path: "/reportes/citas", can: 'reportes.citas' },
                        { name: "Cobro Entidades", path: "/reportes/cobros", can: 'reportes.cobros' },
                        { name: "Conteo Inventarios", path: "/reportes/conteos", can: 'reportes.conteos' },
                        { name: "Reportes Medicamentos", path: "/reportes/medicamentos", can: 'reportes.medicamentos' },
                        { name: "Resoluciones", path: "/reportes/resoluciones", can: 'reportes.resoluciones' },
                        { name: "Reportes PQRSF", path: "/reportes/reportesPqrsf", can: 'reportes.pqrsf'},
                        { name: "Auditoria Agendas", path: "/reportes/agendas", can: 'reportes.agendas'},
                        { name: "Reportes Pacientes", path: "/reportes/pacientes", can: 'reportes.pacientes'},
                        { name: "Reportes Incapacidades", path: "/reportes/incapacidades", can: 'reportes.incapacidades'},
                    ],
                    enabled: true
                });
            }
        }
    };
</script>

<style lang="scss" scoped>
</style>

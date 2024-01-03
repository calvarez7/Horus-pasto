<template>
    <div>
        <v-dialog v-model="preloadPadre" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Tranquilo procesamos tu solicitud !
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-card>
            <v-card-title class="headline">Formulario Filtros</v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12 sm3 md3>
                            <v-select
                                v-model="formFiltros.prioridad"
                                label="Prioridad"
                                :items="['Todas', 'Normal', 'Prioritario']"
                                placeholder="Seleccionar..."
                            ></v-select>
                        </v-flex>

                        <v-flex xs12 sm3 md3>
                            <v-switch
                                color="primary"
                                v-model="formFiltros.girs"
                                label="GIRS"
                            ></v-switch>
                        </v-flex>

                        <v-flex xs2>
                            <v-menu
                                v-model="isActiveStartDateExportAppointment"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <VTextField
                                        v-model="formFiltros.fecha_inicio"
                                        label="Fecha inicio"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    />
                                </template>
                                <VDatePicker
                                    color="primary"
                                    locale="es"
                                    :max="formFiltros.fecha_final"
                                    no-title
                                    v-model="formFiltros.fecha_inicio"
                                    @input="
                                        isActiveStartDateExportAppointment = false
                                    "
                                />
                            </v-menu>
                        </v-flex>
                        <v-flex xs2>
                            <v-menu
                                v-model="isActiveFinishDateExportAppointment"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <VTextField
                                        v-model="formFiltros.fecha_final"
                                        label="Fecha final"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    />
                                </template>
                                <VDatePicker
                                    color="primary"
                                    locale="es"
                                    :min="formFiltros.fecha_inicio"
                                    no-title
                                    v-model="formFiltros.fecha_final"
                                    @input="
                                        isActiveFinishDateExportAppointment = false
                                    "
                                />
                            </v-menu>
                        </v-flex>
                        <v-flex xs1 offset-xs11>
                            <v-btn color="success" @click="getPending()"
                                >Filtrar</v-btn
                            >
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
        </v-card>
        <br />
        <v-layout row wrap justify-center>
            <v-flex xs12>
                <TeleConceptoTable
                    :teles="teles"
                    @getPending="getPending"
                    inPendingPage
                />
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
import TeleConceptoTable from "../../../components/teleconcepto/TeleConceptoTable.vue";
import moment from 'moment';

export default {
    name: "TeleConceptoPending",
    components: {
        TeleConceptoTable,
    },
    data: () => {
        return {
            teles: [],
            preloadPadre: false,
            formFiltros: {
                girs: false,
                prioridad: "Todas",
                fecha_inicio: moment().format("YYYY-MM-DD"),
                fecha_final: moment().format("YYYY-MM-DD"),
            },

            isActiveFinishDateExportAppointment: false,
            isActiveFinistDateExportAppointment: false,
            isActiveStartDateExportAppointment: false,
        };
    },
    mounted() {
    },
    methods: {
        getPending() {
            this.preloadPadre = true
            axios
                .post("/api/teleconcepto/pending", this.formFiltros)
                .then((res) => {
                    this.teles = res.data;
                    this.preloadPadre = false
                })
                .catch((err) => console.log(err.response.data),this.preloadPadre = false);
        },
    },
};
</script>

<style lang="scss" scoped></style>

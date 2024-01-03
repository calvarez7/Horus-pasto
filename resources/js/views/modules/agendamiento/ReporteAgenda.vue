<template>
    <v-card>
        <v-container grid-list-xs fluid>
            <v-flex xs12>
                <v-layout row wrap align-center>
                    <v-flex xs4>
                        <v-select :items="tiposReporte" item-text="name" item-value="id" v-model="reporte.tipo"
                            label="Selecciona el tipo de reporte"></v-select>
                    </v-flex>
                    <v-flex xs2>
                        <v-menu v-model="cerrarFechainicial" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition"
                            offset-y full-width min-width="290px">
                            <template v-slot:activator="{ on }">
                                <VTextField v-model="reporte.fechaInicio" label="Fecha inicio" prepend-icon="event"
                                    readonly v-on="on" />
                            </template>
                            <VDatePicker color="primary" locale="es" :max="reporte.fechaFinal" no-title
                                v-model="reporte.fechaInicio" @input="cerrarFechainicial = false"/>
                        </v-menu>
                    </v-flex>
                    <v-flex xs2>
                        <v-menu v-model="cerrarFechafinal" :close-on-content-click="false" :nudge-right="40" lazy transition="scale-transition"
                            offset-y full-width min-width="290px">
                            <template v-slot:activator="{ on }">
                                <VTextField v-model="reporte.fechaFinal" label="Fecha final" prepend-icon="event"
                                    readonly v-on="on" />
                            </template>
                            <VDatePicker color="primary" locale="es" :min="reporte.fechaInicio" no-title
                                v-model="reporte.fechaFinal"  @input="cerrarFechafinal = false" />
                        </v-menu>
                    </v-flex>
                    <v-flex xs2>
                        <v-btn color="warning" :disabled="disabledExportBtn" round :loading="disabledExportBtn" small
                            @click="reporteGrupales()">
                            exportar
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-container>
    </v-card>
</template>
<script>
    import {
        mapGetters
    } from 'vuex';
    import moment from 'moment';
    import swal from 'sweetalert';
    moment.locale('es');
    export default {
        data() {
            return {
                cerrarFechainicial: false,
                cerrarFechafinal: false,
                disabledExportBtn: false,
                tiposReporte: [{
                    id: 536,
                    name: 'Grupales RCV'
                },{
                    id: 537,
                    name: 'Citas Atendidas Total'
                }],
                reporte: {
                    tipo: '',
                    fechaInicio: moment().format('YYYY-MM-DD'),
                    fechaFinal: moment().format('YYYY-MM-DD'),
                }
            }
        },
        methods: {
            reporteGrupales() {
                if (!this.reporte.tipo) {
                   return this.$alerError('Selecciona tipo de reporte');
                }
                axios.post('/api/agenda/reporteGrupales', this.reporte, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `Citas${this.reporte.fechaInicio}_${this.reporte.fechaFinal}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.loading = false;

                });
            }
        }
    }

</script>

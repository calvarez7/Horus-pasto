<template>
    <div>
        <v-card max-height="100%" class="mb-3">
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Historico de Alertas</span>
            </v-card-title>
            <v-card>
                <v-card-title>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                    </v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="AlertaPacientes" :search="search" class="elevation-1">
                    <template v-slot:items="props">
                        <td class="text-xs-left">{{ props.item.fecha_alerta }}</td>
                        <td class="text-xs-left">{{ props.item.paciente }}</td>
                        <td class="text-xs-left">{{ props.item.Num_Doc }}</td>
                        <td class="text-xs-left">{{ props.item.titulo }}</td>
                        <td class="text-xs-left">{{ props.item.alerta_nombre }}</td>
                        <td class="text-xs-left">{{ props.item.principal }}</td>
                        <td class="text-xs-left">{{ props.item.interaccion }}</td>
                        <td class="text-xs-left">{{ props.item.acepto }}</td>
                        <td class="text-xs-left">{{ props.item.Medico }}</td>
                    </template>
                </v-data-table>
                <v-layout row wrap>
                    <v-flex xs3 md3>
                        <v-text-field type="date" v-model="fecha.fecha_inicio" label="Fecha inicial">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs3 md3>
                        <v-text-field type="date" v-model="fecha.fecha_fin" label="Fecha Final">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 md3>
                        <v-btn color="success" @click="generarExcel">Excel Seguimientos Alertas</v-btn>
                    </v-flex>
                </v-layout>
            </v-card>
            <template>
                <div class="text-center">
                    <v-dialog v-model="preload" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Tranquilo procesamos tu solicitud !
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </div>
            </template>
        </v-card>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                preload: false,
                search: '',
                AlertaPacientes: [],
                fecha: {
                    fecha_inicio: '',
                    fecha_fin: '',
                },
                headers: [{
                        text: 'Fecha de Alerta',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Paciente',
                        value: 'paciente'
                    },
                    {
                        text: '# Documento',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'Titlo Mensaje',
                        value: 'titulo'
                    },
                    {
                        text: 'Tipo de Alerta',
                        value: 'alerta_nombre'
                    },
                    {
                        text: 'Principio',
                        value: 'principal'
                    },
                    {
                        text: 'Interaccion',
                        value: 'interaccion'
                    },
                    {
                        text: 'Aceptacion',
                        value: 'interaccion'
                    },
                    {
                        text: 'Medico',
                        value: 'Medico'
                    },
                ],
            }
        },
        created() {
            this.fechtAlertas();
        },
        methods: {
            fechtAlertas() {
                this.preload = true
                axios.get('/api/orden/fechtAlertas')
                    .then(res => {
                        this.preload = false
                        this.AlertaPacientes = res.data
                    }).catch(err => console.log(err.response));
            },
            generarExcel() {
                axios.post('/api/orden/generarExcelAlertas', this.fecha, {
                    responseType: "arraybuffer"
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `Alertas_${this.fecha.fecha_inicio}_${this.fecha.fecha_fin}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.loading = false;
                    this.clearFilter();
                });
            },
            clearFilter() {
                this.fecha.fecha_inicio = '',
                    this.fecha.fecha_fin = ''
            }

        }
    }

</script>

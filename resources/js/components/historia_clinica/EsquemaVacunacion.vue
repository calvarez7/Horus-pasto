<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card color="lighten-1" class="mb-5" height="auto">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Esquema de vacunación</span>
                </v-card-title>
                <v-container grid-list-xs>
                    <v-layout row wrap>
                        <v-container fluid grid-list-xl>
                            <v-layout wrap align-center>
                                <v-flex xs12 sm6 md2>
                                    <v-select v-model="esquemaVacunacion.vacunas"  :items="vacuna" label="Vacunas" autofocus></v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md3>
                                    <v-select v-model="esquemaVacunacion.dosis"  :items="dosis" label="Dosis"></v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md3 v-if="esquemaVacunacion.vacunas == 'Covid'">
                                    <v-text-field v-model="esquemaVacunacion.laboratorio"  label="Laboratorio"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-text-field type="date" v-model="esquemaVacunacion.fecha_dosis" label="Fecha de Dosis">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-btn fab dark color="success" @click="EsquemaVacunacional()">
                                        <v-icon dark>add</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-layout>
                </v-container>
                <v-card>
                    <v-card-title primary-title>
                        <v-flex xs12 sm12 d-flex>
                            <v-data-table :items="vacunas" :headers="headerEsquemaVacunacion" hide-actions class="elevation-1">
                                <template v-slot:items="props">
                                    <td class="text-xs">{{ props.item.id }}</td>
                                    <td class="text-xs">{{ props.item.vacunas }}</td>
                                    <td class="text-xs">{{ props.item.dosis }}</td>
                                    <td class="text-xs">{{ props.item.laboratorio }}</td>
                                    <td class="text-xs">{{ props.item.fecha_dosis }}</td>
                                    <td class="text-xs">{{ props.item.name }}</td>
                                    <td class="text-xs">{{ props.item.created_at }}</td>
                                </template>
                            </v-data-table>
                        </v-flex>
                    </v-card-title>
                </v-card>
            </v-card>
            <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
        </v-flex>
    </v-layout>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchEsquemaVacunacion();
        },
        data() {
            return {
                esquemaVacunacion: {
                    vacunas: '',
                    dosis: '',
                    laboratorio: '',
                    fecha_dosis: '',
                },
                vacuna:['Covid','BCG','HB','Polio ','Pentavalente','Rotavirus','VPH','DPT','Tetanos, Difteria Y Tos Ferina Acelular','Neumococo','Sarampion, Rubeola- Paperas (Srp)','Fiebre Amarilla','Hepatitis A','Varicela','Toxoide Tetanico Difterico Del Adulto','Influenza'],
                dosis:['1era Dosis','2da Dosis','3era Dosis','4ta Dosis','5ta Dosis','6ta Dosis','7ma Dosis'],
                vacunas:[],
                headerEsquemaVacunacion: [
                    {
                        text: 'id',
                    },
                    {
                        text: 'Vacunas',
                    },
                    {
                        text: 'Dosis',
                    },
                    {
                        text: 'Laboratorio',
                    },
                    {
                        text: 'F dosis',
                    },
                    {
                        text: 'Registrado por',
                    },
                    {
                        text: 'F registro',
                    },
                ]
            }
        },
        methods: {
            EsquemaVacunacional() {
                if (this.esquemaVacunacion.vacunas == 'Covid') {
                     if (!this.esquemaVacunacion.laboratorio) {
                        this.$alerError("El campo laboratorio es requerido!");
                        return;
                     }else{
                        this.esquemaVacunacion.paciente_id = this.datosCita.paciente_id;
                        axios.post('/api/historia/saveAntecedentesVacunales', this.esquemaVacunacion)
                        .then(res => {
                            this.$alertHistoria('<span style="color:#fff">'+res.data.message+'<span>');
                            this.fetchEsquemaVacunacion();
                            this.clear();
                        })
                     }
                }else {
                    this.esquemaVacunacion.paciente_id = this.datosCita.paciente_id;
                    axios.post('/api/historia/saveAntecedentesVacunales', this.esquemaVacunacion)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">'+res.data.message+'<span>');
                        this.fetchEsquemaVacunacion();
                        this.clear();
                    })
                }
            },
            fetchEsquemaVacunacion() {
                axios.get(`/api/historia/fetchEsquemaVacunacion/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.vacunas = res.data;
                    });
            },
            guardarSeguir(){
                this.$emit('respuestaComponente')
            },

            clear(){
                for (const key in this.esquemaVacunacion) {
                    this.esquemaVacunacion[key] = ''
                }
            }
        }
    }

</script>

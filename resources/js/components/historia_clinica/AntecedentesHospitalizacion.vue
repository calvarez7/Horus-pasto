<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                <span class="title layout justify-center font-weight-light">Antecedentes hospitalarios</span>
            </v-card-title>
            <v-layout row wrap>
                <v-flex xs12 sm6 md3 v-if="datosCita.Tipo_agenda == '63' || datosCita.Tipo_agenda == '26'">
                    <v-checkbox color="success" v-model="hospitalizacion.hospitalizacion_neonatal" value="1"
                        label="Hospitalización neonatal mayor a 7 días"></v-checkbox>
                    <!-- <v-select v-model="hospitalizacion.sino_neonatal"
                        v-if="hospitalizacion.hospitalizacion_neonatal == true" :items="sino">
                    </v-select> -->
                    <v-flex xs12 sm6 md6 v-if="hospitalizacion.hospitalizacion_neonatal == true">
                        <v-text-field v-model="hospitalizacion.descripcionneonatal" label="Descripción">
                        </v-text-field>
                    </v-flex>
                </v-flex>

                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="hospitalizacion.consulta_urgencias" value="1"
                        label="Consultas a Urgencias"></v-checkbox>
                    <!-- <v-select v-model="hospitalizacion.sino_urgenicias"
                        v-if="hospitalizacion.consulta_urgencias == true" :items="sino">
                    </v-select> -->
                    <v-flex xs12 sm6 md6 v-if="hospitalizacion.consulta_urgencias == true">
                        <v-text-field v-model="hospitalizacion.descripcionurgencias" label="Descripción">
                        </v-text-field>
                    </v-flex>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="hospitalizacion.hospitalizacion_ultimo_anio" value="1"
                        label="Hospitalizaciones el último año"></v-checkbox>
                    <!-- <v-select v-model="hospitalizacion.sino_hospiultimoanio"
                        v-if="hospitalizacion.hospitalizacion_ultimo_anio == true" :items="sino">
                    </v-select> -->
                    <v-flex xs12 sm6 md6 v-if="hospitalizacion.hospitalizacion_ultimo_anio == true">
                        <v-text-field v-model="hospitalizacion.descripcionhospiurg" label="Descripción">
                        </v-text-field>
                    </v-flex>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="hospitalizacion.mas_tres_hospitalizaciones_anio" value="1"
                        label="Más de 3 hospitalizaciones el último año"></v-checkbox>
                    <!-- <v-select v-model="hospitalizacion.sino_masdetreshospitalizacion"
                        v-if="hospitalizacion.mas_tres_hospitalizaciones_anio == true" :items="sino">
                    </v-select> -->
                    <v-flex xs12 sm6 md6 v-if="hospitalizacion.mas_tres_hospitalizaciones_anio == true">
                        <v-text-field v-model="hospitalizacion.descripcion_urgencias_mas_tres" label="Descripción">
                        </v-text-field>
                    </v-flex>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="hospitalizacion.hospitalizacion_mayor_dos_semanas_anio"
                        value="1" label="Hospitalizaciones mayores a 2 semanas el último año"></v-checkbox>
                    <!-- <v-select v-model="hospitalizacion.sino_mayordossemanashospi"
                        v-if="hospitalizacion.hospitalizacion_mayor_dos_semanas_anio == true" :items="sino">
                    </v-select> -->
                    <v-flex xs12 sm6 md6 v-if="hospitalizacion.hospitalizacion_mayor_dos_semanas_anio == true">
                        <v-text-field v-model="hospitalizacion.descripcion_urgencias_mas_tres_semanas" label="Descripción">
                        </v-text-field>
                    </v-flex>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="hospitalizacion.hospitalizacion_uci_anio" value="1"
                        label="Hospitalizción en UCI el último año"></v-checkbox>
                    <!-- <v-select v-model="hospitalizacion.sino_ucianio"
                        v-if="hospitalizacion.hospitalizacion_uci_anio == true" :items="sino">
                    </v-select> -->
                    <v-flex xs12 sm6 md6 v-if="hospitalizacion.hospitalizacion_uci_anio == true">
                        <v-text-field v-model="hospitalizacion.descripcion_hospi_uci" label="Descripción">
                        </v-text-field>
                    </v-flex>
                </v-flex>
                <v-btn color="success" round @click="saveAntecedenteHospitalizacion()">Guardar y seguir</v-btn>
            </v-layout>
        </v-container>
    </v-form>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchAnteHospitalizacion();
        },
        data() {
            return {
                hospitalizacion: {
                    citapaciente_id: '',
                    hospitalizacion_neonatal: '',
                    sino_neonatal: '',
                    consulta_urgencias: '',
                    sino_urgenicias: '',
                    hospitalizacion_ultimo_anio: '',
                    sino_hospiultimoanio: '',
                    mas_tres_hospitalizaciones_anio: '',
                    sino_masdetreshospitalizacion: '',
                    hospitalizacion_mayor_dos_semanas_anio: '',
                    sino_mayordossemanashospi: '',
                    hospitalizacion_uci_anio: '',
                    sino_ucianio: '',
                    descripcionneonatal: '',
                    descripcionurgencias: '',
                    descripcionhospiurg:'',
                    descripcion_urgencias_mas_tres:'',
                    descripcion_urgencias_mas_tres_semanas:'',
                    descripcion_hospi_uci:''
                },
                sino: ['Si', 'No'],
            }
        },
        methods: {
            saveAntecedenteHospitalizacion() {
                this.hospitalizacion.citapaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveAnteHospitalizacion', this.hospitalizacion)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                    })
            },
            fetchAnteHospitalizacion() {
                axios.get(`/api/historia/fetchAnteHospitalizacion/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.hospitalizacion = res.data;
                    });
            },
        }
    }

</script>

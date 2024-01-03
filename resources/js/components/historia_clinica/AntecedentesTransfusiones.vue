<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm12 md12>
                    <v-card-title class="headline" style="color:white;background-color:#0074a6">
                        <span class="title layout justify-center font-weight-light">Antecedentes Transfusionales</span>
                    </v-card-title>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="transfusiones.transfusiones" value="1" label="Transfusiones">
                    </v-checkbox>
                </v-flex>
                <v-flex xs12 sm12 md3 v-if="transfusiones.transfusiones == true">
                    <v-text-field v-model="transfusiones.fecha_transfusiones" label="Fecha Transfusión" type="date">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md5 v-if="transfusiones.transfusiones == true">
                    <v-textarea v-model="transfusiones.observacion"
                        label="Causa"></v-textarea>
                </v-flex>
                <v-flex xs12 sm6 md1>
                    <v-btn small fab dark color="success" @click="saveAntTransfusiones()">
                        <v-icon dark>add</v-icon>
                    </v-btn>
                </v-flex>
                <v-flex xs12 sm12 md12>
                    <v-data-table :items="getTransfusiones" :headers="header"
                        class="elevation-1">
                        <template v-slot:items="props">
                            <td class="text-xs">{{ props.item.id }}</td>
                            <td class="text-xs">{{ props.item.transfusiones?props.item.transfusiones:'' }}</td>
                            <td class="text-xs">{{ props.item.fecha_transfusiones?props.item.fecha_transfusiones:'' }}</td>
                            <td class="text-xs">{{ props.item.observacion?props.item.observacion:'' }}</td>
                            <td class="text-xs">{{ props.item.medico?props.item.medico:'' }}</td>
                            <td class="text-xs">{{ props.item.created_at?props.item.created_at:'' }}</td>
                        </template>
                    </v-data-table>
                </v-flex>
                <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
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
        data() {
            return {
                transfusiones: {
                    transfusiones: '',
                    fecha_transfusiones: '',
                    observacion: ''
                },
                getTransfusiones: [],
                header: [
                    {text: 'id'},
                    {text: 'Transfusión'},
                    {text: 'F Transfusión'},
                    {text: 'Causa'},
                    {text: 'Medico registro'},
                    {text: 'Fecha registro'},
                ]
            }
        },
        created(){
            this.fetchAntTransfusiones()
        },
        methods: {
            saveAntTransfusiones() {
                 if (!this.transfusiones.transfusiones) {
                    this.$alerError('El campo transfuciones es requerido!')
                    return
                }
                this.transfusiones.citapaciente_id = this.datosCita.cita_paciente_id;
                this.transfusiones.paciente_id = this.datosCita.paciente_id;
                axios.post('/api/historia/saveAntTransfusiones', this.transfusiones)
                    .then(res => {
                        this.$alertHistoria(res.data.message);
                        this.fetchAntTransfusiones();
                    })
            },
            fetchAntTransfusiones() {
                axios.get(`/api/historia/fetchAntTransfusiones/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.getTransfusiones = res.data;
                    });
            },
            guardarSeguir() {
                this.$emit('respuestaComponente')
            }
        }
    }

</script>

o<template>
    <div>
        <v-layout row wrap>
            <v-flex xs3 pr-2>
                <PacienteSearchForm @clearDataPaciente="clearDataPaciente"
                                    @setPaciente="setPaciente" />
            </v-flex>
            <v-flex xs9>
                <v-layout row wrap>
                    <v-flex xs12 mb-3>
                        <PacienteData   @actualizarDatosPersonales="actualizarDatosPersonales"
                                        :paciente="paciente" />
                    </v-flex>
                    <v-flex xs12>
                        <PacienteTeleConceptoForm
                            :data="data"
                            :readonly="readonly"
                            ref="childComponent"
                            @saveTeleconcepto="saveTeleconcepto"/>
                    </v-flex>
                    <Ordenamiento :paciente="paciente" :showBottom="false" ref="ordenamiento"
                                  :cita_paciente="citaPaciente" tipo="teleconcepto" :idReferencia="idTeleconcepto" @clearDataPaciente="clearDataPaciente"></Ordenamiento>

                </v-layout>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import PacienteSearchForm from '../../../components/paciente/PacienteSearchForm1.vue'
    import PacienteData from '../../../components/paciente/PacienteData.vue'
    import PacienteTeleConceptoForm from '../../../components/paciente/PacienteTeleConceptoForm.vue'
    import {EventBus} from "../../../event-bus";
    import Ordenamiento from "../../../components/medicamento/Ordenamiento.vue";


    export default {
        name:'TeleConceptoCreate',
        components:{
            PacienteData,
            PacienteSearchForm,
            PacienteTeleConceptoForm,
            Ordenamiento
        },
        data: () => {
            return {
                idTeleconcepto:null,
                citaPaciente:0,
                data: {
                    adjuntos: [],
                    cie10s: [],
                    tipoSolicitud: null,
                    motivo: null,
                    resumen: null,
                    cup_id:null,
                    cups:[]
                },
                paciente: {},
                readonly: true,
            }
        },
        methods: {
            actualizarDatosPersonales(){
                axios.put(`/api/paciente/edit_ubicacion_data/${this.paciente.id}`, this.paciente)
                        .then((res) => {
                            swal({
                                title: "¡Datos Actualizados!",
                                text: ` `,
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                        })
            },
            clearDataPaciente(){
                this.paciente = {};
                this.citaPaciente = 0;
                this.idTeleconcepto = null;
                this.data = {
                    adjuntos: [],
                    cie10s: [],
                    tipoSolicitud: null,
                    motivo: null,
                    resumen: null,
                    cup_id:null,
                    cups:[],
                },

                this.readonly = true;
                this.$refs.childComponent.resetForm();
            },
            saveTeleconcepto(formData){
                if(this.paciente.hasOwnProperty('id')){
                    formData.append('id_paci',this.paciente.id)
                    const config = {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                    axios.post('/api/teleconcepto/create', formData, config)
                            .then(res => {
                                this.showMessage(res.data.message)
                                //this.clearDataPaciente();

                                this.citaPaciente = parseInt(res.data.citaPaciente);
                                this.idTeleconcepto = parseInt(res.data.teleconcepto);
                                this.$refs.ordenamiento.openModal();

                            })
                            .catch(err => console.log(err))
                }else{
                    this.showMessage('No hay un usuario para generar el anexo','warning',true);
                }

            },
            setPaciente(p){
                this.paciente = p;
                this.readonly = false;
            },
            showMessage(msg,icon = 'success',buttons = false, timer = 2000){
                if(buttons == false){
                    swal({
                        title: msg,
                        text: " ",
                        icon: icon,
                        timer: timer,
                        buttons: buttons
                    });
                }else{
                    swal({
                        title: msg,
                        text: " ",
                        icon: icon,
                        buttons: buttons
                    });
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>

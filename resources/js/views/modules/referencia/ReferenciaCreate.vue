<template>
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
                        <PacienteReferenceForm 
                                        :data="data"
                                        :readonly="readonly"
                                        ref="childComponent"
                                        @saveReferencia="saveReferencia"/>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import PacienteSearchForm from '../../../components/paciente/PacienteSearchForm.vue'
    import PacienteData from '../../../components/paciente/PacienteData.vue'
    import PacienteReferenceForm from '../../../components/paciente/PacienteReferenceForm.vue'

    export default {
        name:'ReferenciaCreate',
        components:{
            PacienteData,
            PacienteSearchForm,
            PacienteReferenceForm
        },
        data: () => {
            return {
                data: {
                    adjuntos: [],
                    cie10s: [],
                    Especialidad_remi: null,
                    tipo_anexo: null,
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
                this.paciente = {},
                this.data = {
                    adjuntos: [],
                    cie10s: [],
                    Especialidad_remi: null,
                    tipo_anexo: null,
                },

                this.readonly = true;
                this.$refs.childComponent.resetForm();
            },
            saveReferencia(formData){
                if(this.paciente.hasOwnProperty('id')){
                    formData.append('id_paci',this.paciente.id)
                    const config = {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                    axios.post('/api/referencia/create', formData, config)
                            .then(res => {
                                this.showMessage(res.data.message)
                                this.clearDataPaciente();
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
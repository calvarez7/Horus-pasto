<template>
    <v-card>
        <v-card-title class="headline success" style="color:white">
            <span class="title layout justify-center font-weight-light">Buscar Paciente</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-container grid-list-md 
                        fluid 
                        class="pa-0">
                <v-layout   wrap 
                            align-center 
                            justify-center>
                    <v-flex xs12>
                        <v-form @submit.prevent="search_paciente()">
                            <v-layout row wrap>
                                <v-flex xs10>
                                    <vTextField label="Número de Documento"
                                                v-model="cedula_paciente"  />
                                </v-flex>
                                <v-flex xs2>
                                    <v-btn 
                                        @click="search_paciente()"
                                        v-if="!paciente.id"
                                        fab 
                                        outline 
                                        small 
                                        color="success">
                                            <v-icon>search</v-icon>
                                    </v-btn> 
                                    <v-btn 
                                        @click="clearFields()"
                                        v-if="paciente.id"
                                        fab 
                                        outline 
                                        small 
                                        color="error">
                                            <v-icon>clear</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>                          
                        </v-form>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card-text>
    </v-card>
</template>

<script>
    export default {
        name: 'PacienteSearchForm',
        data: () => {
            return {
                cedula_paciente: '',
                paciente:{
                    id: null
                }
            }
        },
        methods:{
            search_paciente(){
                if(!this.cedula_paciente) return;

                axios.get(`/api/paciente/showEnabledmedimas/${this.cedula_paciente}`).then((res) => {

                    if(res.data.paciente) {
                        this.paciente = res.data.paciente;
                        this.$emit('setPaciente', this.paciente)
                    }
                    if(res.data.message) this.showMessage(res.data.message);
                });
            },
            showMessage(message){
                swal({
                    title: `${message}`,
                    icon: "warning",
                });
            },
            clearFields(){
                this.$emit('clearDataPaciente')
                this.cedula_paciente = '',
                this.paciente = {
                    id: null
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
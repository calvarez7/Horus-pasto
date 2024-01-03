<template>
    <div>
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
        <v-container>
            <v-layout row wrap>
                <v-flex xs12 lg5 mb-3>
                    <v-expansion-panel popout>
                        <v-expansion-panel-content>
                            <template v-slot:header>
                                <div><h3>RIPS</h3></div>
                            </template>
                            <v-card>
                                <form @submit.prevent="guardar">
                                <v-card-text class="grey lighten-3">
                                    <v-layout row wrap>
                                        <v-flex xs3>
                                            <v-text-field v-model="form.dia_inicio_habilitacion_validador_rips" type="number" label="Dia Inicio Habilitacion Rips" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs3>
                                            <v-text-field v-model="form.dia_final_habilitacion_validador_rips" type="number" label="Dia Finalización Habilitacion Rips" required></v-text-field>
                                        </v-flex>
                                            <v-flex xs3>
                                            <v-checkbox color="primary" v-model="form.excepcion_habilitacion_validador_rips" label="Habilitar por Excepción"></v-checkbox>
                                            </v-flex>
                                        <v-flex xs3>
                                            <v-btn color="success" type="submit">Guardar</v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>
                                </form>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>
<script>

import Swal from "sweetalert2";
import {
    mapGetters
} from "vuex";
import moment from 'moment';
export default {
    computed:{
        ...mapGetters(['can']),
    },
    filters: {
        pesoFormat: (valor) => `$${new Intl.NumberFormat().format(valor) || 0}`
    },
    data() {
        return {
            preload:false,
            form:{
                dia_inicio_habilitacion_validador_rips:null,
                dia_final_habilitacion_validador_rips:null,
                excepcion_habilitacion_validador_rips:false,
                fecha_inicio_habilitacion_validador_202:null,
                fecha_fin_habilitacion_validador_202:null,
                excepcion_habilitacion_validador_202:false,
            }
        }
    },
    mounted() {
        this.configuraciones();
    },
    methods:{
        configuraciones(){
            this.preload = true;
          axios.get('/api/configuraciones/getConfiguraciones').then(res => {
              this.form = res.data;
              this.preload = false;
          }).catch(error => {
              console.log(error.response);
              this.preload = false;
          })
        },
        guardar(){
            this.preload = true;
            if(this.form.dia_inicio_habilitacion_validador_rips > this.form.dia_final_habilitacion_validador_rips){
                this.preload = false;
                return Swal.fire(
                    'Configuración RIPS',
                    'El dia de inicio de habilitación no puede ser mayor al dia de finalización.',
                    'error'
                )
            }
            axios.post('/api/configuraciones/guardarConfiguraciones',this.form).then(res => {
                this.preload = false;
                this.configuraciones();
                return Swal.fire(
                    'Configuración RIPS',
                    res.data,
                    'success'
                )
            }).catch(error => {
                console.log(error.response);
                this.preload = false;
            })
        }
    }
}

</script>

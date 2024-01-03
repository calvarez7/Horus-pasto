<template>
    <div>
        <v-layout>
            <v-flex shrink xs12 mr-1>
                <v-card max-height="100%" class="mb-3">
                    <v-dialog v-model="preload" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Tranquilo procesamos tu solicitud !
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-container grid-list-xl text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-autocomplete v-model="filtros.informe" item-value="id" item-text="nombre"
                                        :items="listado" label="Seleccione el Informe">
                                    </v-autocomplete>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs4>
                                    <v-autocomplete v-model="filtros.entidad" item-value="id" item-text="nombre"
                                        :items="listTipo" label="Seleccione el Contrato">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs1 offset-xs11>
                                    <v-btn color="success" @click="generarInforme">Descargar</v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import {
        mapGetters
    } from "vuex";

    export default {
        name: 'reportePacientes',

        data: () => ({
            preload: false,
            listTipo: [{
                id: 1,
                nombre: 'REDVITAL UT'
            }, {
                id: 3,
                nombre: 'FONDO DE PASIVO SOCIAL DE FERROCARRILES NACIONALES DE COLOMBIA'
            }],
            listado: [{
                id: 1,
                nombre: 'Listado de Pacientes',
                can: 'reportes.pacientes',
                fileName: 'Listado_Pacientes'
            }],
            informe: null,
            filtros: {
                informe: null,
                listTipo: null,
            }
        }),
        computed: {
            ...mapGetters(['can']),
            listaTipo: function () {
                let data = [];
                this.listTipo.forEach(s => {
                    if (this.can(s.can)) {
                        data.push(s)
                    }
                })
                return data
            },
        },
        methods: {
            generarInforme() {
                this.preload = true;
                const name = this.listado.filter(obj => obj.id === this.filtros.informe)
                axios({
                    method: 'post',
                    url: '/api/paciente/generarInforme',
                    responseType: 'blob',
                    params: this.filtros
                }).then(res => {
                    if(res.data.size == 0){
                        this.$alertInfo('Su entidad no tiene usuarios asignados  en calidad de punto de atención primaria en salud.')
                        this.preload = false
                        this.clear()
                    }
                    else{
                        let blob = new Blob([res.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                        });
                        console.log(blob);
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement('a');
    
                        a.download = name[0].fileName;
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        this.preload = false,
                        this.dialog = false
                        this.clear();
                        this.auditoriaDescarga();
                    }
                }).catch(err => {
                    this.preload = false,
                        console.log(err)
                })
            },
            clear() {
                for (const prop of Object.getOwnPropertyNames(this.filtros)) {
                    this.filtros[prop] = null;
                }
            },

            auditoriaDescarga() {
                axios.post('/api/paciente/auditoriaDescarga').then((res) => {
                    this.$alerSuccess('Se realizo la descarga de manera exitosa!')
                });
            }

        },
    }

</script>

<style>

</style>

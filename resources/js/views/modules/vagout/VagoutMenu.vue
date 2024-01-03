<template>
    <v-container fluid grid-list-xl>
        <v-layout row wrap>
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
            <v-flex xs12>
                <v-card>
                    <v-card-title primary-title>
                        <div>
                            <h3 class="headline mb-0">SELECCIONAR CICLO</h3>
                        </div>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-layout>
                                <v-flex xs12 md4>
                                    <v-select label="Seleccionar ciclo" v-model="form.ciclo" :items="ciclos" @input="getCiclos" :readonly="disabled">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 offset-xs1 md4>
                                    <v-text-field v-model="form.comensales" type="number" label="# Comensales" :readonly="disabled"></v-text-field>
                                </v-flex>

                                <v-flex xs12 md4>
                                    <v-btn color="success" @click="generarCicloDia" :disabled="disabled">Generar</v-btn>
                                    <v-btn v-show="disabled" color="warning" @click="generarExcel" >Imprimir</v-btn>

                                </v-flex>
                            </v-layout>
                        </v-container>


                    </v-card-text>

                    <v-card-actions>

                    </v-card-actions>
                </v-card>
            </v-flex>
            <v-flex xs12>
                <v-container fluid grid-list-xl>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-card v-for="(item, index) in consumoList" :key="index">
                                <v-container fluid grid-list-xl text-xs-center>
                                    <v-layout row wrap>
                                        <v-flex xs12>
                                            <h2>{{index}}</h2>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-data-table :items="item" class="elevation-1" hide-actions :headers="headers">
                                                <template v-slot:items="props">
                                                    <td>{{ props.item.nombre }} ({{props.item.producto}})</td>
                                                    <td>{{ props.item.cantidad }} {{props.item.unidad}}</td>
                                                    <td>{{ (form.comensales?parseInt(props.item.cantidad)*parseInt(form.comensales):0) }} {{props.item.unidad}}</td>
                                                </template>
                                            </v-data-table>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>


export default {
    name: "VagoutMenu",
    components: {},
    data: () => ({
        preload:false,
        disabled:false,
        form:{
            ciclo:null,
            comensales:null
        },
        consumoList: [],
        ciclos: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
        headers: [
            {text: 'Ingrediente', value: '', align:'center',sortable:false},
            {text: 'Cantidad Unidad', value: '', align:'center',sortable:false},
            {text: 'Total # Comensales', value: '', align:'center',sortable:false},
            // {text: 'Protein (g)', value: 'protein'},
            // {text: 'Actions', value: 'name', sortable: false}
        ],
    }),

    methods: {
        getCiclos() {
            this.preload = true;
            axios.get('/api/inventario/getCiclos/'+this.form.ciclo).then(res => {
                this.consumoList = res.data;
                this.preload = false;
            }).catch(e => {
                this.preload = false;
            })
        },
        generarCicloDia(){
            if(!this.form.ciclo){
                this.$alerError('El campo ciclo es requerido');
                return
            }
            if(!this.form.comensales){
                this.$alerError('El campo comensales es requerido');
                return
            }
            this.preload = true;
            axios.post('/api/inventario/generarCicloDia',this.form).then(res => {
                this.$alerSuccess(res.data.message)
                this.form.ciclo = null;
                this.form.comensales = null;
                this.cicloActual();
                this.preload = false;
            }).catch(e => {
                this.preload = false;
            })
        },
        cicloActual(){
            this.preload = true;
            axios.get('/api/inventario/cicloActual').then(res => {
                console.log("lalala",res);
                if(res.data.ingredientes.length === undefined){
                    this.consumoList = res.data.ingredientes;
                    this.form.comensales = parseInt(res.data.personas)
                    this.disabled = true;
                }
                this.preload = false;
            }).catch(e => {
                this.preload = false;
            })
        },
        generarExcel(){
            axios({
                method: 'get',
                url: '/api/inventario/cicloActualExcel',
                responseType: 'blob'
            }).then(res => {
                let blob = new Blob([res.data], {
                    type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                });
                let url = window.URL.createObjectURL(blob);
                let a = document.createElement('a');

                a.download = `InformeInventario.xlsx`;
                a.href = url;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                this.dialog = false
            }).catch(err => {
                console.log(err)
            })
        },
    },
    mounted() {
        this.cicloActual();
    }
}
</script>

<template>

    <v-layout>
        <v-flex xs12 sm12>
            <v-card>
                <v-card-title primary-title>
                    <div>
                        <h3 class="headline mb-0">Reporte Conteos</h3>
                    </div>
                </v-card-title>
                <v-container fluid grid-list-xl2>

                    <v-data-table :headers="headers" :items="listaInventarios">
                        <template v-slot:headers>
                            <tr>
                                <td>Bodega</td>
                                <td>Fecha creacion</td>
                                <td>Estado</td>
                                <td></td>
                            </tr>
                        </template>
                        <template v-slot:items="props">
                            <td>{{ props.item.bodega }}</td>
                            <td>{{ props.item.created_at }}</td>
                            <td> <v-chip color="green" text-color="white">{{ props.item.estado }}</v-chip></td>
                            <td> <v-btn color="info" @click="generarExcel(props.item.id)">Generar</v-btn></td>
                        </template>
                        <template v-slot:no-results>
                            <v-alert :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </template>
                    </v-data-table>
                </v-container>
            </v-card>
        </v-flex>
    </v-layout>

</template>
<script>
    export default {
        name: 'reporteConteos',

        data: () => ({
            listaInventarios: [],
            headers: []
        }),
        methods:{
            generarExcel(id){
                axios({
                    method: 'get',
                    url: '/api/bodega/inventarios/detalles/'+id,
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
            listarInventarios(){
                axios.get('/api/bodega/inventarios').then(res => {
                    this.listaInventarios = res.data;
                })
            }
        },
        beforeMount() {
            this.listarInventarios();
        }

    }

</script>

<style>

</style>

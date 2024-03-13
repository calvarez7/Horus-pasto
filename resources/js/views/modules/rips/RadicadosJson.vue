<template>
    <div>

        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table :search="search" :headers="headersInspected" :items="inspected" class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.numDocumentoIdObligado }}</td>
                    <td>{{ props.item.numFactura }}</td>
                    <td>{{ props.item.created_at }}</td>
                    <td>
                        <v-chip class="ma-2"  :color="setColor(props.item.estado_id)" label text-color="white">{{props.item.estado.Nombre}}</v-chip>
                    </td>
                    <td>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn fab dark small color="success" v-on="on" @click="actualizarEstado(props.item.id,7)">
                                    <v-icon dark>check</v-icon>
                                </v-btn>
                            </template>
                            <span>Confirmar</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on }">
                                <v-btn fab dark small color="error" v-on="on" @click="actualizarEstado(props.item.id,6)">
                                    <v-icon dark>clear</v-icon>
                                </v-btn>
                            </template>
                            <span>Anular</span>
                        </v-tooltip>
                    </td>
                </template>
            </v-data-table>
        </v-card>

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

    </div>
</template>
<script>
import Swal from 'sweetalert2';
export default {
    filters: {
        pesoFormat: (valor) => `$${new Intl.NumberFormat().format(valor) || 0}`
    },
    data() {
        return {
            preload:false,
            loadingACAP:false,
            search: '',
            inspected: [],
            headersInspected: [{
                text: '# Remisión',
                value: 'id'
            },
                {
                    text: 'Nit',
                    value: 'provider'
                },
                {
                    text: 'Numero Factura',
                    value: 'name'
                },
                {
                    text: 'Fecha Carga',
                    value: 'created_at'
                },
                {
                    text: 'Estado',
                    value: 'state'
                },
                {
                    text: 'Acciones',
                    value: 'actions'
                },
            ],
        }
    },
    mounted() {
        this.loadInspected();
    },
    methods: {
        loadInspected() {
            this.preload = true;
            axios.get('/api/rips/rips_json_cargados').then(res =>{
                this.preload = false;
                this.inspected = res.data;
            }).catch(e => {
                console.log(e);
                this.preload = false;
            })

        },
        actualizarEstado(id,estado){
            this.preload = true;
            axios.put('/api/rips/estado-rip-json/'+id+'/'+estado).then(res =>{
                this.preload = false;
                this.loadInspected();
            }).catch(e => {
                console.log(e);
                this.preload = false;
            })

        },
        setColor(estado){
            let color = 'warning'
            switch (parseInt(estado)){
                case 7:
                    color = 'success';
                    break;
                case 6:
                    color = 'error';
                    break;
            }
            return color
        }
        // file202(id,name){
        //     this.preload = true;
        //     axios.get('/api/m202/file202/'+id).then(res => {
        //         const blob = new Blob([res.data], {
        //             type: "text/plain"
        //         });
        //         const url = window.URL.createObjectURL(blob);
        //         const a = document.createElement('a');
        //         a.download = name;
        //         a.href = url;
        //         document.body.appendChild(a);
        //         a.click();
        //         document.body.removeChild(a);
        //         this.preload = false;
        //     }).catch(e => {
        //         this.preload = false;
        //         console.log(e);
        //     })
        //
        //
        // },
        // downloadCertificate(id) {
        //     const form = {
        //         type: 1
        //     };
        //     this.preload = true;
        //     axios.post("/api/m202/certificate/"+id, form, {responseType: "arraybuffer"}).then(res => {
        //         const blob = new Blob([res.data], {
        //             type: "application/pdf"
        //         });
        //         const link = document.createElement("a");
        //         link.href = window.URL.createObjectURL(blob);
        //         window.open(link.href, "_blank");
        //         this.preload = false;
        //     }).catch(e => {
        //         console.log(e);
        //         this.preload = false;
        //     })
        //
        // },
        // reject202(item){
        //     Swal.fire({
        //         title: 'Esta seguro de rechazar?',
        //         text: 'Una vez rechazado, se perdera la información',
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: "#4CAF50",
        //         cancelButtonColor: "#FF5252",
        //         confirmButtonText: "SI",
        //         cancelButtonText: "NO"
        //     }).then(async (result) => {
        //         if (result.isConfirmed) {
        //             this.preload = true;
        //             axios.get('/api/m202/reject202/'+item.id).then(res => {
        //                 this.$alerSuccess(res.data.message);
        //                 this.loadInspected();
        //                 this.preload = false;
        //             }).catch(e => {
        //                 console.log(e);
        //                 this.preload = false;
        //             })
        //
        //         }
        //     });
        // }
    }
}
</script>

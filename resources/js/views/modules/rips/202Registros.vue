<template>
    <div>

        <v-card>
            <v-card-title>
                <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table :search="search" :headers="headersInspected" :items="inspected" class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.provider }}</td>
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.created_at }}</td>
                    <td>{{ props.item.start_date }}</td>
                    <td>{{ props.item.final_date }}</td>
                    <td> <div v-if="props.item.state_id == 6">
                        <v-chip class="ma-2" color="red" label text-color="white">Rechazado</v-chip>
                    </div>
                    <div v-else>
                        <v-chip v-if="props.item.partial" class="ma-2" color="deep-purple accent-4" label text-color="white">Parcial</v-chip>
                        <v-chip v-else class="ma-2" color="green" text-color="white" label>{{props.item.state}}</v-chip>
                    </div></td>
                    <td> <div v-if="props.item.state_id != 6">
                        <v-tooltip bottom>
                            <template #activator="{ on, attrs }">
                                <v-btn icon color="info" v-bind="attrs" v-on="on" @click="file202(props.item.id,props.item.name)">
                                    <v-icon dark>
                                        mdi-arrow-down-bold-circle
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Descargar Archivo</span>
                        </v-tooltip>
                        <v-tooltip  bottom>
                            <template #activator="{ on, attrs }">
                                <v-btn icon color="primary" v-bind="attrs" v-on="on" @click="downloadCertificate(props.item.id)">
                                    <v-icon dark>
                                        mdi-file-check-outline
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Descargar Certificado</span>
                        </v-tooltip>
                        <v-tooltip  bottom>
                            <template #activator="{ on, attrs }">
                                <v-btn icon color="error" v-bind="attrs" v-on="on" @click="reject202(props.item)">
                                    <v-icon dark>
                                        mdi-file-cancel-outline
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Rechazar</span>
                        </v-tooltip>
                    </div> </td>
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
                    text: 'IPS',
                    value: 'provider'
                },
                {
                    text: 'Nombre',
                    value: 'name'
                },
                {
                    text: 'Fecha Carga',
                    value: 'created_at'
                },
                {
                    text: 'Fecha Inicio',
                    value: 'start_date'
                },
                {
                    text: 'Fecha Final',
                    value: 'final_date'
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
            axios.get('/api/m202/loadInspected').then(res =>{
                this.preload = false;
                this.inspected = res.data;
            }).catch(e => {
                console.log(e);
                this.preload = false;
            })

        },
        file202(id,name){
            this.preload = true;
            axios.get('/api/m202/file202/'+id).then(res => {
                const blob = new Blob([res.data], {
                    type: "text/plain"
                });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.download = name;
                a.href = url;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                this.preload = false;
            }).catch(e => {
                this.preload = false;
                console.log(e);
            })


        },
        downloadCertificate(id) {
            const form = {
                type: 1
            };
            this.preload = true;
           axios.post("/api/m202/certificate/"+id, form, {responseType: "arraybuffer"}).then(res => {
               const blob = new Blob([res.data], {
                   type: "application/pdf"
               });
               const link = document.createElement("a");
               link.href = window.URL.createObjectURL(blob);
               window.open(link.href, "_blank");
               this.preload = false;
           }).catch(e => {
               console.log(e);
               this.preload = false;
           })

        },
        reject202(item){
            Swal.fire({
                title: 'Esta seguro de rechazar?',
                text: 'Una vez rechazado, se perdera la información',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#4CAF50",
                cancelButtonColor: "#FF5252",
                confirmButtonText: "SI",
                cancelButtonText: "NO"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    this.preload = true;
                    axios.get('/api/m202/reject202/'+item.id).then(res => {
                        this.$alerSuccess(res.data.message);
                        this.loadInspected();
                        this.preload = false;
                    }).catch(e => {
                        console.log(e);
                        this.preload = false;
                    })

                }
            });
        }
    }
}
</script>

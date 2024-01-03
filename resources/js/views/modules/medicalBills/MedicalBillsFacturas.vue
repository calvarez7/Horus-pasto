<template>
    <div>

        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-text>
                        <v-container class="pa-0">
                            <v-layout row wrap>
                                <v-container>
                                    <v-layout row wrap>
                                        <v-flex xs6 px-3>
                                            <v-autocomplete v-model="prestador" :items="allProveedores" item-value="nit"
                                                item-text="name" label="Nit-Prestador">
                                            </v-autocomplete>
                                        </v-flex>

                                        <v-flex xs4>
                                            <v-text-field v-model="factura" label="Factura"></v-text-field>
                                        </v-flex>

                                        <v-flex xs2 px-1>
                                            <v-btn color="info" @click="show()">Ver</v-btn>
                                        </v-flex>

                                        <v-btn color="warning" @click="compress()">Comprimir PDF por grupos (poco peso)
                                        </v-btn>
                                        <v-btn color="info" @click="compress2()">Comprimir PDF uno por uno (mucho peso)
                                        </v-btn>

                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>

        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

    </div>
</template>

<script>
    import {
        PrestadorService,
        AdjuntoService
    } from '../../../services';
    export default {
        name: 'Facturas',
        data: () => ({
            listProveedores: [],
            prestador: '',
            factura: '',
            preload: false
        }),
        computed: {
            allProveedores() {
                return this.listProveedores.map((proveedor) => {
                    return {
                        id: proveedor.Prestador_id,
                        nit: proveedor.nit,
                        name: `${proveedor.nit} - ${proveedor.Nombre}`
                    }
                })
            }
        },
        mounted() {
            this.fetchProveedores();
        },
        methods: {
            async fetchProveedores() {
                try {
                    this.listProveedores = await PrestadorService.getPrestadores();
                } catch (error) {
                    console.log(error)
                }
            },
            async show() {
                if (this.prestador == '' || this.factura == '') {
                    return;
                }
                let ruta = '/facturascuentasmedicas/' + this.prestador + '/' + this.factura + '.pdf';
                this.preload = true;
                try {
                    let adj = await AdjuntoService.get(ruta);
                    let blob = new Blob([adj[1]], {
                        type: adj[0]
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");
                    this.preload = false
                } catch (error) {
                    this.$alerError('El adjunto de la factura no existe!');
                    this.preload = false
                }
            },
            compress() {
                window.open('https://pdf.io/es/compress/')
            },
            compress2() {
                window.open('https://smallpdf.com/compress-pdf')
            }

        }
    }

</script>

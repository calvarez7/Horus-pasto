<template>
    <v-content>

        <v-layout row wrap grid-list-md>
            <v-flex xs12 sm12 md12 lg12 xl12>
                <v-carousel hide-delimiters height="auto">
                    <v-carousel-item id="img" v-for="(item,i) in items" :key="i" :src="item.src">
                    </v-carousel-item>
                </v-carousel>
            </v-flex>
        </v-layout>
        <template>
            <div class="text-center">
                <v-dialog v-model="preload_certificado" persistent width="300">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Tranquilo procesamos tu solicitud !
                            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </div>
        </template>
        <v-flex xs10 offset-xs1 class="mt-2 mb-2">
            <v-container fluid grid-list-xl>
                <v-layout row wrap>
                    <v-flex v-for="card in cards" :key="card.title" xs12 md3 sm2>
                        <v-hover>
                            <v-card v-if="validacion(card)" justify-center :to="card.path" slot-scope="{ hover }"
                                :class="`elevation-${hover ? 24 : 2} mx-auto border-card`">
                                <v-img :src="card.src" height="120px">
                                    <v-container fill-height fluid pa-2>
                                    </v-container>
                                </v-img>

                                <v-card-actions>
                                    <v-layout row wrap justify-center>
                                        <v-card-title>
                                            <div>
                                                <strong>
                                                    <span style="font-size:65%">
                                                        {{ card.title }}
                                                    </span>
                                                </strong>
                                            </div>
                                        </v-card-title>
                                    </v-layout>
                                </v-card-actions>
                            </v-card>
                        </v-hover>
                    </v-flex>
                    <v-flex xs12 md3 sm2>
                        <v-card @click="auditoriaCertificado()">
                            <v-img height="120px" src="/images/certificados.png"></v-img>
                            <v-card-actions>
                                <v-layout row wrap justify-center>
                                    <v-card-title>
                                        <div>
                                            <strong>
                                                <span style="font-size:65%">
                                                    CERTIFICADO DE AFILIACIÓN
                                                </span>
                                            </strong>
                                        </div>
                                    </v-card-title>
                                </v-layout>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-flex>

    </v-content>
</template>

<script>
    export default {
        name: "Home",
        props: {
            paciente: Object
        },
        data() {
            return {
                preload_certificado: false,
                items: [
                    {
                        src: '/images/bannerHome2.png'
                    },
                    {
                        src: '/images/bannerHome3.png'
                    }
                ],

                cards: [
                    {
                        title: 'CITAS MEDICO GENERAL USUARIOS ADSCRITOS A IPS SUMIMEDICAL',
                        src: '/images/cita.png',
                        path: '/gestion/citas',
                        pac: this.paciente
                    },
                    {
                        title: 'TRAMITES ADMINISTRATIVOS Y DE AFILIACIONES',
                        src: '/images/TRAMITES ADMINISTRATIVOS Y AFILIACIONES.png',
                        path: '/gestion/radicacion',
                        pac: this.paciente
                    },
                    {
                        title: 'PQRSF',
                        src: '/images/pqrgestor.png',
                        path: '/gestion/pqrsf',
                        pac: this.paciente
                    },
                ],
            }
        },
        methods: {
            validacion(card) {
                if (card.title == 'CITAS') {
                    if ((card.pac.Estado_Afiliado == 1 && card.pac.prestador_id == 67) &&
                        (card.pac.entidad_id == 1 || card.pac.entidad_id == 3 || card.pac.entidad_id == 5)) {
                        return true;
                    } else {
                        return false;
                    }
                } else if (card.title == 'RADICACIÓN') {
                    if (card.pac.Estado_Afiliado == 1) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return true;
                }
            },
            generalPDF() {
                const data = {
                    type: 'certificadoAfiliado',
                    id: this.paciente.id,
                }
                this.preload_certificado = true;
                axios.post("/api/redvital/print-pdf", data, {
                        responseType: "arraybuffer"
                    }).then(res => {
                        this.preload_certificado = false
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("certificado");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    })
                    .catch(err => console.log(err.response));
            },
            auditoriaCertificado() {
                axios.post('/api/redvital/auditoriaCertificado', this.paciente)
                    .then(res => {
                        this.generalPDF()
                        this.$alerSuccess('Certificado Generado en exito!');
                    })
            }
        }
    }

</script>

<style>
    #img {
        display: block;
        margin: 0 auto;
        max-width: 100%;
    }

</style>

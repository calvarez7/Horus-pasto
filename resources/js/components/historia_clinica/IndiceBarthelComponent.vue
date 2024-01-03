<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="justify-center title layout font-weight-light">INDICE DE BARTHEL
                        <span><b> <span> - Total:</span> <span><b>{{totalBarthel}}</b></span></b></span></span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex sm12 md12 sd12 v-for="barthel in bartheles" :key="barthel.nombre" :value="barthel.valor">
                        <v-card-title class="headline" style="color:#0074a6;background-color:#4caf506b">
                            <span class="justify-center title layout font-weight-light">
                                <span><b>{{barthel.nombre}}</b></span></span>
                        </v-card-title>
                        <v-radio-group v-model="barthel.indiceBarthelValor" color="primary">
                            <v-radio color="primary" v-for="opcion in barthel.opciones" :key="opcion.nombre"
                                :label="opcion.nombre" :value="opcion.valor"></v-radio>
                        </v-radio-group>
                    </v-flex>
                    <v-flex class="text-xs-center" v-if="totalBarthel < 20">
                        <v-chip color="info" text-color="white">
                            <span>Total :</span>{{totalBarthel}}
                            <v-icon right>mdi-alarm-light</v-icon>
                        </v-chip>
                    </v-flex>
                    <v-flex class="text-xs-center" v-if="totalBarthel >= 20 && totalBarthel <= 35">
                        <v-chip color="red" text-color="white">
                            <span>Grave :</span>{{totalBarthel}}
                            <v-icon right>mdi-alarm-light</v-icon>
                        </v-chip>
                    </v-flex>
                    <v-flex class="text-xs-center" v-if="totalBarthel >= 40 && totalBarthel <= 55">
                        <v-chip color="warning" text-color="white">
                            <span>Moderado :</span>{{totalBarthel}}
                            <v-icon right>mdi-alarm-light</v-icon>
                        </v-chip>
                    </v-flex>
                    <v-flex class="text-xs-center" v-if="totalBarthel >= 60 && totalBarthel <= 99">
                        <v-chip color="primary" text-color="white">
                            <span>Leve :</span>{{totalBarthel}}
                            <v-icon right>mdi-alarm-light</v-icon>
                        </v-chip>
                    </v-flex>
                    <v-flex class="text-xs-center" v-if="totalBarthel === 100">
                        <v-chip color="success" text-color="white">
                            <span>Independiente :</span>{{totalBarthel}}
                            <v-icon right>mdi-alarm-light</v-icon>
                        </v-chip>
                    </v-flex>
                    <v-btn color="success" round @click="saveIndiceBarthel()">Guardar y seguir</v-btn>
                </v-layout>
            </v-container>
        </v-form>
    </div>
</template>

<script>
    export default {
        name: 'HorusHealthIndiceBarthelComponent',
        props: {
            datosCita: Object
        },
        data() {
            return {
                bartheles: [{
                        nombre: 'comer',
                        indiceBarthelValor: null,
                        opciones: [{
                                nombre: "Totalmente independiente",
                                valor: 10,
                            },
                            {
                                nombre: "Necesita ayuda para cortar carne, el pan, etc.",
                                valor: 5,
                            },
                            {
                                nombre: "Dependiente",
                                valor: 0,
                            },
                        ]
                    },
                    {
                        nombre: 'Lavarse',
                        indiceBarthelValor: null,
                        opciones: [{
                                nombre: "Independiente: entra y sale solo del baño",
                                valor: 5,
                            },
                            {
                                nombre: "Dependiente",
                                valor: 0,
                            },
                        ]
                    },
                    {
                        nombre: 'Vestirse',
                        indiceBarthelValor: null,
                        opciones: [{
                                nombre: "Independiente: capaz de ponerse y de quitarse la ropa, abotonarse, atarse los zapatos",
                                valor: 10,
                            },
                            {
                                nombre: "Necesita ayuda",
                                valor: 5,
                            },
                            {
                                nombre: "Dependiente",
                                valor: 0,
                            },
                        ]
                    },
                    {
                        nombre: 'Arreglarse',
                        indiceBarthelValor: null,
                        opciones: [{
                                nombre: "Independiente para lavarse la cara, las manos, peinarse, afeitarse, maquillarse, etc.",
                                valor: 5,
                            },
                            {
                                nombre: "Dependiente",
                                valor: 0,
                            },
                        ]
                    },
                    {
                        nombre: 'Deposiciones (valórese la semana previa)',
                        indiceBarthelValor: null,
                        opciones: [{
                                nombre: "Continencia normal ",
                                valor: 10,
                            },
                            {
                                nombre: "Ocasionalmente algún episodio de incontinencia, o necesita ayuda para administrarse supositorios o lavativas",
                                valor: 5,
                            },
                            {
                                nombre: "Incontinencia",
                                valor: 0,
                            },
                        ]
                    },
                    {
                        nombre: 'Micción (valórese la semana previa)',
                        indiceBarthelValor: null,
                        opciones: [{
                                nombre: "Continencia normal, o es capaz de cuidarse de la sonda si tiene una puesta",
                                valor: 10,
                            },
                            {
                                nombre: "Un episodio diario como máximo de incontinencia, o necesita ayuda para cuidar de la sonda",
                                valor: 5,
                            },
                            {
                                nombre: "Incontinencia",
                                valor: 0,
                            },
                        ]
                    },
                    {
                        nombre: 'Usar el retrete',
                        indiceBarthelValor: null,
                        opciones: [{
                                nombre: "Independiente para ir al cuarto de aseo, quitarse y ponerse la ropa…",
                                valor: 10,
                            },
                            {
                                nombre: "Necesita ayuda para ir al retrete, pero se limpia solo",
                                valor: 5,
                            },
                            {
                                nombre: "Dependiente",
                                valor: 0,
                            },
                        ]
                    },
                    {
                        nombre: 'Trasladarse',
                        indiceBarthelValor: null,
                        opciones: [{
                                nombre: "Independiente para ir del sillón a la cama",
                                valor: 15,
                            },
                            {
                                nombre: "Mínima ayuda física o supervisión para hacerlo",
                                valor: 10,
                            },
                            {
                                nombre: "Necesita gran ayuda, pero es capaz de mantenerse sentado solo",
                                valor: 5,
                            },
                            {
                                nombre: "Dependiente",
                                valor: 0,
                            },
                        ]
                    },
                    {
                        nombre: 'Deambular',
                        indiceBarthelValor: null,
                        opciones: [{
                                nombre: "Independiente, camina solo 50 metros",
                                valor: 15,
                            },
                            {
                                nombre: "Necesita ayuda física o supervisión para caminar 50 metros",
                                valor: 10,
                            },
                            {
                                nombre: "Independiente en silla de ruedas sin ayuda",
                                valor: 5,
                            },
                            {
                                nombre: "Dependiente",
                                valor: 0,
                            },
                        ]
                    },
                    {
                        nombre: 'Escalones',
                        indiceBarthelValor: null,
                        opciones: [{
                                nombre: "Independiente para bajar y subir escaleras",
                                valor: 10,
                            },
                            {
                                nombre: "Necesita ayuda física o supervisión para hacerlo",
                                valor: 5,
                            },
                            {
                                nombre: "Dependiente",
                                valor: 0,
                            },
                        ]
                    },
                ],
                allBarthel: {}
            };
        },

        computed: {
            totalBarthel: function () {
                return this.bartheles[0].indiceBarthelValor + this.bartheles[1].indiceBarthelValor + this.bartheles[
                        2].indiceBarthelValor +
                    this.bartheles[3].indiceBarthelValor + this.bartheles[4].indiceBarthelValor + this.bartheles[5]
                    .indiceBarthelValor +
                    this.bartheles[6].indiceBarthelValor + this.bartheles[7].indiceBarthelValor + this.bartheles[8]
                    .indiceBarthelValor +
                    this.bartheles[9].indiceBarthelValor
            }
        },

        created() {
            this.fetchIndiceBarthel();
        },

        methods: {

            saveIndiceBarthel() {
                const data = {
                    citapaciente_id: this.datosCita.cita_paciente_id,
                    barthelComer: this.bartheles[0].indiceBarthelValor,
                    barthelLavarse: this.bartheles[1].indiceBarthelValor,
                    barthelVestirse: this.bartheles[2].indiceBarthelValor,
                    barthelArreglarse: this.bartheles[3].indiceBarthelValor,
                    barthelDeposiciones: this.bartheles[4].indiceBarthelValor,
                    barthelMiccion: this.bartheles[5].indiceBarthelValor,
                    barthelRetrete: this.bartheles[6].indiceBarthelValor,
                    barthelTrasladarse: this.bartheles[7].indiceBarthelValor,
                    barthelDeambular: this.bartheles[8].indiceBarthelValor,
                    barthelEscalones: this.bartheles[9].indiceBarthelValor,
                };
                axios.post('/api/historia/saveIndiceBerthel', data).then(res => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.$emit('respuestaComponente')
                })
            },

            fetchIndiceBarthel() {
                axios.get('/api/historia/fetchIndiceBarthel/' + this.datosCita.cita_paciente_id).then(res => {
                    this.bartheles[0].indiceBarthelValor = parseInt(res.data.barthelComer)
                    this.bartheles[1].indiceBarthelValor= parseInt(res.data.barthelLavarse)
                    this.bartheles[2].indiceBarthelValor= parseInt(res.data.barthelVestirse)
                    this.bartheles[3].indiceBarthelValor= parseInt(res.data.barthelArreglarse)
                    this.bartheles[4].indiceBarthelValor= parseInt(res.data.barthelDeposiciones)
                    this.bartheles[5].indiceBarthelValor= parseInt(res.data.barthelMiccion)
                    this.bartheles[6].indiceBarthelValor= parseInt(res.data.barthelRetrete)
                    this.bartheles[7].indiceBarthelValor= parseInt(res.data.barthelTrasladarse)
                    this.bartheles[8].indiceBarthelValor= parseInt(res.data.barthelDeambular)
                    this.bartheles[9].indiceBarthelValor= parseInt(res.data.barthelEscalones)
                })
            }
        },
    };

</script>

<style lang="scss" scoped>

</style>

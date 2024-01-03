<template>
    <div>
        <v-card-title class="headline" style="color:white;background-color:#0074a6">
            <span class="title layout justify-center font-weight-light">Apgar familiar</span>
        </v-card-title>
        <v-layout row wrap>
            <v-flex xs12 sm6 md3 v-for="c in arrCheckBox" :key="c.id">
                <label style="font-size: 8px;">
                    <h2>{{c.nombre}}</h2>
                </label>
                <v-radio-group style="font-size: 8px" v-model="apgarFamiliar[c.model]">
                    <v-radio color="red" v-for="n in c.options" :key="n.val" :label="n.nombre" :value="n.val"></v-radio>
                </v-radio-group>
            </v-flex>
            <v-flex xs12 sm12 md12 style="text-align: center">
                <label style="font-size: large"><b>Resultado:{{resultadoApgar}}</b></label>
            </v-flex>
            <v-flex xs12 sm12 md12 style="text-align: center">
                <v-btn color="success" round @click="saveResultado()">Guardar Resultado</v-btn>
            </v-flex>
            <v-flex xs12 sm12 md12>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Resultados</span>
                </v-card-title>
                <v-data-table :headers="headerApgar" :items="apgarfamiliares" class="elevation-1">
                    <template v-slot:items="props">
                        <td class="text-xs">{{ props.item.id }}</td>
                        <td class="text-xs">{{ props.item.valor_ayuda_familia | clasificacion}}</td>
                        <td class="text-xs">{{ props.item.valor_le_gusta_familia_comparte | clasificacion}}</td>
                        <td class="text-xs">{{ props.item.valor_cosas_nuevas | clasificacion}}</td>
                        <td class="text-xs">{{ props.item.valor_le_gusta_familia_hace | clasificacion}}</td>
                        <td class="text-xs">{{ props.item.valor_le_gusta_familia_comparte | clasificacion}}</td>
                        <td class="text-xs">{{ props.item.resultado }}</td>
                        <td class="text-xs">{{ props.item.name }}</td>
                    </template>
                    <template v-slot:pageText="props">
                        Lignes {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                    </template>
                </v-data-table>
            </v-flex>
            <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
        </v-layout>
    </div>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchApgarFamiliar();
            this.fetchlistaApgar();
        },
        data() {
            return {
                prueba: 1,
                apgarfamiliares: [],
                arrCheckBox: [{
                        id: 1,
                        nombre: 'Estoy contento de pensar que puedo recurrir a mi familia en busca de ayuda cuando algo me preocupa.',
                        model: 'ayuda_familia',
                        options: [{
                            nombre: 'SIEMPRE',
                            val: 4
                        }, {
                            nombre: 'CASI SIEMPRE',
                            val: 2,
                        }, {
                            nombre: 'ALGUNAS VECES',
                            val: 1,
                        }, {
                            nombre: 'CASI NUNCA',
                            val: 0
                        }]
                    },
                    {
                        id: 2,
                        nombre: 'Estoy satisfecho con el modo que tiene mi familia de hablar las cosas conmigo y de cómo compartimos los problemas.',
                        model: 'familia_habla_con_usted',
                        options: [{
                            nombre: 'SIEMPRE',
                            val: 4
                        }, {
                            nombre: 'CASI SIEMPRE',
                            val: 2
                        }, {
                            nombre: 'ALGUNAS VECES',
                            val: 1
                        }, {
                            nombre: 'CASI NUNCA',
                            val: 0
                        }]
                    },
                    {
                        id: 3,
                        nombre: 'Me agrada pensar que mi familia acepta y apoya mis deseos de llevar a cabo nuevas actividades o seguir una nueva dirección.',
                        model: 'cosas_nuevas',
                        options: [{
                            nombre: 'SIEMPRE',
                            val: 4
                        }, {
                            nombre: 'CASI SIEMPRE',
                            val: 2
                        }, {
                            nombre: 'ALGUNAS VECES',
                            val: 1
                        }, {
                            nombre: 'CASI NUNCA',
                            val: 0
                        }]
                    },
                    {
                        id: 4,
                        nombre: 'Me satisface el modo que tiene mi familia de expresar su afecto y cómo responde a mis emociones, como cólera, tristeza y amor.',
                        model: 'le_gusta_familia_hace',
                        options: [{
                                nombre: 'SIEMPRE',
                                val: 4
                            }, {
                                nombre: 'CASI SIEMPRE',
                                val: 2
                            },
                            {
                                nombre: 'ALGUNAS VECES',
                                val: 1
                            },
                            {
                                nombre: 'CASI NUNCA',
                                val: 0
                            }
                        ]
                    },
                    {
                        id: 5,
                        nombre: 'Me satisface la forma en que mi familia y yo pasamos el tiempo juntos.',
                        model: 'le_gusta_familia_comparte',
                        options: [{
                                nombre: 'SIEMPRE',
                                val: 4
                            }, {
                                nombre: 'CASI SIEMPRE',
                                val: 2
                            },
                            {
                                nombre: 'ALGUNAS VECES',
                                val: 1
                            },
                            {
                                nombre: 'CASI NUNCA',
                                val: 0
                            }
                        ]
                    }
                ],
                result: ['Funcionalidad normal', 'Disfunción moderada', 'Disfunción grave'],
                apgarFamiliar: {
                    ayuda_familia: '',
                    familia_habla_con_usted: '',
                    cosas_nuevas: '',
                    le_gusta_familia_hace: '',
                    le_gusta_familia_comparte: '',
                    resultado: 0
                },
                headerApgar: [{
                        text: 'id',
                    },
                    {
                        text: 'Ayuda la familia',
                    },
                    {
                        text: 'Comparte problemas',
                    },
                    {
                        text: 'Hacen cosas nueva',
                    },
                    {
                        text: 'Apoyo familiar',
                    },
                    {
                        text: 'Tiempo familiar',
                    },
                    {
                        text: 'Resultado',
                    },
                    {
                        text: 'Registrado por',
                    },
                ],
            }
        },
        filters: {
            clasificacion(item){
                if (parseInt(item) == 0) {
                    return 'CASI NUNCA'
                }else if(parseInt(item) == 1){
                    return 'ALGUNAS VECES'
                }else if(parseInt(item) == 2){
                    return 'CASI SIEMPRE'
                }else{
                    return 'SIEMPRE'
                }
            }
        },
        computed: {
            resultadoApgar() {
                return parseInt(this.apgarFamiliar.ayuda_familia + this.apgarFamiliar.familia_habla_con_usted +
                    this.apgarFamiliar.cosas_nuevas + this.apgarFamiliar.le_gusta_familia_hace + this.apgarFamiliar
                    .le_gusta_familia_comparte)
            }
        },
        methods: {
            saveResultado() {
                this.apgarFamiliar.resultado = this.resultadoApgar
                this.apgarFamiliar.citapaciente_id = this.datosCita.cita_paciente_id;
                this.apgarFamiliar.paciente_id = this.datosCita.paciente_id;
                axios.post('/api/historia/saveApgarFamiliar', this.apgarFamiliar)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchApgarFamiliar();
                    })
            },
            fetchApgarFamiliar() {
                axios.get(`/api/historia/fetchApgarFamiliar/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.apgarfamiliares = res.data;
                    });
            },
            fetchlistaApgar() {
                axios.get(`/api/historia/fetchlistaApgar/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.listaApgarfamiliar = res.data;
                        this.apgarFamiliar.ayuda_familia = parseInt(res.data.valor_ayuda_familia);
                        this.apgarFamiliar.familia_habla_con_usted = parseInt(res.data.valor_familia_habla_con_usted);
                        this.apgarFamiliar.cosas_nuevas = parseInt(res.data.valor_cosas_nuevas);
                        this.apgarFamiliar.le_gusta_familia_hace = parseInt(res.data.valor_le_gusta_familia_hace);
                        this.apgarFamiliar.le_gusta_familia_comparte = parseInt(res.data.valor_le_gusta_familia_comparte);
                    });
            },
            guardarSeguir() {
                this.$emit('respuestaComponente');
            }
        }
    }

</script>

<template>
    <div>
        <v-card>
            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                <span class="title layout justify-center font-weight-light">Historial Farmacologicos</span>
            </v-card-title>
            <v-card-title primary-title>
                <v-flex xs12 sm6 md12>
                    <v-data-table :items="getAntecedenteFarmacologia" :headers="hola" hide-actions class="elevation-1">
                        <template v-slot:items="props">
                            <td class="text-xs">{{ props.item.Medicamento }}</td>
                            <td class="text-xs">{{ props.item.Via }}</td>
                            <td class="text-xs">{{ props.item.unidadMedida }}</td>
                            <td class="text-xs">{{ props.item.Frecuencia }}</td>
                            <td class="text-xs">{{ props.item.Fecha_orden }}</td>
                            <td>
                                <v-btn v-if="props.item.alergia=='SI'" color="error">
                                    {{ props.item.alergia}}</v-btn>

                                <v-btn v-if="props.item.alergia!='SI'" color="success">
                                    NO</v-btn>
                            </td>
                            <td class="text-xs">{{ props.item.observacionAlergia }}</td>
                            <td class="text-xs">{{ props.item.medico }} {{props.item.apellido}}</td>
                            <!-- <td class="text-xs">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon color="red" dark v-on="on">
                                            <v-icon @click="inabilitarFarmaco(props.item.id)">
                                                mdi-delete-forever
                                            </v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Historial</span>
                                </v-tooltip>
                            </td> -->
                        </template>
                    </v-data-table>
                </v-flex>
            </v-card-title>
        </v-card>
    </div>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchAntecedenteFarmacologia()
        },
        data() {
            return {
                listArticuloWithout: [],
                loading: false,
                getAntecedenteFarmacologia: [],
                hola: [

                    {
                        text: 'Medicamentos'
                    },
                    {
                        text: 'Via'
                    },
                    {
                        text: 'Presentación'
                    },
                    {
                        text: 'Frecuencia'
                    },
                    {
                        text: 'Fecha Orden'
                    },
                    {
                        text: 'Alergico'
                    },
                    {
                        text: 'Observacion'
                    },
                    {
                        text: 'Medico'
                    },
                ],
            }
        },
        methods: {
            fetchAntecedenteFarmacologia() {
                const farm = {
                    paciente_id: this.datosCita.paciente_id
                }
                axios.post('/api/historia/getAntecedentesQuimico', farm)
                    .then(res => {
                        this.getAntecedenteFarmacologia = res.data
                    });
            },
        }
    }

</script>

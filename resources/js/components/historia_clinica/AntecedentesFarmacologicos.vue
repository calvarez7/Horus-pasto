<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Alergia a medicamentos</span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex xs12 sm6 md4>
                        <VAutocomplete :items="listArticuloWithout" item-text="Nombre" :label="'Medicamento'"
                            :loading="loading" return-object required :search-input.sync="article"
                            v-model="antecedentesFarma.data" />
                    </v-flex>
                    <!-- <v-flex xs12 sm6 md3 v-if="antecedentesFarma.data">
                        <v-text-field v-model="antecedentesFarma.data.Via_Administracion" label="Via" readonly>
                        </v-text-field>
                    </v-flex>

                    <v-flex xs12 sm6 md3 v-if="antecedentesFarma.data">
                        <v-text-field v-model="antecedentesFarma.data.Forma_Farmaceutica" readonly label="Presentación">
                        </v-text-field>
                    </v-flex>

                    <v-flex xs12 sm6 md2 v-if="antecedentesFarma.data">
                        <v-text-field v-model="antecedentesFarma.utiempo" label="U-Tiempo">
                        </v-text-field>
                    </v-flex>

                    <v-flex xs12 sm6 md2>
                        <v-text-field v-model="antecedentesFarma.frecuencia" label="Frecuencia">
                        </v-text-field>
                    </v-flex>

                    <v-flex xs12 sm6 md2>
                        <v-select :items="sino" v-model="antecedentesFarma.alergia" label="Alergia">
                        </v-select>
                    </v-flex> -->
                    <v-flex xs12 sm12 md5>
                    <v-text-field  label="Observación Alergia"
                        v-model="antecedentesFarma.observacionAlergia">
                     </v-text-field>
                </v-flex>
                    <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="guardarAntecedenteFarmacologico()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-form>
        <v-flex xs12 sm6 md12>
            <v-data-table :items="getAntecedenteFarmacologia" :headers="hola" hide-actions class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.created_at }}</td>
                    <td class="text-xs">{{ props.item.Producto }}</td>
                    <!-- <td class="text-xs">{{ props.item.utiempo }}</td>
                    <td class="text-xs">{{ props.item.frecuencia }}</td> -->
                    <td class="text-xs">{{ props.item.observacionAlergia }}</td>
                    <td class="text-xs">{{ props.item.medico }}</td>
                    <td class="text-xs">
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
                    </td>
                </template>
            </v-data-table>
        </v-flex>
         <v-layout row wrap>
                    <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Alergias alimentarias</span>
                        </v-card-title>
                         </v-flex>
                         <v-flex xs12 sm6 md5 >
                        <v-text-field v-model="antecedentesFarma.Alimneto" label="Alimentos">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md5 >
                        <v-text-field v-model="antecedentesFarma.observacionalimneto" label="Observación">
                        </v-text-field>
                    </v-flex>
                     <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="guardarAntecedentesAlergicosAlimentos()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
                 <v-flex xs12 sm6 md12>
            <v-data-table :items="getAntecedenteAlimentos" :headers="alimnetos" hide-actions class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.created_at }}</td>
                    <td class="text-xs">{{ props.item.Alimneto }}</td>
                    <td class="text-xs">{{ props.item.observacionalimneto }}</td>
                    <td class="text-xs">{{ props.item.medico }}</td>
                     <td class="text-xs">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="red" dark v-on="on">
                                    <v-icon @click="inabilitaralimento(props.item.id)">
                                        mdi-delete-forever
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Historial</span>
                        </v-tooltip>
                    </td>
                </template>
            </v-data-table>
        </v-flex>
          <v-layout row wrap>
                    <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Alergias ambientales</span>
                        </v-card-title>
                         </v-flex>
                         <v-flex xs12 sm6 md5 >
                        <v-text-field v-model="antecedentesFarma.Ambientales" label="Ambientales">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md5 >
                        <v-text-field v-model="antecedentesFarma.observacionambiental" label="Observación">
                        </v-text-field>
                    </v-flex>
                     <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="guardarAntecedentesAlergicosAmbientales()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
                 <v-flex xs12 sm6 md12>
            <v-data-table :items="getAntecedenteAmbiantal" :headers="ambientales" hide-actions class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.created_at }}</td>
                    <td class="text-xs">{{ props.item.Ambientales }}</td>
                    <td class="text-xs">{{ props.item.observacionambiental }}</td>
                    <td class="text-xs">{{ props.item.medico }}</td>
                     <td class="text-xs">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="red" dark v-on="on">
                                    <v-icon @click="inabilitarambiental(props.item.id)">
                                        mdi-delete-forever
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Historial</span>
                        </v-tooltip>
                    </td>
                </template>
            </v-data-table>
        </v-flex>
         <v-layout row wrap>
                    <v-flex xs12>
                        <v-card-title class="headline" style="color:white;background-color:#0074a6">
                            <span class="title layout justify-center font-weight-light">Otras alergias</span>
                        </v-card-title>
                         </v-flex>
                         <v-flex xs12 sm6 md5 >
                        <v-text-field v-model="antecedentesFarma.OtrasAlergias" label="Otras alergias">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md5 >
                        <v-text-field v-model="antecedentesFarma.observacionotras" label="Observación">
                        </v-text-field>
                    </v-flex>
                     <v-flex xs12 sm6 md1>
                        <v-btn fab dark small color="success" @click="guardarAntecedentesAlergicosOtros()">
                            <v-icon dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
                 <v-flex xs12 sm6 md12>
            <v-data-table :items="getAntecedenteOtro" :headers="otras" hide-actions class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.created_at }}</td>
                    <td class="text-xs">{{ props.item.OtrasAlergias }}</td>
                    <td class="text-xs">{{ props.item.observacionotras }}</td>
                    <td class="text-xs">{{ props.item.medico }}</td>
                     <td class="text-xs">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="red" dark v-on="on">
                                    <v-icon @click="inabilitarotros(props.item.id)">
                                        mdi-delete-forever
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Historial</span>
                        </v-tooltip>
                    </td>
                </template>
            </v-data-table>
        </v-flex>
        <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
    </div>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchAntecedenteFarmacologia(),
            this.fetchAntecedenteAlergicosAlimentarios(),
            this.fetchAntecedenteAlergicosAmbientales(),
            this.fetchAntecedenteAlergicosOtros()
        },
        data() {
            return {
                antecedentesFarma: {
                    utiempo: '',
                    frecuencia: '',
                    alergia: '',
                    Alimneto:'',
                    Ambientales:'',
                    OtrasAlergias:'',
                    observacionmedicamento:'',
                    observacionalimneto:'',
                    observacionambiental:'',
                    observacionotras:'',
                },
                listArticuloWithout: [],
                loading: false,
                article: '',
                sino: ['SI', 'NO'],
                getAntecedenteFarmacologia: [],
                getAntecedenteAlimentos:[],
                getAntecedenteAmbiantal:[],
                getAntecedenteOtro:[],
                hola: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Medicamneto'
                    },
                    {
                        text: 'Observación'
                    },
                    {
                        text: 'Medico'
                    }
                ],
                alimnetos:[{
                    text:'Fecha'
                },
                {
                    text:'Alimento'
                },
                {
                    text:'Observación'
                },
                    {
                        text: 'Medico'
                    }
                ],
                ambientales:[{
                    text:'Fecha'

                },
                {
                    text:'Ambientales'
                },
                {
                    text:'Observación'
                },
                    {
                        text: 'Medico'
                    }
                ],
                otras:[{
                    text:'Fecha'

                },
                {
                    text:'Otras alergias'
                },
                {
                    text:'Observación'
                },
                    {
                        text: 'Medico'
                    }
                ]

            }
        },
        watch: {
            article: function () {
                if (this.article && this.article.length >= 3) {
                    this.fetchArticulosWithout();
                }
            },
        },
        methods: {

            fetchArticulosWithout() {
                this.loading = true;
                axios.post(`/api/bodega/articulo/allMedicamentos`, {
                        article: this.article,
                    })
                    .then(res => {
                        this.listArticuloWithout = res.data;
                        this.loading = false;
                    });
            },

            guardarAntecedenteFarmacologico() {
               if (!this.antecedentesFarma.data) {
                    this.$alerError("El campo medicamento es requerido!");
                    return
                } else if(!this.antecedentesFarma.observacionAlergia){
                    this.$alerError("El campo observación es requerido!");
                    return
                }
                this.antecedentesFarma.paciente_id = this.datosCita.paciente_id;
                this.antecedentesFarma.citaPaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveAntFarmaco', this.antecedentesFarma).then((res) => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.fetchAntecedenteFarmacologia();
                    this.clearAntecedenteFarmacologico();
                });
            },

            guardarAntecedentesAlergicosAlimentos(){
                if (!this.antecedentesFarma.Alimneto) {
                    this.$alerError("El campo Alimentos es requerido!");
                    return
                } else if(!this.antecedentesFarma.observacionalimneto){
                     this.$alerError("El campo observación es requerido!");
                    return
                }
                this.antecedentesFarma.paciente_id=this.datosCita.paciente_id;
                this.antecedentesFarma.citaPaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveAntAlergicosAlimentos',this.antecedentesFarma).then((res)=>{
                     this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.fetchAntecedenteAlergicosAlimentarios();
                    this.clearAntecedentesAlimento();
                });
            },

            guardarAntecedentesAlergicosAmbientales(){
                if (!this.antecedentesFarma.Ambientales) {
                    this.$alerError("El campo Ambientales es requerido!");
                    return
                } else if(!this.antecedentesFarma.observacionambiental){
                     this.$alerError("El campo observación es requerido!");
                    return
                }
                this.antecedentesFarma.paciente_id=this.datosCita.paciente_id;
                this.antecedentesFarma.citaPaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveAntAlergicosAmbientales',this.antecedentesFarma).then((res)=>{
                     this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.fetchAntecedenteAlergicosAmbientales();
                    this.clearAntecedentesAmbiental();
                });
            },

            guardarAntecedentesAlergicosOtros(){
                if (!this.antecedentesFarma.OtrasAlergias) {
                    this.$alerError("El campo Otras alergias es requerido!");
                    return
                } else if(!this.antecedentesFarma.observacionotras){
                     this.$alerError("El campo observación es requerido!");
                    return
                }
                this.antecedentesFarma.paciente_id=this.datosCita.paciente_id;
                this.antecedentesFarma.citaPaciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveAntAlergicosOtros',this.antecedentesFarma).then((res)=>{
                     this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.fetchAntecedenteAlergicosOtros();
                    this.clearAntecedentesOtros();
                });
            },

            fetchAntecedenteFarmacologia() {
                const farm = {
                    paciente_id: this.datosCita.paciente_id
                }
                axios.post('/api/historia/getAntecedentesFarmacologia', farm)
                    .then(res => {
                        this.getAntecedenteFarmacologia = res.data
                    });
            },

            fetchAntecedenteAlergicosAlimentarios(){
                const farm = {
                    paciente_id: this.datosCita.paciente_id
                }
                axios.post('/api/historia/getAntecedenteAlimentos', farm)
                   .then(res => {
                       this.getAntecedenteAlimentos = res.data
                   });
            },

            fetchAntecedenteAlergicosAmbientales(){
                const farm = {
                    paciente_id: this.datosCita.paciente_id
                }
                axios.post('/api/historia/getAntecedenteAmbientales', farm)
                   .then(res => {
                       this.getAntecedenteAmbiantal = res.data
                   });
            },

              fetchAntecedenteAlergicosOtros(){
                const farm = {
                    paciente_id: this.datosCita.paciente_id
                }
                axios.post('/api/historia/getAntecedenteOtros', farm)
                   .then(res => {
                       this.getAntecedenteOtro = res.data
                   });
            },

            inabilitarFarmaco(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El farmaco será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/farmaco/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchAntecedenteFarmacologia();
                });
            },

            inabilitaralimento(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El alimento será eliminado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/alimento/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchAntecedenteAlergicosAlimentarios();
                });
            },

            inabilitarambiental(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "La alergía será eliminada",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/ambiente/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchAntecedenteAlergicosAmbientales();
                });
            },

            inabilitarotros(id) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "La alergía será eliminada",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/historia/otralaergia/${id}`)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message +
                                    '<span>');
                            })
                            .catch(err => console.log(err.response.data));
                    }
                    this.fetchAntecedenteAlergicosOtros();
                });
            },

            clearAntecedenteFarmacologico() {
                this.antecedentesFarma.data = "";
                this.antecedentesFarma.utiempo = "";
                this.antecedentesFarma.frecuencia = "";
                this.antecedentesFarma.alergia = "";
                this.antecedentesFarma.observacionAlergia = "";
            },

            clearAntecedentesAlimento(){
                this.antecedentesFarma.Alimneto = "";
                this.antecedentesFarma.observacionalimneto='';
            },

            clearAntecedentesAmbiental(){
                this.antecedentesFarma.Ambientales = "";
                this.antecedentesFarma.observacionambiental='';
            },

            clearAntecedentesOtros(){
                this.antecedentesFarma.OtrasAlergias = "";
                this.antecedentesFarma.observacionotras='';
            },

            guardarSeguir() {
                this.$emit('respuestaComponente')
            }
        }
    }

</script>

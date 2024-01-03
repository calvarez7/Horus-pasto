<template>
    <v-container grid-list-md>
        <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-card-text class="headline success" style="color:white">
                    <h4 style="color:white">Consolidado 202</h4>
                </v-card-text>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs4 px-2>
                            <v-radio-group v-model="formConsolidate.tipoFecha" color="primary">
                                <template v-slot:label>
                                    <div>Seleccionar Tipo de fecha</div>
                                </template>
                                <v-radio value="1" color="primary">
                                    <template v-slot:label>
                                        <div>Fecha Carga</div>
                                    </template>
                                </v-radio>
                                <v-radio value="2" color="primary">
                                    <template v-slot:label>
                                        <div>Fecha Reporte</div>
                                    </template>
                                </v-radio>
                            </v-radio-group>
                        </v-flex>
                        <template v-if="parseInt(formConsolidate.tipoFecha) === 2">
                            <v-flex xs4>
                                <v-select v-model="formConsolidate.trimestre" :items="trimestres" item-text="nombre" item-value="val" label="Seleccionar Trimestre..."></v-select>
                            </v-flex>
                            <v-flex xs4>
                                <v-select v-model="formConsolidate.anio" :items="anios" label="Seleccionar año..."></v-select>
                            </v-flex>
                        </template>
                        <template v-else>
                            <v-flex xs4>
                                <v-text-field v-model="formConsolidate.fechaDesde" label="Fecha Inicial" prepend-icon="mdi-calendar" type="date" />
                            </v-flex>
                            <v-flex xs4>
                                <v-text-field v-model="formConsolidate.fechaHasta" :min="formConsolidate.fechaDesde" :max="formConsolidate.today" label="Fecha Final" prepend-icon="mdi-calendar" type="date" />
                            </v-flex>
                        </template>
                        <v-flex xs1>
                            <v-btn color="warning" @click="printConsolidated202">Descargar</v-btn>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
        <v-dialog v-model="loadingTipo3" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Generando Consolidado ...
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-layout>
    </v-container>
</template>
<script>
import moment from 'moment';
export default {
    data: () => ({
        loadingTipo3:false,
        trimestres:[
            {nombre:'Primer Trimestre',val:1},
            {nombre:'Segundo Trimestre',val:2},
            {nombre:'Tercer Trimestre',val:3},
            {nombre:'Cuarto Trimestre',val:4}
        ],
        anios:[],
        formConsolidate:{
            anio:null,
            trimestre:null,
            tipoFecha: '2',
            fechaDesde: moment().format('YYYY-MM-DD'),
            fechaHasta: moment().format('YYYY-MM-DD'),
        }
    }),
    methods:{
        async printConsolidated202(){
            let fechaCorte = null;
            switch (this.formConsolidate.trimestre){
                case 1:
                    fechaCorte = this.formConsolidate.anio+'0331';
                    break;
                case 2:
                    fechaCorte = this.formConsolidate.anio+'0630';
                    break;
                case 3:
                    fechaCorte = this.formConsolidate.anio+'0930';
                    break;
                case 4:
                    fechaCorte = this.formConsolidate.anio+'1231';
                    break;
            }
            this.loadingTipo3 = true;
            // const data = await this.$axios.$post(consolidate202(), this.formConsolidate)
            const data = await axios.post('/api/m202/consolidate202',this.formConsolidate);
            this.loadingTipo3 = false;
            const blob = new Blob([data.data], {
                type: "text/plain"
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.download = "SGD280RPED"+fechaCorte+"NI000860525148P08.TXT";
            a.href = url;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    },
    created() {
        const year = new Date();
        this.anios = [year.getFullYear()-3,year.getFullYear()-2,year.getFullYear()-1,year.getFullYear()]
    }
}
</script>

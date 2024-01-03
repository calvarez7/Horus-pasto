<template>
    <v-layout column>
        <v-card :disabled="loading">

            <v-card-title v-if="!loading">
                <h1>Descargar Fias</h1>
            </v-card-title>

            <v-card-text class="text-xs-center" v-if="loading">
                <v-progress-circular
                    :size="70"
                    :width="7"
                    color="primary"
                    indeterminate
                ></v-progress-circular>
            </v-card-text>

            <v-card-text v-else>
                <form @submit.prevent="descargar">

                    <v-layout row wrap>
                        <v-flex xs12 md12>
                            <v-select
                                v-model="form.tipo_fias"
                                label="Tipo de Fias"
                                :items="tipos_fias_sin_filtro"
                                item-value="id"
                                item-text="nombre"
                                solo
                                required
                            ></v-select>
                        </v-flex>

                        <v-flex xs12 md6>
                            <v-select
                                v-model="form.mes"
                                label="Mes"
                                :items="meses"
                                item-value="id"
                                item-text="nombre"
                                solo
                                required
                            ></v-select>
                        </v-flex>

                        <v-flex xs12 md6>
                            <v-select
                                v-model="form.anio"
                                label="Año"
                                :items="anios"
                                solo
                                required
                            ></v-select>
                        </v-flex>
                    </v-layout>

                    <v-card-actions>
                        <v-btn type="submit" color="success">Descargar</v-btn>
                    </v-card-actions>

                </form>
            </v-card-text>
        </v-card>
    </v-layout>
</template>
<script>
import { descargarFile } from '../../../api/descargas';
export default {

    created() {
        const year = new Date();
        this.anios = [
            year.getFullYear(),
            year.getFullYear()-1,
            year.getFullYear()-2,
            year.getFullYear()-3,
            year.getFullYear()-4,
            year.getFullYear()-5
        ]
    },

    data() {
        return {
            preload:false,
            anios:null,
            tipos_fias: [],
            tipos_fias_sin_filtro: [
                {nombre:"FIAS No. 2A - MORBILIDAD DE LA POBLACION GENERAL DEL MAGISTERIO POR GRUPOS ETARIOS POR AMBITO AMBULATORIO" ,id:'F2A', permiso:'descargar.F2A'},
                {nombre:"FIAS No. 2B - MORBILIDAD DE LA POBLACION GENERAL DEL MAGISTERIO POR GRUPOS ETARIOS POR AMBITO HOSPITALARIO" ,id:'F2B', permiso:'descargar.F2B'},
                {nombre:"FIAS No. 2C - MORBILIDAD DE LA POBLACION GENERAL DEL MAGISTERIO POR GRUPOS ETARIOS POR AMBITO DE URGENCIAS" ,id:'F2C', permiso:'descargar.F2C'},
                {nombre:"FIAS No. 2D - MORBILIDAD DE LA POBLACION GENERAL DEL MAGISTERIO POR GRUPOS ETARIOS POR AMBITO DOMICILIARIO" ,id:'F2D', permiso:'descargar.F2D'},
                {nombre:"FIAS No. 3 - MORTALIDAD DE LA POBLACION TOTAL DEL MAGISTERIO POR CICLO DE VIDA" ,id:'F3', permiso:'descargar.F3'},
                {nombre:"FIAS No. 4 - SEGUIMIENTO A PATOLOGIAS DE SINDROME METABOLICO" ,id:'F4', permiso:'descargar.F4'},
                {nombre:"FIAS No. 5 - SEGUIMIENTO AL COMPORTAMIENTO DEL CANCER " ,id:'F5', permiso:'descargar.F5'},
                {nombre:"FIAS No. 6 - SEGUIMIENTO AL COMPORTAMIENTO DE LAS ENFERMEDADES HUERFANAS" ,id:'F6', permiso:'descargar.F6'},
                {nombre:"FIAS No. 7 - SEGUIMIENTO AL COMPORTAMIENTO DE LA ENFERMEDAD RENAL CRONICA" ,id:'F7', permiso:'descargar.F7'},
                {nombre:"FIAS No. 8 - SEGUIMIENTO AL COMPORTAMIENTO DE LAS ALTERACIONES DE LA COAGULACIÓN" ,id:'F8', permiso:'descargar.F8'},
                {nombre:"FIAS No. 9 - SEGUIMIENTO AL COMPORTAMIENTO DE ARTRITIS REUMATOIDE" ,id:'F9', permiso:'descargar.F9'},
                {nombre:"FIAS No. 10 - SEGUIMIENTO AL COMPORTAMIENTO DEL VIH - ESTRATEGIA 90-90-90" ,id:'F10', permiso:'descargar.F10'},
                {nombre:"FIAS No. 11 - SEGUIMIENTO AL FONDO DE DISTRIBUCIÓN DE RIESGO" ,id:'F11', permiso:'descargar.F11'},
                {nombre:"FIAS No. 12 - ACTIVIDADES DE PROTECCIÓN ESPECIFICA Y DETECCIÓN TEMPRANA R3280" ,id:'F12', permiso:'descargar.F12'},
                {nombre:"FIAS No. 12A - INFORME CASOS MENSUALES CENTINELA" ,id:'F12A', permiso:'descargar.F12A'},
                {nombre:"FIAS No. 12B - INFORME DE CASOS DE NOTIFICCION OBLIGATORIA" ,id:'F12B', permiso:'descargar.F12B'},
                {nombre:"FIAS No. 13 - CONSOLIDADO DE ACTIVIDADES CON COSTOS Y FRECUENCIAS DE USO" ,id:'F13', permiso:'descargar.F13'},
                {nombre:"FIAS No. 14 - CONSULTAS POR ÁMBITO DE ATENCIÓN" ,id:'F14', permiso:'descargar.F14'},
                {nombre:"FIAS No. 15 - PORTABILIDAD" ,id:'F15', permiso:'descargar.F15'},
                {nombre:"FIAS No. 16 SEGUIMIENTO A USUARIOS A TRASPLANTAR Y TRASPLANTADOS" ,id:'F16', permiso:'descargar.F16'},
                {nombre:"FIAS No. 17 REPORTE DE ASIGNACIÓN DE CITAS DE PRIMERA VEZ" ,id:'F17', permiso:'descargar.F17'},
                {nombre:"FIAS No. 18 REPORTE DE ASIGNACIÓN DE CITAS ESPECIALIZADAS" ,id:'F18', permiso:'descargar.F18'},
                {nombre:"FIAS No. 19 REPORTE DE MEDICAMENTOS" ,id:'F19', permiso:'descargar.F19'},
                {nombre:"FIAS No. 20 REPORTE DE AYUDAS DIAGNOSTICAS" ,id:'F20', permiso:'descargar.F20'},
                {nombre:"FIAS No. 21 REPORTE DE PROCEDIMIENTOS QUIRURGICOS" ,id:'F21', permiso:'descargar.F21'},
                {nombre:"FIAS No. 22 REPORTE DE PROCEDIMIENTOS QUIRURGICOS" ,id:'F22', permiso:'descargar.F22'},
            ],
            meses:[
                {id: '01', nombre: 'Enero'},
                {id: '02', nombre: 'Febrero'},
                {id: '03', nombre: 'Marzo'},
                {id: '04', nombre: 'Abril'},
                {id: '05', nombre: 'Mayo'},
                {id: '06', nombre: 'Junio'},
                {id: '07', nombre: 'Julio'},
                {id: '08', nombre: 'Agosto'},
                {id: '09', nombre: 'Septiembre'},
                {id: '10', nombre: 'Octubre'},
                {id: '11', nombre: 'Noviembre'},
                {id: '12', nombre: 'Diciembre'},
                ],
            form:{
                tipo_fias: null,
                departamento_id: null,
                mes: null,
                anio: null,
            },
            loading: false,
        }
    },

    methods: {
        /** Descarga un archivo fias */
        async descargar(){
            try{
                this.loading = true;
                const { data } = await axios.post('/api/fias/descargar', this.form);
                console.log(data);
                this.loading = false;
                this.$alerSuccess('El fias se esta generando, sera enviado a el correo.');
                this.limpiar();
            }catch(error){
                console.log(error.response);
                this.loading = false
                this.$alerError('Error al generar el fias.')
            }
        },

        /** Limpia el formulario */
        limpiar(){
            this.form.tipo_fias = null
            this.form.departamento_id = null
            this.form.mes = null
            this.form.anio = null
        },

        /** Valida que los tipo_fias y el departamento_id esten seleccionados */
        hayError(){
            if(!this.form.tipo_fias){
                this.$alerError('Todos los campos son necesarios.')
                return true;
            }
            return false;
        },
    }
}

</script>

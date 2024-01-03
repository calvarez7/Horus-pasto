<template>
    <v-container fluid>
        <v-layout column>
            <v-card>
                <v-card-title v-if="!loading">
                    <h1>Descargar Rips</h1>
                </v-card-title>
                <v-card-text class="text-xs-center" v-if="loading">
                    <v-progress-circular
                        :size="70"
                        :width="7"
                        color="primary"
                        indeterminate
                    ></v-progress-circular>
                    <v-card-text class="text-xs-center">
                        {{informacionDeDescarga}}
                    </v-card-text>
                </v-card-text>
                <v-card-text v-else>
                    <form @submit.prevent="descargar">
                        <v-layout row wrap>
                            <v-flex xs12 md6>
                                <v-autocomplete
                                    v-model="form.tipo_rips"
                                    label="Tipo de Rips"
                                    :items="tipos_rips"
                                    solo
                                    required
                                >
                                </v-autocomplete>
                            </v-flex>
                            
                            <v-flex xs12 md6>
                                <v-select
                                    v-model="form.entidad"
                                    label="Entidad"
                                    :items="entidades"
                                    :item-text="'nombre'"
                                    :item-value="'id'"
                                    solo
                                    required
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 transition="slide-y-reverse-transition" v-if="!conglomerado">
                                <v-autocomplete
                                    v-model="form.sede"
                                    :items="sedes"
                                    item-text="Nombre"
                                    item-value="Codigo"
                                    placeholder="Sede"
                                    solo
                                >    
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs12>
                                <v-checkbox
                                    class="mt-0"
                                    v-model="conglomerado"
                                    label="conglomerado"
                                ></v-checkbox>
                            </v-flex>
                            <v-flex xs12 md6>
                                <v-text-field
                                    solo
                                    v-model="form.fecha_inicial"
                                    type="date"
                                    label="Fecha inicial"
                                    placeholder="Seleccione una fecha"
                                    required
                                ></v-text-field>
                            </v-flex>
                            
                            <v-flex xs12 md6>
                                <v-text-field
                                    solo
                                    v-model="form.fecha_final"
                                    type="date"
                                    label="Fecha Final"
                                    placeholder="Seleccione una fecha"
                                    required
                                ></v-text-field>
                            </v-flex>
                        </v-layout>

                        <v-card-actions>
                            <v-btn type="submit" color="pink lighten-3" dark>Descargar</v-btn>
                            <v-btn type="button" @click="limpiar()">Cancelar</v-btn>
                        </v-card-actions>

                    </form>
                </v-card-text>
            </v-card>
        </v-layout>
    </v-container>
</template>
<script>
import axios from 'axios'
import { descargarFile } from '../../../api/descargas' 
export default {
    name: 'RipsDescargaPage',
    data(){
        return {
            sedes: [],
            entidades: [
                {id: 1, nombre: 'REDVITAL UT'},
                {id: 3, nombre: 'FONDO DE PASIVO SOCIAL DE FERROCARRILES NACIONALES DE COLOMBIA'},
                {id: 7, nombre: 'UNIVERSIDAD DE ANTIOQUIA'}
            ],
            tipos_rips: ['AC', 'AP', 'AM', 'AT', 'US', 'AF', 'CT', 'AC-COVID', 'AP-COVID', 'AM-COVID', 'AT-COVID', 'US-COVID', 'AF-COVID', 'CT-COVID'],
            form:{
                sede: null,
                tipo_rips: null,
                entidad: null,
                fecha_inicial: null,
                fecha_final: null,
            },
            informacion:{
                total_registros: 0,
                registro_actual: 0,
            },
            conglomerado: false,
            loading: false,
        }
    },

    mounted(){
        this.getSedes();
    },

    computed: {
        informacionDeDescarga(){
            if(this.informacion.total_registros){
                return this.informacion.registro_actual + ' de ' + this.informacion.total_registros
            }else{
                return 'Descargando Información ...'
            }
        }
    },

    methods: {

        /**Obtener las sedes */
        async getSedes(){
            try{
                const proveedor_id = 67; // Sumimedical
                const response = await axios.get('/api/sedeproveedore/listar?proveedor_id=' + proveedor_id)
                this.sedes = response.data;
            }catch(error){
                this.$alerError(error.response)
            }
        },

        /** Descarga un archivo plano con los filtros seleccionados */
        async descargar(){
            try {
                /** Validamos antes de enviar la peticion */
                if(this.hayError()) return false;
                /** preguntamos el estado de conglomerado, para mantener la sede, o asignarle un 0 */
                if(this.conglomerado){
                    this.form.sede = 0
                }
                /** Iniciamos el loading */
                this.loading = true
                const response = await axios.post('/api/rips/descargar', this.form)
                const cadena = this.generarCadena(response.data)
                descargarFile(cadena, this.generarNombre())
                this.limpiar()
                this.loading = false
                this.$alerSuccess('El archivo se esta descargando');
            }catch(error) {
                this.loading = false
                this.$alerError('Error interno')
                console.log(error.response)
            }
        },

        /** Valida que los tipo_rips y la entidad esten seleccionadas */
        hayError(){
            if(!this.form.tipo_rips || !this.form.entidad){
                this.$alerError('Todos los campos son necesarios.')
                return true;
            }
            return false;
        },
        
        /** Limpia el formulario */
        limpiar(){
            this.form.sede = null
            this.form.tipo_rips = null
            this.form.entidad = null
            this.form.fecha_inicial = null
            this.form.fecha_final = null
            this.conglomerado = false
        },

        /**  Genera el nombre para el archivo */
        generarNombre(){
            const fecha = new Date(this.form.fecha_final);
            const anioActual = fecha.getFullYear().toString();
            let mesActual = (fecha.getMonth() + 1).toString();

            if(mesActual.length == 1){
                mesActual = '0' + mesActual;
            }

            return this.form.tipo_rips + anioActual + mesActual + '.TXT';
        },

        /** Genera una cadena para */
        generarCadena(data){
            this.informacion.total_registros = data.length
            let cadena = '';
            data.forEach((item, index) => {
                cadena += Object.values(item).join() + "\n";
                this.informacion.registro_actual = index + 1;
            }, this)
            this.informacion.total_registros = 0
            this.informacion.registro_actual = 0
            return cadena
        }
    }
}
</script>
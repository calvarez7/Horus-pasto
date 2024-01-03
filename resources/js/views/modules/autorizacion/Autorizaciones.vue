<template>
  <v-card>
    <v-container pt-3 pb-1>
      <v-layout row>
        <v-dialog v-model="dialog" max-width="700px">
          <v-card>
            <v-card-title>
              <span class="headline">Acciones</span>
              
            </v-card-title>
            <v-card-text>
              <div class="text-center w-100">
                <v-btn
                    color="green"
                    class="ma-2 white--text"
                    @click="guardarAccion('Aprobado')">
                  Aprobado
                  <v-icon right dark>done</v-icon>
                </v-btn>
                <v-btn
                    color="yellow darken-4"
                    class="ma-2 white--text"
                    @click="guardarAccion('Inadecuado')">
                  Inadecuado
                  <v-icon right dark>report</v-icon>
                </v-btn>
                <v-btn
                    color="red"
                    class="ma-2 white--text"
                    @click="guardarAccion('Negado')">
                  Negado
                  <v-icon right dark>clear</v-icon>
                </v-btn>
                <v-btn
                    color="black"
                    class="ma-2 white--text"
                    @click="guardarAccion('Anulado')">
                  Anulado
                  <v-icon right dark>remove_circle</v-icon>
                </v-btn>
              </div>
              <v-container v-if="mostrarObservacion">
                <v-textarea
                    name="input-7-1"
                    label="Observacion"
                    value=""
                    v-model="observaciones"
                ></v-textarea>
                <v-btn
                    color="blue"
                    class="ma-2 white--text"
                    @click="enviarAccion(estadoElegido)">
                  Enviar
                  <v-icon right dark>send</v-icon>
                </v-btn>
              </v-container>

            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click="cerrarModal()">Cerrar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout>
    </v-container>
    <v-container>
      <v-layout row wrap>
        <v-flex xs12>
          <v-select
              :items="listaAutorizacionesShow"
              label="Estado a buscar"
              :value="4"
              @change="filterEstado"
          ></v-select>
        </v-flex>
      </v-layout>
    </v-container>
    <v-data-table
        :headers="headers"
        :items="listaAutorizaciones"
    >

      <template v-slot:items="props">
        <td>{{ props.item.id}}</td>
        <td class="text-xs-center">
          <v-btn
            color="blue"
            class="ma-2 white--text"
            @click="abrirModal( props.item.id)">
            Acciones
            <v-icon right dark>send</v-icon>
          </v-btn>
        </td>
        <td class="text-xs-center">{{ props.item.Departamento}}</td>
        <td class="text-xs-center">{{ props.item.Municipio}}</td>
        <td class="text-xs-center">{{ props.item.FechaSolicitud}}</td>
        <td class="text-xs-center">{{ props.item.FechaAutorizacion}}</td>
        <td class="text-xs-center">{{ contadorDias(props.item.FechaSolicitud)}}</td>
        <td class="text-xs-center">{{ props.item.Nombre}}</td>
        <td class="text-xs-center">{{ props.item.Cedula}}</td>
        <td class="text-xs-center">{{ props.item.IpsAtencion}}</td>
        <td class="text-xs-center">{{ props.item.IpsAfiliacion}}</td>
        <td class="text-xs-center">{{ props.item.Cup}}</td>
        <td class="text-xs-center">{{ props.item.DescripcionCup}}</td>
        <td class="text-xs-center">{{ props.item.FuncionarioCargaServicio}}</td>
        <td class="text-xs-center">{{ props.item.FuncionarioSolicitaServicio}}</td>
        <td class="text-xs-center">{{ props.item.EstadoServicio}}</td>
        <td class="text-xs-center">{{ props.item.IpsAutorizada}}</td>
        <td class="text-xs-center">{{ props.item.FechaAnestesia}}</td>
        <td class="text-xs-center">{{ props.item.FechaConsultaPreQuirurjica}}</td>
        <td class="text-xs-center">{{ props.item.FechaCirujia}}</td>
        <td class="text-xs-center">{{ props.item.FechaQuimio}}</td>
        <td class="text-xs-center">{{ props.item.FechaRealizacionAyudas}}</td>
      </template>
      <template v-slot:no-data>
        <v-alert :value="true" color="error" icon="warning">
          No hay movimientos en este Estado.
        </v-alert>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>

    import {Transaction} from '../../../models/Transaction'
    import {Movimiento} from '../../../models/Movimiento'

    export default {
        name: 'BodegaMovimientos',
        data() {
            return {

                listaAutorizaciones: [],
                listaAutorizacionesShow: [],
                headers: [
                    {text: 'Id', sortable: false, value: 'id'},
                    {text: 'Acciones', sortable: false, align: 'center',value: ''},
                    {text: 'Departamento', sortable: false, align: 'center', value: ''},
                    {text: 'Municipio', sortable: false, align: 'center', value: 'Municipio'},
                    {text: 'FechaSolicitud', sortable: false, align: 'center', value: ''},
                    {text: 'FechaAutorizacion', sortable: false, align: 'center', value: ''},
                    {text: 'Contador', sortable: false, align: 'center', value: ''},
                    {text: 'Nombre', sortable: false, align: 'center', value: ''},
                    {text: 'Cedula', sortable: false, align: 'center', value: ''},
                    {text: 'IpsAtencion', sortable: false, align: 'center', value: ''},
                    {text: 'IpsAfiliacion', sortable: false, align: 'center', value: ''},
                    {text: 'Cup', sortable: false, align: 'center', value: ''},
                    {text: 'DescripcionCup', sortable: false, align: 'center', value: ''},
                    {text: 'FuncionarioCargaServicio', sortable: false, align: 'center', value: ''},
                    {text: 'FuncionarioSolicitaServicio', sortable: false, align: 'center', value: ''},
                    {text: 'EstadoServicio', sortable: false, align: 'center', value: ''},
                    {text: 'IpsAutorizada', sortable: false, align: 'center', value: ''},
                    {text: 'FechaAnestesia', sortable: false, align: 'center', value: ''},
                    {text: 'FechaConsultaPreQuirurjica', sortable: false, align: 'center', value: ''},
                    {text: 'FechaCirujia', sortable: false, align: 'center', value: ''},
                    {text: 'FechaQuimio', sortable: false, align: 'center', value: ''},
                    {text: 'FechaRealizacionAyudas', sortable: false, align: 'center', value: ''},
                ],
                estadosMovimiento: Movimiento.estados,
                dialog: false,
                observaciones: '',
                idAutorizacion: 0,
                mostrarObservacion: false,
                estadosMovimientoFormat: [],

            }
        },
        mounted() {
            this.fetchAutorizaciones()
        },
        computed: {},
        watch: {},
        methods: {
            filterEstado(value){
                // console.log(value);
                this.listaAutorizacionesShow = this.listaAutorizaciones.filter(mov => {
                    // console.log(value == mov.Estado_id)
                    return value == mov.Estado_id;
                })
            },
            formatEstados() {
                // console.log(this.estadosMovimientoFormat)
                this.estadosMovimiento.forEach((element) => {
                    this.estadosMovimientoFormat.push({text: element['name'], value: element['id']})
                });

            },
            fetchAutorizaciones() {
                // let dataFake = {id: 1, Departamento: 'Antioquia'}
                // this.listaAutorizaciones.push(dataFake);
                // this.listaAutorizacionesShow = this.listaAutorizaciones

                // se llama el servicio que trae las autorizaciones
                axios.get('/api/autorizacion/all')
                .then(res => this.listaAutorizaciones = res.data)
            },
            contadorDias(fecha) {

            },
            enviarAccion(nameAccion) {

                if (nameAccion != 'Aprobado' &&  this.observaciones == '') {
                    swal({
                        title: 'Debe llenar la Observacion',
                        icon: 'warning',
                    })
                    return
                }

                const msg = 'Esta seguro de cambiar el estado de esta Autorizacion a '+ nameAccion;
                swal({
                    title: msg,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    let dataSend = {
                        observaciones: this.observaciones,
                        idAutorizacion: this.idAutorizacion
                    }
                    if(confirm){
                        this.mostrarObservacion = false;
                        /*axios.put('/api/movimiento/'+idMovimiento+'/cancelar', dataSend)
                            .then(res => {
                                this.fetchAutorizaciones()
                            })*/
                        this.cerrarModal()
                    }

                });

            },
            guardarAccion(nameAccion) {

                if (nameAccion != 'Aprobado') {
                    this.mostrarObservacion = true;
                    this.estadoElegido = nameAccion;
                } else {
                    this.mostrarObservacion = false;

                    this.enviarAccion(nameAccion)
                }
            },

            abrirModal(id) {
                this.idAutorizacion = id;
                this.dialog = true;
            },
            cerrarModal() {
                this.idAutorizacion = 0;
                this.dialog = false;
                this.mostrarObservacion = false;
                this.estadoElegido = '';
                this.observaciones = '';
            }


        }
    }
</script>

<style lang="scss" scoped>

</style>

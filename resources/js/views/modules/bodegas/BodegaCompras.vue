<template>
  <v-card>
    <v-container pa-1>
      <v-container pt-3 pb-1>
      </v-container>
      <v-card-title>
        <v-flex xs12>
          <v-layout row wrap>
            <v-flex xs5>
              <v-select
                :items="bodegas"
                item-text="Nombre"
                item-value="id"
                v-model="bodega_id"
                label="Bodega"
                @change="fetchRequest()"
              ></v-select>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs5>
              <v-select
                :items="sedesproveedores"
                item-text="Nombre"
                item-value="id"
                v-model="data.sedeselected"
                label="Proveedor"
              ></v-select>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex xs12>
          <v-layout row wrap>
            <v-btn 
              color="primary" 
              outline 
              round 
              v-if="data.articuloSelected.length > 0"
              @click="requestAcepted()"
              >Aceptar Solicitud</v-btn>
            <v-spacer></v-spacer>
            <v-flex 
             sm5
             xs12
             >
                <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Buscar..."
                    single-line
                    hide-details
                ></v-text-field>
            </v-flex> 
          </v-layout>
        </v-flex> 
      </v-card-title>
      <v-data-table
          :headers="headers"
          :items="solicitudes"
          :search="search"
          v-model="data.articuloSelected"
          select-all
          >
          <template v-slot:headers="props">
            <tr>
              <th>
                <v-checkbox
                  color="primary"
                  :input-value="props.all"
                  :indeterminate="props.indeterminate"
                  primary
                  hide-details
                  @click.stop="toggleAll"
                ></v-checkbox>
              </th>
              <th
                v-for="header in props.headers"
                :key="header.text"
                :class="`text-xs-${header.align}`"
              >
                {{ header.text }}
              </th>
            </tr>
          </template>
          <template v-slot:items="props">
              <td>
                <v-checkbox
                  color="primary"
                  v-model="props.selected"
                  primary
                  hide-details
                ></v-checkbox>
              </td>
              <td class="text-xs-right">{{ props.item.CUM_completo }}</td>
              <td class="text-xs-right">{{ props.item.Nombre }}</td>
              <td class="text-xs-right">{{ props.item.Titular }}</td>
              <td class="text-xs-center">
                <v-edit-dialog
                    :return-value.sync="props.item.Cantidad"
                    large
                    lazy
                    persistent
                    cancel-text="Cancelar"
                    save-text="Cambiar"
                  >
                    <div>{{ props.item.Cantidad }}</div>
                    <template v-slot:input>
                    <div class="mt-3 title">Update Valor</div>
                    </template>
                    <template v-slot:input>
                    <v-text-field
                        v-model="props.item.Cantidad"
                        label="Edit"
                        single-line
                        counter
                        autofocus
                    ></v-text-field>
                    </template>
                </v-edit-dialog>
            </td>
          </template>
          <v-alert v-slot:no-results :value="true" color="error" icon="warning">
              Your search for "{{ search }}" found no results.
          </v-alert>
      </v-data-table>
    </v-container>
  </v-card> 
</template>

<script>
export default {
    name: 'BodegaCompras',
    data: () => {
      return {
        headers: [
          {
              text: 'CUM',
              align: 'center',
              sortable: false,
              value: 'CUM_completo'
          },
          {
              text: 'Nombre',
              align: 'right',
              sortable: false,
              value: 'Nombre'
          },
          {
              text: 'Titular',
              align: 'right',
              sortable: false,
              value: 'Titular'
          },
          {
              text: 'Cantidad',
              align: 'center',
              sortable: false,
              value: 'Cantidad'
          },
        ],
        data: {
          articuloSelected: [],
          sedeselected: null,
        },
        solicitudes:[],
        search: '',
        bodegas: [],
        bodega_id: null,
        sedesproveedores: [],
        
      }
    },
    mounted(){
      this.fetchBodegas();
      this.fetchProveedores();
    },
    methods:{
      fetchProveedores(){
        axios.get('/api/sedeproveedore/allproveedores')
          .then(res => this.sedesproveedores = res.data)
      },
      fetchBodegas(){
        axios.get('/api/bodega/all')
            .then(res => this.bodegas = res.data)
      },
      fetchRequest(){
        axios.post(`/api/bodega/${this.bodega_id}/ordencompra/allOrdens`)
            .then(res => this.solicitudes = res.data)
      },
      // fetchSedePrestadores(){
      //   axios.post('/api/sedeproveedore/all',{ Prestador_id: this.Prestador_id })
      //     .then((res) => {
      //         this.sedesprestadores = res.data
      //     })
      //     .catch((err) => console.log(err));
      // },
      requestAcepted(){
        axios.put(`/api/bodega/${this.bodega_id}/ordencompra/acceptRequest`, this.data)
            .then((res) => {
                swal({
                    title: res.data.message,
                    text: " ",
                    timer: 2000,
                    icon: "success",
                    buttons: false
                });
                this.fetchRequest();
            })
            .catch((err) => console.log(err));
      },
      toggleAll () {
        if (this.data.articuloSelected.length) this.data.articuloSelected = []
        else this.data.articuloSelected = this.solicitudes.slice()
      },
    }
}
</script>

<style>

</style>
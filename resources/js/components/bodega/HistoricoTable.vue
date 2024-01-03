<template>
    <v-card>
        <historico-show-dialog  :detail="detail"
                                :showDetailDialog="showDetailDialog"
                                @closeDialog="showDetailDialog=false">
        </historico-show-dialog>
        <v-container>
            <v-layout>
                <v-flex xs6>
                    <v-text-field
                        v-model="search"
                        append-icon="search"
                        label="Buscar"
                        single-line
                        hide-details
                    ></v-text-field>
                </v-flex>
            </v-layout>
        </v-container>
        <v-data-table
            class="elevation-0"
            :headers="headers"
            :items="movimientos"
            :loading="loading"
            :search="search">
            <template v-slot:no-data>
                <span>No hay movimientos realizados</span>
            </template>
            <template v-slot:items="props">
                <td class="text-xs-center">{{ props.index+1 }}</td>
                <td class="text-xs-center">{{ props.item.Num_Doc }}</td>
                <td class="text-xs-center">{{ props.item.paciente }}</td>
                <td class="text-xs-center">{{ props.item.name }} {{ props.item.apellido }}</td>
                <td class="text-xs-center">{{ props.item.Orden_id }}</td>
                <td class="text-xs-center">
                    <v-btn  color="warning"
                            icon
                            @click="showDetail(props.item)">
                        <v-icon>library_books</v-icon>
                    </v-btn>
                </td>
            </template>

        </v-data-table>

    </v-card>
</template>

<script>
    import HistoricoShowDialog from '../../components/bodega/HistoricoShowDialog.vue'
    import moment from 'moment';
    moment.locale('es');

    export default {
        name:'ReferenciaTable',
        components: {
            HistoricoShowDialog
        },
        props:{
            loading: Boolean,
            movimientos: Array,
            page: Object,
            bodega: Number,
            startDate: String,
            finishDate: String,
            usuario: Number
        },
        data: () => {
            return {
                detail: [],
                search:'',
                headers: [
                    {
                        text: '#',
                        value: '',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Identificacón Paciente',
                        value: 'Num_Doc',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Nombre Paciente',
                        value: 'paciente',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Realizado por',
                        value: 'name',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Número de orden',
                        value: 'Orden_id',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Detalle',
                        value: 'string',
                        align: 'center',
                        sortable: false
                    }
                ],
                showDetailDialog: false
            }
        },
        filters:{
            date: (date) => {
                return moment(date).format('lll')
            }
        },
        methods:{
            next(e){
                console.log('e', e);

                this.$emit('getDispensadoPage', e);
            },
            showDetail(detail){
                axios.post(`/api/bodega/${this.bodega}/historico/dispensado/detalle`,{
                    startDate: this.startDate,
                    finishDate: this.finishDate,
                    usuario: this.usuario,
                    orden:detail.Orden_id
                }).then(res => {
                    this.detail = res.data;
                    this.showDetailDialog = true;
                })
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>

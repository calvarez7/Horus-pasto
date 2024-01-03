<template>
    <v-layout v-show="showDetailDialog" row justify-center>
        <v-dialog v-model="showDetailDialog" persistent max-width="1200px">
            <v-card>
                <v-card-text>
                    <v-data-table
                        :headers="headers"
                        :items="detail"
                        hide-actions>
                        <template v-slot:no-data>
                            <span>No hay movimientos realizados</span>
                        </template>
                        <template v-slot:items="props">
                            <td class="text-xs-center">{{ props.index+1 }}</td>
                            <td class="text-xs-center">{{ props.item.id }}</td>
                            <td class="text-xs-center">{{ props.item.created_at | date }}</td>
                            <td class="text-xs-center">{{ props.item.Cum_Validacion }}</td>
                            <td class="text-xs-center">{{ props.item.Producto }}</td>
                            <td class="text-xs-center">{{ props.item.Numlote }}</td>
                            <td class="text-xs-center">{{ props.item.Cantidadtotal }}</td>
                            <td class="text-xs-center">{{ props.item.CantidadtotalFactura }}</td>
                        </template>
                    </v-data-table>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click="closeDialog()">Cerrar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    import { mapGetters } from 'vuex';
    import moment from 'moment';
    moment.locale('es');

    export default {
        name: 'HistoricoShowDialog',
        props: {
            showDetailDialog: Boolean,
            detail: Array,
        },
        filters:{
            date: (date) => {
                return moment(date).format('lll')
            }
        },
        data: () => {
            return {
                headers: [
                    {
                        text: '#',
                        value: '',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'id',
                        value: 'id',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Fecha Realización',
                        value: 'created_at',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'CUM',
                        value: 'Cum_Validacion',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Producto',
                        value: 'Producto',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Lote',
                        value: 'Numlote',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Cantidad disponible antes de dispensar',
                        value: 'Cantidadtotal',
                        align: 'center',
                        sortable: false
                    },
                    {
                        text: 'Cantidad dispensada',
                        value: 'string',
                        align: 'center',
                        sortable: false
                    },
                ],
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        methods:{
            closeDialog(){
                this.$emit('closeDialog')
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>

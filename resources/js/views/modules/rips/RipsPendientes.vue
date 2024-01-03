<template>
    <div>
        <v-dialog
            v-model="isActiveAfDialog"
            persistent
            transition="dialog-transition">
            <v-card>
                <v-card-title primary-title>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <span class="display-1 font-weight-bold">{{ rep.razonSocial }}</span>
                        </v-flex>
                        <v-flex xs12>
                            <span>
                                <em>Cod. Hab. {{ rep.code }} - {{ rep.documentType }} {{ rep.documentNumber }}</em>
                            </span>
                        </v-flex>
                        <v-flex xs12>
                            <v-layout row wrap justify-center>
                                <span class="title font-weight-medium">Archivo 'AF{{ paqCode }}'</span>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-container grid-list-xs fluid>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-data-table
                                    :headers="afHeader"
                                    :items="afDialog"
                                    hide-actions
                                    item-key="id"
                                >
                                    <template v-slot:items="props">
                                        <td class="text-xs-center">{{ props.item.billNumber }}</td>
                                        <td class="text-xs-center">{{ props.item.billDate }}</td>
                                        <td class="text-xs-center">{{ props.item.startDate }}</td>
                                        <td class="text-xs-center">{{ props.item.finishDate }}</td>
                                        <td class="text-xs-center">{{ props.item.netoValue | pesoFormat }}</td>
                                    </template>
                                </v-data-table>
                            </v-flex>
                            <v-flex xs12 mt-3>
                                <v-card>
                                    <v-container grid-list-xs>
                                        <v-layout row wrap>
                                            <v-flex xs4>
                                                <span class="title">Número de facturas: {{ afDialog.length }}</span>
                                            </v-flex>
                                            <VSpacer />
                                            <v-flex xs4>
                                                <span class="title">Valor de radicación: {{ totalValor | pesoFormat }}</span>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-container>
                    <v-card-actions>
                        <v-btn
                            flat
                            @click="isActiveAfDialog = !isActiveAfDialog">
                            Cerrar
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="error"
                            flat
                            v-if="can('rips.pendientes.rechazar')"
                            @click="setRejectMotivo()">
                            Rechazar
                        </v-btn>
                        <v-btn
                            color="success"
                            flat
                            v-if="can('rips.pendientes.guardar')"
                            @click="acceptPendingRips()">
                            Guardar facturas
                        </v-btn>
                    </v-card-actions>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog
            v-model="isLoadingRips"
            persistent
            width="300">
            <v-card
                color="primary"
                dark>
                <v-card-text>
                    {{ stateLoad }}, espere por favor.
                    <VProgressLinear
                        indeterminate
                        color="white"
                        class="mb-0" />
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog
            v-model="isActiveMotivoDialog"
            persistent
            max-width="500px"
            transition="dialog-transition">
            <v-card>
                <v-layout row wrap>
                    <v-flex xs12 ma-4>
                        <v-textarea
                            autofocus
                            name="rejectMotivo"
                            label="Motivo de rechazo"
                            v-model="motivo">
                        </v-textarea>
                    </v-flex>
                </v-layout>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        outline
                        small
                        @click="isActiveMotivoDialog = !isActiveMotivoDialog">
                        Cancelar
                    </v-btn>
                    <v-btn
                        color="warning"
                        outline
                        small
                        @click="rejectPendingRips()">
                        Rechazar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-card>
            <v-container grid-list-xs fluid>
                <v-flex xs12>
                    <v-tabs centered>
                        <v-tabs-slider color="primary"></v-tabs-slider>
                        <v-tab href="#tab-1">
                            Pendientes
                        </v-tab>
                        <v-tab href="#tab-2" @click="getAuditPaqs()">
                            Revisados
                        </v-tab>
                        <v-tab-item value="tab-1">
                            <v-card flat>
                                <v-data-table
                                    :loading="isLoadingTable"
                                    :headers="pendingHeaders"
                                    :items="pendingItems"
                                    hide-actions
                                    item-key="id">
                                    <template v-slot:items="props">
                                        <td c>{{ props.item.id }}</td>
                                        <td class="text-xs-center">{{ props.item.creator_user.name }} {{ props.item.creator_user.apellido }}</td>
                                        <td class="text-xs-center">{{props.item.af_temps[0].entityCode}}</td>
                                        <td class="text-xs-center">{{ props.item.namePaq }}</td>
                                        <td class="text-xs-center">{{ props.item.totalBills }}</td>
                                        <td class="text-xs-center">{{ props.item.totalValueAf | pesoFormat }}</td>
                                        <td class="text-xs-center">{{ props.item.created_at }}</td>

                                        <td class="text-xs-center">
                                            <v-btn color="primary" icon small @click="showAfDialog(props.item)">AF</v-btn>
                                        </td>
                                    </template>
                                </v-data-table>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item value="tab-2">
                            <v-card flat>
                                <v-data-table
                                    :loading="isLoadingTable"
                                    :headers="auditHeaders"
                                    :items="auditItems"
                                    hide-actions
                                    item-key="id">
                                    <template v-slot:items="props">
                                        <td class="text-xs-center">{{ props.item.id }}</td>
                                        <td class="text-xs-center">{{ props.item.creator_user.name }} {{ props.item.creator_user.apellido }}</td>
                                        <td class="text-xs-center"></td>
                                        <td class="text-xs-center">{{ props.item.namePaq }}</td>
                                        <td class="text-xs-center">{{ props.item.totalBills }}</td>
                                        <td class="text-xs-center">{{ props.item.totalValueAf | pesoFormat }}</td>
                                        <td class="text-xs-center">{{ props.item.estado.Nombre }}</td>
                                        <td class="text-xs-center">{{ props.item.reason}}</td>
                                        <td class="text-xs-center">{{ props.item.created_at }}</td>
                                    </template>
                                </v-data-table>
                            </v-card>
                        </v-tab-item>
                    </v-tabs>
                </v-flex>
            </v-container>
        </v-card>
        <v-snackbar
            v-model="isActiveSnackbar"
            color="success"
            :timeout="timeoutSnackbar"
            >
            {{ textSnackbar }}
            <v-btn
                dark
                flat
                @click="isActiveSnackbar = false"
            >
                Cerrar
            </v-btn>
        </v-snackbar>
    </div>
</template>

<script>
    import {
        mapGetters
    } from "vuex";
export default {
    name: "RipsPendientes",
    data: () => {
        return {
            afDialog: [],
            afHeader: [
                {
                    text: "Número de factura",
                    align: "center",
                    value: "billNumber",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: 'Fecha Expedición',
                    align: 'center',
                    value: 'id',
                    sortable: false
                },
                {
                    text: 'Fecha Inicio',
                    align: 'center',
                    value: 'id',
                    sortable: false
                },
                {
                    text: 'Fecha Final',
                    align: 'center',
                    value: 'id',
                    sortable: false
                },
                {
                    text: "Valor neto",
                    align: "center",
                    value: "netoValue",
                    width: "5px",
                    sortable: false,
                    fixed: true
                }
            ],
            auditHeaders: [
                {
                    text: "#",
                    align: "center",
                    value: "id",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Usuario que envía",
                    align: "center",
                    value: "userName",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Código entidad",
                    align: "center",
                    value: "",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Código paquete",
                    align: "center",
                    value: "codigo",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Cantidad total facturas",
                    align: "center",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Valor total",
                    align: "center",
                    value: "",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Estado",
                    align: "center",
                    value: "estado.Name",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Motivo",
                    align: "center",
                    value: "motivo",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Fecha Carga",
                    align: "center",
                    value: "",
                    width: "5px",
                    sortable: false,
                    fixed: true
                }
            ],
            auditItems: [],
            currentPaq: {},
            isActiveSnackbar: false,
            isLoadingRips: false,
            isLoadingTable: false,
            isActiveAfDialog: false,
            isActiveMotivoDialog: false,
            motivo: '',
            paqCode: '',
            pendingHeaders: [
                {
                    text: "#",
                    align: "center",
                    value: "id",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Usuario que envía",
                    align: "center",
                    value: "userName",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Código paquete",
                    align: "center",
                    value: "codigo",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Número de facturas",
                    align: "center",
                    value: "creator_user.name",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Valor total",
                    align: "center",
                    value: "",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "Fecha Carga",
                    align: "center",
                    value: "",
                    width: "5px",
                    sortable: false,
                    fixed: true
                },
                {
                    text: "",
                    align: "center",
                    value: "",
                    width: "5px",
                    sortable: false,
                    fixed: true
                }
            ],
            pendingItems: [],
            rep: {},
            stateLoad: '',
            textSnackbar: '',
            timeoutSnackbar: 5000,
            totalValor: 0,
        }
    },
    filters: {
        pesoFormat: (valor) => `$${new Intl.NumberFormat().format(valor) || 0}`
    },
    computed:{
        ...mapGetters(['can']),
    },
    mounted(){
        this.getPendingPaqs()
    },
    methods:{
        clearAndCloseAfDialog(){
            this.isActiveAfDialog = false
            this.currentPaq = {}
            this.afDialog = []
            this.paqCode = ''
            this.totalValor = 0
        },
        clearAndCloseMotivoDialog(){
            this.motivo = ''
            this.isActiveMotivoDialog = false
        },
        getPendingPaqs: async function () {
            try {
                this.isLoadingTable = true;
                let { data } = await axios.get('/api/rips/getPendingPaqs')
                this.isLoadingTable = false;
                this.pendingItems = data.paqTemps;
            } catch (error) {
                console.error(error)
            }
        },
        getAuditPaqs: async function () {
            try {
                this.isLoadingTable = true
                let { data } = await axios.get('/api/rips/getAuditPaqs')
                this.isLoadingTable = false
                this.auditItems = data.paqTemps
            } catch (error) {
                console.error(error)
            }
        },
        acceptPendingRips: async function () {
            try {
                let isSure = await this.$alertQuestion('¿Está seguro que desea guardar las facturas?')
                if(isSure.value) {
                    this.stateLoad = 'Guardando'
                    this.isLoadingRips = true
                    let { data } = await axios.post('/api/rips/acceptPendingRips',{ paq_id: this.currentPaq.id })
                    this.isLoadingRips = false
                    this.clearAndCloseAfDialog();
                    this.textSnackbar = data.message
                    this.isActiveSnackbar = true
                    this.getPendingPaqs()
                }

            } catch (error) {
                this.isLoadingRips = false
                console.error(error)
            }
        },
        setRejectMotivo() {
            this.isActiveMotivoDialog = true;
        },
        rejectPendingRips: async function () {
            try {
                this.stateLoad = 'Rechanzando'
                this.isLoadingRips = true
                let { data } = await axios.post('/api/rips/rejectPendingRips',{ paq_id: this.currentPaq.id, motivo: this.motivo })
                this.isLoadingRips = false
                this.clearAndCloseMotivoDialog()
                this.clearAndCloseAfDialog()
                this.textSnackbar = data.message
                this.isActiveSnackbar = true
                this.getPendingPaqs()
            } catch (error) {
                console.log(error)
            }
        },
        showAfDialog(paq){
            this.isActiveAfDialog = true
            this.currentPaq = paq
            this.afDialog = paq.af_temps
            this.paqCode = paq.namePaq
            this.totalValor = 0
            this.totalValor = paq.totalValueAf
            this.rep = {
                code: paq.af_temps[0].code,
                razonSocial: paq.repName,
                documentType: paq.af_temps[0].documentType,
                documentNumber: paq.af_temps[0].documentNumber,
            }
        },
    }
};
</script>

<style lang="scss" scoped></style>

<template>
    <v-layout row wrap>
        <v-flex xs6>
            <v-layout row wrap>
                <v-flex xs11>
                    <v-card class="card">
                        <!-- header -->
                        <template>
                            <div class="header-container elevation-4">
                                <div  class="header-title">
                                    <p class="header-title-text white--text"> Bitácora </p>
                                    <p class="header-paticipants-text">
                                        <span>
                                            <strong>Radicado:</strong> {{ referencia.rzeuz }} |
                                            <strong>Anexo:</strong> {{ referencia.tipo_anexo }} |
                                            <strong>Tipo solicitud:</strong> {{ referencia.tipo_solicitud }} 
                                        </span>
                                    </p>
                                </div>
                                <v-menu offset-y>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-if="can('referencia.manage')" icon><v-icon color="secondary" v-on="on">more_vert</v-icon></v-btn>
                                    </template>
                                    <v-list>
                                        <v-list-tile @click="finishBitacora" v-if="referencia.state == 1">
                                            <v-list-tile-title>
                                                Finalizar bitacora
                                            </v-list-tile-title>
                                        </v-list-tile>
                                    </v-list>
                                </v-menu>
                            </div>
                        </template>
                        <!-- container messages -->
                        <v-card-text>
                            <div ref="containerMessageDisplay" class="container-message-display" >
                                <v-layout v-show="preload" justify-center align-center>
                                    <v-progress-circular
                                    indeterminate
                                    color="primary"
                                    ></v-progress-circular>
                                </v-layout>
                                <div class="message-container" :class="(message.User_id == user.id)? 'my-message': 'other-message'" v-for="(message, index) in messages" :key="index">
                                    <div class="message-text" :class="(message.User_id == user.id)? 'black--text': 'white--text othermessage'">
                                        <p class="message-username">{{ message.name }}</p>
                                        <v-btn v-if="message.Archivo" 
                                            color="warning"
                                            :href="message.Archivo"
                                            round
                                            small
                                            target="_blank">
                                            Abrir archivo
                                        </v-btn>
                                        <p v-else >{{ message.message }}</p>
                                    </div>
                                    <div class="message-timestamp" :style="{'justify-content': (message.User_id == user.id)?'flex-end':'baseline'}">
                                            {{ message.created_at | created_at }}
                                    </div>
                                </div>
                            </div>
                        </v-card-text>
                        <v-divider />
                        <v-card-actions v-if="referencia.state == 1 || can('referencia.manage')">
                            <v-flex xs12>
                                <v-text-field
                                    autofocus
                                    :append-outer-icon="message? 'mdi-send' : 'cloud_upload'"
                                    @click:append-outer="send"
                                    id="id"
                                    @keyup.13="sendMessage"
                                    name="name"
                                    placeholder="Escribir un mensaje"
                                    solo
                                    v-model.trim="message">

                                    <template v-slot:append>
                                        <v-fade-transition leave-absolute>
                                            <v-progress-circular
                                            v-if="loading"
                                            size="24"
                                            color="info"
                                            indeterminate
                                            ></v-progress-circular>
                                        </v-fade-transition>
                                    </template>
                                </v-text-field>
                                <input hidden type="file" ref="fileChat" @change="sendFile">
                            </v-flex>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-flex>
        <v-flex xs6>
            <v-layout row wrap>
                <vSpacer />
                <v-flex xs12>
                    <v-flex xs12>
                        <v-card class="card">
                            <v-card-actions class="pa-3">
                                <strong>Prestador: </strong> {{ referencia.prestador }}
                                <v-spacer></v-spacer>
                            </v-card-actions>
                            <v-divider light></v-divider>
                            <v-layout row>
                                <v-flex xs7 >
                                <v-card-title primary-title>
                                    <div>
                                        <div class="headline">{{ referencia.paciente }}</div>
                                        <div class="title">{{ referencia.documento }}</div>
                                        <div>{{ referencia.Tipo_Afiliado }}</div>
                                    </div>
                                </v-card-title>
                                </v-flex>
                                <v-flex xs5 my-3>
                                <v-img
                                    src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png"
                                    height="125px"
                                    contain
                                ></v-img>
                                </v-flex>
                            </v-layout>
                        </v-card>
                    </v-flex>
                </v-flex>
            </v-layout>
        </v-flex>
    </v-layout>
</template>

<script>
    import { mapState, mapGetters } from 'vuex';
    import moment from 'moment'
    moment.locale('es')
    export default {
        name:'BitacoraReferencia',
        data: () => {
            return{
                bitacora: 0,
                loading: false,
                message: '',
                messages: [],
                preload: true,
                referencia: {},
            }
        },
        created(){
            this.$emit('showMenu', false);
            this.bitacora = this.$route.params.bitacora
            Echo.private(`sendMessage${this.bitacora}`)
                .listen('EventSendMessageBitacora', (e) => {
                    this.messages.push({
                        ...e.message,
                        name: e.user.name
                    })
                });
            Echo.private(`endBitacora${this.bitacora}`)
                .listen('EventEndChatBitacora', (e) => {
                    this.referencia.state = e.bitacora.Estado
                });
        },
        mounted() {
            this.scrollToTop();
            this.getReferencia();
            this.getAllMessagesBitacora();
        },
        updated(){
            this.scrollDown();
        },
        computed: {
            ...mapState(['user']),
            ...mapGetters(['can'])
        },
        filters:{
            created_at: (date) => moment(date).format('lll'), 
        },
        methods:{
            addFile(){
               this.$refs.fileChat.click(); 
            },
            getAllMessagesBitacora(){
                axios.get(`/api/referencia/bitacora/getAllMessagesBitacora/${this.bitacora}`)
                    .then(res => {
                        this.messages = res.data.messages;
                        this.preload = false;
                    })
                    .catch(err => console.log('err.response',err.response))
            },
            getReferencia(){
                axios.get(`/api/referencia/getReferenciaByBitacora/${this.bitacora}`)
                    .then(res => {
                        this.referencia = {
                            prestador: res.data.referencia.prestador,
                            paciente: `${res.data.referencia.Primer_Nom? `${res.data.referencia.Primer_Nom}` : '' } ${res.data.referencia.SegundoNom? ` ${res.data.referencia.SegundoNom}` : ''} ${res.data.referencia.Primer_Ape? ` ${res.data.referencia.Primer_Ape}` : ''} ${res.data.referencia.Segundo_Ape? ` ${res.data.referencia.Segundo_Ape}` : ''}`,
                            documento: `${res.data.referencia.Tipo_Doc}: ${res.data.referencia.Num_Doc}`,
                            Tipo_Afiliado: res.data.referencia.Tipo_Afiliado,
                            tipo_solicitud: res.data.referencia.tipo_solicitud,
                            tipo_anexo: res.data.referencia.tipo_anexo,
                            rzeuz: res.data.referencia.rzeuz,
                            state: res.data.referencia.state
                        }
                    })
                    .catch(err => console.log('err.response :', err.response))
            },
            scrollDown(){
                let scrollDiv = this.$refs.containerMessageDisplay
                scrollDiv.scrollTop = scrollDiv.scrollHeight
            },
            finishBitacora(){
                swal({
                    title: 'Terminar bitácora',
                    icon: 'warning',
                    buttons: ['NO','SI'],
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        axios.put(`/api/referencia/bitacora/endBitacora/${this.bitacora}`)
                            .then((res) => {
                                console.log(res.data)
                                // swal({
                                //     title: "¡Agenda cancelada!",
                                //     text: `${res.data.message}`,
                                //     timer: 2000,
                                //     icon: "success",
                                //     buttons: false
                                // });
                                // this.fetchAgendaMedico();
                            }).catch((err) => console.log(err.response))
                    }
                });
            },
            scrollToTop() {
                window.scrollTo(0,0);
            },
            send(){
                if(this.message && !this.loading){
                    this.sendMessage();
                }
                if(!this.message && !this.loading){
                    this.addFile();
                }
            },
            sendFile(){
                let adjunto = this.$refs.fileChat.files[0];
                if(adjunto){
                    this.loading = true
                    let formData = new FormData();
                    formData.append('file', adjunto);
                    axios.post(`/api/referencia/bitacora/sendFile/${this.bitacora}`,formData, { headers: {'Content-Type': 'multipart/form-data'}})
                    .then(res => {
                        this.$refs.fileChat.value = '';
                        this.loading = false;
                    })
                    .catch(err => console.log('err.response',err.response))
                }

                
            },
            sendMessage(){
                if(this.message && !this.loading){

                    this.loading = true
                    axios.post(`/api/referencia/bitacora/sendMessage/${this.bitacora}`, { message: this.message})
                    .then(res => {
                        this.message = '';
                        this.loading = false
                    })
                    .catch(err => console.log('err.response',err.response))
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
//header
    .header-container{
        /* background: #d30303; */
        height: 70px;
        display: flex;
        padding: 0 20px 0 10px;
        align-items: center;
        -webkit-box-shadow:  0 2px 20px 2px rgba(90, 90, 90, 0.47);
        box-shadow: 0 2px 20px 2px rgba(90, 90, 90, 0.47);
        z-index: 5;
        border-radius: 15px 15px 0 0 !important;
        background: #8cbe38;
    }
    .header-title{
        padding: 10px;
        flex:1;
        text-align: left;
    }
    .header-title-text{
        /* color: #fff; */
        margin-bottom: 0;
        font-size: 15px;
    }
    .header-paticipants-text{
        color: #e4e4e4;
        font-size: 12px;
        margin-top: 5px;
        max-height: 30px;
        overflow: hidden;
    }
    .header-exit-button{
        text-decoration: none;
        color: #fff;
        font-size: 20px;
    }
    .icon-close-chat{
        color:#fff;
        width: 100%;
    }
    .icon-close-chat:hover{
        color:rgb(238, 121, 121)
    }


// mesages-content
    .card{
        border-radius: 15px;
    }
    .container-message-display{
        /* display: flex;
        flex-direction: column;
        justify-content: flex-end; */
        /* align-items: center; */
        flex: 1;
        overflow-y: scroll;
        overflow-x: hidden;
        // display: flex;
        flex-direction: column;
        padding-bottom: 10px;
        height: 60vh;
    }
    .message-text{
        background: #fff;
        padding: 6px 10px;
        border-radius: 15px;
        margin: 5px 0 5px 0;
        max-width: 70%;
        overflow-wrap: break-word;
        text-align: left;
        white-space: pre-wrap;
    }
    .message-text > p {
        margin: 5px 0 5px 0;
        height: 100%;

    }
    .message-timestamp{
        padding: 2px 7px;
        border-radius: 15px;
        margin: 0;
        max-width: 50%;
        overflow-wrap: break-word;
        text-align: left;
        font-size: 10px;
        color: #bdb8b8;
        width: 100%;
        display: flex;
        align-items: center;
    }
    .my-message >.message-timestamp{
        text-align: right;
    }
    .my-message{
        justify-content: flex-end;
        padding-right: 15px;
        align-items: flex-end;
    }
    .other-message{
        justify-content: flex-start;
        padding-left: 15px;
        align-items: flex-start;
    }
    .other-message >.message-text{
        /* background: #fb4141;  */
        color: #fff;
        border-bottom-left-radius: 0px;
    }
    .my-message >.message-text{
        border-bottom-right-radius: 0px;
        border: #000 solid 1px;
    }
    .message-container{
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
    }
    .message-username{
        font-size: 10px;
        font-weight: bold;
    }
    .icon-sent{
        width: 12px;
        padding-left: 5px;
        color: rgb(129, 127, 127);
    }
    .message-loading{
        height: 8px;
        width: 8px;
        border: 1px solid rgb(187, 183, 183);
        border-left-color: rgb(59, 59, 59);
        border-radius: 50%;
        margin-left: 5px;
        animation: spin 1.3s ease 13;
        
    }
    @keyframes spin{
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

//form message

    .container-message-manager{
        height: 65px;
        background: #fff;
        display: flex;
        align-items: center;
        padding: 0 20px 0 20px;
        -webkit-box-shadow: 0px -2px 40px 0px rgba(186,186,186,0.67);
        box-shadow: 0px -2px 40px 0px rgba(186,186,186,0.67);
    }
    .message-text-box{
        padding: 0 10px 0 10px;
        flex: 1;
        overflow: hidden;
    }
    .message-input{
        width: 100%;
        resize: none;
        border: none;
        outline: none;
        box-sizing: border-box;
        font-size: 15px;
        font-weight: 400;
        line-height: 1.33;
        white-space: pre-wrap;
        word-wrap: break-word;
        color: #565867;
        -webkit-font-smoothing: antialiased;
        max-height: 40px;
        bottom: 0;
        overflow: scroll;
        overflow-x: hidden;
        overflow-y: auto;
        text-align: left;
        cursor: text;
        display: inline-block;
    }
    .message-input:empty:before {
    content: attr(placeholder);
    display: block; /* For Firefox */
    /* color: rgba(86, 88, 103, 0.3); */
    filter: contrast(15%);
    outline: none;
    }
    .message-input:focus{
        outline: none;
    }
    .container-send-message{
        margin-left: 10px;
    }
    .icon-send-message{
        /* color:#b91010; */
        width: 20px;
        cursor: pointer;
        opacity: 0.7;
        transition: 0.3s;
        border-radius: 11px;
        padding: 8px;
    }
    .icon-send-message:hover{
        opacity: 1;
        background: #eee;
    }


//slide content-chat
    .slide-fade-enter-active {
        transition: all .3s ease;
    }
    .slide-fade-leave-active {
        transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to{
        transform: translateX(10px);
        opacity: 0;
    }
//

</style>
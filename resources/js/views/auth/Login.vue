<template>
    <v-content id="imglogin">
        <v-container fill-height>
            <v-layout align-center>
                <v-flex xs8 md6>
                    <v-flex xs12 mb-5>
                        <h1 class="white--text sombra display-1"><b >Bienvenido a Horus-Health</b></h1>
                    </v-flex>
                    <FormLogin />
                </v-flex>
            </v-layout>
        </v-container>
    </v-content>
</template>
<script>
import FormLogin from '../../components/auth/FormLogin'
export default {
    name: 'Login',
    components: {
        FormLogin
    }
}
</script>
<style>
    #imglogin{
        background-image: url('/storage/images/dia-doctor-lindo-joven-apuesto-bata-laboratorio-gafas-sonriendo-sosteniendo-libro.jpg');
        background-size: cover
    }
    html{
        overflow-y: auto !important;
        overflow-x: auto !important;
    }
    .sombra{
        box-shadow: 10px
    }
</style>

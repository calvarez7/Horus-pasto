<template>
    <v-content id="imglogin">
        <FormPassword />
    </v-content>
</template>
<script>
    import FormPassword from '../../components/auth/FormPassword'
    export default {
        name: 'Password',
        components: {
            FormPassword
        }
    }

</script>
<style>
    #imglogin {
        background-image: url('/storage/images/dia-doctor-lindo-joven-apuesto-bata-laboratorio-gafas-sonriendo-sosteniendo-libro.jpg');
        background-size: cover
    }

</style>

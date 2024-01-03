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
        background-image: url('/storage/images/imglogin.png');
        background-size: cover
    }

</style>

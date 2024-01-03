<template>
    <v-layout justify-center >
        <v-flex shrink xs12>
            <v-card>
                <TablePermiso />
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import TablePermiso from '../../../components/permiso/TablePermiso.vue'
    export default {
        name: 'Permisos',
        components:{
            TablePermiso
        }
    }
</script>

<style lang="scss" scoped>

</style>
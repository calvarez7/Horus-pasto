<template>
    <div>
        <v-layout justify-center >
            <v-flex shrink xs6 px-1>
                <TableTipoFamilia />
            </v-flex>
            <v-flex shrink xs6 pa-1>
                <TableFamilia />
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import TableTipoFamilia from '../../../components/tipo_familia/TableTipoFamilia.vue'
    import TableFamilia from '../../../components/tipo_familia/TableFamilia.vue'
 
    export default {
        name: 'TipoServicio',
        components: {
            TableTipoFamilia,
            TableFamilia
        }
    }
</script>

<style lang="scss" scoped>

</style>
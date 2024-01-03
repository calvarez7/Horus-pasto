<template>
    <v-layout justify-center >
        <v-flex shrink xs12>
            <v-card min-height="100%">
                <TableCargo />
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import TableCargo from '../../../components/cargo/TableCargo.vue'
 
    export default {
        name: 'Cargo',
        components: {
            TableCargo
        }
    }
</script>

<style lang="scss" scoped>

</style>
<template>
    <v-layout justify-center >
        <v-flex shrink xs12>
            <v-card min-height="100%">
                <TableColegio />
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import TableColegio from '../../../components/colegio/TableColegio.vue'
 
    export default {
        name: 'Colegios',
        components: {
            TableColegio
        }
    }
</script>

<style lang="scss" scoped>

</style>
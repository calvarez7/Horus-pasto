<template>
  <div>
    <v-layout justify-center>
      <v-flex shrink xs12 px-1>
        <TableCup />
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import TableCup from "../../../components/cups/TableCup.vue";

export default {
  name: "TipoServicio",
  components: {
    TableCup
  }
};
</script>

<style lang="scss" scoped>
</style>
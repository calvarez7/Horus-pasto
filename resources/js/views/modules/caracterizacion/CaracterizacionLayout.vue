<template>
  <v-layout justify-center>
    <v-flex shirnk xs12>
      <router-view />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "CaracterizacionLayout",
  components: {},
  mounted () {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Modulo de caracterización",
       items: [
           { name: "Registro", path: "/caracterizacion/registro", can: 'registro.caracterizacion' },
           { name: "Reporte de caracterización", path: "/caracterizacion/reporte", can: 'registro.caracterizacion' }
        ],
        enabled: true
      });
    }
  }
}
</script>

<style lang="scss" scoped>

</style>

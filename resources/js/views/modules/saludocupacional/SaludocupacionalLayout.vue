<template>
  <v-layout justify-center>
    <v-flex shirnk xs12>
      <router-view />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "SaludocupacionalLayout",
  components: {},
  mounted () {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Modulo de salud ocupacional",
       items: [
           { name: "Asignar cita", path: "/saludocupacional/citaOcupacional", can: 'cita.ocupacional' },
           { name: "Certificado de Aptitud", path: "/saludocupacional/certificado", can: 'certificado.ocupacional' },
           { name: "Historial Ocupacional", path: "/saludocupacional/historial", can: 'historia.ocupacional' },
           { name: "Reporte condiciones de salud", path: "/saludocupacional/reporte", can: 'reporte.ocupacional' },
        ],
        enabled: true
      });
    }
  }
}
</script>

<style lang="scss" scoped>

</style>

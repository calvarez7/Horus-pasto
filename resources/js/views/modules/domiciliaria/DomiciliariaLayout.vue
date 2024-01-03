<template>
  <v-layout justify-center>
    <v-flex shirnk xs12>
      <router-view />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "DomiciliariaLayout",
  components: {},
  mounted () {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Domiciliaria",
        items: [
          { name: 'Nueva Remisión', path: '/domiciliaria/remision', can:'remision-domiciliaria' },
          { name: 'Admisión', path: '/domiciliaria/admision', can:'remision-domiciliaria'},
          { name: "Seguimiento", path: "/domiciliaria/seguimiento", can:'remision-domiciliaria' },
          { name: "Alta", path: "/domiciliaria/alta", can:'remision-domiciliaria' },
          { name: "Hitorico", path: "/domiciliaria/Hitorico", can:'historico-domiciliaria' }
        ],
        enabled: true
      });
    }
  }
}
</script>

<style lang="scss" scoped>

</style>

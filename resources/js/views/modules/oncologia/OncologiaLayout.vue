<template>
  <v-layout justify-center>
    <v-flex shirnk xs12>
      <router-view />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "OncologiaLayout",
  components: {},
  mounted () {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Oncológico",
        items: [
          { name: 'Parametrización Esquemas', path: '/oncologia', can:'esquema.oncologico' },
             { name: "Ordenado", path: "/oncologia/ordenado", can:'ordenado.oncologico' },
            { name: "Autorización", path: "/oncologia/autorizacion", can:'autorizaquimico' },
            { name: "Enfermera", path: "/oncologia/enfermera", can:'enfermera.enter' },
            { name: "NotasEnfermeria", path: "/oncologia/notasenfermeria", can:'enfermera.enter' }
        ],
        enabled: true
      });
    }
  }
}
</script>

<style lang="scss" scoped>

</style>

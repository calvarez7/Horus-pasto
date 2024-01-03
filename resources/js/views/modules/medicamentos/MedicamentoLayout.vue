<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "MedicamentoLayout",
  components: {},
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Medicamentos",
        items: [
          // { name: 'Medicamentos', path: '/medicamentos' },
          { name: "Dispensación", path: "/dispensacion", can:'any' },
          { name: "Vademecum (LBM-D)", path: "/medicamentos", can:'any' },
            { name: "Firma Ordenes", path: "/medicamentos/firmas", can:'firma.ordenes' },
            { name: "Perfil Farmacoterapeutico", path: "/medicamentos/farmacoterapuetico", can:'farmacoterapuetico.enter' },
            { name: "Alertas Farmacoterapeuticas", path: "/medicamentos/alertas", can:'alertas.enter' },
            { name: "Seguimiento Alertas", path: "/medicamentos/alertas/seguimiento", can:'alertas.segumiento.enter' }

        ],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>

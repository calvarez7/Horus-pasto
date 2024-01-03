<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "MedicalBillsLayout",
  mounted () {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Cuentas Medicas",
        items: [
          { name: "Asignación Factura", path: "/cuentasmedicas/asignar", can: 'cuentasmedicas.asignar' },
          { name: "Auditoria", path: "/cuentasmedicas/auditoria", can: 'cuentasmedicas.auditoria' },
          { name: "Facturas", path: "/cuentasmedicas/facturas", can: 'cuentasmedicas.facturas' },
          { name: "Tesoreria", path: "/cuentasmedicas/tesoreria", can: 'cuentasmedicas.tesoreria' },
          { name: "Informe", path: "/cuentasmedicas/informe", can: 'cuentasmedicas.informe' },
          { name: "Admin", path: "/cuentasmedicas/admin", can: 'cuentasmedicas.admin' }
        ],
        enabled: true
      });
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
<template>
  <div>
    <h1>{{ clientId ? 'Editar Cliente' : 'Novo Cliente' }}</h1>
    <ClientForm :clientId="clientId" />
  </div>
</template>

<script>
import ClientForm from '@/components/ClientForm.vue'

export default {
  components: {
    ClientForm
  },
  props: {
    id: {
      type: [String, Number],
      default: null
    }
  },
  computed: {
    clientId() {
      return this.id || this.$route.params.id
    }
  }
}
</script>
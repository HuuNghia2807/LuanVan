<template>
  <div
    class="fixed surface-100 opacity-80 top-0 bottom-0 right-0 left-0 flex align-items-center z-3 loader"
    v-if="isLoading"
  >
    <ProgressSpinner strokeWidth="1" animationDuration=".5s" />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import ProgressSpinner from "primevue/progressspinner";

export default defineComponent({
  components: { ProgressSpinner },
  props: {
    isLoading: {
      required: true,
      type: Boolean,
      default: false,
    },
  },
});
</script>

<style scoped>
.loader {
  position: absolute !important;
}
</style>

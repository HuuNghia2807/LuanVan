<template>
  <div class="container">
    <div class="wrap">
      <div class="search">
        <span class="p-input-icon-left">
          <i class="pi pi-search" />
          <InputText type="text" v-model="search" placeholder="Search" />
        </span>
      </div>
      <div class="sort">
        <span>sort by:</span>
      </div>
    </div>
    <div class="action"></div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import InputText from "primevue/inputtext";

export default defineComponent({
  components: {
    InputText,
  },
  setup() {
    const search = ref("");
    return {
      search,
    };
  },
});
</script>

<style scoped></style>

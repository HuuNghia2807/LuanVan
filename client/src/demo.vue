<template>
  <div>components</div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  setup() {
    const defineMethod = () => {
      console.log("Đây là một methods");
    };

    return {
      defineMethod,
    };
  },
});
</script>

<style scoped></style>

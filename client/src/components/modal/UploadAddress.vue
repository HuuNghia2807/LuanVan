<template>
  <div class="update-address">
    <my-dialog
      header="Cập nhật địa chỉ"
      :visible="displayModal"
      @update:visble="displayModal"
      :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
      :style="{ width: '50vw' }"
      :modal="true"
    >
      div
      <template #footer>
        <my-button
          label="No"
          icon="pi pi-times"
          @click="closeModal"
          class="p-button-text"
        />
        <my-button
          label="Yes"
          icon="pi pi-check"
          @click="closeModal"
          autofocus
        />
      </template>
    </my-dialog>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    displayModal: { type: Boolean, default: false },
  },
  setup(props, { emit }) {
    const closeModal = () => {
      emit("close-modal");
    };

    return {
      closeModal,
    };
  },
});
</script>

<style lang="scss" scoped>
.update-address {
  :deep(.p-component) {
    font-size: 1.6rem !important;
  }
}
</style>

<template>
  <my-dialog
    :visible="isConfirm"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '40vw' }"
    :modal="true"
    @update:visible="closeModal"
  >
    <div class="confirmation-content">
      <h3>Bạn có chắc muốn hủy đơn hàng này!</h3>
      <div class="text-wrap">
        <span v-for="text in listText" :key="text" @click="handleText(text)">
          {{ text }}
        </span>
      </div>
      <div class="field">
        <label for="content" class="label content">Khác:</label>
        <Textarea id="content" v-model="note" :autoResize="true" rows="2" />
      </div>
    </div>
    <template #footer>
      <my-button
        label="No"
        icon="pi pi-times"
        @click="closeModal"
        class="p-button-outlined"
      />
      <my-button label="Yes" icon="pi pi-check" @click="handleConfirm" />
    </template>
  </my-dialog>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import Textarea from "primevue/textarea";

export default defineComponent({
  props: {
    isConfirm: { type: Boolean, default: false },
  },
  components: {
    Textarea,
  },
  setup(props, { emit }) {
    const listText = ref([
      "Tôi đã chọn được mẫu khác!",
      "Nó không phù hợp với tôi!",
      "Tôi thấy nó không thực sự tốt!",
      "Nó vượt quá khả năng của tôi!",
    ]);
    const note = ref("");
    const closeModal = () => {
      emit("close-modal");
    };
    const handleText = (text: string) => {
      note.value = text;
    };
    const handleConfirm = () => {
      emit("cancel-order", note.value);
    };
    return {
      note,
      listText,
      handleText,
      closeModal,
      handleConfirm,
    };
  },
});
</script>

<style lang="scss" scoped>
.confirmation-content {
  font-size: 1.6rem !important;

  :deep(.p-component) {
    font-size: 1.6rem !important;
  }

  :deep(.p-inputtext) {
    font-size: 1.6rem;
    height: 8rem;
    line-height: 1.6;
    padding-left: 1rem;
  }

  .text-wrap {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin: 2rem 0;

    span {
      background-color: rgb(190, 255, 255);
      padding: 0.5rem 2rem;
      border-radius: 10px;
      margin-bottom: 0.5rem;
      cursor: pointer;

      &:hover {
        background-color: rgb(138, 255, 255);
        font-weight: 500;
      }
    }
  }
}
.field {
  width: 100%;
  display: flex;
  flex-direction: column;
  margin-bottom: 1.5rem;
}
.label {
  font-size: 1.5rem;
}
</style>

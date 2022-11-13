<template>
  <my-dialog
    header="Thêm Khuyến Mãi"
    :visible="isAddDiscount"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '30vw' }"
    :modal="true"
    @update:visible="closeModal"
  >
    <form @submit.prevent="handleSubmit(!v$.$invalid)" class="p-fluid">
      <div class="field">
        <div class="p-float-label">
          <my-inputText
            id="title"
            v-model="v$.title.$model"
            :class="{ 'p-invalid': v$.title.$invalid && submitted }"
          />
          <label
            for="title"
            :class="{ 'p-error': v$.title.$invalid && submitted }"
            >Tiêu đề khuyến mãi *</label
          >
        </div>
        <small
          v-if="(v$.title.$invalid && submitted) || v$.title.$pending"
          class="p-error"
          >{{ v$.title.required.$message }}</small
        >
      </div>
      <div class="field">
        <div class="p-float-label">
          <my-inputText
            id="percent"
            v-model="v$.percent.$model"
            :class="{ 'p-invalid': v$.percent.$invalid && submitted }"
          />
          <label
            for="percent"
            :class="{ 'p-error': v$.percent.$invalid && submitted }"
            >Phần trăm khuyến mãi *</label
          >
        </div>
        <small
          v-if="(v$.percent.$invalid && submitted) || v$.percent.$pending"
          class="p-error"
          >{{ v$.percent.required.$message }}</small
        >
      </div>
      <div
        class="flex justify-content-center align-items-center mb-3"
        v-if="msgError"
      >
        <small class="p-error text-xl">{{ msgError }}</small>
      </div>
      <div class="flex justify-content-end">
        <my-button
          label="Cancel"
          icon="pi pi-times"
          @click="closeModal"
          class="p-button-outlined"
        />
        <my-button
          type="submit"
          label="ADD"
          icon="pi pi-check"
          class="ml-3"
          :loading="showLoading"
        />
      </div>
    </form>
  </my-dialog>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from "vue";
import { helpers, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import store from "@/store";

export default defineComponent({
  components: {},
  props: {
    isAddDiscount: { type: Boolean, default: false },
  },
  setup(props, { emit }) {
    const submitted = ref(false);
    const showLoading = ref(false);
    const state = reactive({
      title: "",
      percent: "",
    });
    const rules = {
      title: {
        required: helpers.withMessage("Vui lòng nhập tiêu đề", required),
      },
      percent: {
        required: helpers.withMessage(
          "Phần trăm khuyến mãi. example: 1, 10.05, 50.50, 100",
          (value: string) =>
            // eslint-disable-next-line
            /^([0-9]\.[0-9]{1}|[0-9]\.[0-9]{2}|\.[0-9]{2}|[1-9][0-9]\.[0-9]{1}|[1-9][0-9]\.[0-9]{2}|[0-9][0-9]|[1-9][0-9]\.[0-9]{2})$|^([0-9]|[0-9][0-9]|[0-99])$|^100$/.test(
              value
            )
        ),
      },
    };

    const msgError = ref("");
    const v$ = useVuelidate(rules, state);
    const handleSubmit = (isFormValid: any) => {
      msgError.value = "";
      submitted.value = true;
      if (isFormValid) {
        addDiscount();
        return;
      }
    };

    const addDiscount = async () => {
      showLoading.value = true;
      msgError.value = "";
      const discount = {
        discount_title: state.title,
        discount: parseFloat(state.percent),
      };
      await store.dispatch("product/addDiscount", discount);
      const err = store.getters["product/getError"]?.data;
      if (err) {
        msgError.value = err.message;
        showLoading.value = false;
        return;
      }
      //   clearForm();
      showLoading.value = false;
    };

    const clearForm = () => {
      state.title = "";
      state.percent = "";
      submitted.value = false;
    };

    const closeModal = () => {
      emit("close-modal-add");
    };

    return {
      submitted,
      state,
      v$,
      msgError,
      showLoading,
      handleSubmit,
      closeModal,
    };
  },
});
</script>

<style lang="scss" scoped>
@import "@/assets/styles/deepInput.scss";
:deep(.p-component) {
  font-size: 1.6rem !important;
}
:deep(.p-inputtext) {
  font-size: 1.6rem;
  height: 4rem;
  line-height: 1.6;
  padding-left: 1rem;
}

form {
  margin-top: 1.8rem;
}
.field {
  margin-bottom: 1.5rem;
  height: 5rem;

  small {
    font-size: 1.2rem;
  }
}
</style>

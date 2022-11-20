<template>
  <my-dialog
    header="Đổi tên danh mục"
    :visible="isCategoryUpdate"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '30vw' }"
    :modal="true"
    @update:visible="closeModal"
  >
    <form @submit.prevent="handleSubmit(!v$.$invalid)" class="p-fluid">
      <div class="field">
        <div class="p-float-label">
          <my-inputText
            id="name"
            v-model="v$.name.$model"
            :class="{
              'p-invalid': v$.name.$invalid && submitted,
            }"
          />

          <label
            for="name"
            :class="{ 'p-error': v$.name.$invalid && submitted }"
            >Tên danh mục*</label
          >
        </div>
        <small
          v-if="(v$.name.$invalid && submitted) || v$.name.$pending"
          class="p-error"
          >{{ v$.name.required.$message }}</small
        >
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
          label="Update"
          icon="pi pi-check"
          class="ml-3"
          :loading="showLoading"
        />
      </div>
    </form>
  </my-dialog>
</template>

<script lang="ts">
import { defineComponent, PropType, reactive, ref } from "vue";
import { helpers, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { ICategory } from "@/interface/product/product.state";

export default defineComponent({
  components: {},
  props: {
    isCategoryUpdate: { type: Boolean, default: false },
    category: { type: Object as PropType<ICategory> },
  },
  setup(props, { emit }) {
    const submitted = ref(false);
    const showLoading = ref(false);
    const state = reactive({
      name: props.category?.categoryName,
    });
    const rules = {
      name: {
        required: helpers.withMessage(
          "Tên danh mục không được bỏ trống và không có khoảng cách!",
          (value: string) =>
            // eslint-disable-next-line
            /^[a-zA-Z0-9_]*$/i.test(value)
        ),
      },
    };

    const v$ = useVuelidate(rules, state);
    const handleSubmit = (isFormValid: any) => {
      submitted.value = true;
      if (isFormValid) {
        emit("handle-update-category", state.name);
        return;
      }
    };

    const closeModal = () => {
      emit("close-modal");
    };

    return {
      submitted,
      state,
      v$,
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

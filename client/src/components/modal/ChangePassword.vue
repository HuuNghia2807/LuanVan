<template>
  <my-dialog
    header="Đổi mật khẩu"
    :visible="isChangePass"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '30vw' }"
    :modal="true"
  >
    <form @submit.prevent="handleSubmit(!v$.$invalid)" class="p-fluid">
      <div class="field">
        <div class="p-float-label">
          <my-password
            id="oldPass"
            v-model="v$.oldPass.$model"
            :class="{ 'p-invalid': v$.oldPass.$invalid && submitted }"
            toggleMask
            :feedback="false"
          />

          <label
            for="oldPass"
            :class="{ 'p-error': v$.oldPass.$invalid && submitted }"
            >Mật khẩu củ*</label
          >
        </div>
        <small
          v-if="(v$.oldPass.$invalid && submitted) || v$.oldPass.$pending"
          class="p-error"
          >{{ v$.oldPass.required.$message }}</small
        >
      </div>
      <div class="field">
        <div class="p-float-label">
          <my-password
            id="newPass"
            v-model="v$.newPass.$model"
            :class="{ 'p-invalid': v$.newPass.$invalid && submitted }"
            toggleMask
          />

          <label
            for="newPass"
            :class="{ 'p-error': v$.newPass.$invalid && submitted }"
            >Mật khẩu mới*</label
          >
        </div>
        <small
          v-if="(v$.newPass.$invalid && submitted) || v$.newPass.$pending"
          class="p-error"
          >{{ v$.newPass.required.$message }}</small
        >
      </div>
      <div class="field">
        <div class="p-float-label">
          <my-password
            id="comfirmPass"
            v-model="v$.comfirmPass.$model"
            :class="{ 'p-invalid': v$.comfirmPass.$invalid && submitted }"
            toggleMask
          />

          <label
            for="comfirmPass"
            :class="{ 'p-error': v$.comfirmPass.$invalid && submitted }"
            >Mật khẩu *</label
          >
        </div>
        <small
          v-if="
            (v$.comfirmPass.$invalid && submitted) || v$.comfirmPass.$pending
          "
          class="p-error"
          >{{ v$.comfirmPass.required.$message }}</small
        >
      </div>
      <div class="flex justify-content-end">
        <my-button
          label="Cancel"
          icon="pi pi-times"
          @click="closeModal"
          class="p-button-text"
        />
        <my-button
          type="submit"
          label="Update"
          icon="pi pi-check"
          autofocus
          class="ml-3"
        />
      </div>
    </form>
  </my-dialog>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from "vue";
import { helpers, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

export default defineComponent({
  components: {},
  props: {
    isChangePass: { type: Boolean, default: false },
  },
  setup(props, { emit }) {
    const submitted = ref(false);
    const state = reactive({
      oldPass: "",
      newPass: "",
      comfirmPass: "",
    });
    const rules = {
      oldPass: {
        required: helpers.withMessage("Vui lòng nhập mật khẩu củ", required),
      },
      newPass: {
        required: helpers.withMessage("Vui lòng nhập mật khẩu mới", required),
      },
      comfirmPass: {
        required: helpers.withMessage(
          "Vui lòng nhập lại mật khẩu mới",
          required
        ),
      },
    };

    const v$ = useVuelidate(rules, state);
    const handleSubmit = (isFormValid: any) => {
      submitted.value = true;

      if (isFormValid) {
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
}
</style>

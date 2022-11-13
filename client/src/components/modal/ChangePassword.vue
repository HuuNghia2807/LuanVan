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
import { helpers, minLength, required, sameAs } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import store from "@/store";
import {
  IChangePassParams,
  ICustomer,
} from "@/interface/auth/authentication.state";

export default defineComponent({
  components: {},
  props: {
    isChangePass: { type: Boolean, default: false },
    customer: { type: Object as PropType<ICustomer> },
  },
  setup(props, { emit }) {
    const submitted = ref(false);
    const showLoading = ref(false);
    const state = reactive({
      oldPass: "",
      newPass: "",
      comfirmPass: "",
    });
    const rules = {
      oldPass: {
        required: helpers.withMessage("Vui lòng nhập mật khẩu củ", required),
        minLengthValue: minLength(6),
      },
      newPass: {
        required: helpers.withMessage("Vui lòng nhập mật khẩu mới", required),
        minLengthValue: minLength(6),
      },
      comfirmPass: {
        required: helpers.withMessage(
          "Vui lòng nhập lại mật khẩu mới",
          required
        ),
        minLengthValue: minLength(6),
      },
    };

    const msgError = ref("");
    const v$ = useVuelidate(rules, state);
    const handleSubmit = (isFormValid: any) => {
      msgError.value = "";
      submitted.value = true;
      if (state.newPass !== state.comfirmPass) {
        msgError.value = "Mật khẩu không khớp vui lòng kiểm tra lại!";
        return;
      }
      if (isFormValid) {
        changePass();
        return;
      }
    };

    const changePass = async () => {
      showLoading.value = true;
      msgError.value = "";
      await store.dispatch("auth/changePass", {
        customer_id: props.customer?.id,
        old_pass: state.oldPass,
        new_pass: state.newPass,
        new_pass_confirmation: state.comfirmPass,
      } as IChangePassParams);
      const err = store.getters["auth/getError"]?.data;
      if (err) {
        msgError.value = err.message;
        showLoading.value = false;
        return;
      }
      emit("change-pass-success");
      clearForm();
      showLoading.value = false;
    };

    const clearForm = () => {
      state.oldPass = "";
      state.newPass = "";
      state.comfirmPass = "";
      submitted.value = false;
    };

    const closeModal = () => {
      emit("close-modal");
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

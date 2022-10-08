<template>
  <div class="login flex justify-content-center">
    <div class="card">
      <div class="flex justify-content-center my-5">
        <img src="@/assets/img/icon-login.png" width="50" />
      </div>
      <form @submit.prevent="handleSubmit(!v$.$invalid)" class="p-fluid">
        <div class="field">
          <div class="p-float-label">
            <my-inputText
              id="phone"
              v-model="v$.phone.$model"
              :class="{ 'p-invalid': v$.phone.$invalid && submitted }"
            />
            <label
              for="phone"
              :class="{ 'p-error': v$.phone.$invalid && submitted }"
              >Số điện thoại *</label
            >
          </div>
        </div>
        <div class="field">
          <div class="p-float-label">
            <my-password
              id="password"
              v-model="v$.password.$model"
              :class="{ 'p-invalid': v$.password.$invalid && submitted }"
              toggleMask
              :feedback="false"
            >
            </my-password>
            <label
              for="password"
              :class="{ 'p-error': v$.password.$invalid && submitted }"
              >Mật khẩu*</label
            >
          </div>
        </div>
        <div
          class="my-4 flex justify-content-center"
          v-if="
            (v$.phone.$invalid && submitted) ||
            v$.phone.$pending ||
            (v$.password.$invalid && submitted) ||
            v$.password.$pending
          "
        >
          <small class="p-error">{{
            v$.phone.required.$message || v$.password.required.$message
          }}</small>
        </div>

        <my-button type="submit" label="Đăng Nhập" class="mt-2" />
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from "vue";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { useStore } from "vuex";
import { ILoginParams } from "@/interface/auth/authentication.state";

export default defineComponent({
  setup() {
    const store = useStore();
    const state = reactive({
      phone: "",
      password: "",
    });

    const rules = {
      phone: {
        required: helpers.withMessage("Số điện thoại không đúng", required),
        maxLength: maxLength(10),
        minLengthValue: minLength(10),
      },
      password: {
        required: helpers.withMessage("Vui lòng nhập password", required),
      },
    };

    const submitted = ref(false);

    const v$ = useVuelidate(rules, state);

    const handleSubmit = async (isFormValid: any) => {
      submitted.value = true;
      const user: ILoginParams = {
        userName: state.phone,
        password: state.password,
      };

      if (!isFormValid) {
        await store.dispatch("auth/login", user);
        const name = store.getters["auth/getUserName"];
        return;
      }
    };

    return {
      state,
      v$,
      submitted,
      handleSubmit,
    };
  },
});
</script>

<style lang="scss" scoped>
@import "@/assets/styles/deepInput.scss";
.login {
  width: 100%;
}

.card {
  width: 100%;
  margin: 0 2rem;

  form {
    margin-top: 4rem;
  }

  .field {
    margin-bottom: 3rem;
  }

  @media screen and (max-width: 960px) {
    .card {
      width: 80%;
    }
  }
}
</style>

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
              id="userName"
              v-model="v$.email.$model"
              :class="{ 'p-invalid': v$.email.$invalid && submitted }"
            />
            <label
              for="userName"
              :class="{ 'p-error': v$.email.$invalid && submitted }"
              >Email *</label
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
            (v$.email.$invalid && submitted) ||
            v$.email.$pending ||
            (v$.password.$invalid && submitted) ||
            v$.password.$pending
          "
        >
          <small class="p-error">{{
            v$.email.required.$message || v$.password.required.$message
          }}</small>
        </div>

        <my-button type="submit" label="Đăng Nhập" class="mt-2" />
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from "vue";
import { helpers, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { useStore } from "vuex";
import { ILoginParams } from "@/interface/auth/authentication.state";

export default defineComponent({
  setup() {
    const store = useStore();
    const state = reactive({
      email: "",
      password: "",
    });

    const rules = {
      email: {
        required: helpers.withMessage("Email không đúng", required),
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
        userName: state.email,
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

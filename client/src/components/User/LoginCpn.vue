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
          v-if="(v$.email.$invalid && submitted) || v$.email.$pending"
        >
          <small class="p-error">{{ v$.email.required.$message }}</small>
        </div>
        <div
          class="my-4 flex justify-content-center"
          v-if="(v$.password.$invalid && submitted) || v$.password.$pending"
        >
          <small class="p-error">{{ v$.password.required.$message }}</small>
        </div>
        <div class="my-4 flex justify-content-center" v-if="!!errorMsg">
          <small class="p-error">{{ errorMsg }}</small>
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
import { useRouter } from "vue-router";

export default defineComponent({
  setup() {
    const router = useRouter();
    const store = useStore();
    const state = reactive({
      email: "",
      password: "",
    });

    const errorMsg = ref("");

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

    const handleSubmit = (isFormValid: any) => {
      submitted.value = true;
      errorMsg.value = "";

      if (isFormValid) {
        const user: ILoginParams = {
          email: state.email,
          password: state.password,
        };
        login(user);
        return;
      }
    };

    const login = async (user: ILoginParams) => {
      await store.dispatch("auth/login", user);
      const error = store.getters["auth/getError"];

      if (error) {
        errorMsg.value = error.data.message as string;
      } else {
        router.push("/");
      }
    };

    return {
      state,
      v$,
      submitted,
      errorMsg,
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

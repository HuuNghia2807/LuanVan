<template>
  <div class="login flex justify-content-center">
    <div class="card">
      <form @submit.prevent="handleSubmit(!v$.$invalid)" class="p-fluid">
        <div class="field">
          <div class="p-float-label">
            <my-inputText
              id="name"
              v-model="v$.name.$model"
              :class="{ 'p-invalid': v$.name.$invalid && submitted }"
            />
            <label
              for="name"
              :class="{ 'p-error': v$.name.$invalid && submitted }"
              >Họ và tên *</label
            >
          </div>
        </div>
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
            <my-inputText
              id="email"
              v-model="v$.email.$model"
              :class="{ 'p-invalid': v$.email.$invalid && submitted }"
            />
            <label
              for="email"
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
            >
              <template #footer="sp">
                {{ sp.level }}
                <Divider />
                <ul class="pl-2 ml-2 mt-0" style="line-height: 1.5">
                  <li>Ít nhất một ký tự thường</li>
                  <li>Ít nhất một ký tự in hoa</li>
                  <li>Ít nhất một số</li>
                  <li>8 kí tự trở lên</li>
                </ul>
              </template>
            </my-password>
            <label
              for="password"
              :class="{ 'p-error': v$.password.$invalid && submitted }"
              >Mật khẩu *</label
            >
          </div>
        </div>
        <div
          class="my-4 flex justify-content-center"
          v-if="
            (v$.name.$invalid && submitted) ||
            v$.name.$pending ||
            (v$.phone.$invalid && submitted) ||
            v$.phone.$pending ||
            (v$.email.$invalid && submitted) ||
            v$.email.$pending ||
            (v$.password.$invalid && submitted) ||
            v$.password.$pending
          "
        >
          <small class="p-error">{{
            v$.name.required.$message ||
            v$.phone.required.$message ||
            v$.email.required.$message ||
            v$.password.required.$message
          }}</small>
        </div>

        <my-button
          type="submit"
          label="Đăng Ký"
          class="mt-2"
          v-tooltip="'Kiểm tra kỹ mật khẩu trước khi đăng ký'"
        />
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
      name: "",
      phone: "",
      email: "",
      password: "",
    });

    const rules = {
      name: {
        required: helpers.withMessage("Vui lòng nhập họ tên", required),
      },
      phone: {
        required: helpers.withMessage("Số điện thoại không đúng", required),
        maxLength: maxLength(10),
        minLengthValue: minLength(10),
      },
      email: {
        required: helpers.withMessage("Vui lòng nhập email", required),
      },
      password: {
        minLengthValue: minLength(8),
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

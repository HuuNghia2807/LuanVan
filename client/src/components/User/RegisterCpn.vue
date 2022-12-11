<template>
  <my-toast />
  <div class="login flex justify-content-center">
    <div class="card">
      <form @submit.prevent="handleSubmit(!v$.$invalid)" class="p-fluid">
        <div class="flex align-items-center">
          <div class="field">
            <div class="p-float-label">
              <my-inputText
                id="first_name"
                v-model="v$.first_name.$model"
                :class="{ 'p-invalid': v$.first_name.$invalid && submitted }"
              />
              <label
                for="first_name"
                :class="{ 'p-error': v$.first_name.$invalid && submitted }"
                >Họ *</label
              >
            </div>
            <small
              v-if="
                (v$.first_name.$invalid && submitted) || v$.first_name.$pending
              "
              class="p-error"
              >{{ v$.first_name.required.$message }}</small
            >
          </div>
          <div class="field ml-4">
            <div class="p-float-label">
              <my-inputText
                id="last_name"
                v-model="v$.last_name.$model"
                :class="{ 'p-invalid': v$.last_name.$invalid && submitted }"
              />
              <label
                for="last_name"
                :class="{ 'p-error': v$.last_name.$invalid && submitted }"
                >Tên *</label
              >
            </div>
            <small
              v-if="
                (v$.last_name.$invalid && submitted) || v$.last_name.$pending
              "
              class="p-error"
              >{{ v$.last_name.required.$message }}</small
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
          <small
            v-if="(v$.phone.$invalid && submitted) || v$.phone.$pending"
            class="p-error"
            >{{ v$.phone.required.$message }}</small
          >
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
          <small
            v-if="(v$.email.$invalid && submitted) || v$.email.$pending"
            class="p-error"
            >{{ v$.email.required.$message }}</small
          >
        </div>
        <div class="field">
          <div class="p-float-label">
            <my-password
              id="password-register"
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
                  <li>6 kí tự trở lên</li>
                </ul>
              </template>
            </my-password>
            <label
              for="password-register"
              :class="{ 'p-error': v$.password.$invalid && submitted }"
              >Mật khẩu *</label
            >
          </div>
          <small
            v-if="(v$.password.$invalid && submitted) || v$.password.$pending"
            class="p-error"
            >{{ v$.password.required.$message }}</small
          >
        </div>
        <div class="my-4 flex justify-content-center" v-if="errorMsg">
          <small class="p-error">{{ errorMsg }}</small>
        </div>
        <my-button
          type="submit"
          label="Đăng Ký"
          class="mt-2"
          id="btn-submit-register"
          :loading="showLoading"
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
import Divider from "primevue/divider";
import { useToast } from "primevue/usetoast";

export default defineComponent({
  components: {
    Divider,
  },
  setup(_props, { emit }) {
    const store = useStore();
    const toast = useToast();
    const errorMsg = ref("");
    const showLoading = ref(false);
    const state = reactive({
      first_name: "",
      last_name: "",
      phone: "",
      email: "",
      password: "",
    });

    const rules = {
      first_name: {
        required: helpers.withMessage("Vui lòng nhập họ tên", required),
      },
      last_name: {
        required: helpers.withMessage("Vui lòng nhập họ tên", required),
      },
      phone: {
        required: helpers.withMessage("Số điện thoại không đúng", required),
        maxLength: maxLength(10),
        minLengthValue: minLength(10),
      },
      email: {
        required: helpers.withMessage(
          "Email chưa đúng định dạng",
          (value: string) =>
            // eslint-disable-next-line
            /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(
              value
            )
        ),
      },
      password: {
        minLengthValue: minLength(6),
        required: helpers.withMessage("Vui lòng nhập password", required),
      },
    };

    const submitted = ref(false);

    const v$ = useVuelidate(rules, state);

    const handleSubmit = (isFormValid: any) => {
      submitted.value = true;
      errorMsg.value = "";
      if (isFormValid) {
        const customer = {
          first_name: state.first_name,
          last_name: state.last_name,
          phone: state.phone,
          email: state.email,
          password: state.password,
        };
        register(customer);
      }
    };

    const register = async (cus: any) => {
      showLoading.value = true;
      await store.dispatch("auth/register", cus);
      showLoading.value = false;
      const error = store.getters["auth/getError"];
      if (error) {
        errorMsg.value = error.data?.message;
        return;
      }
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Đăng ký tài khoản thành công",
        life: 3000,
      });
      clearForm();
    };

    const clearForm = () => {
      state.first_name = "";
      state.last_name = "";
      state.email = "";
      state.password = "";
      state.phone = "";
      submitted.value = false;
    };

    return {
      state,
      v$,
      submitted,
      errorMsg,
      showLoading,
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

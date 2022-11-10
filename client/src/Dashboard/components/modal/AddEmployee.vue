<template>
  <my-dialog
    header="Thêm nhân viên"
    :visible="isAddEmployee"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '40vw' }"
    :modal="true"
    @update:visible="closeModal"
  >
    <my-toast />
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
              id="password"
              v-model="v$.password.$model"
              :class="{ 'p-invalid': v$.password.$invalid && submitted }"
              toggleMask
              :feedback="false"
            />

            <label
              for="password"
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
        <div class="field">
          <div class="p-float-label">
            <my-dropdown
              id="role"
              v-model="v$.role.$model"
              :options="roles"
              :filter="true"
              optionLabel="role"
              optionValue="id"
              :class="{
                'p-invalid': v$.role.$invalid && submitted,
              }"
            >
              <template #option="slotProps">
                <div class="text-2xl">{{ slotProps.option.role }}</div>
              </template>
            </my-dropdown>
            <label
              for="role"
              class="label"
              :class="{
                'p-error': v$.role.$invalid && submitted,
              }"
            >
              Chức vụ *
            </label>
          </div>
          <small
            v-if="(v$.role.$invalid && submitted) || v$.role.$pending"
            class="p-error"
          >
            {{ v$.role.required.$message }}
          </small>
        </div>
        <div class="my-4 flex justify-content-center" v-if="errorMsg">
          <small class="p-error text-2xl">{{ errorMsg }}</small>
        </div>
        <my-button
          type="submit"
          label="Thêm nhân viên"
          class="mt-2"
          :loading="showLoading"
          v-tooltip="'Kiểm tra kỹ mật khẩu trước khi thêm'"
        />
      </form>
    </div>
  </my-dialog>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from "vue";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { useStore } from "vuex";
import { useToast } from "primevue/usetoast";

export default defineComponent({
  components: {},
  props: {
    isAddEmployee: { type: Boolean, default: false },
  },
  setup(_props, { emit }) {
    const closeModal = () => {
      emit("close-modal-add-employee");
    };
    const store = useStore();
    const toast = useToast();
    const errorMsg = ref("");
    const showLoading = ref(false);
    const roles = ref([
      {
        role: "Nhân viên",
        id: 1,
      },
      {
        role: "Quản trị viên",
        id: 2,
      },
      {
        role: "Admin",
        id: 3,
      },
    ]);
    const state = reactive({
      first_name: "",
      last_name: "",
      phone: "",
      email: "",
      password: "",
      role: "",
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
      role: {
        required: helpers.withMessage("Vui lòng chọn chức vụ", required),
      },
    };

    const submitted = ref(false);

    const v$ = useVuelidate(rules, state);

    const handleSubmit = (isFormValid: any) => {
      submitted.value = true;
      errorMsg.value = "";
      if (isFormValid) {
        const employee = {
          first_name: state.first_name,
          last_name: state.last_name,
          phone: state.phone,
          email: state.email,
          password: state.password,
          role_id: state.role,
        };
        register(employee);
      }
    };

    const register = async (employee: any) => {
      showLoading.value = true;
      await store.dispatch("auth/addEmployee", employee);
      showLoading.value = false;
      const error = store.getters["auth/getError"];
      if (error) {
        errorMsg.value = error.data?.message;
        return;
      }
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Thêm nhân viên thành công",
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
      state.role = "";
      submitted.value = false;
    };

    return {
      closeModal,
      state,
      v$,
      submitted,
      errorMsg,
      showLoading,
      roles,
      handleSubmit,
    };
  },
});
</script>

<style lang="scss" scoped>
@import "@/assets/styles/deepInput.scss";

.card {
  width: 100%;
  padding: 0 2rem;

  form {
    margin-top: 4rem;
  }

  .p-fluid {
    // margin-top: 0;

    :deep(.p-inputtext),
    :deep(.p-button) {
      font-size: 1.6rem;
      height: 4rem;
    }
  }

  .field {
    width: 100%;
    margin-bottom: 3rem;

    small {
      font-size: 1.2rem;
    }
  }
}
</style>

<template>
  <my-dialog
    header="Cập nhật thông tin"
    :visible="isEditInfo"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '70vw' }"
    :modal="true"
    @update:visible="closeModal"
  >
    <form @submit.prevent="handleSubmit(!v$.$invalid)" class="personal">
      <div class="flex">
        <div class="information">
          <div class="p-fluid formgrid">
            <div class="flex align-items-center">
              <div class="field w-6 mr-3">
                <label
                  for="firstName"
                  :class="{ 'p-error': v$.firstName.$invalid && submitted }"
                >
                  Họ *
                </label>
                <my-inputText
                  id="firstName"
                  v-model="v$.firstName.$model"
                  :class="{ 'p-invalid': v$.firstName.$invalid && submitted }"
                />
                <small
                  v-if="
                    (v$.firstName.$invalid && submitted) ||
                    v$.firstName.$pending
                  "
                  class="p-error"
                  >{{ v$.firstName.required.$message }}</small
                >
              </div>
              <div class="field w-6">
                <label
                  for="lastName"
                  :class="{ 'p-error': v$.lastName.$invalid && submitted }"
                >
                  Tên *
                </label>
                <my-inputText
                  id="lastName"
                  v-model="v$.lastName.$model"
                  :class="{ 'p-invalid': v$.lastName.$invalid && submitted }"
                />
                <small
                  v-if="
                    (v$.lastName.$invalid && submitted) || v$.lastName.$pending
                  "
                  class="p-error"
                  >{{ v$.lastName.required.$message }}</small
                >
              </div>
            </div>
            <div class="gender field">
              <div
                v-for="gender in genders"
                :key="gender.name"
                class="field-radiobutton mr-4"
              >
                <RadioButton
                  :inputId="gender.name"
                  name="gender"
                  :value="gender.name"
                  v-model="state.gender"
                />
                <label :for="gender.name">{{ gender.name }}</label>
              </div>
            </div>
            <div class="field">
              <label for="birth">Ngày Sinh</label>
              <my-inputText id="birth" v-model="state.birth" />
            </div>
            <div class="field">
              <label
                for="firstName"
                :class="{ 'p-error': v$.firstName.$invalid && submitted }"
              >
                Số điện thoại *
              </label>
              <my-inputText
                id="phone"
                v-model="v$.phone.$model"
                type="number"
                :class="{ 'p-invalid': v$.phone.$invalid && submitted }"
              />
              <small
                v-if="(v$.phone.$invalid && submitted) || v$.phone.$pending"
                class="p-error"
                >{{ v$.phone.required.$message }}</small
              >
            </div>
            <!-- <div class="field">
              <label for="phone">Số điện thoại: </label>
              <my-inputText id="phone" v-model="state.phone" />
            </div> -->
            <div class="field">
              <label for="email">Email: </label>
              <my-inputText
                id="email"
                type="email"
                v-model="state.email"
                disabled
              />
            </div>
          </div>
        </div>
        <div class="avatar">
          <div class="flex justify-content-between">
            <div class="avt-wrap">
              <div class="img">
                <img
                  :src="
                    state?.avatar ||
                    require('@/assets/img/avatar_default/default-avatar.png')
                  "
                />
                <label class="upload" for="avatar">
                  <i class="pi pi-camera" style="font-size: 5rem"></i>
                  <input
                    id="avatar"
                    type="file"
                    class="hidden"
                    @change="onUpload($event)"
                  />
                </label>
              </div>
              <span class="name">{{
                `${state.firstName} ${state.lastName}`
              }}</span>
              <span class="email">{{ state.email }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="flex justify-content-end mt-8">
        <my-button
          label="Cancel"
          icon="pi pi-times"
          @click="closeModal"
          class="p-button-outlined w-2"
        />
        <my-button
          type="submit"
          label="Update"
          icon="pi pi-check"
          autofocus
          class="w-2 ml-3"
        />
      </div>
    </form>
  </my-dialog>
</template>

<script lang="ts">
import { defineComponent, PropType, reactive, ref } from "vue";
import RadioButton from "primevue/radiobutton";
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import { ICustomer } from "@/interface/auth/authentication.state";
import { convertToBase64 } from "@/function/convertImage";

export default defineComponent({
  props: {
    isEditInfo: { type: Boolean, default: false },
    customer: { type: Object as PropType<ICustomer> },
  },
  components: {
    RadioButton,
  },
  setup(props, { emit }) {
    const submitted = ref(false);
    const state = reactive({
      firstName: props.customer?.firstName,
      lastName: props.customer?.lastName,
      phone: props.customer?.phone,
      email: props.customer?.email,
      gender: props.customer?.gender,
      birth: props.customer?.birth,
      avatar: props.customer?.avatar,
    });
    const genders = ref([
      {
        name: "Nam",
      },
      {
        name: "Nữ",
      },
      {
        name: "Khác",
      },
    ]);

    const rules = {
      firstName: {
        required: helpers.withMessage("Tên không được trống", required),
      },
      lastName: {
        required: helpers.withMessage("Tên không được trống", required),
      },
      phone: {
        required: helpers.withMessage(
          "Số điện thoại không được trống và có 10 số",
          required
        ),
        maxLength: maxLength(10),
        minLengthValue: minLength(10),
      },
    };
    const v$ = useVuelidate(rules, state);
    const handleSubmit = (isFormValid: any) => {
      submitted.value = true;

      if (isFormValid) {
        emit("update-info", state);
        return;
      }
    };

    const onUpload = (e: any) => {
      const target = e.target.files[0];
      // const img = URL.createObjectURL(target);
      convertToBase64(target).then((res) => {
        state.avatar = res as string;
      });
    };

    const closeModal = () => {
      emit("close-modal");
    };
    return {
      genders,
      v$,
      state,
      submitted,
      onUpload,
      closeModal,
      handleSubmit,
    };
  },
});
</script>

<style lang="scss" scoped>
.personal {
  display: flex;
  margin: 3rem 5rem;
  flex-direction: column;

  .head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
  }
  .information {
    width: 50%;

    .p-fluid {
      display: flex;
      flex-direction: column;
    }

    .gender {
      display: flex;
      align-items: center;
    }

    .field {
      margin: 1rem 0;

      label {
        font-size: 1.6rem;
      }

      small {
        font-size: 1.2rem;
      }

      input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
      }
    }

    :deep(.p-inputtext) {
      font-size: 1.6rem !important;
    }
  }
  :deep(.p-component:disabled) {
    opacity: 0.9 !important;
  }

  .avatar {
    width: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;

    .btn-edit {
      padding: 0.5rem 0.8rem;
      border-radius: 50%;
      cursor: pointer;
      width: 4rem;
      height: 4rem;
      display: flex;
      align-items: center;
      justify-content: center;

      &:hover {
        background-color: var(--primary-color);
      }
    }
    .active {
      background-color: var(--primary-color);
    }
    .img {
      position: relative;
      img {
        width: 30rem;
        height: 30rem;
        object-fit: cover;
        border-radius: 50%;
      }

      .upload {
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        border-radius: 50%;
        color: var(--white-color);
        background-color: rgb(0, 0, 0);
        opacity: 0.2;
        position: absolute;
        width: 30rem;
        height: 30rem;
        top: 0;
        left: 0;

        &:hover {
          opacity: 0.7;
        }
      }
    }

    .avt-wrap {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .name {
      margin: 1rem 0 0.2rem 0;
      font-weight: 600;
      font-size: 2rem;
    }

    .email {
      color: #888;
      font-size: 1.7rem;
    }

    .btn {
      margin-top: 10rem;

      button {
        margin: 0 2rem;
      }

      :deep(.p-button) {
        font-size: 1.6rem;
      }
    }
  }
}
</style>

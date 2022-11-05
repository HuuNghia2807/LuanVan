<template>
  <my-dialog
    header="Cập nhật thông tin"
    :visible="isEditInfo"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '70vw' }"
    :modal="true"
  >
    <form @submit.prevent="handleSubmit(!v$.$invalid)" class="personal">
      <div class="flex">
        <div class="information">
          <div class="p-fluid formgrid">
            <div class="field">
              <label for="username">Họ và tên: </label>
              <my-inputText
                id="username"
                type="username"
                v-model="state.name"
              />
            </div>
            <div class="gender field">
              <div
                v-for="gender in genders"
                :key="gender.key"
                class="field-radiobutton mr-4"
              >
                <RadioButton
                  :inputId="gender.key"
                  name="gender"
                  :value="gender.key"
                  v-model="state.gender"
                />
                <label :for="gender.key">{{ gender.name }}</label>
              </div>
            </div>
            <div class="field">
              <label for="dateformat">Ngày sinh: </label>
              <my-inputText id="dateformat" v-model="state.birth" />
            </div>
            <div class="field">
              <label for="phone">Số điện thoại: </label>
              <my-inputText id="phone" v-model="state.phone" />
            </div>
            <div class="field">
              <label for="email">Email: </label>
              <my-inputText id="email" type="email" v-model="state.email" />
            </div>
          </div>
        </div>
        <div class="avatar">
          <div class="flex justify-content-between">
            <div class="avt-wrap">
              <div class="img">
                <img src="@/assets/img/background-login.jpg" />
                <label class="upload" for="avatar">
                  <i class="pi pi-camera" style="font-size: 5rem"></i>
                  <input id="avatar" type="file" class="hidden" />
                </label>
              </div>
              <span class="name">{{ state.name }}</span>
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
          class="p-button-text w-2"
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
import { helpers, required } from "@vuelidate/validators";
import { ICustomer } from "@/interface/auth/authentication.state";

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
      name: "Nguyễn Hoàng Thanh Toàn",
      phone: "0123456789",
      email: "toan@gmail.com",
      gender: "woman",
      birth: "01-01-2000",
      avatar: "",
    });
    const genders = ref([
      {
        name: "Nam",
        key: "man",
      },
      {
        name: "Nữ",
        key: "woman",
      },
      {
        name: "Khác",
        key: "other",
      },
    ]);

    const rules = {
      name: {
        required: helpers.withMessage("Tên không được trống", required),
      },
      phone: {
        required: helpers.withMessage(
          "Số điện thoại không được trống",
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
      genders,
      v$,
      state,
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
    }

    :deep(.p-inputtext) {
      font-size: 1.6rem !important;
    }

    :deep(.p-component:disabled) {
      opacity: 0.8 !important;
    }
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

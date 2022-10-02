<template>
  <form class="personal">
    <div class="information">
      <div class="head">
        <h2>THÔNG TIN CÁ NHÂN</h2>
      </div>
      <div class="p-fluid formgrid">
        <div class="field">
          <label for="username">Họ và tên: </label>
          <my-inputText
            id="username"
            type="username"
            v-model="info.name"
            :disabled="!canEdit"
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
              v-model="info.gender"
              :disabled="!canEdit"
            />
            <label :for="gender.key">{{ gender.name }}</label>
          </div>
        </div>
        <div class="field">
          <label for="dateformat">Ngày sinh: </label>
          <my-inputText
            id="dateformat"
            v-model="info.birth"
            :disabled="!canEdit"
          />
        </div>
        <div class="field">
          <label for="phone">Số điện thoại: </label>
          <my-inputText id="phone" v-model="info.phone" :disabled="!canEdit" />
        </div>
        <div class="field">
          <label for="email">Email: </label>
          <my-inputText
            id="email"
            type="email"
            v-model="info.email"
            :disabled="!canEdit"
          />
        </div>
        <div class="field">
          <label for="address">Địa chỉ: </label>
          <Textarea
            id="address"
            v-model="info.address"
            :autoResize="true"
            rows="5"
            cols="30"
            :disabled="!canEdit"
          />
        </div>
      </div>
    </div>
    <div class="avatar">
      <div class="flex justify-content-between">
        <div class="avt-wrap">
          <div class="img">
            <img src="@/assets/img/background-login.jpg" />
            <label class="upload" for="avatar" v-if="canEdit">
              <i class="pi pi-camera" style="font-size: 5rem"></i>
              <input id="avatar" type="file" class="hidden" />
            </label>
          </div>
          <span class="name">{{ info.name }}</span>
          <span class="email">{{ info.email }}</span>
        </div>
        <div class="btn-edit" @click="handleEdit" :class="{ active: canEdit }">
          <img
            src="@/assets/img/icons/edit-personal.png"
            width="25"
            alt="edit-personal"
          />
        </div>
      </div>
      <div class="btn" v-if="canEdit">
        <my-button label="Lưu Thông tin" class="p-button-raised" />
        <my-button
          label="Hủy Bỏ"
          class="p-button-raised p-button-danger"
          @click="handleEdit"
        />
      </div>
    </div>
  </form>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from "vue";
import RadioButton from "primevue/radiobutton";
import Textarea from "primevue/textarea";

export default defineComponent({
  components: {
    RadioButton,
    Textarea,
  },
  setup() {
    const canEdit = ref(false);
    const info = reactive({
      name: "Nguyễn Hoàng Thanh Toàn",
      phone: "0123456789",
      email: "toan@gmail.com",
      gender: "woman",
      birth: "01-01-2000",
      address: "Hẻm nào đó, Xã Gì Đó, Quận Gì Đó, TP Cần Thơ",
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
    const handleEdit = () => {
      canEdit.value = !canEdit.value;
    };
    return {
      info,
      genders,
      canEdit,
      handleEdit,
    };
  },
});
</script>

<style lang="scss" scoped>
.personal {
  display: flex;
  margin: 3rem 5rem;

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

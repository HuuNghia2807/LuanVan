<template>
  <div>
    <TheLoader :is-loading="showLoading" />

    <div class="customer">
      <div class="left-info">
        <div class="avt-wrap">
          <div class="img">
            <Image
              :src="
                customer.avatar ||
                require('@/assets/img/avatar_default/default-avatar.png')
              "
              preview
            />
            <div class="btn-upload">
              <label class="upload" for="avatar">
                Upload
                <i class="pi pi-image" style="font-size: 1.6rem"></i>
              </label>
              <input id="avatar" type="file" class="hidden" />
            </div>
          </div>
          <div class="name">
            <span class="name-info">{{ customer.fullName }}</span>
            <span class="email">{{ customer.email }}</span>
          </div>
        </div>

        <div class="order">
          <TableOrderCustomerCpn
            :orders="personalOrder"
            @cancel-order="cancelOrder"
          />
        </div>
      </div>
      <div class="right-info">
        <div class="information">
          <div class="head">
            <h2>THÔNG TIN CÁ NHÂN</h2>
            <div
              class="btn-edit"
              @click="handleEdit"
              :class="{ active: canEdit }"
            >
              <img
                src="@/assets/img/icons/edit-personal.png"
                width="25"
                alt="edit-personal"
              />
            </div>
          </div>
          <form class="p-fluid formgrid">
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
            <div class="flex align-item-center">
              <div class="field mr-2">
                <label for="dateformat">Ngày sinh: </label>
                <my-inputText
                  id="dateformat"
                  v-model="info.birth"
                  :disabled="!canEdit"
                />
              </div>
              <div class="field">
                <label for="phone">Số điện thoại: </label>
                <my-inputText
                  id="phone"
                  v-model="info.phone"
                  :disabled="!canEdit"
                />
              </div>
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
            <div
              class="field flex"
              v-for="(add, i) in customer.address"
              :key="add.address_id"
            >
              <div class="w-10">
                <label class="font-semibold">Địa chỉ {{ i }}: </label>
                <span class="ml-2 text-2xl">{{
                  `${add.address}, 
                ${add.ward.ward},       
                ${add.district.district},
                ${add.city.city}`
                }}</span>
              </div>
              <span
                class="text-2xl text-teal-500 w-2 text-right cursor-pointer"
                @click="openModal(add)"
                >Thay đổi</span
              >
            </div>
          </form>
        </div>
      </div>
    </div>
    <UploadAddress :display-modal="displayModal" @close-modal="closeModal" />
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref, reactive } from "vue";
import Image from "primevue/image";
import { computed } from "@vue/reactivity";
import { useStore } from "vuex";
import { getItemLocal } from "@/function/handleLocalStorage";
import {
  IAddressCustomer,
  ICustomer,
} from "@/interface/auth/authentication.state";
import TableOrderCustomerCpn from "@/components/order/TableOrderCustomerCpn.vue";
import TheLoader from "@/components/common/TheLoader.vue";
import { IOrders } from "@/interface/order/order.state";
import RadioButton from "primevue/radiobutton";
import UploadAddress from "@/components/modal/UploadAddress.vue";

export default defineComponent({
  components: {
    Image,
    TableOrderCustomerCpn,
    TheLoader,
    RadioButton,
    UploadAddress,
  },
  setup() {
    const store = useStore();
    const showLoading = ref(false);
    const personalOrder = ref([] as IOrders[]);
    const personalAddress = ref([] as any[]);
    const displayModal = ref(false);
    const addressModal = ref({});
    const canEdit = ref(false);
    const customer = computed(() => {
      return (store.getters["auth/getUser"] ||
        getItemLocal("customer") ||
        null) as ICustomer;
    });
    const info = reactive({
      name: customer.value.fullName,
      phone: customer.value.phone,
      email: customer.value.email,
      gender: "woman",
      birth: customer.value.birth,
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

    const cancelOrder = (order_id: number) => {
      console.log("----", order_id);
    };
    const handleEdit = () => {
      canEdit.value = !canEdit.value;
    };
    const openModal = (add: IAddressCustomer) => {
      addressModal.value = add;
      displayModal.value = true;
    };
    const closeModal = () => {
      addressModal.value = {};
      displayModal.value = false;
    };
    onMounted(async () => {
      showLoading.value = true;
      personalOrder.value = await store.dispatch(
        "order/getPersonalOrder",
        customer.value.id
      );
      personalAddress.value = await store.dispatch(
        "auth/getPersonalAddress",
        customer.value.id
      );
      showLoading.value = false;
    });
    return {
      customer,
      showLoading,
      personalOrder,
      canEdit,
      info,
      genders,
      displayModal,
      closeModal,
      openModal,
      handleEdit,
      cancelOrder,
    };
  },
});
</script>

<style lang="scss" scoped>
.customer {
  min-height: 90vh;
  display: flex;
  padding: 2rem 5rem;
  background-color: #efe4d3;

  :deep(.p-component) {
    font-size: 1.6rem !important;
  }

  .left-info {
    width: 60%;
    margin-right: 1rem;

    .avt-wrap {
      display: flex;
      border-radius: 10px;
      padding: 2rem;
      background-color: #fff;
      margin-bottom: 2rem;

      .img {
        margin-left: 4rem;
      }

      :deep(.p-image-preview-container > img),
      :deep(.p-image-preview-indicator) {
        width: 10rem;
        height: 10rem;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid #ccc;
      }

      .btn-upload {
        margin: 1rem 0;
        display: flex;
        justify-content: center;
        align-items: center;

        .upload {
          border: 1px solid #ccc;
          background-color: var(--green-400);
          color: #fff;
          font-weight: 600;
          padding: 0.5rem 2rem;
          border-radius: 5px;
        }
      }

      .name {
        display: flex;
        flex-direction: column;
        margin: 2rem 0 0 2.5rem;

        span {
          display: block;
          font-size: 1.8rem;
          color: var(--black-color);
          margin-bottom: 0.5rem;
        }

        .name-info {
          font-weight: 700;
          font-size: 2.5rem;
        }

        .email {
          margin-left: 1rem;
          color: #3845fb;
        }
      }
    }
  }

  .right-info {
    flex: 1;
    background-color: #fff;
    border-radius: 10px;
    padding: 2rem;

    .head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 2rem;
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
    }

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
      opacity: 1 !important;
    }
  }
}
</style>

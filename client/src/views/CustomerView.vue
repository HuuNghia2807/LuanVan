<template>
  <div>
    <TheLoader :is-loading="showLoading" />

    <div class="customer">
      <div class="left-info">
        <div class="avt-wrap">
          <div class="img">
            <Image
              :src="
                customer?.avatar ||
                require('@/assets/img/avatar_default/default-avatar.png')
              "
              preview
            />
          </div>
          <div class="name">
            <span class="name-info">{{ customer?.fullName }}</span>
            <span class="email">{{ customer?.email }}</span>
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
            <div class="flex align-items-center">
              <div
                class="btn-edit mr-3"
                v-tooltip="'Đổi mật khẩu'"
                @click="openModalChangePass"
              >
                <img
                  src="@/assets/img/icons/password.png"
                  width="25"
                  alt="edit-personal"
                />
              </div>
              <div
                class="btn-edit"
                @click="openModalEdit"
                v-tooltip="'Chỉnh sửa thông tin cá nhân'"
              >
                <img
                  src="@/assets/img/icons/edit-personal.png"
                  width="25"
                  alt="edit-personal"
                />
              </div>
            </div>
          </div>
          <form class="p-fluid formgrid">
            <div class="field">
              <label for="username">Họ và tên: </label>
              <span>{{ customer?.fullName }}</span>
            </div>
            <div class="field">
              <label>Giới tính:</label>
              <span>{{ customer?.gender }}</span>
            </div>
            <div class="field">
              <label for="dateformat">Ngày sinh: </label>
              <span>{{ customer?.birth }}</span>
            </div>
            <div class="field">
              <label for="phone">Số điện thoại: </label>
              <span>{{ customer?.phone }}</span>
            </div>
            <hr class="line" />
            <div
              class="flex mb-3"
              v-for="(add, i) in customer?.address"
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
    <UploadAddress
      v-if="displayModal"
      :display-modal="displayModal"
      :address="addressModal"
      @close-modal="closeModal"
      @update-address="handleUpdateAddress"
    />
    <EditInfomation
      v-if="isEditInfo"
      :is-edit-info="isEditInfo"
      :customer="customer"
      @close-modal="closeModal"
    />
    <ChangePassword
      v-if="isChangePass"
      :is-change-pass="isChangePass"
      @close-modal="closeModal"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
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
import { useRouter } from "vue-router";
import UploadAddress from "@/components/modal/UploadAddress.vue";
import EditInfomation from "@/components/modal/EditInfomation.vue";
import ChangePassword from "@/components/modal/ChangePassword.vue";

export default defineComponent({
  components: {
    Image,
    TableOrderCustomerCpn,
    TheLoader,
    UploadAddress,
    EditInfomation,
    ChangePassword,
  },
  setup() {
    const router = useRouter();
    const store = useStore();
    const showLoading = ref(false);
    const personalOrder = ref([] as IOrders[]);
    const displayModal = ref(false);
    const isEditInfo = ref(false);
    const isChangePass = ref(false);
    const addressModal = ref<IAddressCustomer>();
    const customer = computed(() => {
      return (store.getters["auth/getUser"] ||
        getItemLocal("customer") ||
        null) as ICustomer;
    });

    const cancelOrder = (order_id: number) => {
      console.log("----", order_id);
    };
    const openModalEdit = () => {
      isEditInfo.value = true;
    };
    const openModalChangePass = () => {
      isChangePass.value = true;
    };
    const openModal = (add: IAddressCustomer) => {
      addressModal.value = add;
      displayModal.value = true;
    };
    const closeModal = () => {
      addressModal.value = undefined;
      displayModal.value = false;
      isEditInfo.value = false;
      isChangePass.value = false;
    };
    const handleUpdateAddress = (address_id: number, state: any) => {
      console.log("-----", address_id, state);
    };
    onMounted(async () => {
      showLoading.value = true;
      personalOrder.value = await store.dispatch(
        "order/getPersonalOrder",
        customer.value.id
      );
      await store.dispatch("order/getProvince");
      if (!store.getters["auth/getIslogged"]) {
        router.push("/");
      }
      showLoading.value = false;
    });
    return {
      customer,
      showLoading,
      personalOrder,
      displayModal,
      addressModal,
      isEditInfo,
      isChangePass,
      openModalChangePass,
      handleUpdateAddress,
      closeModal,
      openModal,
      openModalEdit,
      cancelOrder,
    };
  },
});
</script>

<style lang="scss" scoped>
.customer {
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
        width: 14rem;
        height: 14rem;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid #ccc;
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
    // height: 50rem;

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

    .field {
      margin: 1rem 0;
      display: flex;
      align-items: center;

      label {
        font-weight: 600;
        width: 25%;
        margin-bottom: 0;
      }

      span {
        display: block;
        width: 80%;
        padding: 0.5rem 1rem;
        background-color: #bdc2ff;
        border-radius: 5px;
        height: 3rem;
      }
    }

    .line {
      margin: 2rem 3rem;
    }

    :deep(.p-inputtext) {
      font-size: 1.6rem !important;
    }
  }
}
</style>

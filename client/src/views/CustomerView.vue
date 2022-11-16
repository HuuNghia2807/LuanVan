<template>
  <div>
    <TheLoader :is-loading="showLoading" />
    <my-toast />
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
            @view-order-detail="handleOrderDetail"
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
                <label class="font-semibold">Địa chỉ {{ i + 1 }}: </label>
                <span class="ml-2 text-2xl">{{
                  `${add.address}, 
                ${add.ward.ward},       
                ${add.district.district},
                ${add.city.city}`
                }}</span>
              </div>
              <span
                class="text-2xl text-teal-500 w-2 text-right cursor-pointer"
                @click="openModalUpdateAddress(add)"
                >Thay đổi</span
              >
            </div>
          </form>
        </div>
      </div>
    </div>
    <UploadAddress
      v-if="isUpdateAddress"
      :display-modal="isUpdateAddress"
      :address="addressModal"
      @close-modal="closeModal"
      @update-address="handleUpdateAddress"
    />
    <EditInfomation
      v-if="isEditInfo"
      :is-edit-info="isEditInfo"
      :customer="customer"
      @close-modal="closeModal"
      @update-info="updateInfo"
    />
    <ChangePassword
      v-if="isChangePass"
      :customer="customer"
      :is-change-pass="isChangePass"
      @close-modal="closeModal"
      @change-pass-success="handleChangePass"
    />
    <ConfirmCancelOrder
      v-if="isConfirm"
      :is-confirm="isConfirm"
      @close-modal="closeModal"
      @cancel-order="handleCancelOrder"
    />
    <OrderDetailCpn
      v-if="isOrderDetail"
      :is-order-detail="isOrderDetail"
      :order="orderDetail"
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
  IUpdateAddressParams,
  IUpdateInfoCustomerParams,
} from "@/interface/auth/authentication.state";
import {
  IOrders,
  IUpdateStatusOrderParams,
} from "@/interface/order/order.state";
import { useRouter } from "vue-router";
import { useToast } from "primevue/usetoast";
import TableOrderCustomerCpn from "@/components/order/TableOrderCustomerCpn.vue";
import TheLoader from "@/components/common/TheLoader.vue";
import UploadAddress from "@/components/modal/UploadAddress.vue";
import EditInfomation from "@/components/modal/EditInfomation.vue";
import ChangePassword from "@/components/modal/ChangePassword.vue";
import ConfirmCancelOrder from "@/components/modal/ConfirmCancelOrder.vue";
import OrderDetailCpn from "@/components/order/OrderDetailCpn.vue";

export default defineComponent({
  components: {
    Image,
    TableOrderCustomerCpn,
    TheLoader,
    UploadAddress,
    EditInfomation,
    ChangePassword,
    ConfirmCancelOrder,
    OrderDetailCpn,
  },
  setup() {
    const router = useRouter();
    const store = useStore();
    const toast = useToast();
    const showLoading = ref(false);
    const personalOrder = ref([] as IOrders[]);
    const isUpdateAddress = ref(false);
    const isEditInfo = ref(false);
    const isChangePass = ref(false);
    const isConfirm = ref(false);
    const idOrderCancel = ref();
    const addressModal = ref<IAddressCustomer>();
    const orderDetail = ref<IOrders>();
    const isOrderDetail = ref(false);
    const customer = computed(() => {
      return (store.getters["auth/getUser"] ||
        getItemLocal("customer") ||
        null) as ICustomer;
    });

    const cancelOrder = (orderId: number) => {
      idOrderCancel.value = orderId;
      isConfirm.value = true;
    };
    const handleCancelOrder = (note: string) => {
      const data = {
        order_id: idOrderCancel.value,
        status_id: 5,
        note: note,
      } as IUpdateStatusOrderParams;
      isConfirm.value = false;
      updateStatus(data);
    };
    const updateStatus = async (data: IUpdateStatusOrderParams) => {
      showLoading.value = true;
      await store.dispatch("order/handleStatusOrder", data);
      showLoading.value = false;
      const error = store.getters["order/getError"];
      if (error) {
        toast.add({
          severity: "error",
          summary: "Thất bại",
          detail: "Xử lý thất bại!",
          life: 3000,
        });
        return;
      }
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Xử lý thành công!",
        group: "br",
        life: 3000,
      });
      loadPage();
    };
    const openModalEdit = () => {
      isEditInfo.value = true;
    };
    const openModalChangePass = () => {
      isChangePass.value = true;
    };
    const openModalUpdateAddress = (add: IAddressCustomer) => {
      addressModal.value = add;
      isUpdateAddress.value = true;
    };
    const closeModal = () => {
      addressModal.value = undefined;
      idOrderCancel.value = undefined;
      orderDetail.value = undefined;
      isUpdateAddress.value = false;
      isEditInfo.value = false;
      isChangePass.value = false;
      isConfirm.value = false;
      isOrderDetail.value = false;
    };
    const handleUpdateAddress = async (address_id: number, state: any) => {
      isUpdateAddress.value = false;
      await store.dispatch("auth/updateAddress", {
        address_id,
        address: state.address,
        ward_id: state.ward,
      } as IUpdateAddressParams);
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Cập nhật thông tin thành công",
        life: 3000,
      });
      loadPage();
    };
    const updateInfo = async (data: any) => {
      showLoading.value = true;
      await store.dispatch("auth/updateInfo", {
        customer_id: customer.value.id,
        info: {
          first_name: data.firstName,
          last_name: data.lastName,
          email: data.email,
          gender: data.gender,
          birth: data.birth,
          phone: data.phone,
          avatar: data.avatar,
        },
      } as IUpdateInfoCustomerParams);
      if (store.getters["auth/getError"]) {
        toast.add({
          severity: "error",
          summary: "Thất bại",
          detail: "Cập nhật bị lỗi vui lòng kiểm tra và thử lại",
          life: 3000,
        });
        showLoading.value = false;
        return;
      }
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Cập nhật thông tin thành công",
        life: 3000,
      });
      showLoading.value = false;
      isEditInfo.value = false;
    };

    const handleChangePass = () => {
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Thay đổi mật khẩu thành công",
        life: 3000,
      });
    };

    const handleOrderDetail = (order: IOrders) => {
      orderDetail.value = order;
      isOrderDetail.value = true;
    };

    const loadPage = async () => {
      showLoading.value = true;
      personalOrder.value = await store.dispatch(
        "order/getPersonalOrder",
        customer.value.id
      );
      await store.dispatch("order/getProvince");
      await store.dispatch("auth/getCustomer", customer.value.id);
      if (!store.getters["auth/getIslogged"]) {
        router.push("/");
      }
      showLoading.value = false;
    };
    onMounted(() => {
      loadPage();
    });
    return {
      customer,
      showLoading,
      personalOrder,
      isUpdateAddress,
      addressModal,
      isEditInfo,
      isChangePass,
      isConfirm,
      isOrderDetail,
      orderDetail,
      handleOrderDetail,
      handleCancelOrder,
      handleChangePass,
      updateInfo,
      openModalChangePass,
      handleUpdateAddress,
      closeModal,
      openModalUpdateAddress,
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
    min-height: 80vh;

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

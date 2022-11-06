<template>
  <my-dialog
    header="Chi tiết đơn hàng"
    :visible="isOrderDetail"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '45vw' }"
    :modal="true"
  >
    <div class="order-detail">
      <div class="head">
        <img src="@/assets/img/logo-2.png" alt="logo" />
        <div class="flex align-items-center flex-column">
          <span>[cskh.kingshoes.vn@gmail.com]</span>
          <span>[0902.368.001]</span>
        </div>
      </div>
      <div class="title">
        <div class="item">
          <label>Mã đơn hàng: </label>
          <span>{{ order?.orderId }}</span>
        </div>
        <div class="item">
          <label>Tên khách hàng: </label>
          <span>{{ order?.customer.fullName }}</span>
        </div>
        <div class="item">
          <label>Số điện thoại: </label>
          <span>{{ order?.customer.phone }}</span>
        </div>
        <div class="item">
          <label>Nhân viên BH: </label>
          <span>{{ order?.employee || "Chưa xác nhận" }}</span>
        </div>
        <div class="flex align-items-center w-full">
          <div class="item w-6 mr-3">
            <label class="w-4">Ngày ĐH: </label>
            <span>{{
              translateUnixTimeToFullTime(order?.orderTime || 99999)
            }}</span>
          </div>
          <div class="item w-6">
            <label class="w-4">Ngày NH: </label>
            <span>{{
              order?.receiveTime
                ? translateUnixTimeToFullTime(order?.receiveTime)
                : "chưa xác định"
            }}</span>
          </div>
        </div>
      </div>
      <div class="address">
        <label>Địa chỉ:</label>
        <span>{{ order?.address }}</span>
      </div>
      <div class="products">
        <DataTable :value="order?.productOrder" responsiveLayout="scroll">
          <my-column
            v-for="col of columns"
            :field="col.field"
            :header="col.header"
            :key="col.field"
            :style="{ width: col.width }"
          >
            <template v-if="col.field === 'productImage'" #body="{ data }">
              <img :src="data[col.field]" alt="anh" class="w-6rem h-6rem" />
            </template>
          </my-column>
          <my-column header="Giá" key="gia" :style="{ width: '15rem' }">
            <template #body="{ data }">
              {{ formatPrice(data.productPrice) }}
            </template>
          </my-column>
          <my-column
            header="Thành tiền"
            key="thanhtien"
            :style="{ width: '10rem' }"
          >
            <template #body="{ data }">
              <span class="text-red">{{
                formatPrice(data.productPrice * data.productOrderQuantity)
              }}</span>
            </template>
          </my-column>
        </DataTable>
      </div>
      <div class="prices">
        <div class="price">
          <label>Phí vận chuyển:</label>
          <span class="text-red">{{ formatPrice(20000) }}</span>
        </div>
        <div class="price">
          <label>Tổng hóa đơn:</label>
          <span class="text-red font-bold">{{
            formatPrice(order?.totalPrice || 9999999)
          }}</span>
        </div>
      </div>
    </div>
    <template #footer>
      <my-button
        label="close"
        icon="pi pi-times"
        @click="closeModal"
        class="p-button-outlined"
      />
    </template>
  </my-dialog>
</template>

<script lang="ts">
import { formatPrice, translateUnixTimeToFullTime } from "@/function/common";
import { IOrders } from "@/interface/order/order.state";
import { defineComponent, PropType, ref } from "vue";

export default defineComponent({
  components: {},
  props: {
    isOrderDetail: { type: Boolean, default: false },
    order: { type: Object as PropType<IOrders> },
  },
  setup(props, { emit }) {
    const columns = ref([
      { field: "productCode", header: "Mã SP", width: "15rem" },
      { field: "productName", header: "Tên SP", width: "20rem" },
      { field: "productImage", header: "Ảnh SP", width: "10rem" },
      { field: "productSize", header: "Size", width: "10rem" },
      { field: "productOrderQuantity", header: "Số lượng", width: "10rem" },
    ]);
    const closeModal = () => {
      emit("close-modal");
    };
    return {
      columns,
      closeModal,
      formatPrice,
      translateUnixTimeToFullTime,
    };
  },
});
</script>

<style lang="scss" scoped>
.order-detail {
  :deep(.p-component) {
    font-size: 1.2rem !important;
  }
  .text-red {
    color: red;
    font-weight: 600;
  }
  span {
    font-size: 1.5rem;
  }
  .head {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    img {
      width: 7rem;
      height: 7rem;
      object-fit: contain;
    }
    span {
      font-weight: 600;
    }
  }

  .title {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 0 1rem;
    .item {
      margin: 0.2rem;
      display: flex;
      align-items: center;
    }

    label {
      width: 20%;
      font-size: 1.5rem;
      font-weight: 700;
    }

    span {
      width: 80%;
      font-style: italic;
    }
  }
  .address {
    margin: 0.5rem 1rem;
    display: flex;
    align-items: center;

    label {
      width: 12%;
      font-size: 1.6rem;
      font-weight: 700;
      margin-right: 0.5rem;
    }

    span {
      width: 88%;
      font-size: 1.5rem;
      background-color: rgb(185, 255, 232);
      border-radius: 10px;
      padding: 0.5rem 1rem;
    }
  }
  .prices {
    display: flex;
    flex-direction: column;
    justify-content: center;

    .price {
      display: flex;
      justify-content: end;
      margin-bottom: 1rem;
      label {
        width: 15rem;
        font-weight: 600;
        font-size: 1.6rem;
        text-align: left;
      }
      span {
        width: 10rem;
        font-size: 1.6rem;
        text-align: right;
      }
    }
  }
}
</style>

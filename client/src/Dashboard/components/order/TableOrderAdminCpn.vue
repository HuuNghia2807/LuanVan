<template>
  <DataTable
    :value="orders"
    :paginator="true"
    class="p-datatable-customers"
    :rows="9"
    dataKey="id"
    :rowHover="true"
    v-model:filters="filters"
    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} entries"
    responsiveLayout="scroll"
  >
    <template #header>
      <div class="flex justify-content-between align-items-center">
        <h3 class="m-0">Danh Sách Đơn Hàng</h3>
        <span class="p-input-icon-left">
          <i class="pi pi-search" />
          <my-inputText
            v-model="filters['global'].value"
            placeholder="Keyword Search"
          />
        </span>
      </div>
    </template>
    <template #empty> No orders found. </template>
    <my-column
      v-for="col of columns"
      :field="col.field"
      :header="col.header"
      :key="col.field"
      :style="{ width: col.width }"
    >
      <template v-if="col.field === 'customer'" #body="{ data }">
        {{ data[col.field].fullName }}
      </template>
    </my-column>
    <my-column header="Điện Thoại" :style="{ width: '12rem' }">
      <template #body="slotProps">
        {{ slotProps.data.customer.phone }}
      </template>
    </my-column>
    <my-column header="Tổng" :style="{ width: '12rem' }">
      <template #body="slotProps">
        <span class="text-green-500">{{
          formatPrice(slotProps.data.totalPrice)
        }}</span>
      </template>
    </my-column>
    <my-column header="Trạng Thái" :style="{ width: '12rem' }">
      <template #body="slotProps">
        <span :class="'order-badge status-' + slotProps.data.status">{{
          slotProps.data.status.toUpperCase()
        }}</span>
      </template>
    </my-column>
    <my-column header="Thời Gian Đặt Hàng" :style="{ width: '20rem' }">
      <template #body="slotProps">
        {{ translateUnixTimeToFullTime(slotProps.data.orderTime) }}
      </template>
    </my-column>
    <my-column
      v-if="
        option !== 'new' && option !== 'processing' && option !== 'shipping'
      "
      header="Thời Gian Nhận Hàng"
      :style="{ width: '22rem' }"
    >
      <template #body="slotProps">
        {{
          slotProps.data.receiveTime
            ? translateUnixTimeToFullTime(slotProps.data.receiveTime)
            : "none"
        }}
      </template>
    </my-column>
    <my-column
      headerStyle="width: 4rem; text-align: center"
      bodyStyle="text-align: center; overflow: visible"
    >
      <template #body="slotProps">
        <div class="flex justify-content-between align-items-center">
          <template v-if="option === 'new'">
            <my-button
              type="button"
              icon="pi pi-check"
              class="p-button-rounded p-button-info"
              @click="acceptOrder(slotProps.data)"
            ></my-button>
          </template>
          <template v-if="option === 'processing'">
            <div
              class="processing"
              v-tooltip="'Vận chuyển đơn hàng'"
              @click="deliveryOrder(slotProps.data)"
            >
              <img src="@/assets/img/icons/delivery-icon.png" />
            </div>
          </template>
          <template v-if="option === 'shipping'">
            <my-button
              type="button"
              icon="pi pi-verified"
              class="p-button-rounded p-button-success"
              @click="completeOrder(slotProps.data)"
            ></my-button>
          </template>
          <my-button
            type="button"
            icon="pi pi-eye"
            class="p-button-rounded p-button-success"
          ></my-button>
          <my-button
            v-if="option === 'new' || option === 'processing'"
            type="button"
            icon="pi pi-times"
            class="p-button-rounded p-button-danger"
            @click="cancelOrder(slotProps.data)"
          ></my-button>
        </div>
      </template>
    </my-column>
  </DataTable>
</template>

<script lang="ts">
import { defineComponent, PropType, ref } from "vue";
import { formatPrice, translateUnixTimeToFullTime } from "@/function/common";
import { FilterMatchMode } from "primevue/api";
import { IOrders } from "@/interface/order/order.state";

export default defineComponent({
  props: {
    orders: { type: Object as PropType<IOrders[]> },
    option: { type: String },
  },
  setup(props, { emit }) {
    const columns = ref([
      { field: "orderId", header: "Mã ĐH", width: "10rem" },
      { field: "customer", header: "Khách Hàng", width: "18rem" },
      { field: "payment", header: "Thanh Toán", width: "12rem" },
    ]);
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });
    const acceptOrder = (order: IOrders) => {
      emit("accept-order", order.orderId);
    };
    const deliveryOrder = (order: IOrders) => {
      emit("delivery-order", order.orderId);
    };
    const completeOrder = (order: IOrders) => {
      emit("complete-order", order.orderId);
    };
    const cancelOrder = (order: IOrders) => {
      emit("cancel-order", order.orderId);
    };
    return {
      columns,
      filters,
      cancelOrder,
      completeOrder,
      deliveryOrder,
      acceptOrder,
      formatPrice,
      translateUnixTimeToFullTime,
    };
  },
});
</script>

<style lang="scss" scoped>
.order-badge {
  border-radius: 2px;
  padding: 0.25em 0.5rem;
  text-transform: uppercase;
  font-weight: 700;
  font-size: 12px;
  letter-spacing: 0.3px;
}

.order-badge.status-delivered {
  background-color: #c8e6c9;
  color: #256029;
}

.order-badge.status-processing {
  background-color: #a1a2ed;
  color: #373ec6;
}

.order-badge.status-shipping {
  background-color: #aaf5b8;
  color: #1ccb16;
}

.order-badge.status-new {
  background-color: #edee9a;
  color: #cbbf16;
}

.order-badge.status-canceled {
  background-color: #fddde0;
  color: #c63737;
}

.processing {
  border-radius: 50%;
  margin-right: 1rem;
  cursor: pointer;
  &:hover {
    background-color: #fddde0;
  }
  img {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
  }
}

:deep(.p-paginator) {
  .p-paginator-current {
    margin-left: auto;
  }
}

:deep(.p-progressbar) {
  height: 0.5rem;
  background-color: #d8dadc;

  .p-progressbar-value {
    background-color: #607d8b;
  }
}

:deep(.p-datepicker) {
  td {
    font-weight: 400;
  }
}

:deep(.p-datatable.p-datatable-customers) {
  .p-datatable-header {
    padding: 1rem;
    text-align: center;
    font-size: 1.5rem;
  }

  .p-paginator {
    padding: 1rem;
  }

  .p-datatable-thead > tr > th {
    text-align: center;
  }

  .p-datatable-tbody > tr > td {
    cursor: auto;
  }

  .p-dropdown-label:not(.p-placeholder) {
    text-transform: uppercase;
  }
}
</style>

<template>
  <div class="order">
    <DataTable
      :value="orders"
      :paginator="true"
      class="p-datatable-customers"
      :rows="6"
      dataKey="id"
      :rowHover="true"
      v-model:filters="filters"
      :loading="loading"
      paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
      currentPageReportTemplate="Showing {first} to {last} of {totalRecords} entries"
      responsiveLayout="scroll"
    >
      <template #header>
        <div class="flex justify-content-between align-items-center">
          <h3 class="m-0">Danh Sách Hóa Đơn</h3>
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
      <template #loading> Loading orders data. Please wait. </template>
      <Column field="id" header="Mã hóa đơn" style="width: 8rem">
        <template #body="{ data }">
          {{ data.id }}
        </template>
      </Column>
      <Column header="Ảnh sản phẩm" field="productImage" style="width: 15rem">
        <template #body="{ data }">
          <img
            class="img"
            src="https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-max-90-love-letter-dd8029-100-king-shoes-sneaker-real-hcm-12-1638423994.jpeg"
          />
          <span class="hidden">{{ data.productImage }}</span>
        </template>
      </Column>
      <Column header="Tên sản phẩm" field="productName" style="width: 15rem">
        <template #body="{ data }">
          {{ data.productName }}
        </template>
      </Column>
      <Column field="quantity" header="Số Lượng" style="width: 10rem">
        <template #body="{ data }">
          {{ data.quantity }}
        </template>
      </Column>
      <Column field="totalPrice" header="Tổng hóa đơn" style="width: 10rem">
        <template #body="{ data }">
          {{ data.totalPrice }}
        </template>
      </Column>
      <Column field="status" header="Trạng thái" style="width: 10rem">
        <template #body="{ data }">
          <span :class="'order-badge status-' + data.status">{{
            data.status
          }}</span>
        </template>
      </Column>
      <Column
        field="payment"
        header="Phương thức thanh toán"
        style="width: 10rem"
      >
        <template #body="{ data }">
          {{ data.payment }}
        </template>
      </Column>

      <Column
        headerStyle="width: 4rem; text-align: center"
        bodyStyle="text-align: center; overflow: visible"
      >
        <template #body>
          <div class="flex justify-content-between align-items-center">
            <my-button
              type="button"
              icon="pi pi-check"
              class="p-button-rounded p-button-info"
            ></my-button>
            <my-button
              type="button"
              icon="pi pi-eye"
              class="p-button-rounded p-button-success"
            ></my-button>
            <my-button
              type="button"
              icon="pi pi-times"
              class="p-button-rounded p-button-danger"
            ></my-button>
          </div>
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { FilterMatchMode } from "primevue/api";
import DataTable from "primevue/datatable";
import Column from "primevue/column";

export default defineComponent({
  components: {
    DataTable,
    Column,
  },
  setup() {
    const orders = ref([
      {
        id: "1",
        productImage:
          "https://kingshoes.vn/data/upload/media/nike-air-force-1-volt-neon-db1555-100-king-shoes-sneaker-real-hcm-1624255364.jpeg",
        productName: "Jordan 1234",
        productPrice: "100000000",
        quantity: "2",
        totalPrice: "200000000",
        status: "unconfimred",
        payment: "Thanh toan tien mat",
        address: "Phu Thuan, Thoai Son, An Giang",
        note: "bla bla bla",
      },
      {
        id: "2",
        productImage:
          "https://kingshoes.vn/data/upload/media/nike-air-force-1-volt-neon-db1555-100-king-shoes-sneaker-real-hcm-1624255364.jpeg",
        productName: "Jordan 1234",
        productPrice: "100000000",
        quantity: "2",
        totalPrice: "200000000",
        status: "confimred",
        payment: "Thanh toan online",
        address: "Phu Thuan, Thoai Son, An Giang",
        note: "bla bla bla",
      },
      {
        id: "1",
        productImage:
          "https://kingshoes.vn/data/upload/media/nike-air-force-1-volt-neon-db1555-100-king-shoes-sneaker-real-hcm-1624255364.jpeg",
        productName: "Jordan 1234",
        productPrice: "100000000",
        quantity: "2",
        totalPrice: "200000000",
        status: "confimred",
        payment: "Thanh toan tien mat",
        address: "Phu Thuan, Thoai Son, An Giang",
        note: "bla bla bla",
      },
      {
        id: "2",
        productImage:
          "https://kingshoes.vn/data/upload/media/nike-air-force-1-volt-neon-db1555-100-king-shoes-sneaker-real-hcm-1624255364.jpeg",
        productName: "Jordan 1234",
        productPrice: "100000000",
        quantity: "2",
        totalPrice: "200000000",
        status: "unconfimred",
        payment: "Thanh toan online",
        address: "Phu Thuan, Thoai Son, An Giang",
        note: "bla bla bla",
      },
      {
        id: "1",
        productImage:
          "https://kingshoes.vn/data/upload/media/nike-air-force-1-volt-neon-db1555-100-king-shoes-sneaker-real-hcm-1624255364.jpeg",
        productName: "Jordan 1234",
        productPrice: "100000000",
        quantity: "2",
        totalPrice: "200000000",
        status: "unconfimred",
        payment: "Thanh toan tien mat",
        address: "Phu Thuan, Thoai Son, An Giang",
        note: "bla bla bla",
      },
      {
        id: "2",
        productImage:
          "https://kingshoes.vn/data/upload/media/nike-air-force-1-volt-neon-db1555-100-king-shoes-sneaker-real-hcm-1624255364.jpeg",
        productName: "Jordan 1234",
        productPrice: "100000000",
        quantity: "2",
        totalPrice: "200000000",
        status: "unconfimred",
        payment: "Thanh toan online",
        address: "Phu Thuan, Thoai Son, An Giang",
        note: "bla bla bla",
      },
      {
        id: "1",
        productImage:
          "https://kingshoes.vn/data/upload/media/nike-air-force-1-volt-neon-db1555-100-king-shoes-sneaker-real-hcm-1624255364.jpeg",
        productName: "Jordan 1234",
        productPrice: "100000000",
        quantity: "2",
        totalPrice: "200000000",
        status: "unconfimred",
        payment: "Thanh toan tien mat",
        address: "Phu Thuan, Thoai Son, An Giang",
        note: "bla bla bla",
      },
      {
        id: "2",
        productImage:
          "https://kingshoes.vn/data/upload/media/nike-air-force-1-volt-neon-db1555-100-king-shoes-sneaker-real-hcm-1624255364.jpeg",
        productName: "Jordan 1234",
        productPrice: "100000000",
        quantity: "2",
        totalPrice: "200000000",
        status: "unconfimred",
        payment: "Thanh toan online",
        address: "Phu Thuan, Thoai Son, An Giang",
        note: "bla bla bla",
      },
    ]);
    const loading = ref(false);
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });
    return {
      orders,
      loading,
      filters,
    };
  },
});
</script>

<style lang="scss" scoped>
.order {
  padding: 5rem 2rem;
  background-color: rgb(235, 235, 235);
  min-height: 90vh;

  .img {
    width: 7rem;
    height: 5rem;
    object-fit: cover;
  }

  .order-badge {
    border-radius: 2px;
    padding: 0.25em 0.5rem;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: 0.3px;
  }

  .order-badge.status-confimred {
    background-color: #c8e6c9;
    color: #256029;
  }

  .order-badge.status-unconfimred {
    background-color: #ffcdd2;
    color: #c63737;
  }

  :deep(.p-component) {
    font-size: 1.6rem !important;
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
}
</style>

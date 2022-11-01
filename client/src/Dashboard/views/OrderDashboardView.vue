<template>
  <div class="order">
    <TabView ref="tabview1">
      <TabPanel header="Đơn Hàng Mới">
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
              <h3 class="m-0">Danh Sách Đơn Hàng Mới</h3>
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
          <my-column header="Điện Thoại" :style="{ width: '15rem' }">
            <template #body="slotProps">
              {{ slotProps.data.customer.phone }}
            </template>
          </my-column>
          <my-column header="Tổng" :style="{ width: '15rem' }">
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
          </my-column>
        </DataTable>
      </TabPanel>
      <TabPanel header="Đơn Hàng Đã Xác Nhận">
        <p>
          Sed ut perspiciatis unde omnis iste natus error sit voluptatem
          accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae
          ab illo inventore veritatis et quasi architecto beatae vitae dicta
          sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit
          aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos
          qui ratione voluptatem sequi nesciunt. Consectetur, adipisci velit,
          sed quia non numquam eius modi.
        </p>
      </TabPanel>
      <TabPanel header="Đơn Hàng Đang Vận Chuyển">
        <p>
          At vero eos et accusamus et iusto odio dignissimos ducimus qui
          blanditiis praesentium voluptatum deleniti atque corrupti quos dolores
          et quas molestias excepturi sint occaecati cupiditate non provident,
          similique sunt in culpa qui officia deserunt mollitia animi, id est
          laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita
          distinctio. Nam libero tempore, cum soluta nobis est eligendi optio
          cumque nihil impedit quo minus.
        </p>
      </TabPanel>
      <TabPanel header="Đơn Hàng Đã Giao">
        <p>
          At vero eos et accusamus et iusto odio dignissimos ducimus qui
          blanditiis praesentium voluptatum deleniti atque corrupti quos dolores
          et quas molestias excepturi sint occaecati cupiditate non provident,
          similique sunt in culpa qui officia deserunt mollitia animi, id est
          laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita
          distinctio. Nam libero tempore, cum soluta nobis est eligendi optio
          cumque nihil impedit quo minus.
        </p>
      </TabPanel>
      <TabPanel header="Tất Cả Đơn Hàng">
        <p>zzz</p>
      </TabPanel>
    </TabView>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, ref } from "vue";
import { FilterMatchMode } from "primevue/api";
import { useStore } from "vuex";
import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";
import { IOrders } from "@/interface/order/order.state";
import { formatPrice, translateUnixTimeToFullTime } from "@/function/common";

export default defineComponent({
  components: {
    TabView,
    TabPanel,
  },
  setup() {
    const store = useStore();
    const columns = ref([
      { field: "orderId", header: "Mã ĐH", width: "10rem" },
      { field: "customer", header: "Khách Hàng", width: "18rem" },
      { field: "payment", header: "Thanh Toán", width: "18rem" },
    ]);
    const orders = computed(() => {
      const order = store.getters["order/getOrders"] as IOrders[];
      return order || [];
    });
    const loading = ref(false);
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });
    onMounted(async () => {
      await store.dispatch("order/getOrder");
    });
    return {
      columns,
      orders,
      loading,
      filters,
      translateUnixTimeToFullTime,
      formatPrice,
    };
  },
});
</script>

<style lang="scss" scoped>
.order {
  padding: 3rem 2rem 5rem 2rem;
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

  .order-badge.status-new {
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

  .order-badge.status-delivered {
    background-color: #edee9a;
    color: #cbbf16;
  }

  .order-badge.status-canceled {
    background-color: #fddde0;
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

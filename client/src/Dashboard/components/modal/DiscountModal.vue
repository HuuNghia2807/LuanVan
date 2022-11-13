<template>
  <my-dialog
    header="Tạo Khuyến Mãi"
    :visible="isDiscountModal"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '65vw' }"
    :modal="true"
    position="topleft"
    @update:visible="closeModalDiscount"
  >
    <div class="list">
      <Toolbar class="mb-4">
        <template #start>
          <h4>Danh sách khuyến mãi</h4>
        </template>
        <template #end>
          <div class="flex jutify-content-center">
            <my-button
              label="Xóa"
              icon="pi pi-trash"
              class="p-button-danger mr-4"
              disabled
            />
            <my-button
              label="Thêm Khuyến Mãi"
              icon="pi pi-plus"
              class="p-button-success"
              @click="openAddDiscount"
            />
          </div>
        </template>
      </Toolbar>
      <DataTable
        v-model:selection="selectedDiscount"
        :value="listDiscount"
        :paginator="true"
        :rows="10"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
        responsiveLayout="scroll"
      >
        <template #empty> No orders found. </template>
        <my-column
          selectionMode="multiple"
          style="width: 3rem"
          :exportable="false"
        ></my-column>
        <my-column
          v-for="col of columns"
          :field="col.field"
          :header="col.header"
          :key="col.field"
          :style="{ width: col.width }"
        />
        <my-column header="Giảm giá" :style="{ width: '8rem' }">
          <template #body="slotProps">
            <span class="text-green-600 font-semibold"
              >{{ slotProps.data.percent }}%</span
            >
          </template>
        </my-column>
        <my-column header="Ngày tạo" :style="{ width: '14rem' }">
          <template #body="slotProps">
            {{ translateUnixTimeToFullTime(slotProps.data.dateCreate) }}
          </template>
        </my-column>
        <my-column :style="{ width: '15rem' }">
          <template #body="slotProps">
            <div class="btn-table">
              <my-button
                label="Chọn sản phẩm"
                icon="pi pi-check"
                class="p-button-rounded p-button-info mr-3"
                aria-haspopup="true"
                aria-controls="overlay_panel"
                @click="toggle($event, slotProps.data.discountId)"
              />
              <my-button
                label="Xóa sản phẩm"
                icon="pi pi-verified"
                class="p-button-rounded p-button-warning"
                @click="
                  handleRemoveProductToDiscount(slotProps.data.discountId)
                "
              />
            </div>
          </template>
        </my-column>
      </DataTable>
    </div>

    <OverlayPanel
      ref="op"
      appendTo="self"
      :showCloseIcon="true"
      id="overlay_panel"
      style="width: 500px"
      :breakpoints="{ '960px': '75vw' }"
    >
      <DataTable
        v-model:selection="selectedProduct"
        :value="products"
        :paginator="true"
        :rows="8"
        :filters="filters"
        responsiveLayout="scroll"
      >
        <template #header>
          <div>
            <span class="p-input-icon-left">
              <i class="pi pi-search" />
              <my-inputText
                v-model="filters['global'].value"
                placeholder="Search..."
              />
            </span>
          </div>
        </template>
        <my-column field="productCode" header="Mã SP" sortable></my-column>
        <my-column header="Ảnh">
          <template #body="slotProps">
            <my-image
              :src="slotProps.data.images[0].product_image_link"
              :alt="slotProps.data.images[0].product_image_name"
              class="product-image"
              preview
            />
          </template>
        </my-column>
        <my-column field="category" header="Loại" sortable>
          <template #body="slotProps">
            {{ slotProps.data.category }}
          </template>
        </my-column>
        <my-column field="productPrice" header="Price" sortable>
          <template #body="slotProps">
            {{ formatPrice(slotProps.data.productPrice) }}
          </template>
        </my-column>

        <my-column
          selectionMode="multiple"
          style="width: 3rem"
          :exportable="false"
        ></my-column>
      </DataTable>
    </OverlayPanel>

    <AddDiscount
      :is-add-discount="isAddDiscount"
      @close-modal-add="closeModalAdd"
    />
    <my-toast />
  </my-dialog>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { IDiscount, IProduct } from "@/interface/product/product.state";
import { formatPrice, translateUnixTimeToFullTime } from "@/function/common";
import { FilterMatchMode } from "primevue/api";
import Toolbar from "primevue/toolbar";
import AddDiscount from "./AddDiscount.vue";
import OverlayPanel from "primevue/overlaypanel";

export default defineComponent({
  props: {
    isDiscountModal: { type: Boolean, default: false },
  },
  components: {
    Toolbar,
    AddDiscount,
    OverlayPanel,
  },
  setup(props, { emit }) {
    const store = useStore();
    const selectedDiscount = ref([]);
    const isAddDiscount = ref(false);
    const op = ref();
    const columns = ref([
      { field: "discountId", header: "Mã", width: "5rem" },
      { field: "title", header: "Tiêu đề", width: "15rem" },
    ]);
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });

    const listDiscount = ref([] as IDiscount[]);

    const selectedProduct = ref();

    const products = computed(() => {
      return store.getters["product/getProducts"] as IProduct[];
    });

    const closeModalDiscount = () => {
      emit("close-modal-discount");
    };

    const openAddDiscount = () => {
      isAddDiscount.value = true;
    };

    const closeModalAdd = () => {
      isAddDiscount.value = false;
    };

    const toggle = (event: any, idDiscount: number) => {
      op.value?.toggle(event);
      console.log(idDiscount);
    };

    const handleRemoveProductToDiscount = (idDiscount: number) => {
      console.log(idDiscount);
    };

    onMounted(async () => {
      listDiscount.value = await store.dispatch("product/getDiscount");
    });
    return {
      columns,
      listDiscount,
      selectedDiscount,
      isAddDiscount,
      products,
      selectedProduct,
      op,
      filters,
      formatPrice,
      openAddDiscount,
      closeModalAdd,
      toggle,
      handleRemoveProductToDiscount,
      translateUnixTimeToFullTime,
      closeModalDiscount,
    };
  },
});
</script>

<style lang="scss" scoped>
:deep(.p-component) {
  font-size: 1.6rem;
}
.btn-table {
  display: flex;
  justify-content: space-between;
  align-items: center;

  :deep(.p-button) {
    font-size: 1.2rem;
  }
}

:deep(.p-image-preview-container > img) {
  width: 5rem;
  height: 4rem;
}
</style>

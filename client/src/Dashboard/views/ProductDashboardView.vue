<template>
  <div class="container-product">
    <div class="card">
      <Toolbar class="mb-4">
        <template #start>
          <my-button
            label="Thêm sản phẩm"
            icon="pi pi-plus"
            class="p-button-success mr-2"
            @click="editProduct"
          />
          <my-button
            label="Xóa Sản Phẩm"
            icon="pi pi-trash"
            class="p-button-danger"
            @click="confirmDeleteSelected"
            :disabled="!selectedProducts || !selectedProducts.length"
          />
        </template>

        <!-- <template #end>
          <FileUpload
            mode="basic"
            accept="image/*"
            :maxFileSize="1000000"
            label="Import"
            chooseLabel="Import"
            class="mr-2 inline-block"
          />
          <my-button
            label="Export"
            icon="pi pi-upload"
            class="p-button-help"
            @click="exportCSV($event)"
          />
        </template> -->
      </Toolbar>

      <DataTable
        :value="products"
        v-model:selection="selectedProducts"
        dataKey="id"
        :paginator="true"
        :rows="10"
        :filters="filters"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
        currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
        responsiveLayout="scroll"
      >
        <template #header>
          <div
            class="table-header flex flex-column md:flex-row md:justiify-content-between"
          >
            <h5 class="mb-2 md:m-0 p-as-md-center">Quản Lý Sản Phẩm</h5>
            <span class="p-input-icon-left">
              <i class="pi pi-search" />
              <InputText
                v-model="filters['global'].value"
                placeholder="Search..."
              />
            </span>
          </div>
        </template>

        <Column
          selectionMode="multiple"
          style="width: 3rem"
          :exportable="false"
        ></Column>
        <Column
          field="id"
          header="Mã Sản Phẩm"
          :sortable="true"
          style="width: 10rem"
        ></Column>
        <Column
          field="name"
          header="Tên Sản Phẩm"
          :sortable="true"
          style="width: 20rem"
        ></Column>
        <Column header="Ảnh Sản Phẩm">
          <template #body="slotProps">
            <my-image
              :src="slotProps.data.img"
              :alt="slotProps.data.img"
              width="80"
            />
          </template>
        </Column>
        <Column
          field="price"
          header="Giá Sản Phẩm"
          :sortable="true"
          style="min-width: 8rem"
        >
          <template #body="slotProps">
            {{ slotProps.data.price }}
          </template>
        </Column>
        <Column
          field="category"
          header="Loại Sản Phẩm"
          :sortable="true"
          style="min-width: 12rem"
        >
          <template #body="slotProps">
            <span
              :class="
                'product-badge status-' +
                (slotProps.data.category
                  ? slotProps.data.category.toLowerCase()
                  : '')
              "
              >{{ slotProps.data.category }}</span
            >
          </template>
        </Column>
        <Column field="rating" header="Đánh giá" style="min-width: 12rem">
          <template #body="slotProps">
            <Rating
              :modelValue="slotProps.data.rating"
              :readonly="true"
              :cancel="false"
            />
          </template>
        </Column>
        <Column :exportable="false" style="min-width: 8rem">
          <template #body="slotProps">
            <my-button
              icon="pi pi-pencil"
              class="p-button-rounded p-button-success mr-2"
              @click="editProduct(slotProps.data)"
            />
            <my-button
              icon="pi pi-trash"
              class="p-button-rounded p-button-warning"
              @click="confirmDeleteProduct(slotProps.data)"
            />
          </template>
        </Column>
      </DataTable>
    </div>
  </div>

  <my-dialog
    v-model:visible="deleteProductDialog"
    :style="{ width: '450px' }"
    header="Confirm"
    :modal="true"
  >
    <div class="confirmation-content">
      <i class="pi pi-exclamation-triangle mr-3" style="font-size: 3rem" />
      <span v-if="product" style="font-size: 1.6rem"
        >Are you sure you want to delete <b>{{ product.name }}</b
        >?</span
      >
    </div>
    <template #footer>
      <my-button
        label="No"
        icon="pi pi-times"
        class="p-button-text"
        style="font-size: 1.6rem"
        @click="deleteProductDialog = false"
      />
      <my-button
        label="Yes"
        icon="pi pi-check"
        class="p-button-text"
        style="font-size: 1.6rem"
        @click="deleteProduct"
      />
    </template>
  </my-dialog>

  <my-dialog
    header="ACTIONS"
    v-model:visible="productDialog"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '60vw', fontSize: '2rem' }"
    :modal="true"
  >
    <AddOrEditProductCpn />
  </my-dialog>
  <my-toast position="top-right" group="tr" />
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import AddOrEditProductCpn from "@/Dashboard/components/product/AddOrEditProductCpn.vue";
import Rating from "primevue/rating";
import InputText from "primevue/inputtext";
import { FilterMatchMode } from "primevue/api";
import { useToast } from "primevue/usetoast";
import Toolbar from "primevue/toolbar";
import Column from "primevue/column";

export default defineComponent({
  components: {
    AddOrEditProductCpn,
    InputText,
    Rating,
    Toolbar,
    Column,
  },
  setup() {
    const products = ref([
      {
        id: 1,
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        category: "NIKE",
        rating: 5,
        price: "2.200.000 đ",
        sale: "",
      },
      {
        id: 2,
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        category: "JORDAN",
        rating: 5,
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
      {
        id: 3,
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        category: "PUMA",
        rating: 5,
        price: "2.200.000 đ",
        sale: "",
      },
      {
        id: 4,
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        category: "NIKE",
        rating: 5,
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
      {
        id: 5,
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        category: "NIKE",
        rating: 5,
        price: "2.200.000 đ",
        sale: "",
      },
      {
        id: 6,
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        category: "NIKE",
        rating: 5,
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
      {
        id: 7,
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        category: "NIKE",
        rating: 5,
        price: "2.200.000 đ",
        sale: "",
      },
      {
        id: 8,
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        category: "NIKE",
        rating: 5,
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
      {
        id: 9,
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        category: "NIKE",
        rating: 5,
        price: "2.200.000 đ",
        sale: "",
      },
      {
        id: 10,
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        category: "NIKE",
        rating: 5,
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
      {
        id: 11,
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        category: "NIKE",
        price: "2.200.000 đ",
        sale: "",
      },
      {
        id: 12,
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        rating: 5,
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
    ]);
    const selectedProducts = ref([] as any[]);
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });
    const deleteProductDialog = ref(false);
    const productDialog = ref(false);
    const product = ref<any>({});
    const toast = useToast();
    const deleteProductsDialog = ref(false);

    const confirmDeleteSelected = () => {
      deleteProductDialog.value = true;
    };
    const editProduct = (prod?: any) => {
      product.value = { ...prod };
      productDialog.value = true;
    };
    const deleteProduct = () => {
      if (selectedProducts.value.length) {
        deleteSelectedProducts();
      }

      products.value = products.value.filter(
        (val) => val.id !== product.value.id
      );
      deleteProductDialog.value = false;
      product.value = {};
      toast.add({
        severity: "success",
        summary: "Successful",
        detail: "Product Deleted",
        group: "tr",
        life: 3000,
      });
    };
    const deleteSelectedProducts = () => {
      products.value = products.value.filter(
        (val) => !selectedProducts.value.includes(val)
      );
      deleteProductsDialog.value = false;
      selectedProducts.value = [];
      toast.add({
        severity: "success",
        summary: "Successful",
        detail: "Products Deleted",
        group: "tr",
        life: 3000,
      });
    };

    const confirmDeleteProduct = (prod: any) => {
      product.value = prod;
      deleteProductDialog.value = true;
    };

    return {
      products,
      filters,
      deleteProductDialog,
      productDialog,
      product,
      deleteProductsDialog,
      selectedProducts,
      confirmDeleteSelected,
      editProduct,
      deleteProduct,
      deleteSelectedProducts,
      confirmDeleteProduct,
    };
  },
});
</script>

<style lang="scss" scoped>
.card {
  background: var(--surface-card);
  padding: 2rem;
  border-radius: 10px;
  margin-bottom: 2rem;
}

:deep(.p-component),
h5 {
  font-size: 1.4rem !important;
}

@keyframes pulse {
  0% {
    background-color: rgba(165, 165, 165, 0.1);
  }
  50% {
    background-color: rgba(165, 165, 165, 0.3);
  }
  100% {
    background-color: rgba(165, 165, 165, 0.1);
  }
}

.product-badge.status-nike {
  background: #c8e6c9;
  color: #256029;
}

.product-badge.status-jordan {
  background: #ffcdd2;
  color: #c63737;
}

.product-badge.status-puma {
  background: #feedaf;
  color: #8a5340;
}

.table-header {
  display: flex;
  align-items: center;
  justify-content: space-between;

  @media screen and (max-width: 960px) {
    align-items: start;
  }
}

.confirmation-content {
  display: flex;
  align-items: center;
  justify-content: center;
}
@media screen and (max-width: 960px) {
  ::v-deep(.p-toolbar) {
    flex-wrap: wrap;

    .p-button {
      margin-bottom: 0.25rem;
    }
  }
}
</style>

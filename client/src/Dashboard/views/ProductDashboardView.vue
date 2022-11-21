<template>
  <TheLoader :is-loading="showLoading" />
  <my-toast position="top-right" group="tr" />
  <div class="container-product">
    <div class="card">
      <Toolbar class="mb-4">
        <template #start>
          <my-button
            label="Thêm Sản Phẩm"
            icon="pi pi-plus"
            class="p-button-success mr-2"
            @click="addProduct"
          />
          <my-button
            label="Xóa Sản Phẩm"
            icon="pi pi-trash"
            class="p-button-danger"
            @click="confirmDeleteSelected"
            :disabled="!selectedProducts || !selectedProducts.length"
          />
        </template>
        <template #end>
          <my-button
            label="Danh Mục Sản Phẩm"
            icon="pi pi-table"
            class="p-button-warning mr-2"
            @click="handleCategory"
          />
          <my-button
            label="Tạo Khuyến Mãi"
            icon="pi pi-plus"
            class="p-button"
            @click="handleDiscount"
          />
        </template>
      </Toolbar>

      <DataTable
        :value="listProduct"
        v-model:selection="selectedProducts"
        dataKey="productId"
        :paginator="true"
        :rows="5"
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
          field="productCode"
          header="Mã SP"
          :sortable="true"
          style="width: 10rem"
        ></Column>
        <Column
          field="productName"
          header="Tên Sản Phẩm"
          :sortable="true"
          style="width: 20rem"
        ></Column>
        <Column header="Ảnh Sản Phẩm">
          <template #body="slotProps">
            <my-image
              :src="slotProps.data.images[0].product_image_link"
              :alt="slotProps.data.productName"
              width="80"
            />
          </template>
        </Column>
        <Column
          field="productPrice"
          header="Giá Sản Phẩm"
          :sortable="true"
          style="min-width: 8rem"
        >
          <template #body="slotProps">
            <div v-if="slotProps.data.discountId === 0">
              {{ formatPrice(slotProps.data.productPrice) }}
            </div>
            <div v-else>
              <div class="font-medium">
                {{
                  formatPrice(
                    caculatorSale(
                      slotProps.data.productPrice,
                      slotProps.data.discountPercent
                    )
                  )
                }}
              </div>
              <div class="line-through text-500">
                {{ formatPrice(slotProps.data.productPrice) }}
              </div>
            </div>
          </template>
        </Column>
        <my-column field="discountPercent" header="Giảm Giá" sortable>
          <template #body="slotProps">
            <div :class="stockClass(slotProps.data)">
              {{ slotProps.data.discountPercent }}%
            </div>
          </template>
        </my-column>
        <Column
          header="Loại Sản Phẩm"
          field="category"
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
        <Column header="Đánh giá" style="min-width: 12rem">
          <template #body="slotProps">
            <Rating
              :modelValue="slotProps.data.productRating"
              :readonly="true"
              :cancel="false"
            />
          </template>
        </Column>
        <Column :exportable="false" style="min-width: 8rem">
          <template #body="slotProps">
            <my-button
              icon="pi pi-cog"
              class="p-button-rounded p-button-success"
              @click="editProduct(slotProps.data)"
            />
            <my-button
              icon="pi pi-comments"
              class="p-button-rounded p-button mx-2"
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
      <span style="font-size: 1.6rem"
        >Are you sure you want to delete <b>{{ product?.productName }}</b
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
        @click="deleteProduct(product?.productId)"
      />
    </template>
  </my-dialog>

  <AddOrEditProductCpn
    v-if="isProductModal"
    :is-product-modal="isProductModal"
    :product-action="product"
    @load-product="loadProduct"
    @close-modal="closeProductModal"
  />

  <CategoryModal
    v-if="isCategoryModal"
    :is-category-modal="isCategoryModal"
    @close-modal="closeCategoryModal"
    @edit-category-success="loadProduct"
  />

  <DiscountModal
    v-if="isDiscountModal"
    :is-discount-modal="isDiscountModal"
    @close-modal-discount="closeModalDiscount"
  />
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import AddOrEditProductCpn from "@/Dashboard/components/product/AddOrEditProductCpn.vue";
import Rating from "primevue/rating";
import InputText from "primevue/inputtext";
import { FilterMatchMode } from "primevue/api";
import { useToast } from "primevue/usetoast";
import Toolbar from "primevue/toolbar";
import Column from "primevue/column";
import { IProduct } from "@/interface/product/product.state";
import { useStore } from "vuex";
import { caculatorSale, formatPrice } from "@/function/common";
import TheLoader from "@/components/common/TheLoader.vue";
import DiscountModal from "@/Dashboard/components/modal/DiscountModal.vue";
import CategoryModal from "@/Dashboard/components/modal/CategoryModal.vue";

export default defineComponent({
  components: {
    AddOrEditProductCpn,
    InputText,
    Rating,
    Toolbar,
    Column,
    TheLoader,
    DiscountModal,
    CategoryModal,
  },
  setup() {
    const store = useStore();
    const listProduct = ref([] as IProduct[]);
    const selectedProducts = ref([] as any[]);
    const showLoading = ref(false);
    const isDiscountModal = ref(false);
    const isCategoryModal = ref(false);
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });
    const deleteProductDialog = ref(false);
    const isProductModal = ref(false);
    const product = ref<IProduct>();
    const toast = useToast();
    const deleteProductsDialog = ref(false);

    const confirmDeleteSelected = () => {
      deleteProductDialog.value = true;
    };
    const addProduct = () => {
      product.value = undefined;
      isProductModal.value = true;
    };
    const editProduct = (prod?: any) => {
      product.value = { ...prod };
      isProductModal.value = true;
    };
    const deleteProduct = async (id?: number) => {
      deleteProductDialog.value = false;
      if (id) {
        await store.dispatch("product/deleteProduct", [id]);
        product.value = undefined;
        toast.add({
          severity: "success",
          summary: "Successful",
          detail: "Product Deleted",
          group: "tr",
          life: 3000,
        });
        loadProduct();
        return;
      }
      if (selectedProducts.value.length) {
        deleteSelectedProducts();
      }
    };

    const deleteSelectedProducts = async () => {
      const listId = selectedProducts.value.map((ele) => {
        return ele.productId;
      });

      await store.dispatch("product/deleteProduct", listId);
      selectedProducts.value = [];
      toast.add({
        severity: "success",
        summary: "Successful",
        detail: "Products Deleted",
        group: "tr",
        life: 3000,
      });
      loadProduct();
    };

    const confirmDeleteProduct = (prod: any) => {
      product.value = prod;
      deleteProductDialog.value = true;
    };

    const handleDiscount = () => {
      isDiscountModal.value = true;
    };

    const closeModalDiscount = () => {
      isDiscountModal.value = false;
    };

    const closeProductModal = () => {
      isProductModal.value = false;
    };

    const handleCategory = () => {
      isCategoryModal.value = true;
    };

    const closeCategoryModal = () => {
      isCategoryModal.value = false;
    };

    const stockClass = (data: IProduct) => {
      return [
        {
          "sale-active": data.discountId !== 0,
        },
      ];
    };

    const loadProduct = async () => {
      showLoading.value = true;
      await store.dispatch("product/getProducts");
      listProduct.value = store.getters["product/getProducts"] as IProduct[];
      showLoading.value = false;
    };

    onMounted(() => {
      loadProduct();
    });

    return {
      filters,
      deleteProductDialog,
      isProductModal,
      product,
      deleteProductsDialog,
      selectedProducts,
      listProduct,
      showLoading,
      isDiscountModal,
      isCategoryModal,
      closeCategoryModal,
      handleCategory,
      caculatorSale,
      stockClass,
      closeProductModal,
      closeModalDiscount,
      handleDiscount,
      loadProduct,
      addProduct,
      confirmDeleteSelected,
      editProduct,
      deleteProduct,
      deleteSelectedProducts,
      confirmDeleteProduct,
      formatPrice,
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

.product-badge {
  border-radius: 2px;
  padding: 0.25em 0.5rem;
  text-transform: uppercase;
  font-weight: 700;
  font-size: 12px;
  letter-spacing: 0.3px;
}

.sale-active {
  color: green;
  font-weight: 700;
}

.product-badge.status-nike {
  background-color: #aaf5b8;
  color: #1ccb16;
}

.product-badge.status-jordan {
  background-color: #a1a2ed;
  color: #373ec6;
}

.product-badge.status-puma {
  background-color: #c8e6c9;
  color: #256029;
}

.product-badge.status-adidas {
  background-color: #edee9a;
  color: #cbbf16;
}
.product-badge.status-yezzy {
  background-color: #fddde0;
  color: #c63737;
}

.table-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.confirmation-content {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>

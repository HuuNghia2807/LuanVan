<template>
  <my-dialog
    header="Tạo Khuyến Mãi"
    :visible="isDiscountModal"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '65vw' }"
    :modal="true"
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
              :disabled="!selectedDiscount.length"
              :loading="showLoading"
              @click="handleDeleteDiscount"
            />
            <my-button
              label="Thêm Khuyến Mãi"
              icon="pi pi-plus"
              class="p-button-success"
              @click="openAddDiscountModal"
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
                @click="handleAddDiscountToProduct(slotProps.data.discountId)"
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

    <ShowProductToDiscount
      v-if="isDiscountToProduct"
      :is-add-discount-to-product="isDiscountToProduct"
      :id-discount="idDiscount"
      :is-remove-discount="isRemoveDiscountToProduct"
      @close-discount-to-product="closeDiscountToProduct"
    />

    <AddDiscount
      :is-add-discount="isAddDiscount"
      @close-modal-add="closeAddDiscountModal"
      @create-discount-success="createSuccess"
    />
    <my-toast />
  </my-dialog>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { IDiscount, IProduct } from "@/interface/product/product.state";
import { translateUnixTimeToFullTime } from "@/function/common";
import Toolbar from "primevue/toolbar";
import AddDiscount from "./AddDiscount.vue";
import ShowProductToDiscount from "./ShowProductToDiscount.vue";
import { useToast } from "primevue/usetoast";

export default defineComponent({
  props: {
    isDiscountModal: { type: Boolean, default: false },
  },
  components: {
    Toolbar,
    AddDiscount,
    ShowProductToDiscount,
  },
  setup(_props, { emit }) {
    const store = useStore();
    const toast = useToast();
    const showLoading = ref(false);
    const isAddDiscount = ref(false);
    const isDiscountToProduct = ref(false);
    const isRemoveDiscountToProduct = ref(false);
    const idDiscount = ref();
    const columns = ref([
      { field: "discountId", header: "Mã", width: "5rem" },
      { field: "title", header: "Tiêu đề", width: "15rem" },
    ]);

    const listDiscount = ref([] as IDiscount[]);

    const selectedProduct = ref([] as IProduct[]);
    const selectedDiscount = ref([] as IDiscount[]);

    const closeModalDiscount = () => {
      emit("close-modal-discount");
    };

    const openAddDiscountModal = () => {
      isAddDiscount.value = true;
    };

    const closeAddDiscountModal = () => {
      isAddDiscount.value = false;
    };

    const handleAddDiscountToProduct = (id: number) => {
      isDiscountToProduct.value = true;
      idDiscount.value = id;
    };

    const closeDiscountToProduct = () => {
      isDiscountToProduct.value = false;
      idDiscount.value = undefined;
      isRemoveDiscountToProduct.value = false;
    };

    const handleRemoveProductToDiscount = (id: number) => {
      isDiscountToProduct.value = true;
      idDiscount.value = id;
      isRemoveDiscountToProduct.value = true;
    };

    const handleDeleteDiscount = async () => {
      if (selectedDiscount.value?.length) {
        const ids_discount = selectedDiscount.value.map(
          (ele) => ele.discountId
        );
        showLoading.value = true;
        await store.dispatch("product/deleteDiscount", { ids: ids_discount });
        toast.add({
          severity: "success",
          summary: "Thành công",
          detail: "Xoa thành công",
          life: 3000,
        });
        getDiscount();
        showLoading.value = false;
      }
    };

    const createSuccess = () => {
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Thêm khuyến mãi thành công",
        life: 3000,
      });
      getDiscount();
    };

    const getDiscount = async () => {
      listDiscount.value = await store.dispatch("product/getDiscount");
      await store.dispatch("product/getProducts");
    };

    onMounted(() => {
      getDiscount();
    });

    return {
      columns,
      listDiscount,
      selectedDiscount,
      isAddDiscount,
      selectedProduct,
      isDiscountToProduct,
      idDiscount,
      isRemoveDiscountToProduct,
      showLoading,
      handleDeleteDiscount,
      createSuccess,
      handleAddDiscountToProduct,
      closeDiscountToProduct,
      openAddDiscountModal,
      closeAddDiscountModal,
      handleRemoveProductToDiscount,
      translateUnixTimeToFullTime,
      closeModalDiscount,
    };
  },
});
</script>

<style lang="scss" scoped>
:deep(.p-component) {
  font-size: 1.4rem;
}
.btn-table {
  display: flex;
  justify-content: space-between;
  align-items: center;

  :deep(.p-button) {
    font-size: 1.2rem !important;
  }
}
</style>

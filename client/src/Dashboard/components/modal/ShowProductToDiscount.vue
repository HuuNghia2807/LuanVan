<template>
  <my-dialog
    header="Tạo Khuyến Mãi"
    :visible="isAddDiscountToProduct"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '60vw' }"
    :modal="true"
    @update:visible="closeDiscountToProduct"
  >
    <div class="add-discount-to-product">
      <DataTable
        v-model:selection="selectedProduct"
        :value="products"
        :paginator="true"
        :rows="5"
        :filters="filters"
        responsiveLayout="scroll"
      >
        <template #header>
          <div class="flex justify-content-center">
            <span class="p-input-icon-left">
              <i class="pi pi-search" />
              <my-inputText
                v-model="filters['global'].value"
                placeholder="Search..."
              />
            </span>
          </div>
        </template>
        <my-column
          field="productCode"
          header="Mã SP"
          sortable
          style="width: 10rem"
        ></my-column>
        <my-column
          field="productName"
          header="Tên SP"
          sortable
          style="width: 20rem"
        ></my-column>
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
        </my-column>
        <my-column field="discountPercent" header="Sale" sortable>
          <template #body="slotProps">
            <div :class="stockClass(slotProps.data)">
              <span
                :class="
                  slotProps.data.discountId === idDiscount
                    ? 'text-yellow-500'
                    : ''
                "
              >
                {{ slotProps.data.discountPercent }}%
              </span>
            </div>
          </template>
        </my-column>

        <my-column
          selectionMode="multiple"
          style="width: 3rem"
          :exportable="false"
        >
        </my-column>
      </DataTable>
    </div>

    <template #footer>
      <div class="flex justify-content-end">
        <my-button
          label="Cancel"
          class="p-button-outlined p-button-warning mr-5"
          @click="closeDiscountToProduct"
        />
        <my-button
          v-if="isRemoveDiscount"
          label="Save"
          class="p-button-raised p-button-info"
          :disabled="!idsRemove?.length"
          @click="handleDiscountToProduct"
        />
        <my-button
          v-else
          label="Save"
          class="p-button-raised p-button-info"
          :disabled="!selectedProduct?.length"
          @click="handleDiscountToProduct"
        />
      </div>
    </template>
    <TheLoader :is-loading="showLoading" />
  </my-dialog>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, ref, watchEffect } from "vue";
import { caculatorSale, formatPrice } from "@/function/common";
import { useStore } from "vuex";
import { IProduct } from "@/interface/product/product.state";
import { FilterMatchMode } from "primevue/api";
import TheLoader from "@/components/common/TheLoader.vue";

export default defineComponent({
  props: {
    isAddDiscountToProduct: { type: Boolean, default: false },
    idDiscount: { type: Number },
    isRemoveDiscount: { type: Boolean, default: false },
  },
  components: {
    TheLoader,
  },
  setup(props, { emit }) {
    const store = useStore();
    const selectedProduct = ref([] as IProduct[]);
    const idsRemove = ref([] as number[]);
    const showLoading = ref(false);
    const filters = ref({
      global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });

    const products = computed(() => {
      return store.getters["product/getProducts"] as IProduct[];
    });

    const handleDiscountToProduct = async () => {
      if (props.idDiscount && selectedProduct.value.length) {
        showLoading.value = true;
        if (props.isRemoveDiscount) {
          await store.dispatch("product/removeDiscountToProduct", {
            discount_id: props.idDiscount,
            product_ids: idsRemove.value,
          });
        } else {
          await store.dispatch("product/addDiscountToProduct", {
            discount_id: props.idDiscount,
            product_ids: selectedProduct.value.map((ele) => ele.productId),
          });
        }
        showLoading.value = false;
      }
    };

    const closeDiscountToProduct = () => {
      emit("close-discount-to-product");
    };

    const stockClass = (data: IProduct) => {
      return [
        {
          "sale-active": data.discountId !== 0,
        },
      ];
    };

    watchEffect(() => {
      const ids_sale = [] as number[];

      if (props.isRemoveDiscount) {
        products.value.forEach((ele) => {
          if (ele.discountId === props.idDiscount) {
            ids_sale.push(ele.productId);
          }
        });
        selectedProduct.value = selectedProduct.value.filter((ele) => {
          if (ids_sale.includes(ele.productId)) {
            return ele;
          }
        });
        idsRemove.value = ids_sale.filter((ele) => {
          if (
            !selectedProduct.value.some((eleSel) => ele === eleSel.productId)
          ) {
            return ele;
          }
        });
      } else {
        products.value.forEach((ele) => {
          if (ele.discountId !== 0) {
            ids_sale.push(ele.productId);
          }
        });
        selectedProduct.value = selectedProduct.value.filter((ele) => {
          if (!ids_sale.includes(ele.productId)) {
            return ele;
          }
        });
      }
    });

    onMounted(() => {
      if (props.isRemoveDiscount) {
        selectedProduct.value = products.value.filter(
          (ele) => ele.discountId === props.idDiscount
        );
      }
    });

    return {
      filters,
      selectedProduct,
      products,
      showLoading,
      idsRemove,
      caculatorSale,
      stockClass,
      closeDiscountToProduct,
      handleDiscountToProduct,
      formatPrice,
    };
  },
});
</script>

<style lang="scss" scoped>
.add-discount-to-product {
  font-size: 1.4rem !important;
}
:deep(.p-component) {
  font-size: 1.4rem;
}
:deep(.p-image-preview-container > img) {
  width: 5rem;
  height: 5rem;
}

.sale-active {
  color: green;
  font-weight: 700;
}
</style>

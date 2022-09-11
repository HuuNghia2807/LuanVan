<template>
  <div class="card">
    <DataView
      :value="products"
      :layout="layout"
      :paginator="true"
      :rows="6"
      :sortOrder="sortOrder"
      :sortField="sortField"
    >
      <template #header>
        <div
          class="header grid grid-nogutter justify-content-between align-items-center"
        >
          <div class="flex align-items-center" style="text-align: left">
            <my-dropdown
              v-model="sortKey"
              :options="sortOptions"
              optionLabel="label"
              placeholder="Sắp xếp theo giá"
              @change="onSortChange($event)"
            />
            <div class="ml-4">
              <span class="p-input-icon-left">
                <i class="pi pi-search" />
                <InputText type="text" v-model="search" placeholder="Search" />
              </span>
            </div>
          </div>
          <div class="flex align-items-center" style="text-align: right">
            <div class="mr-4">
              <my-button
                label="Thêm sản phẩm"
                icon="pi pi-plus"
                class="p-button-success"
                @click="openModal"
              />
            </div>
            <DataViewLayoutOptions v-model="layout" />
          </div>
        </div>
      </template>

      <template #list="slotProps">
        <div class="col-12">
          <div class="product-list-item">
            <img
              class="image-col12"
              :src="slotProps.data.img"
              :alt="slotProps.data.name"
            />
            <div class="product-list-detail">
              <div class="product-name">{{ slotProps.data.name }}</div>
              <div class="product-code">
                Mã SP: <span class="font-bold text-orange-500">5177 89</span>
              </div>
              <div>
                <Rating
                  :modelValue="slotProps.data.rating"
                  :cancel="false"
                  :disabled="true"
                  :readonly="true"
                ></Rating>
              </div>
              <i
                class="pi pi-tag product-category-icon"
                style="font-size: 1.6rem"
              ></i
              ><span class="product-category">Nike</span>
            </div>
            <div class="product-list-action">
              <span class="product-price">{{ slotProps.data.price }}</span>
              <span class="product-quantity"
                >Số lượng: <span class="sl">1000</span></span
              >
            </div>
          </div>
        </div>
      </template>

      <template #grid="slotProps">
        <div class="col-12 md:col-4">
          <div class="product-grid-item card">
            <div class="product-grid-item-top">
              <div>
                <i
                  class="pi pi-tag product-category-icon"
                  style="font-size: 1.6rem"
                ></i>
                <span class="product-category">Nike</span>
              </div>
              <div class="action-top">
                <div class="mr-2">
                  <my-button
                    icon="pi pi-pencil"
                    class="p-button-rounded p-button-success"
                  />
                </div>
                <div>
                  <my-button
                    icon="pi pi-times"
                    class="p-button-rounded p-button-danger"
                  />
                </div>
              </div>
            </div>
            <div class="product-grid-item-content">
              <img
                class="image-col4"
                :src="slotProps.data.img"
                :alt="slotProps.data.name"
              />
              <div class="product-code">
                Mã SP: <span class="font-bold text-orange-500">5177 89</span>
              </div>
              <div class="product-name">{{ slotProps.data.name }}</div>
              <div class="rating">
                <Rating
                  :modelValue="slotProps.data.rating"
                  :readonly="true"
                  :cancel="false"
                  :disabled="true"
                ></Rating>
              </div>
            </div>
            <div class="product-grid-item-bottom">
              <span class="product-price">{{ slotProps.data.price }}</span>
              <span class="product-quantity"
                >Số lượng: <span class="sl">1000</span></span
              >
            </div>
          </div>
        </div>
      </template>
    </DataView>
  </div>
  <my-dialog
    header="ACTIONS"
    v-model:visible="isActions"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '60vw', fontSize: '2rem' }"
    :modal="true"
  >
    <AddOrEditProductCpn />
  </my-dialog>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import AddOrEditProductCpn from "@/Dashboard/components/product/AddOrEditProductCpn.vue";
import Rating from "primevue/rating";
import DataViewLayoutOptions from "primevue/dataviewlayoutoptions";
import InputText from "primevue/inputtext";

export default defineComponent({
  components: {
    AddOrEditProductCpn,
    InputText,
    Rating,
    DataViewLayoutOptions,
  },
  setup() {
    const products = ref([
      {
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        rate: [1, 1, 1, 1, 1],
        price: "2.200.000 đ",
        sale: "",
        rating: 3,
      },
      {
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        rate: [1, 1, 1, 1, 1],
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
      {
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        rate: [1, 1, 1, 1, 1],
        price: "2.200.000 đ",
        sale: "",
      },
      {
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        rate: [1, 1, 1, 1, 1],
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
      {
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        rate: [1, 1, 1, 1, 1],
        price: "2.200.000 đ",
        sale: "",
      },
      {
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        rate: [1, 1, 1, 1, 1],
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
      {
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        rate: [1, 1, 1, 1, 1],
        price: "2.200.000 đ",
        sale: "",
      },
      {
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        rate: [1, 1, 1, 1, 1],
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
      {
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        rate: [1, 1, 1, 1, 1],
        price: "2.200.000 đ",
        sale: "",
      },
      {
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        rate: [1, 1, 1, 1, 1],
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
      {
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        rate: [1, 1, 1, 1, 1],
        price: "2.200.000 đ",
        sale: "",
      },
      {
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        rate: [1, 1, 1, 1, 1],
        price: "9.500.000 đ",
        sale: "8.000.000 đ",
      },
    ]);
    const isActions = ref(false);
    const layout = ref("grid");
    const sortKey = ref(null);
    const sortOrder = ref(0);
    const sortField = ref(0);
    const search = ref("");
    const sortOptions = ref([
      { label: "Giá thấp đến cao", value: "!price" },
      { label: "Giá cao đến thấp", value: "price" },
    ]);

    const onSortChange = (event: any) => {
      const value = event.value.value;
      const sortValue = event.value;

      if (value.indexOf("!") === 0) {
        sortOrder.value = -1;
        sortField.value = value.substring(1, value.length);
        sortKey.value = sortValue;
      } else {
        sortOrder.value = 1;
        sortField.value = value;
        sortKey.value = sortValue;
      }
    };
    const openModal = () => {
      isActions.value = true;
    };
    const closeModal = () => {
      isActions.value = false;
    };
    return {
      products,
      layout,
      sortKey,
      sortOrder,
      sortField,
      sortOptions,
      search,
      isActions,
      onSortChange,
      openModal,
      closeModal,
    };
  },
});
</script>

<style lang="scss" scoped>
.card {
  background: #ffffff;
  padding: 2rem;
  box-shadow: 0 2px 1px -1px rgba(0, 0, 0, 0.2), 0 1px 1px 0 rgba(0, 0, 0, 0.14),
    0 1px 3px 0 rgba(0, 0, 0, 0.12);
  border-radius: 4px;
  padding: 2rem;
  font-size: 1.6rem;

  .header {
    height: 5rem;
  }
}
.p-dropdown {
  width: 14rem;
  font-weight: normal;
}

.image-col12 {
  width: 12rem;
  height: 10rem;
  object-fit: contain;
}

.image-col4 {
  width: 20rem;
  height: 16rem;
  object-fit: contain;
}

.rating {
  display: flex;
  justify-content: flex-start;
}

.product-description {
  margin: 0 0 1rem 0;
}

.product-category-icon {
  vertical-align: middle;
  margin-right: 0.5rem;
}

.product-category {
  font-size: 1.6rem;
  font-weight: 600;
  vertical-align: middle;
}

::v-deep(.product-list-item) {
  display: flex;
  align-items: center;
  padding: 1rem;
  width: 100%;

  img {
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
    margin-right: 2rem;
  }

  .product-list-detail {
    flex: 1 1 0;
    .product-name {
      font-size: 1.6rem;
      font-weight: 700;
      color: #495057;
      overflow: hidden;
    }
  }

  .p-rating {
    margin: 0 0 0.5rem 0;
  }

  .product-price {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
    align-self: flex-end;
    color: red;
  }

  .product-list-action {
    display: flex;
    flex-direction: column;
  }

  .p-button {
    margin-bottom: 0.5rem;
  }
}

.action-top {
  display: none;
  align-items: center;
}

.product-code {
  font-size: 1.5rem;
  margin: 0.5rem 0;
  color: #495050;
}

.product-grid-item:hover .action-top {
  display: flex;
}

::v-deep(.product-grid-item) {
  margin: 0.5rem;
  border: 1px solid var(--surface-border);
  height: 40rem;

  .product-grid-item-top,
  .product-grid-item-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .product-grid-item-top {
    height: 2rem;
  }

  img {
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
    margin: 2rem 0;
  }

  .product-grid-item-content {
    text-align: center;
    .product-name {
      font-size: 1.6rem;
      font-weight: 700;
      margin: 0 0 1rem 0;
      color: #495057;
      height: 4.2rem;
      overflow: hidden;
    }
  }

  .product-price {
    font-size: 1.6rem;
    font-weight: 600;
    color: red;
    margin: 1rem 0;
  }
}
.product-quantity {
  font-size: 1.6rem;
  font-weight: 600;
  color: #495057;
}

.sl {
  color: #000;
  font-size: 1.7rem;
  font-weight: 800;
  font-style: italic;
}
:deep(.p-rating-icon.pi-star-fill) {
  color: yellow !important;
}

:deep(.p-rating .p-rating-icon) {
  font-size: 1.8rem;
}

.modal-container {
  font-size: 1.8rem;
}

@media screen and (max-width: 576px) {
  .product-list-item {
    flex-direction: column;
    align-items: center;

    img {
      margin: 2rem 0;
    }

    .product-list-detail {
      text-align: center;
    }

    .product-price {
      align-self: center;
    }

    .product-list-action {
      display: flex;
      flex-direction: column;
    }

    .product-list-action {
      margin-top: 2rem;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      width: 100%;
    }
  }
}
</style>

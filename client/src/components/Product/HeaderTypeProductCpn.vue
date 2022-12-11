<template>
  <div class="header-type">
    <div class="option-search">
      <span class="title">Chọn Size Giày</span>
      <my-dropdown
        :filter="true"
        v-model="size"
        :options="sizes"
        optionLabel="name"
        optionValue="code"
        placeholder="Chọn Size Giày"
      >
        <template #option="slotProps">
          <div class="option-item">{{ slotProps.option.name }}</div>
        </template>
      </my-dropdown>
    </div>
    <div class="option-search">
      <span class="title">Khoảng Giá</span>
      <my-dropdown
        :filter="true"
        v-model="price"
        :options="prices"
        optionLabel="name"
        optionValue="code"
        placeholder="Tất cả"
      >
        <template #option="slotProps">
          <div class="option-item">{{ slotProps.option.name }}</div>
        </template>
      </my-dropdown>
    </div>
    <div class="option-search">
      <span class="title">Sắp Xếp Theo</span>
      <my-dropdown
        :filter="true"
        v-model="sort"
        :options="sorts"
        optionLabel="name"
        optionValue="code"
        placeholder="Chọn giá"
      >
        <template #option="slotProps">
          <div class="option-item">{{ slotProps.option.name }}</div>
        </template>
      </my-dropdown>
    </div>
    <div class="option-search">
      <my-button
        class="p-button-lg p-button-warning default"
        label="Tìm Giày Ngay"
        icon="pi pi-search"
        iconPos="right"
        :loading="loading"
        @click="handleFilter"
      />
      <my-button
        class="p-button-rounded p-button-warning responsive"
        icon="pi pi-search"
        :loading="loading"
        @click="handleFilter"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { IFilterProduct } from "@/interface/product/product.state";

export default defineComponent({
  setup(_props, { emit }) {
    const store = useStore();
    const route = useRoute();
    const type = computed(() => {
      return route.path.slice(1);
    });
    const sizes = ref([] as any[]);
    const prices = ref([
      { name: "Tất cả", code: undefined },
      { name: "Dưới 3 triệu", code: 1 },
      { name: "Từ 3 đến 5 triệu", code: 2 },
      { name: "Từ 5 đến 10 triệu", code: 3 },
      { name: "Trên 10 triệu", code: 4 },
    ]);
    const sorts = ref([
      { name: "Giá thấp đến cao", code: "min" },
      { name: "Giá cao đến thấp", code: "max" },
    ]);
    const sort = ref("");
    const size = ref();
    const price = ref();
    const loading = ref(false);
    const handleFilter = () => {
      loading.value = true;
      const filter = {
        category: type.value,
        size: size.value,
        price: price.value,
        sort: sort.value,
      } as IFilterProduct;
      emit("filter-product", filter);
      loading.value = false;
    };
    onMounted(async () => {
      sizes.value = (
        (await store.dispatch("product/getSizes")) as number[]
      ).map((ele) => {
        return {
          name: ele,
          code: ele,
        };
      });
      sizes.value.unshift({
        name: "Tất cả",
        code: undefined,
      });
    });
    return { sizes, size, prices, price, sorts, sort, loading, handleFilter };
  },
});
</script>

<style lang="scss" scoped>
.header-type {
  height: 11rem;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #f1f1f1;
  padding: 0 5rem;

  :deep(.p-inputtext) {
    font-size: 1.6rem;
    height: 4rem;
    line-height: 1.6;
    padding-left: 1rem;
    width: 100%;
  }
  .option-search {
    display: flex;
    flex: 1;
    flex-direction: column;
    justify-content: center;
    margin: 0 2rem;
  }

  .responsive {
    display: none;
  }

  .title {
    font-size: 1.6rem;
    color: #212529;
    font-style: italic;
    font-weight: 400;
    line-height: 1.5;
    margin-bottom: 1rem;
  }

  :deep(.p-my-dropdown-label) {
    font-size: 1.6rem !important;
    line-height: 1.4rem !important;
    padding: 1rem;
  }

  :deep(.p-button.p-button-lg) {
    padding: 1rem 2rem;
    font-size: 2rem;
    font-weight: 550;
    margin-right: 2rem;
    border-radius: 5rem;
  }
}
.option-item {
  font-size: 1.6rem !important;
}

@media screen and (min-width: 1px) and (max-width: 1179px) {
  .default {
    display: none;
  }
  .responsive {
    display: flex !important;
    font-size: 2rem !important;
    width: 5rem !important;
    height: 5rem !important;

    :deep(.pi) {
      font-size: 2rem !important;
    }
  }
  .option-search {
    align-items: center;
  }
  :deep(.p-dropdown) {
    width: 100%;
  }
}

@media screen and (max-width: 768px) {
  .header-type {
    flex-direction: column;
  }
  .header-type {
    height: auto;
  }
  .option-search {
    margin: 1rem 0 !important;
  }
}
</style>

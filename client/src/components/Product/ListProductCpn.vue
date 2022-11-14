<template>
  <div class="list-product grid">
    <ProductCpn
      v-for="product in products"
      :key="product.productCode"
      :product-detail="product"
      :type="type"
    />
  </div>
</template>

<script lang="ts">
import { IProduct } from "@/interface/product/product.state";
import { defineComponent, PropType } from "vue";
import ProductCpn from "./ProductCpn.vue";

export default defineComponent({
  props: {
    products: { type: Array as PropType<IProduct[]> },
    type: { type: String },
  },
  components: {
    ProductCpn,
  },
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
.list-product {
  width: 100%;
}
</style>

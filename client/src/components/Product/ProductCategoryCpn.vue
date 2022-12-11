<template>
  <div class="product-category">
    <router-link
      to="/product"
      class="title"
      :style="categoryProduct?.type !== 'New' && 'pointer-events: none'"
    >
      {{ `- ${categoryProduct?.title}` }}
      <span class="sup__title">{{ categoryProduct?.supTitle }}</span>
    </router-link>
    <div class="list-product">
      <ListProductCpn
        :products="categoryProduct?.products"
        :type="categoryProduct?.type"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { IOptionProduct } from "@/interface/product/product.state";
import { defineComponent, PropType } from "vue";
import ListProductCpn from "./ListProductCpn.vue";

export default defineComponent({
  props: {
    categoryProduct: { type: Object as PropType<IOptionProduct> },
  },
  components: {
    ListProductCpn,
  },
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
.product-category {
  padding: 0 6rem;
  margin-bottom: 10rem;

  .title {
    display: block;
    text-decoration: none;
    position: relative;
    text-transform: uppercase;
    font-size: 2rem;
    font-weight: 700;
    color: #000;
    margin: 7.5rem 0;
    &:hover {
      color: var(--primary-color);
    }
  }
  .sup__title {
    position: absolute;
    z-index: -1;
    text-transform: uppercase;
    top: 0;
    transform: translateY(-35%);
    left: -0.5rem;
    font-size: 8rem;
    font-weight: 600;
    color: #f4f4f4;
    letter-spacing: 0.08em;
  }
}

@media screen and (max-width: 768px) {
  .title {
    font-size: 1rem !important;
  }
  .sup__title {
    font-size: 4rem !important;
  }
}
</style>

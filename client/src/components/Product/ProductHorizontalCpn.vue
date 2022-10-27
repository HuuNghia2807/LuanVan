<template>
  <router-link class="product no-underline" to="/">
    <div class="wrap">
      <img
        :src="product?.images[0].product_image_link"
        :alt="product?.productName"
        class="img-product"
      />
      <div class="product-context">
        <span class="name-product">{{ product?.productName }}</span>
        <div class="list-rate">
          <img
            src="@/assets/img/icons/star.png"
            class="icon"
            v-for="(_star, i) in product?.productRating"
            :key="i"
          />
        </div>
        <div class="product-price">
          <span class="price">{{ product?.productPrice }}</span>
        </div>
      </div>
    </div>
  </router-link>
</template>

<script lang="ts">
import { IProduct } from "@/interface/product/product.state";
import { defineComponent, PropType } from "vue";

export default defineComponent({
  props: {
    product: { type: Object as PropType<IProduct> },
  },
  components: {},
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
.product {
  position: relative;
  margin-bottom: 1rem;
  color: var(--black-color);

  .product-context {
    margin-left: 1.4rem;
  }

  .wrap {
    display: flex;
    border: 1px solid transparent;
    height: 100%;
  }
  &:hover .wrap {
    border: 1px solid #ccc;
  }

  .img-product {
    width: 100%;
    height: 10rem;
    width: 10rem;
    object-fit: contain;
  }

  .name-product {
    display: block;
    margin-top: 1.5rem;
    font-size: 1.4rem;
    font-weight: 700;
    color: #5b5b5b;
    line-height: 1.4rem;
    height: 2.8rem;
    overflow: hidden;

    &:hover {
      color: var(--primary-color);
    }
  }

  .list-rate {
    margin-top: 0.5rem;
  }

  .icon {
    width: 1.4rem;
  }

  .product-price {
    margin: 0.6rem 0 1rem 0;
  }

  .price {
    display: block;
    font-size: 1.4rem;
    font-weight: 700;
    color: #5b5b5b;
  }

  .price-sale {
    display: block;
    font-size: 1.4rem;
    font-weight: 700;
    margin-top: 0.1rem;
    text-decoration: line-through;
    color: #bcbcbc;
  }
}
</style>

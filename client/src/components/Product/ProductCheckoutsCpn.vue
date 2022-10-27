<template>
  <div class="product-checkouts">
    <div class="img-main">
      <img class="image" :src="product?.img" alt="" />
      <span class="badge">{{ product?.quantity }}</span>
    </div>
    <div class="product-name">
      <div class="name">{{ product?.name }}</div>
      <span class="size">Size Giày: {{ product?.size }}</span>
    </div>
    <div class="product-price">
      <span class="price">{{ formatPrice(product?.price || 999999) }}</span>
    </div>
  </div>
</template>

<script lang="ts">
import { formatPrice } from "@/function/common";
import { ICartList } from "@/interface/product/product.state";
import { defineComponent, PropType } from "vue";

export default defineComponent({
  props: {
    product: { type: Object as PropType<ICartList> },
  },
  setup() {
    return {
      formatPrice,
    };
  },
});
</script>

<style lang="scss" scoped>
.product-checkouts {
  display: flex;
  align-items: center;
  margin-bottom: 1.5rem;

  .img-main {
    position: relative;
    .image {
      width: 7rem;
      height: 7rem;
      object-fit: contain;

      border: 1px solid #ccc;
      border-radius: 1rem;
    }

    .badge {
      position: absolute;
      background-color: rgba(153, 153, 153, 0.9);
      font-size: 0.85714em;
      font-weight: 500;
      white-space: nowrap;
      padding: 0.15em 0.65em;
      border-radius: 2em;
      color: #fff;
      position: absolute;
      right: -0.75em;
      top: -0.75em;
      z-index: 2;
    }
  }

  .product-name {
    flex: 1;
    margin-left: 1.5rem;

    .name {
      font-size: 1.3rem;
      text-decoration: none;
      font-weight: 500;
      color: #4b4b4b;
    }

    .size {
      display: block;
      padding: 0.35em 0.65em;
      font-size: 0.75em;
      font-weight: 500;
      line-height: 1;
      color: #fff;
      text-align: center;
      white-space: nowrap;
      border-radius: 0.25rem;
      background-color: #d26e4b;
      margin: 2px 2px;
      width: 9rem;
    }
  }

  .product-price {
    width: 20%;
    text-align: end;

    .price {
      font-weight: 500;
      color: #4b4b4b;
      font-size: 1.4rem;
    }
  }
}
</style>

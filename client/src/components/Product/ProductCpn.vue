<template>
  <router-link
    v-if="!!productDetail"
    class="product no-underline"
    :to="{ name: 'showDetail', params: { code: productDetail?.productCode } }"
    :style="`width: ${type ? '20' : '25'}%`"
  >
    <div class="wrap">
      <img
        :src="productDetail?.images[0].product_image_link"
        :alt="productDetail?.images[0].product_image_name"
        class="img-product"
      />
      <div class="product-context">
        <span class="name-product">{{ productDetail?.productName }}</span>
        <div class="list-rate">
          <img
            src="@/assets/img/icons/star.png"
            class="icon"
            v-for="(_star, i) in productDetail?.productRating"
            :key="i"
          />
        </div>
        <div class="product-price">
          <span class="price" v-if="productDetail.discountId === 0">{{
            formatPrice(productDetail.productPrice || 9999999)
          }}</span>
          <div v-else>
            <span class="price">{{
              formatPrice(
                caculatorSale(
                  productDetail.productPrice || 9999999,
                  productDetail.discountPercent || 0
                )
              )
            }}</span>
            <span class="price-sale">{{
              formatPrice(productDetail.productPrice || 9999999)
            }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="tag" v-if="type === 'New'">
      <span class="text-tag">{{ type }}</span>
    </div>
    <div class="tag hot-tag" v-if="type === 'Hot'">
      <span class="text-tag">{{ type }}</span>
    </div>
    <div class="tag tag-sale" v-if="productDetail.discountId !== 0">
      <span class="text-tag">{{ productDetail.discountPercent }}%</span>
    </div>
  </router-link>
  <div class="product" v-else>
    <Skeleton class="wrap" width="30rem" height="40rem" borderRadius="16px" />
  </div>
</template>

<script lang="ts">
import { caculatorSale, formatPrice } from "@/function/common";
import { defineComponent, PropType } from "vue";
import Skeleton from "primevue/skeleton";
import { IProduct } from "@/interface/product/product.state";

export default defineComponent({
  props: {
    none: { type: Boolean, default: false },
    productDetail: { type: Object as PropType<IProduct> },
    type: { type: String },
  },
  components: {
    Skeleton,
  },
  setup() {
    return {
      formatPrice,
      caculatorSale,
    };
  },
});
</script>

<style lang="scss" scoped>
.product {
  position: relative;
  height: 40rem;
  padding-left: 1.2rem;
  padding-right: 1.2rem;
  margin-bottom: 2.5rem;
  color: var(--black-color);

  .tag {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 7.5rem;
    height: 3rem;
    position: absolute;
    top: 0;
    left: 1.2rem;
    background-color: var(--primary-color);
  }

  .hot-tag {
    background-color: #ff8585;
  }

  .tag-sale {
    position: absolute;
    top: 10%;
    background-color: #cf5050;
  }
  .text-tag {
    display: block;
    font-size: 1.4rem;
    color: var(--white-color);
  }

  .product-context {
    margin-left: 1rem;
  }

  .wrap {
    border: 1px solid transparent;
    height: 100%;
  }
  &:hover .wrap {
    border: 1px solid #ccc;
  }

  .img-product {
    width: 100%;
    height: 26rem;
    object-fit: cover;
  }

  .name-product {
    display: block;
    margin-top: 1rem;
    font-size: 1.6rem;
    font-weight: 700;
    color: #5b5b5b;
    line-height: 2rem;
    height: 3.6rem;
    overflow: hidden;

    &:hover {
      color: var(--primary-color);
    }
  }

  .list-rate {
    margin-top: 2rem;
  }

  .icon {
    width: 1.8rem;
    padding-right: 0.4rem;
  }

  .product-price {
    margin: 0.6rem 0 2rem 0;
  }

  .price {
    display: block;
    font-size: 1.6rem;
    font-weight: 700;
    color: #5b5b5b;
  }

  .price-sale {
    display: block;
    font-size: 1.6rem;
    font-weight: 700;
    margin-top: 0.1rem;
    text-decoration: line-through;
    color: #bcbcbc;
  }
}
@media screen and (min-width: 769px) and (max-width: 1179px) {
  .product {
    width: 50% !important;
  }
}
@media screen and (max-width: 768px) {
  .product {
    width: 100% !important;
  }
}
</style>

<template>
  <ScrollPanel style="width: 100%; height: 30rem" class="custombar1">
    <div
      class="product"
      v-for="product in productList"
      :key="product.productCode"
      @click="hideProductSearch(product.productCode)"
    >
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
        </div>
        <div class="product-price">
          <span class="price" v-if="product.discountId === 0">{{
            formatPrice(product.productPrice || 9999999)
          }}</span>
          <div v-else>
            <span class="price">{{
              formatPrice(
                caculatorSale(
                  product.productPrice || 9999999,
                  product.discountPercent || 0
                )
              )
            }}</span>
            <span class="price-sale">{{
              formatPrice(product.productPrice || 9999999)
            }}</span>
          </div>
        </div>
      </div>
    </div>
  </ScrollPanel>
</template>

<script lang="ts">
import { caculatorSale, formatPrice } from "@/function/common";
import { IProduct } from "@/interface/product/product.state";
import { defineComponent, PropType } from "vue";
import ScrollPanel from "primevue/scrollpanel";
import { useRouter } from "vue-router";

export default defineComponent({
  components: {
    ScrollPanel,
  },
  props: {
    productList: { type: Array as PropType<IProduct[]> },
  },
  setup(_props, { emit }) {
    const router = useRouter();
    const hideProductSearch = (code: string) => {
      router.push({ name: "showDetail", params: { code } });
      emit("hide-product-search");
    };

    return {
      hideProductSearch,
      formatPrice,
      caculatorSale,
    };
  },
});
</script>

<style lang="scss" scoped>
.product {
  position: relative;
  margin-bottom: 1rem;
  color: var(--black-color);
  cursor: pointer;

  .product-context {
    margin-left: 1.4rem;
  }

  ::v-deep(.p-scrollpanel) {
    &.custombar1 {
      .p-scrollpanel-wrapper {
        border-right: 9px solid var(--surface-ground);
      }

      .p-scrollpanel-bar {
        background-color: rgb(202, 202, 202);
        opacity: 1;
        transition: background-color 0.2s;

        &:hover {
          background-color: rgb(158, 158, 158);
        }
      }
    }
  }

  .wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid transparent;
    margin: 1rem;
  }
  &:hover .wrap {
    background-color: rgb(255, 234, 206);
  }

  .img-product {
    height: 7rem;
    width: 7rem;
    object-fit: cover;
  }

  .name-product {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
    color: #5b5b5b;
    line-height: 1.4rem;
    height: 1.4rem;
    overflow: hidden;
    width: 10rem;

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
    flex: 1;
    text-align: right;
    padding-right: 1rem;
  }

  .price {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
    color: #5b5b5b;
  }

  .price-sale {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
    margin-top: 0.1rem;
    text-decoration: line-through;
    color: #bcbcbc;
  }
}
</style>

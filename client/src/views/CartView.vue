<template>
  <div class="cart">
    <my-toast />
    <TheLoader :is-loading="showLoading" />
    <div class="container">
      <div class="cart-wrap">
        <span class="cart-title">Giỏ Hàng</span>
        <div class="product-list" v-if="cartList.length">
          <ProductCartCpn
            v-for="(product, i) in cartList"
            :key="i"
            :product="product"
          />
        </div>
        <div class="cart-empty" v-else>
          <img src="@/assets/img/cart_empty.png" alt="giỏ hàng trống" />
        </div>
        <div class="cart-footer">
          <div class="total-price">
            Tổng tiền: <span class="price">{{ formatPrice(totalOrder) }}</span>
          </div>
          <div class="btn-cart">
            <Button
              label="MUA TIẾP"
              icon="pi pi-chevron-right"
              iconPos="right"
              class="p-button-lg p-button-warning p-button-rounded"
              @click="backToProduct"
            />
            <Button
              label="ĐẶT HÀNG"
              icon="pi pi-chevron-right"
              class="p-button-lg p-button-warning p-button-rounded"
              iconPos="right"
              @click="handleOrder"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { computed, defineComponent, ref } from "vue";
import ProductCartCpn from "@/components/Product/ProductCartCpn.vue";
import Button from "primevue/button";
import { useStore } from "vuex";
import { ICart, IProduct } from "@/interface/product/product.state";
import { formatPrice } from "@/function/common";
import { useRouter } from "vue-router";
import { getCartList } from "@/function/getCartList";
import { useToast } from "primevue/usetoast";
import TheLoader from "@/components/common/TheLoader.vue";

export default defineComponent({
  components: {
    ProductCartCpn,
    Button,
  },
  setup() {
    const toast = useToast();
    const store = useStore();
    const router = useRouter();
    const showLoading = ref(false);
    const cartList = computed(() => {
      const listProduct: IProduct[] =
        store.getters["product/getProducts"] || [];
      const cartItem: ICart[] = store.getters["auth/getCart"] || [];

      return getCartList(listProduct, cartItem);
    });
    const totalOrder = computed(() => {
      return cartList.value.reduce(
        (prev, cur) => prev + (cur?.price || 0) * (cur?.quantity || 0),
        0
      );
    });

    const backToProduct = () => {
      showLoading.value = true;
      router.push("/");
      showLoading.value = false;
    };

    const handleOrder = () => {
      const isLogged = store.getters["auth/getIslogged"];
      if (!cartList.value.length) {
        toast.add({
          severity: "error",
          summary: "Thất bại",
          detail: "Vui lòng thêm sản phẩm trước khi mua hàng!",
          life: 3000,
        });
        return;
      }
      if (isLogged) {
        router.push("/checkouts");
      } else {
        router.push("/account");
      }
    };
    return {
      cartList,
      totalOrder,
      showLoading,
      formatPrice,
      backToProduct,
      handleOrder,
    };
  },
});
</script>

<style lang="scss" scoped>
.cart {
  min-height: 100vh;
  display: flex;
  justify-content: center;

  .container {
    margin: 3rem 0;
    width: 45%;
    border: 1px solid #d8d8d8;
    box-shadow: 0 0 20px rgb(0 0 0 / 15%);

    .cart-wrap {
      margin: 0 2rem;
    }

    .cart-title {
      display: block;
      font-size: 2.6rem;
      font-weight: 700;
      color: #5b5b5b;
      padding: 2rem 0;
      text-transform: uppercase;
      border-bottom: 1px solid #d8d8d8;
    }

    .product-list {
      border-bottom: 1px solid #d8d8d8;
    }

    .cart-empty {
      border-bottom: 1px solid #d8d8d8;

      img {
        width: 100%;
        height: 40rem;
        object-fit: contain;
      }
    }

    .cart-footer {
      margin: 3rem 0 6rem 0;
      display: flex;

      .total-price {
        flex: 1;
        font-size: 1.6rem;
        font-weight: 700;

        .price {
          color: var(--red-500);
          font-size: 1.8rem;
        }
      }

      .btn-cart {
        flex: 1;
      }

      :deep(.p-button.p-button-lg) {
        padding: 1.2rem 2rem;
        font-size: 1.6rem;
        color: #000;
        font-weight: 600;
        margin-right: 1.2rem;
        box-shadow: 0 0 1.3rem rgb(0 0 0 / 30%);
        border-radius: 5rem;
      }
    }
  }
}
</style>

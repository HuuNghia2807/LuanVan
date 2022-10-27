<template>
  <div class="product-type">
    <HeaderTypeProductCpn />
    <div class="container">
      <span class="title"
        >Trang chủ > Sản phẩm > <span style="color: red">Nike</span></span
      >
      <div class="list-product">
        <div class="products">
          <ListProductCpn :products="listProduct" />
        </div>
        <div class="right-bar">
          <img
            src="@/assets/img/bannersitebar01.jpg"
            alt="banner"
            class="img-right"
          />
          <div class="list-new-product">
            <span class="title">SẢN PHẨM MỚI</span>
            <div class="list-new">
              <ProductHorizontalCpn
                v-for="(product, i) in newProducts"
                :key="i"
                :product="product"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, ref } from "vue";
import HeaderTypeProductCpn from "@/components/Product/HeaderTypeProductCpn.vue";
import ListProductCpn from "@/components/Product/ListProductCpn.vue";
import ProductHorizontalCpn from "@/components/Product/ProductHorizontalCpn.vue";
import { useStore } from "vuex";
import { IProduct } from "@/interface/product/product.state";

export default defineComponent({
  components: {
    HeaderTypeProductCpn,
    ListProductCpn,
    ProductHorizontalCpn,
  },
  setup() {
    const store = useStore();
    const listProduct = computed(() => {
      return store.getters["product/getProducts"] || [];
    });

    const newProducts = computed(() => {
      const list = (store.getters["product/getProducts"] as IProduct[]) || [];
      return list.map((ele) => {
        return {
          ...ele,
          display: "ngang",
        };
      });
    });

    onMounted(async () => {
      await store.dispatch("product/getProducts");
    });
    return {
      listProduct,
      newProducts,
    };
  },
});
</script>

<style lang="scss" scoped>
.product-type {
  margin-bottom: 10rem;
  width: 100%;

  .container {
    padding: 0 6rem;

    .title {
      display: block;
      margin: 5rem 0;
      text-transform: uppercase;
      font-size: 1.3rem;
      font-weight: 700;
      color: #333;
    }

    .list-product {
      width: 100%;
      display: flex;
    }

    .products {
      width: 77%;
    }

    .right-bar {
      width: 23%;

      .img-right {
        position: relative;
        width: 100%;
        transform: translateY(-6rem);
      }

      .list-new-product {
        border-top: 1px solid #ccc;

        .title {
          font-size: 2.4rem;
          color: #626262;
          font-weight: 700;
          text-transform: uppercase;
        }

        .list-new {
          display: flex;
          flex-direction: column;
        }
      }
    }
  }
}
</style>

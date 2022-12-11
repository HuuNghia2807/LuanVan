<template>
  <div class="product-type">
    <HeaderTypeProductCpn @filter-product="handleFilter" />
    <div class="container">
      <span class="title"
        >Trang chủ > Sản phẩm >
        <span style="color: red">{{ type.toUpperCase() }}</span></span
      >
      <div class="list-product">
        <div class="products">
          <ListProductCpn
            :products="productFilter.length ? productFilter : listProduct"
          />
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
import { IFilterProduct, IProduct } from "@/interface/product/product.state";
import { useRoute } from "vue-router";
import { sortMax, sortMin } from "@/function/common";

export default defineComponent({
  components: {
    HeaderTypeProductCpn,
    ListProductCpn,
    ProductHorizontalCpn,
  },
  setup() {
    const store = useStore();
    const route = useRoute();
    const type = computed(() => {
      return route.path.slice(1);
    });
    const productFilter = ref([] as IProduct[]);
    const listProduct = computed(() => {
      const list = (store.getters["product/getProducts"] as IProduct[]) || [];

      return list
        .map((ele) => {
          if (type.value === "product") {
            return ele;
          }
          if (type.value === "sale" && ele.discountId !== 0) {
            return ele;
          }
          if (ele.category.toLowerCase() === type.value) {
            return ele;
          }
        })
        .filter((ele) => ele) as IProduct[];
    });

    const handleFilter = (filter: IFilterProduct) => {
      let result = [...listProduct.value] as IProduct[];
      if (filter.price) {
        const price = getPriceFilter(filter.price);
        result = result.filter((ele) => {
          if (
            (ele.productPrice || 0) >= (price?.start || 0) &&
            (ele.productPrice || 0) <= (price?.end || 0)
          ) {
            return ele;
          }
        });
      }
      if (filter.size) {
        result = result.filter((ele) => {
          if (ele.sizes.some((eleS) => eleS.size === filter.size)) {
            return ele;
          }
        });
      }
      if (filter.sort) {
        result = filter.sort === "min" ? sortMin(result) : sortMax(result);
      }
      productFilter.value = result;
    };

    const getPriceFilter = (option: number) => {
      switch (option) {
        case 1:
          return {
            start: 0,
            end: 3000000,
          };
        case 2:
          return {
            start: 3000000,
            end: 5000000,
          };
        case 3:
          return {
            start: 5000000,
            end: 10000000,
          };
        case 4:
          return {
            start: 10000000,
            end: 1000000000,
          };

        default:
          break;
      }
    };

    const newProducts = computed(() => {
      const list =
        (store.getters["product/getNewProducts"] as IProduct[]) || [];
      return list.map((ele) => {
        return {
          ...ele,
          display: "ngang",
        };
      });
    });

    onMounted(async () => {
      await store.dispatch("product/getProducts");
      await store.dispatch("product/getHomeProduct");
    });
    return {
      listProduct,
      newProducts,
      type,
      productFilter,
      handleFilter,
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

@media screen and (min-width: 1px) and (max-width: 1179px) {
  .right-bar {
    display: none;
  }

  .products {
    width: 100% !important;
  }
}
</style>

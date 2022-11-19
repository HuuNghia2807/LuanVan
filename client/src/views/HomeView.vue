<template>
  <div class="home">
    <SliderCpn />
    <IntroduceCpn />
    <ProductCategoryCpn :category-product="newProduct" />
    <ProductCategoryCpn :category-product="hotProduct" />
    <ProductCategoryCpn :category-product="saleProduct" />
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, reactive } from "vue";
import SliderCpn from "@/components/SliderCpn.vue";
import IntroduceCpn from "@/components/IntroduceCpn.vue";
import ProductCategoryCpn from "@/components/Product/ProductCategoryCpn.vue";
import { useStore } from "vuex";
import {
  IOptionProduct,
  IProductHome,
} from "@/interface/product/product.state";

export default defineComponent({
  name: "HomeView",
  components: { SliderCpn, IntroduceCpn, ProductCategoryCpn },
  setup() {
    const store = useStore();
    const newProduct = reactive({
      type: "New",
      title: "Sản phẩm mới",
      supTitle: "Product",
      products: [],
    } as IOptionProduct);

    const hotProduct = reactive({
      type: "Hot",
      title: "Sản phẩm hot",
      supTitle: "Hot",
      products: [],
    } as IOptionProduct);
    const saleProduct = reactive({
      type: "Sale",
      title: "Sản phẩm khuyến mãi",
      supTitle: "Discount",
      products: [],
    } as IOptionProduct);
    onMounted(async () => {
      const homeProduct = (await store.dispatch(
        "product/getHomeProduct"
      )) as IProductHome;
      newProduct.products = homeProduct.productNew;
      hotProduct.products = homeProduct.productHot.filter((ele, i) => {
        if (i <= 9) {
          return ele;
        }
      });
      saleProduct.products = homeProduct.productSale;
    });
    return {
      newProduct,
      hotProduct,
      saleProduct,
    };
  },
});
</script>

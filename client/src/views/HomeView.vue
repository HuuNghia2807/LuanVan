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
import { useStore } from "vuex";
import SliderCpn from "@/components/SliderCpn.vue";
import IntroduceCpn from "@/components/IntroduceCpn.vue";
import ProductCategoryCpn from "@/components/Product/ProductCategoryCpn.vue";

export default defineComponent({
  name: "HomeView",
  components: { SliderCpn, IntroduceCpn, ProductCategoryCpn },
  setup() {
    const newProduct = reactive({
      type: "New",
      title: "Sản phẩm mới",
      supTitle: "Product",
      product: [
        {
          id: 1,
          img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
          name: "AIR FORCE 1",
          rate: [1, 1, 1, 1, 1],
          price: "2.200.000 đ",
          sale: "",
        },
        {
          id: 1,
          img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
          name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
          rate: [1, 1, 1, 1, 1],
          price: "9.500.000 đ",
          sale: "8.000.000 đ",
        },
        {
          id: 2,
          img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
          name: "AIR FORCE 1",
          rate: [1, 1, 1, 1, 1],
          price: "2.200.000 đ",
          sale: "",
        },
        {
          id: 3,
          img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
          name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
          rate: [1, 1, 1, 1, 1],
          price: "9.500.000 đ",
          sale: "8.000.000 đ",
        },
        {
          id: 4,
          img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
          name: "AIR FORCE 1",
          rate: [1, 1, 1, 1, 1],
          price: "2.200.000 đ",
          sale: "",
        },
        {
          id: 5,
          img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
          name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
          rate: [1, 1, 1, 1, 1],
          price: "9.500.000 đ",
          sale: "8.000.000 đ",
        },
      ],
    });
    const hotProduct = reactive({
      type: "Hot",
      title: "Sản phẩm hot",
      supTitle: "Hot",
      product: [
        {
          img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-max-ap-marathon-cu4870-001-king-shoes-sneaker-real-hcm-30-1637493513.jpeg",
          name: "AIR MAX AP MARATHON",
          rate: [1, 1, 1, 1],
          price: "3,200,000 đ",
          sale: "",
        },
        {
          img: "https://kingshoes.vn/data/upload/media/giay-nike-air-more-uptempo-gs-varsity-red-415082-600-king-shoes-sneaker-real-hcm-26-1634564878.jpeg",
          name: "AIR MORE UPTEMPO BULLS HOOPS PACK",
          rate: [1, 1, 1, 1, 1],
          price: "4,800,000 đ",
          sale: "8.000.000 đ",
        },
      ],
    });
    const saleProduct = reactive({
      title: "Sản phẩm khuyến mãi",
      supTitle: "Discount",
      product: [
        {
          img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
          name: "AIR FORCE 1",
          rate: [1, 1, 1, 1, 1],
          price: "2.200.000 đ",
          sale: "-10%",
        },
        {
          img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
          name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
          rate: [1, 1, 1, 1, 1],
          price: "9.500.000 đ",
          sale: "-11%",
        },
        {
          img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
          name: "AIR FORCE 1",
          rate: [1, 1, 1, 1, 1],
          price: "2.200.000 đ",
          sale: "-12%",
        },
        {
          img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
          name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
          rate: [1, 1, 1, 1, 1],
          price: "9.500.000 đ",
          sale: "-13%",
        },
        {
          img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
          name: "AIR FORCE 1",
          rate: [1, 1, 1, 1, 1],
          price: "2.200.000 đ",
          sale: "-14%",
        },
        {
          img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
          name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
          rate: [1, 1, 1, 1, 1],
          price: "9.500.000 đ",
          sale: "-15%",
        },
      ],
    });
    onMounted(async () => {
      await useStore().dispatch("auth/login", {
        userName: "nguyen huu nghia",
        password: "123456",
      });
      console.log("zzzzzzzzzzz", useStore());
    });
    return {
      newProduct,
      hotProduct,
      saleProduct,
    };
  },
});
</script>

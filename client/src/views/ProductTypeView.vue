<template>
    <div class="product-type">
        <HeaderTypeProductCpn />
        <div class="container">
            <span class="title"
                >Trang chủ > Sản phẩm >
                <span style="color: red">Nike</span></span
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
                            <ProductHorizontal
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
import { defineComponent, ref } from "vue";
import HeaderTypeProductCpn from "@/components/Product/HeaderTypeProductCpn.vue";
import ListProductCpn from "@/components/Product/ListProductCpn.vue";
import ProductHorizontal from "@/components/Product/ProductHorizontal.vue";

export default defineComponent({
    components: {
        HeaderTypeProductCpn,
        ListProductCpn,
        ProductHorizontal,
    },
    setup() {
        const listProduct = ref([
            {
                img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
                name: "AIR FORCE 1",
                rate: [1, 1, 1, 1, 1],
                price: "2.200.000 đ",
                sale: "",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
                name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
                rate: [1, 1, 1, 1, 1],
                price: "9.500.000 đ",
                sale: "8.000.000 đ",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
                name: "AIR FORCE 1",
                rate: [1, 1, 1, 1, 1],
                price: "2.200.000 đ",
                sale: "",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
                name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
                rate: [1, 1, 1, 1, 1],
                price: "9.500.000 đ",
                sale: "8.000.000 đ",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
                name: "AIR FORCE 1",
                rate: [1, 1, 1, 1, 1],
                price: "2.200.000 đ",
                sale: "",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
                name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
                rate: [1, 1, 1, 1, 1],
                price: "9.500.000 đ",
                sale: "8.000.000 đ",
            },
        ]);

        const newProducts = ref([
            {
                img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
                name: "AIR FORCE 1",
                rate: [1, 1, 1, 1, 1],
                price: "2.200.000 đ",
                sale: "",
                display: "ngang",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
                name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
                rate: [1, 1, 1, 1, 1],
                price: "9.500.000 đ",
                sale: "8.000.000 đ",
                display: "ngang",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
                name: "AIR FORCE 1",
                rate: [1, 1, 1, 1, 1],
                price: "2.200.000 đ",
                sale: "",
                display: "ngang",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
                name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
                rate: [1, 1, 1, 1, 1],
                price: "9.500.000 đ",
                sale: "8.000.000 đ",
                display: "ngang",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
                name: "AIR FORCE 1",
                rate: [1, 1, 1, 1, 1],
                price: "2.200.000 đ",
                sale: "",
                display: "ngang",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
                name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
                rate: [1, 1, 1, 1, 1],
                price: "9.500.000 đ",
                sale: "8.000.000 đ",
                display: "ngang",
            },
        ]);
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

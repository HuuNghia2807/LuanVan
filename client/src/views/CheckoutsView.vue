<template>
    <div class="checkouts">
        <div class="left">
            <div class="wrap">
                <img class="img" src="@/assets/img/logo.png" alt="" />
                <div class="breadcrumb">
                    <router-link class="text text-cart" to="/gio-hang"
                        >Giỏ hàng</router-link
                    >
                    <i
                        class="pi pi-angle-right mx-2"
                        style="font-size: 1.3rem"
                    ></i>
                    <span class="text">Thông tin giao hàng </span>
                    <i
                        class="pi pi-angle-right mx-2"
                        style="font-size: 1.3rem"
                    ></i>
                    <span class="text text-disabled"
                        >Phương thức thanh toán</span
                    >
                </div>
                <div class="form-info">
                    <h3 class="title">Thông tin giao hàng</h3>
                </div>
            </div>
        </div>
        <div class="right">
            <div class="list-product">
                <ProductCheckoutsCpn
                    v-for="(product, i) in productCheckout"
                    :key="i"
                    :product="product"
                />
            </div>
            <div class="price-temp">
                <div class="temp">
                    <span class="text">Tam tinh</span>
                    <span class="price">54,300,000 đ</span>
                </div>
                <div class="temp">
                    <span class="text">Phí vận chuyển</span>
                    <span class="price">Chưa bao gồm</span>
                </div>
            </div>
            <div class="total-price">
                <span class="text">Tổng cộng</span>
                <span class="total">54,300,000 đ</span>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import ProductCheckoutsCpn from "@/components/Product/ProductCheckoutsCpn.vue";

export default defineComponent({
    components: {
        ProductCheckoutsCpn,
    },
    setup() {
        const productCheckout = ref([
            {
                img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
                name: "AIR FORCE 1",
                price: "2.200.000 đ",
                size: 41,
                quantity: 2,
            },
            {
                img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
                name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD DDDD FFFFF",
                price: "9.500.000 đ",
                size: 42,
                quantity: 3,
            },
            {
                img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
                name: "AIR FORCE 1",
                price: "2.200.000 đ",
                size: 40,
                quantity: 1,
            },
            {
                img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
                name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
                price: "9.500.000 đ",
                size: 43,
                quantity: 1,
            },
        ]);
        return {
            productCheckout,
        };
    },
});
</script>

<style lang="scss" scoped>
.checkouts {
    display: flex;
    min-height: 100vh;

    .left {
        width: 55%;
        min-height: 100%;
        background-color: #efe4d3;
        padding: 6rem 4rem 2rem 20rem;

        .img {
            width: 32rem;
        }

        .breadcrumb {
            margin-top: 2rem;
            .text {
                text-decoration: none;
                font-size: 1.3rem;
                font-weight: 400;
                color: #4d4d4d;
            }

            .text-cart {
                color: #338dbc;
            }

            .text-disabled {
                color: #999;
            }
        }

        .form-info {
            margin-top: 4rem;

            .title {
                font-size: 1.8rem;
                color: #333;
                font-weight: 400;
            }
        }
    }

    .right {
        flex: 1;
        padding: 5rem 16rem 2rem 4rem;

        .list-product {
            border-bottom: 1px solid #ccc;
        }

        .price-temp {
            margin-top: 3rem;
            border-bottom: 1px solid #ccc;
            padding-bottom: 1.5rem;

            .temp {
                display: flex;
                align-items: center;
                justify-content: space-around;
                margin-bottom: 1rem;

                .text {
                    display: block;
                    flex: 1;
                    color: #737373;
                    font-size: 1.4rem;
                }

                .price {
                    display: block;
                    flex: 1;
                    text-align: end;
                    font-weight: 500;
                    color: #4b4b4b;
                    font-size: 1.5rem;
                }
            }
        }
        .total-price {
            display: flex;
            align-items: center;
            margin-top: 1.5rem;
            padding-bottom: 1.5rem;

            .text {
                flex: 1;
                font-size: 1.6rem;
                color: #4b4b4b;
            }

            .total {
                flex: 1;
                text-align: end;
                font-size: 2rem;
                color: var(--red-500);
            }
        }
    }
}
</style>

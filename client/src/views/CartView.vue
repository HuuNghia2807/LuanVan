<template>
    <div class="cart">
        <div class="container">
            <div class="cart-wrap">
                <span class="cart-title">Giỏ Hàng</span>
                <div class="product-list">
                    <ProductCartCpn
                        v-for="(product, i) in cartProducts"
                        :key="i"
                        :product="product"
                    />
                </div>
                <div class="cart-footer">
                    <div class="total-price">
                        Tổng tiền: <span class="price">54,300,000 đ</span>
                    </div>
                    <div class="btn-cart">
                        <router-link to="/" class="no-underline">
                            <Button
                                label="MUA TIẾP"
                                icon="pi pi-chevron-right"
                                iconPos="right"
                                class="p-button-lg p-button-warning p-button-rounded"
                            />
                        </router-link>
                        <router-link to="/checkouts" class="no-underline">
                            <Button
                                label="ĐẶT HÀNG"
                                icon="pi pi-chevron-right"
                                class="p-button-lg p-button-warning p-button-rounded"
                                iconPos="right"
                            />
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref } from "vue";
import ProductCartCpn from "@/components/Product/ProductCartCpn.vue";
import Button from "primevue/button";

export default defineComponent({
    components: {
        ProductCartCpn,
        Button,
    },
    setup() {
        const cartProducts = ref([
            {
                img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
                name: "AIR FORCE 1",
                rate: [1, 1, 1, 1, 1],
                price: 2200000,
                sale: "",
                display: "ngang",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
                name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
                rate: [1, 1, 1, 1, 1],
                price: 9500000,
                sale: "8.000.000",
                display: "ngang",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
                name: "AIR FORCE 1",
                rate: [1, 1, 1, 1, 1],
                price: 2200000,
                sale: "",
                display: "ngang",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
                name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
                rate: [1, 1, 1, 1, 1],
                price: 9500000,
                sale: "8.000.000",
                display: "ngang",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
                name: "AIR FORCE 1",
                rate: [1, 1, 1, 1, 1],
                price: 2200000,
                sale: "",
                display: "ngang",
            },
            {
                img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
                name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
                rate: [1, 1, 1, 1, 1],
                price: 9500000,
                sale: "8.000.000",
                display: "ngang",
            },
        ]);
        return {
            cartProducts,
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

<template>
    <div class="product-detail">
        <HeaderTypeProductCpn />
        <div class="container">
            <div class="list-img-wrap">
                <div class="list-img">
                    <img
                        class="imgs"
                        src="https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-mid-light-smoke-grey-554724-078-king-shoes-2.png"
                        alt=""
                    />
                    <img
                        class="imgs"
                        src="https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-mid-light-smoke-grey-554724-078-king-shoes-2.png"
                        alt=""
                    />
                    <img
                        class="imgs"
                        src="https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-mid-light-smoke-grey-554724-078-king-shoes-2.png"
                        alt=""
                    />
                    <img
                        class="imgs"
                        src="https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-mid-light-smoke-grey-554724-078-king-shoes-2.png"
                        alt=""
                    />
                    <img
                        class="imgs"
                        src="https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-mid-light-smoke-grey-554724-078-king-shoes-2.png"
                        alt=""
                    />
                </div>
            </div>
            <div class="show-img">
                <img
                    class="img"
                    src="https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-mid-light-smoke-grey-554724-078-king-shoes-2.png"
                    alt=""
                />
            </div>
            <div class="content">
                <div>
                    <Rating v-model="rate" :cancel="false" />
                    <span class="product-name"
                        >JORDAN 1 MID LIGHT SMOKE GREY</span
                    >
                    <span class="product-code">
                        Mã SP: <span class="sup-code"> 554724 078</span>
                    </span>
                    <span class="product-price">7,800,000 đ</span>
                </div>
                <div class="product-size">
                    <div
                        v-for="(size, i) of sizes"
                        :key="i"
                        class="field-radiobutton"
                    >
                        <RadioButton
                            :inputId="size.name"
                            name="size"
                            :value="size.name"
                            v-model="selectedSize"
                            :disabled="size.name === '42'"
                        />
                        <label :for="size.name">{{ size.name }}</label>
                    </div>
                </div>
                <div>
                    <div class="mt-4">
                        <InputNumber
                            inputId="horizontal"
                            v-model="quantity"
                            mode="decimal"
                            showButtons
                            buttonLayout="horizontal"
                            :min="0"
                            :max="40"
                            decrementButtonClass="p-button-secondary"
                            incrementButtonClass="p-button-secondary"
                            incrementButtonIcon="pi pi-plus"
                            decrementButtonIcon="pi pi-minus"
                        />
                    </div>
                    <div class="mt-5">
                        <Button
                            label="Thêm vào giỏ hàng"
                            icon="pi pi-shopping-cart"
                            class="p-button-lg p-button-warning p-button-rounded"
                        />
                        <Button
                            label="Mua Ngay"
                            icon="pi pi-chevron-right"
                            class="p-button-lg p-button-danger p-button-rounded"
                            iconPos="right"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="other mb-8">
            <div class="my-3">
                ⚡️ KINGSHOES.VN
                <span class="font-normal">
                    ✓15 Ngày Đổi Hàng ✓Giao Hàng Miễn Phí ✓Thanh Toán Khi Nhận
                    Hàng ✓Bảo Hành Hàng Chính Hãng Trọn Đời.!!!</span
                >
            </div>
            <div class="my-3">KINGSHOES.VN "You're King In Your Way".!!!</div>
            <router-link to="/" class="linkCheck my-3"
                >KING'S & QUEEN'S Check in Tại KINGSHOES.VN</router-link
            >
            <div class="my-3">
                192/2 Nguyễn Thái Bình, phường 12, quận Tân Bình, thành phố Hồ
                Chí Minh
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref } from "vue";
import HeaderTypeProductCpn from "@/components/Product/HeaderTypeProductCpn.vue";
import Rating from "primevue/rating";
import RadioButton from "primevue/radiobutton";
import InputNumber from "primevue/inputnumber";
import Button from "primevue/button";

export default defineComponent({
    components: {
        HeaderTypeProductCpn,
        Rating,
        RadioButton,
        InputNumber,
        Button,
    },
    setup() {
        const rate = ref(3);
        const sizes = ref([
            { name: "40", value: 40 },
            { name: "41", value: 41 },
            { name: "42", value: 42 },
            { name: "43", value: 43 },
            { name: "44", value: 44 },
        ]);
        const selectedSize = ref<number>();
        const quantity = ref(1);
        return {
            rate,
            sizes,
            selectedSize,
            quantity,
        };
    },
});
</script>

<style lang="scss" scoped>
.product-detail {
    min-height: 20rem;

    .container {
        padding: 4rem 12rem;
        display: flex;

        .list-img-wrap {
            width: 15%;
        }
        .list-img {
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 40rem !important;
            overflow: auto;

            .imgs {
                width: 45%;
                margin-bottom: 2rem;
                border: 0.3rem solid #8d8d8d;
            }
        }

        .show-img {
            width: 46%;

            .img {
                width: 59rem;
                height: 59rem;
            }
        }

        .content {
            width: 49%;
            padding-left: 4rem;

            .img-star {
                width: 3rem;
            }

            .product-name {
                display: block;
                font-size: 3rem;
                font-weight: 700;
                color: #313131;
                text-transform: uppercase;
                margin: 1rem 0 1.5rem 0;
            }

            .product-code {
                display: block;
                font-size: 1.8rem;
                color: #5b5b5b;

                .sup-code {
                    font-weight: bold;
                }
            }

            .product-price {
                display: block;
                font-size: 2.4rem;
                font-weight: bold;
                color: #fb0000;
                margin: 3rem 0;
            }

            .product-size {
                border-top: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
                display: flex;
                flex-wrap: wrap;
                padding: 3rem 0;
                font-size: 2rem;
            }
        }

        :deep(.p-rating-icon) {
            font-size: 2.5rem;
            color: yellow !important;
        }

        :deep(.p-rating-icon.pi-star-fill) {
            color: yellow !important;
        }

        :deep(.field-radiobutton) {
            flex-direction: column;
            justify-content: center;
            margin: 0 1.2rem;
            font-size: 2rem;
        }

        :deep(.p-radiobutton) {
            margin-bottom: 0.5rem;
        }

        :deep(.p-inputtext) {
            font-size: 2rem;
            width: 6rem;
            text-align: center;
        }

        :deep(.p-button-icon-only) {
            padding: 1rem 2rem;
        }

        :deep(.p-button.p-button-lg) {
            padding: 1rem 2rem;
            font-size: 2rem;
            font-weight: 550;
            margin-right: 2rem;
            border-radius: 5rem;
        }

        :deep(.p-button.p-button-lg .p-button-icon) {
            font-size: 2rem;
        }
    }

    .other {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #5b5b5b;
        font-weight: 600;

        .linkCheck {
            text-decoration: none;
            color: #5b5b5b;

            &:hover {
                color: var(--primary-color);
            }
        }
    }
}
</style>

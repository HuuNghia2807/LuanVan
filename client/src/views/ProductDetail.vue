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
                    <span>42</span>
                </div>
                <div>btn</div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref } from "vue";
import HeaderTypeProductCpn from "@/components/Product/HeaderTypeProductCpn.vue";
import Rating from "primevue/rating";

export default defineComponent({
    components: {
        HeaderTypeProductCpn,
        Rating,
    },
    setup() {
        const rate = ref(3);
        return {
            rate,
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
            width: 10%;
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
            width: 50%;

            .img {
                width: 62rem;
                height: 62rem;
            }
        }

        .content {
            width: 40%;
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
            }
        }
    }

    :deep(.p-rating-icon) {
        font-size: 2.5rem;
        color: yellow !important;
    }

    :deep(.p-rating-icon.pi-star-fill) {
        color: yellow !important;
    }
}
</style>

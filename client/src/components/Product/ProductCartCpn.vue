<template>
    <div class="product no-underline" to="/">
        <div class="wrap">
            <router-link to="/">
                <img
                    :src="product?.img"
                    :alt="product?.name"
                    class="img-product"
                />
            </router-link>
            <div class="product-context">
                <router-link to="/" class="name-product">{{
                    product?.name
                }}</router-link>
                <span class="cart-size mt-1">Size giay: 41</span>
                <span class="cart-code-product mt-1"
                    >Mã SP:
                    <span class="font-normal text-black-alpha-80"
                        >CW2288 111</span
                    ></span
                >
                <div class="cart-quantity mt-3">
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
                    <i class="pi pi-times mx-2" style="font-size: 1.3rem"></i>
                    <span class="price price-red">{{ product?.price }} đ</span>
                </div>
                <div class="total-price mt-4">
                    Thành tiền:
                    <span class="price-red">{{ totalPrice }} đ</span>
                </div>
            </div>
            <div class="btn-cart-remove">
                <Button
                    icon="pi pi-times"
                    class="p-button-rounded p-button-danger"
                />
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { computed, defineComponent, ref } from "vue";
import Button from "primevue/button";

import InputNumber from "primevue/inputnumber";

export default defineComponent({
    props: {
        product: { type: Object },
    },
    components: {
        InputNumber,
        Button,
    },
    setup(props) {
        const quantity = ref(1);

        const totalPrice = computed(() => {
            return quantity.value * (props.product?.price as number);
        });
        return {
            quantity,
            totalPrice,
        };
    },
});
</script>

<style lang="scss" scoped>
.product {
    position: relative;
    margin-bottom: 3rem;
    color: var(--black-color);

    .product-context {
        margin: 0 1.5rem;
        flex: 1;
    }

    .wrap {
        display: flex;
        margin: 2rem 0;
        border: 1px solid transparent;
        height: 100%;
    }

    .img-product {
        height: 15rem;
        width: 15rem;
        object-fit: contain;
    }

    .name-product {
        display: block;
        font-size: 2rem;
        color: #333;
        line-height: 1.5em;
        text-transform: capitalize;
        font-weight: bold;
        height: 3rem;
        overflow: hidden;
        text-decoration: none;

        &:hover {
            color: var(--primary-color);
            cursor: pointer;
        }
    }

    .cart-size {
        display: block;
        font-size: 1.4rem;
        padding: 5px 10px;
        background: #ffd600;
        font-weight: 700;
        color: #000;
        width: 11rem;
        text-align: center;
        border-radius: 8px;
    }

    .cart-code-product {
        display: block;
        font-size: 1.4rem;
        color: #5c5c5c;
        font-weight: 700;
    }

    .cart-quantity {
        display: flex;
        align-items: center;
    }

    .product-price {
        margin: 0.6rem 0 1rem 0;
    }

    .price {
        display: block;
        font-size: 1.6rem;
        font-weight: 550;
    }

    .total-price {
        display: block;
        font-size: 1.6rem;
        font-weight: bold;
        color: #737373;
    }

    .price-red {
        color: var(--red-500);
    }

    .btn-cart-remove {
        width: 15%;
    }

    .btn-cart-remove {
        display: flex;
        justify-content: center;
        align-items: center;

        :deep(.pi) {
            font-size: 1.8rem;
            font-weight: 700;
        }
    }

    :deep(.p-inputtext) {
        font-size: 1.4rem;
        width: 4rem;
        text-align: center;
    }

    :deep(.p-button-icon-only) {
        padding: 0.5rem 1.4rem;
    }

    :deep(.p-button.p-button-icon-only.p-button-rounded) {
        height: 4rem;
        width: 4rem;
        background-color: #ff0000;
    }
}
</style>

<template>
    <div class="list-product grid">
        <ProductCpn
            v-for="(product, i) in products"
            :key="i"
            :product="product"
            :type="type"
        />
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import ProductCpn from "./ProductCpn.vue";

export default defineComponent({
    props: {
        products: { type: Array },
        type: { type: String },
    },
    components: {
        ProductCpn,
    },
    setup() {
        return {};
    },
});
</script>

<style lang="scss" scoped>
.list-product {
    width: 100%;
}
</style>

<template>
    <div class="product-type">
        <HeaderTypeProductCpn />
        <div class="container">
            <span class="title">Trang chủ > Sản phẩm</span>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import HeaderTypeProductCpn from "@/components/Product/HeaderTypeProductCpn.vue";

export default defineComponent({
    components: {
        HeaderTypeProductCpn,
    },
    setup() {
        return {};
    },
});
</script>

<style lang="scss" scoped>
.product-type {
    min-height: 500px;
    width: 100%;
}
</style>

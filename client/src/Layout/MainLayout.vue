<template>
    <div class="main-layout">
        <HeaderCpn />
        <router-view />
        <FooterCpn />
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import HeaderCpn from "@/components/HeaderCpn.vue";
import FooterCpn from "@/components/FooterCpn.vue";

export default defineComponent({
    components: {
        HeaderCpn,
        FooterCpn,
    },
    setup() {
        return {};
    },
});
</script>

<style lang="scss" scoped>
.main-layout {
    margin-top: var(--height-header);
}
</style>

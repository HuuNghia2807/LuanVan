<template>
    <div>
        <HeaderCpnVue />
        <router-view />
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import HeaderCpnVue from "@/components/HeaderCpn.vue";

export default defineComponent({
    components: {
        HeaderCpnVue,
    },
    setup() {
        return {};
    },
});
</script>

<style scoped></style>

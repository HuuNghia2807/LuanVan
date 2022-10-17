<template>
  <div class="main-layout">
    <HeaderCpn />
    <router-view />
    <FooterCpn />
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import { useStore } from "vuex";
import { setStateCart } from "@/function/handleLocalStorage";
import HeaderCpn from "@/components/HeaderCpn.vue";
import FooterCpn from "@/components/FooterCpn.vue";

export default defineComponent({
  components: {
    HeaderCpn,
    FooterCpn,
  },
  setup() {
    const store = useStore();
    onMounted(async () => {
      await store.dispatch("product/getProducts");
      setStateCart(store);
    });
    return {};
  },
});
</script>

<style lang="scss" scoped>
.main-layout {
  margin-top: var(--height-header);
}
</style>

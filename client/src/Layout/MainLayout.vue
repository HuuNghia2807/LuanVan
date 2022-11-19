<template>
  <TheLoader :is-loading="showLoading" />
  <div class="main-layout">
    <HeaderCpn />
    <router-view />
    <FooterCpn />
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { setCustomerLogin, setStateCart } from "@/function/handleLocalStorage";
import HeaderCpn from "@/components/HeaderCpn.vue";
import FooterCpn from "@/components/FooterCpn.vue";
import TheLoader from "@/components/common/TheLoader.vue";
export default defineComponent({
  components: {
    HeaderCpn,
    FooterCpn,
    TheLoader,
  },
  setup() {
    const store = useStore();
    const showLoading = ref(false);
    onMounted(async () => {
      // showLoading.value = true;
      await store.dispatch("product/getProducts");
      setStateCart(store);
      setCustomerLogin(store);
      // showLoading.value = false;
    });
    return {
      showLoading,
    };
  },
});
</script>

<style lang="scss" scoped>
.main-layout {
  margin-top: var(--height-header);
}
</style>

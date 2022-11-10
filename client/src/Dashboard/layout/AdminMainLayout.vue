<template>
  <div class="main-dashboard">
    <div class="sidebar__main">
      <SidebarCpn />
    </div>
    <div class="container">
      <div>
        <HeaderAdminCpn />
      </div>
      <div class="content">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import SidebarCpn from "@/Dashboard/components/SidebarCpn.vue";
import HeaderAdminCpn from "@/Dashboard/components/HeaderAdminCpn.vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

export default defineComponent({
  components: {
    SidebarCpn,
    HeaderAdminCpn,
  },
  setup() {
    const store = useStore();
    const router = useRouter();

    onMounted(() => {
      // if (!store.getters["auth/getIsloggedDashboard"]) {
      //   router.push("/login");
      // }
    });
    return {};
  },
});
</script>

<style lang="scss" scoped>
.main-dashboard {
  min-height: 100vh;
  display: flex;

  .sidebar__main {
    width: 18%;
    min-height: 100vh;
    background-color: #10182b;
  }

  .container {
    flex: 1;
  }
}
</style>

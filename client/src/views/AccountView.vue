<template>
  <div class="account">
    <div class="container">
      <TabView ref="tabview1">
        <TabPanel header="ĐĂNG NHẬP" id="tab-login">
          <LoginCpn />
        </TabPanel>
        <TabPanel header="ĐĂNG KÝ" id="tab-register">
          <RegisterCpn />
        </TabPanel>
      </TabView>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import TabView from "primevue/tabview";
import LoginCpn from "@/components/User/LoginCpn.vue";
import TabPanel from "primevue/tabpanel";
import RegisterCpn from "@/components/User/RegisterCpn.vue";

export default defineComponent({
  components: {
    TabView,
    TabPanel,
    LoginCpn,
    RegisterCpn,
  },
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
.account {
  display: flex;
  justify-content: flex-end;
  width: 100vw;
  height: 100vh;
  background-image: url("@/assets/img/background-login.jpg");
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;

  .container {
    margin: 10rem 25rem 0 0;
    width: 45rem;
    height: 50rem;
    background-color: #fff;
    border-radius: 10px;
    padding: 1rem;
  }

  :deep(.p-component) {
    font-size: 1.6rem !important;
  }
}
</style>

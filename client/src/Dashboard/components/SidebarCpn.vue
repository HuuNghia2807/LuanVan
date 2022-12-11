<template>
  <div class="sidebar">
    <router-link to="/dashboard" class="img-link">
      <img class="logo" src="@/assets/img/logo-2.png" alt="logo" />
      <div>
        <span class="text-logo">KINGSHOES.VN</span>
        <span class="text-logo text-lg">MANAGE</span>
      </div>
    </router-link>
    <div class="line" />
    <div class="user">
      <div class="avatar">
        <img
          :src="
            employee?.avatar ||
            require('@/assets/img/avatar_default/default-avatar.png')
          "
          alt="avatar"
        />
      </div>
      <div class="name">
        <span>{{ employee?.fullName }}</span>
        <router-link to="/dashboard/personal" class="no-underline">
          <i
            class="pi pi-cog text-white"
            style="font-size: 1.6rem"
            v-tooltip="'Chỉnh sửa thông tin cá nhân'"
          ></i>
        </router-link>
      </div>
    </div>
    <div class="line -mx-5" />
    <div class="list-menu">
      <router-link
        v-for="(item, i) in menuSidebar"
        :to="item.link"
        :key="i"
        class="item"
        v-show="item.accept.includes(employee.role || '')"
      >
        <i :class="item.icon" style="font-size: 2.4rem"></i>
        <span class="text">{{ item.name }}</span>
      </router-link>
    </div>
  </div>
</template>

<script lang="ts">
import { getItemLocal } from "@/function/handleLocalStorage";
import { IEmployee } from "@/interface/auth/authentication.state";
import { computed, defineComponent, ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

export default defineComponent({
  components: {},
  setup() {
    const router = useRouter();
    const store = useStore();
    const menuSidebar = ref([
      {
        name: "Dashboard",
        icon: "pi pi-home",
        link: "/dashboard",
        accept: ["Admin", "Manage"],
      },
      {
        name: "Products",
        icon: "pi pi-shopping-bag",
        link: "/dashboard/products",
        accept: ["Admin", "Manage"],
      },
      {
        name: "Users",
        icon: "pi pi-user",
        link: "/dashboard/user",
        accept: ["Admin"],
      },
      {
        name: "Orders",
        icon: "pi pi-money-bill",
        link: "/dashboard/order",
        accept: ["Admin", "Manage", "Employee"],
      },
      // {
      //   name: "Report",
      //   icon: "pi pi-chart-bar",
      //   link: "/dashboard/report",
      //   accept: ["Admin", "Manage"],
      // },
    ]);
    const employee = computed(() => {
      return (store.getters["auth/getUserDashboard"] ||
        getItemLocal("userDashboard") ||
        null) as IEmployee;
    });

    return {
      menuSidebar,
      employee,
    };
  },
});
</script>

<style lang="scss" scoped>
.sidebar {
  padding: 2rem 2.5rem 0 2.5rem;
  width: 100%;
  z-index: 2;
  min-height: 100vh;

  .img-link {
    text-decoration: none;
    display: flex;
    align-items: center;
    width: 100%;
    color: var(--white-color);

    .logo {
      height: 4rem;
      margin-right: 1rem;
    }
    .text-logo {
      font-size: 1.4rem;
      display: block;
    }
  }
  .line {
    margin-top: 1rem;
    display: block;
    height: 1px;
    border-bottom: 1px solid #fff;
  }

  .user {
    margin: 2rem 0;
    display: flex;
    align-items: center;
    cursor: pointer;

    .avatar {
      img {
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
      }
    }

    .name {
      flex: 1;
      display: flex;
      margin-left: 1rem;
      justify-content: space-between;
      color: var(--white-color);
      font-size: 1.4rem;
    }
  }

  .list-menu {
    margin-top: 2rem;

    .item {
      display: flex;
      padding: 1.3rem 2.5rem;
      align-items: center;
      text-decoration: none;
      margin: 1.5rem 0;
      border-radius: 1rem;
      color: var(--white-color);

      .text {
        font-size: 1.4rem;
        text-transform: uppercase;
        margin-left: 2rem;
        height: 1.4rem;
      }

      &:hover {
        background-color: hsla(0, 0%, 100%, 0.18);
      }
    }

    .active {
      background-color: hsla(0, 0%, 100%, 0.23);
    }
  }
}
</style>

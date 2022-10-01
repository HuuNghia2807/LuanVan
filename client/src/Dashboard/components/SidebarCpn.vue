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
      <Avatar
        image="https://scontent.fsgn5-8.fna.fbcdn.net/v/t1.6435-9/180041959_1021589654914677_1918695516674184677_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=8bfeb9&_nc_ohc=GAZqmffjXJAAX_LTr5B&_nc_ht=scontent.fsgn5-8.fna&oh=00_AT_yu1G92NwRXDPNOSeU6mLpSBtyirIhPevgqVX-L3sidg&oe=635C3CB9"
        class="avatar"
        shape="circle"
      />
      <div class="name">
        <span>NGUYEN NGHIA</span>
        <i class="pi pi-cog" style="font-size: 1.6rem"></i>
      </div>
    </div>
    <div class="line -mx-5" />
    <div class="list-menu">
      <router-link
        v-for="(item, i) in menuSidebar"
        :to="item.link"
        :key="i"
        class="item"
      >
        <i :class="item.icon" style="font-size: 2.4rem"></i>
        <span class="text">{{ item.name }}</span>
      </router-link>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import Avatar from "primevue/avatar";

export default defineComponent({
  components: {
    Avatar,
  },
  setup() {
    const menuSidebar = ref([
      { name: "Dashboard", icon: "pi pi-home", link: "/dashboard" },
      {
        name: "Products",
        icon: "pi pi-shopping-bag",
        link: "/dashboard/products",
      },
      {
        name: "Users",
        icon: "pi pi-user",
        link: "/dashboard/user",
      },
      {
        name: "Orders",
        icon: "pi pi-money-bill",
        link: "/dashboard/order",
      },
      {
        name: "Report",
        icon: "pi pi-chart-bar",
        link: "/dashboard/report",
      },
      {
        name: "Logout",
        icon: "pi pi-sign-out",
        link: "/login",
      },
    ]);
    return {
      menuSidebar,
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
      width: 4rem;
      height: 4rem;
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

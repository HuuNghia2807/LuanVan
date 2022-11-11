<template>
  <div class="header-admin">
    <div class="title">
      <my-button
        icon="pi pi-bars"
        class="p-button-rounded p-button-secondary p-button-text"
      />
      <h3 class="text">Dashboard</h3>
    </div>
    <div class="nav-list">
      <InputText
        class="input"
        type="text"
        v-model="search"
        placeholder="Search"
      />
      <div class="notification">
        <i
          class="pi pi-bell p-text-warning"
          style="font-size: 2rem"
          v-badge="2"
        ></i>
      </div>
      <div
        class="user"
        @mouseover="toggleShowSubMenu(true)"
        @mouseleave="toggleShowSubMenu(false)"
      >
        <i class="pi pi-user" style="font-size: 1.6rem"></i>
        <div
          class="sub-user"
          v-show="isShowSubmenu"
          @mouseover="toggleShowSubMenu(true)"
          @mouseleave="toggleShowSubMenu(false)"
        >
          <ul class="special-list flex flex-column z-100">
            <li class="opacity-100 z-5 control-item">
              <a href="#" class="font-normal text-left" @click="handleClick">
                <div class="text-2xl mr-2"></div>
                Đăng Xuất
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import InputText from "primevue/inputtext";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

export default defineComponent({
  components: {
    InputText,
  },
  setup() {
    const router = useRouter();
    const store = useStore();
    const search = ref("");
    const isShowSubmenu = ref(false);
    const toggleShowSubMenu = (show?: boolean) => {
      isShowSubmenu.value = show || false;
    };
    const handleClick = () => {
      store.dispatch("auth/logoutDashboard");
      router.push("/dashboard");
    };
    return {
      search,
      isShowSubmenu,
      handleClick,
      toggleShowSubMenu,
    };
  },
});
</script>

<style lang="scss" scoped>
.header-admin {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 6rem;
  background-color: #fff;
  padding: 0 2rem;
  border-bottom: 1px solid #ccc;

  .title {
    display: flex;
    align-items: center;

    .text {
      font-size: 1.7rem;
      font-weight: 400;
      margin-left: 1.5rem;
    }

    :deep(.pi) {
      font-size: 1.8rem;
      color: #10182b;
    }
  }

  .nav-list {
    display: flex;
    align-items: center;
    margin-right: 2rem;

    .input {
      padding: 0.6rem 1rem;
      font-size: 1.5rem;
    }

    .notification {
      cursor: pointer;
      margin: 0 2rem;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 50%;
      padding: 0.6rem;

      &:hover {
        background-color: #ccc;
      }
    }

    .user {
      border-radius: 50%;
      border: 1px solid blue;
      position: relative;
      padding: 0.5rem 0.8rem;
      cursor: pointer;

      .sub-user {
        position: absolute;
        top: 100%;
        right: -50%;
      }

      .special-list {
        margin-top: 0;
        background-color: #fff;
        border: solid 1px #f3f2f1;
        border-radius: 10px;
        background-color: #f3f2f1;
        min-width: 8rem;
        list-style: none;
        box-shadow: 0.2rem 0.2rem 0.2rem #ccc;
      }

      .control-item {
        display: flex;
        padding: 1rem;
        cursor: pointer;

        &:hover {
          background-color: var(--white-color);
        }

        a {
          min-width: 120px;
          text-decoration: none;
          color: #000;
        }
      }

      .z-100 {
        z-index: 100;
      }
    }

    :deep(.p-button-rounded) {
      height: 3.5rem;
      width: 3.5rem;
    }
    :deep(.pi-user) {
      font-size: 1.5rem;
    }
  }
}
</style>

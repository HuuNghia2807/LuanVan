<template>
  <div class="header flex align-items-center justify-content-between">
    <div class="flex align-items-center justify-content-between">
      <router-link class="h-6rem cursor-pointer" to="/">
        <img src="@/assets/img/logo.png" alt="logo" class="h-6rem" />
      </router-link>
      <div class="mx-8 flex flex-wrap">
        <router-link
          class="item-menu"
          v-for="item in listMenu"
          :key="item.name"
          :to="`/${item.link}`"
        >
          {{ item.name }}
        </router-link>
      </div>
    </div>

    <div class="flex align-items-center justify-content-between">
      <div class="p-input-icon-right">
        <i class="icon-search pi pi-search" />
        <InputText
          type="text"
          placeholder="Nhập sản phẩm cần tìm"
          class="p-inputtext-lg input"
        />
      </div>
      <router-link
        to="/gio-hang"
        class="icon-cart mx-4"
        aria-controls="overlay_panel"
        aria-haspopup="true"
        @mouseover="showCart"
        @mouseleave="hideCart"
      >
        <i class="icon pi pi-shopping-cart" style="font-size: 2.2rem">
          <span class="badge-icon-cart">{{ cartQuantity.length }}</span>
        </i>
        <div
          class="cart-header"
          v-show="isShow"
          @mouseleave="hideCart"
          @mouseover="showCart"
        >
          <!-- <CartView /> -->
          <DataTable
            :value="cartList"
            selectionMode="single"
            :rows="6"
            responsiveLayout="scroll"
          >
            <my-column field="name" header="Tên" style="width: 20%"></my-column>
            <my-column header="Ảnh" style="width: 20%">
              <template #body="slotProps">
                <img
                  :src="slotProps.data.img"
                  :alt="slotProps.data.name"
                  class="product-image"
                />
              </template>
            </my-column>
            <my-column header="Size" style="width: 20%">
              <template #body="slotProps">
                {{ slotProps.data.size }}
              </template>
            </my-column>
            <my-column header="Giá" sortable style="width: 20%">
              <template #body="slotProps">
                {{ formatPrice(slotProps.data.price) }}
              </template>
            </my-column>
            <my-column header="Số lượng" style="width: 20%">
              <template #body="slotProps">
                {{ slotProps.data.quantity }}
              </template>
            </my-column>
          </DataTable>
        </div>
      </router-link>
      <div class="account">
        <div
          class="flex align-items-center justify-content-center"
          v-if="!customer"
        >
          <router-link to="/account" class="login no-underline"
            >Đăng nhập</router-link
          >
          <span class="line" />
          <router-link to="/account" class="login no-underline"
            >Đăng ký</router-link
          >
        </div>
        <div
          class="user"
          v-else
          @mouseover="toggleShowSubMenu(true)"
          @mouseleave="toggleShowSubMenu(false)"
        >
          <img
            :src="
              customer.avatar ||
              require('@/assets/img/avatar_default/default-avatar.png')
            "
            class="img"
          />
          <span>{{ customer.fullName }}</span>
          <div
            class="sub-user"
            v-show="isShowUser"
            @mouseover="toggleShowSubMenu(true)"
            @mouseleave="toggleShowSubMenu(false)"
          >
            <ul class="special-list flex flex-column z-100">
              <li
                v-for="action of subMenuItems"
                :key="action.label"
                class="opacity-100 z-5 control-item"
              >
                <a
                  href="#"
                  class="font-normal text-left"
                  @click="handleClick(action.action)"
                >
                  <div :class="action.icon" class="text-2xl mr-2"></div>
                  {{ action.label }}
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { computed, defineComponent, ref } from "vue";
import { useStore } from "vuex";
import InputText from "primevue/inputtext";
import { ICart, IProduct } from "@/interface/product/product.state";
import { getCartList } from "@/function/getCartList";
import { formatPrice } from "@/function/common";
import { getItemLocal } from "@/function/handleLocalStorage";
import { ICustomer } from "@/interface/auth/authentication.state";
import { useRouter } from "vue-router";
import CartView from "@/views/CartView.vue";

export default defineComponent({
  components: {
    InputText,
    // CartView,
  },
  setup() {
    const store = useStore();
    const router = useRouter();
    const listMenu = ref([
      { name: "NIKE", link: "nike" },
      { name: "ADIDAS", link: "adidas" },
      { name: "JORDAN", link: "jordan" },
      { name: "YEZZY", link: "yezzy" },
      { name: "SALE", link: "sale" },
      { name: "LIÊN HỆ", link: "contact" },
    ]);
    const isShow = ref(false);
    const isShowUser = ref(false);

    const cartList = computed(() => {
      const listProduct: IProduct[] =
        store.getters["product/getProducts"] || [];
      const cartItem: ICart[] = store.getters["auth/getCart"] || [];

      return getCartList(listProduct, cartItem);
    });

    const subMenuItems = [
      {
        label: "Trang cá nhân",
        icon: "pi pi-user",
        action: "personal",
      },
      {
        label: "Đăng xuất",
        icon: "pi pi-fw pi-power-off",
        action: "logout",
      },
    ];

    const customer = computed(() => {
      return (store.getters["auth/getUser"] ||
        getItemLocal("customer") ||
        null) as ICustomer;
    });

    const cartQuantity = computed(() => {
      return store.getters["auth/getCart"] || [];
    });

    const showCart = () => {
      isShow.value = true;
    };

    const hideCart = () => {
      isShow.value = false;
    };

    const handleClick = (ac: string) => {
      switch (ac) {
        case "personal":
          router.push({ path: "/profile", query: { user: customer.value.id } });
          break;
        case "logout":
          store.dispatch("auth/logout");
          router.push("/");
          break;
        default:
          break;
      }
    };

    const toggleShowSubMenu = (show?: boolean) => {
      isShowUser.value = show || false;
    };

    return {
      listMenu,
      cartQuantity,
      cartList,
      isShow,
      customer,
      subMenuItems,
      isShowUser,
      toggleShowSubMenu,
      handleClick,
      hideCart,
      showCart,
      formatPrice,
    };
  },
});
</script>

<style lang="scss" scoped>
.product-image {
  width: 6rem;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}
.header {
  position: fixed;
  height: var(--height-header);
  width: 100vw;
  top: 0;
  z-index: 3;
  padding: 0 5rem;
  border-top: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
  background-color: var(--white-color);

  :deep(.p-overlaypanel) {
    font-size: 1.6rem !important;
  }
}

@keyframes fill-color {
  0% {
    fill: transparent;
  }
  50% {
    fill: rgb(235, 217, 112);
  }
  100% {
    fill: var(--primary-color);
  }
}

.item-menu {
  border-radius: 50px;
  padding: 8px 18px;
  text-decoration: none;
  font-size: 1.5rem;
  font-weight: 600;
  margin: 0 0.4rem;
  color: var(--black-color);
  background-color: transparent;
  transition: all 0.4s ease;

  &:hover {
    background-color: var(--primary-color);
    color: var(--white-color);
    animation: fill-color 0.5s linear forwards;
  }
}

.input {
  height: 4rem;
  width: 20rem;
  border: none;
  border-color: none;
  background-color: #f3f3f3;
  border-radius: 40px;
  font-size: 1.3rem;
  padding-left: 20px !important;
  padding-right: 30px !important;
}
.input:focus {
  box-shadow: none !important;
  border-color: none !important;
}

.icon-search {
  font-size: 1.4rem;
  padding-right: 8px;
  color: #000;
  cursor: pointer;

  &:hover {
    color: var(--primary-color);
  }
}

.icon-cart {
  border: 1px solid #ccc;
  background-color: var(--black-color);
  border-radius: 50%;
  cursor: pointer;
  position: relative;

  .cart-header {
    position: absolute;
    top: 100%;
    right: -300%;
    border-radius: 10px;
    min-width: 40rem;
    height: 40rem;
  }

  &:hover {
    background-color: var(--primary-color);
  }
}

.icon {
  position: relative;
  color: var(--white-color);
  padding: 10px;

  .badge-icon-cart {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--white-color);
    color: var(--white-color);
    background-color: var(--primary-color);
    border-radius: 50%;
    font-size: 1.5rem;
    width: 25px;
    height: 25px;
    padding: 5px;
    right: -8px;
    top: -9px;
  }
}

.account {
  .line {
    border-right: 2px solid var(--black-color);
    height: 20px;
    margin: 0 0.8rem;
  }
}

.login {
  color: var(--black-color);
  font-size: 1.4rem;
  cursor: pointer;

  &:hover {
    color: var(--primary-color);
  }
}

.user {
  display: flex;
  align-items: center;
  position: relative;
  min-width: 15rem;
  .img {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    object-fit: contain;
    border: 1px solid #ccc;
  }

  span {
    margin-left: 1rem;
    font-size: 1.5rem;
  }

  .sub-user {
    position: absolute;
    top: 100%;
    width: 100%;
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
</style>

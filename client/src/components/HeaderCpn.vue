<template>
  <div class="header flex align-items-center justify-content-between">
    <div class="flex align-items-center justify-content-between">
      <div class="side-responsive">
        <my-button
          icon="pi pi-align-justify"
          @click="visibleLeft = true"
          class="mr-4 p-button-rounded p-button-secondary p-button-outlined"
        />
        <Sidebar v-model:visible="visibleLeft" :baseZIndex="10000">
          <div class="categories-responsive">
            <router-link
              class="item-menu my-1"
              :class="{
                active: routePath.toLowerCase() === item.link.toLowerCase(),
              }"
              v-for="item in listMenu"
              :key="item.name"
              :to="{ name: 'category', params: { branch: item.link } }"
              @click="visibleLeft = false"
            >
              {{ item.name }}
            </router-link>
          </div>
        </Sidebar>
      </div>
      <router-link class="logo-home cursor-pointer" to="/">
        <img src="@/assets/img/logo-store.jpg" alt="logo" />
      </router-link>
      <div class="categories">
        <router-link
          class="item-menu my-1"
          :class="{
            active: routePath.toLowerCase() === item.link.toLowerCase(),
          }"
          v-for="item in listMenu"
          :key="item.name"
          :to="{ name: 'category', params: { branch: item.link } }"
        >
          {{ item.name }}
        </router-link>
      </div>
    </div>

    <div class="flex align-items-center justify-content-between">
      <div class="p-input-icon-right relative">
        <i
          class="icon-search pi pi-microphone"
          :class="{ 'voice-active': isVoice }"
          @click="handleSearchByVoice"
        />
        <my-inputText
          id="search-text"
          type="text"
          :placeholder="isVoice ? 'Đang lắng nghe...' : 'Nhập sản phẩm cần tìm'"
          class="p-inputtext-lg input"
          v-model="searchText"
          @focus="showProductSearch"
        />
        <div
          v-show="displayProductSearch"
          class="subSearch"
          @mouseleave="hideProductSearch"
        >
          <ProductSearch
            :product-list="listProductSearch"
            @hide-product-search="hideProductSearch"
          />
        </div>
      </div>
      <div class="search-responsive">
        <my-button
          icon="pi pi-search"
          class="p-button-rounded p-button-secondary p-button-outlined"
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
        <div class="account-responsive" v-if="!customer">
          <my-button
            id="login-link-responsive"
            label="Login"
            class="p-button-raised p-button-secondary p-button-text"
            @click="goToLoginPage"
          />
        </div>
        <div class="menu-account" v-if="!customer">
          <router-link to="/account" class="login no-underline" id="login-link"
            >Đăng nhập</router-link
          >
          <span class="line" />
          <router-link
            to="/account"
            class="login no-underline"
            id="register-link"
            >Đăng ký</router-link
          >
        </div>
        <div
          class="user"
          id="user-login"
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
          <span class="full-name">{{ customer.fullName }}</span>
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
import { computed, defineComponent, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import {
  ICart,
  ICategory,
  ICategoryRouting,
  IProduct,
} from "@/interface/product/product.state";
import { getCartList } from "@/function/getCartList";
import { formatPrice } from "@/function/common";
import { getItemLocal } from "@/function/handleLocalStorage";
import { ICustomer } from "@/interface/auth/authentication.state";
import { useRoute, useRouter } from "vue-router";
import ProductSearch from "./Product/ProductSearch.vue";
import Sidebar from "primevue/sidebar";

export default defineComponent({
  components: {
    ProductSearch,
    Sidebar,
  },
  setup() {
    const store = useStore();
    const router = useRouter();
    const route = useRoute();
    const visibleLeft = ref(false);
    const listMenu = ref([] as ICategoryRouting[]);
    const isShow = ref(false);
    const isShowUser = ref(false);
    const isVoice = ref(false);
    const searchText = ref("");
    const listProductSearch = ref([] as IProduct[]);
    const displayProductSearch = ref(false);
    const cartList = computed(() => {
      const listProduct: IProduct[] =
        store.getters["product/getProducts"] || [];
      const cartItem: ICart[] = store.getters["auth/getCart"] || [];

      return getCartList(listProduct, cartItem);
    });

    const routePath = computed(() => {
      return route.path.slice(1);
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

    const showProductSearch = () => {
      displayProductSearch.value = true;
    };

    const hideProductSearch = () => {
      displayProductSearch.value = false;
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

    const SpeechRecognition =
      (window as any).SpeechRecognition ||
      (window as any).webkitSpeechRecognition;
    const SpeechGrammarList =
      (window as any).SpeechGrammarList ||
      (window as any).webkitSpeechGrammarList;

    const grammar = "#JSGF V1.0;";

    const recognition = new SpeechRecognition();
    const speechRecognitionList = new SpeechGrammarList();
    speechRecognitionList.addFromString(grammar, 1);
    recognition.grammars = speechRecognitionList;
    recognition.lang = "vi-VN";
    recognition.interimResults = false;

    recognition.onresult = async (event: any) => {
      var lastResult = event.results.length - 1;
      var content = event.results[lastResult][0].transcript;
      searchText.value = content;
      listProductSearch.value = await store.dispatch(
        "product/searchProduct",
        searchText.value
      );
    };

    recognition.onspeechend = () => {
      recognition.stop();
      isVoice.value = false;
    };

    recognition.onerror = (event: any) => {
      searchText.value = "Error occurred in recognition: " + event.error;
    };

    const handleSearchByVoice = () => {
      recognition.start();
      isVoice.value = true;
    };

    const goToLoginPage = () => {
      router.push("/account");
    };

    watch(searchText, async () => {
      displayProductSearch.value = true;
      listProductSearch.value = await store.dispatch(
        "product/searchProduct",
        searchText.value
      );
      if (searchText.value === "") {
        listProductSearch.value = [];
        displayProductSearch.value = false;
      }
    });

    onMounted(async () => {
      const cate = (await store.dispatch(
        "product/getCategories"
      )) as ICategory[];
      listMenu.value = cate.map((ele) => {
        return {
          name: ele.categoryName.toUpperCase(),
          link: ele.categoryName.toLowerCase(),
        };
      });
      listMenu.value.unshift({ name: "SẢN PHẨM", link: "product" });
      listMenu.value.push({ name: "SALE", link: "sale" });
      store.commit("product/setCategories", listMenu.value);
    });

    return {
      listMenu,
      cartQuantity,
      cartList,
      isShow,
      customer,
      subMenuItems,
      isShowUser,
      searchText,
      listProductSearch,
      displayProductSearch,
      routePath,
      isVoice,
      visibleLeft,
      goToLoginPage,
      handleSearchByVoice,
      hideProductSearch,
      showProductSearch,
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
  min-height: var(--height-header);
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

.side-responsive {
  display: none;
}

.logo-home {
  height: 6rem;

  img {
    height: 6rem;
    width: 16rem;
    object-fit: cover;
  }
}

.categories {
  margin-left: 1.5rem;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
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
    opacity: 0.7;
    color: var(--white-color);
    animation: fill-color 0.5s linear forwards;
  }
}

.active {
  background-color: var(--primary-color);
  color: var(--white-color);
  animation: fill-color 0.5s linear forwards;
}

.input {
  height: 4rem;
  width: 30rem;
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
  color: #000;
  cursor: pointer;
  border-radius: 50%;
  padding: 1rem;

  &:hover {
    color: var(--primary-color);
  }
}

.voice-active {
  background-color: rgb(255, 179, 117);
  color: blue;
}

.search-responsive {
  display: none;
}

:deep(.p-input-icon-right > i) {
  top: 18%;
}

.subSearch {
  position: absolute;
  top: 100%;
  background-color: #fff;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
  width: 100%;
  min-height: 5rem;
  border-radius: 5px;
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

.account-responsive {
  display: none;
}

.account {
  .menu-account {
    display: flex;
    align-items: center;
    justify-content: center;
  }
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
    width: 10rem;
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

@media screen and (min-width: 1px) and (max-width: 1179px) {
  .full-name {
    display: none;
  }
  .user {
    min-width: 0;
  }
  .categories-responsive {
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  .menu-account {
    display: none !important;
  }
  .account-responsive {
    display: block;
  }
  .side-responsive {
    display: block;
  }
  .header {
    padding: 0 2rem;
  }
  .categories {
    display: none;
  }
  .sub-user {
    right: 235%;
  }
  .special-list {
    width: 15rem;
  }
}

@media screen and (min-width: 769px) and (max-width: 1179px) {
}

@media screen and (max-width: 768px) {
  :deep(.p-input-icon-right) {
    display: none;
  }

  .icon {
    font-size: 1.3rem !important;
  }
  .badge-icon-cart {
    width: 20px !important;
    height: 20px !important;
  }
  .search-responsive {
    display: block;
  }
}
</style>

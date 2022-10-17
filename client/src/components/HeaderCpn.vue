<template>
  <div class="header flex align-items-center justify-content-between">
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
    >
      <i class="icon pi pi-shopping-cart" style="font-size: 2.2rem">
        <span class="badge-icon-cart">{{ cartQuantity.length }}</span>
      </i>
      <OverlayPanel
        ref="op"
        appendTo="body"
        id="overlay_panel"
        style="width: 450px"
        :breakpoints="{ '960px': '75vw' }"
        @mouseleave="showCart"
      >
        <DataTable
          :value="cartList"
          selectionMode="single"
          :rows="6"
          responsiveLayout="scroll"
        >
          <Column field="name" header="Tên" style="width: 20%"></Column>
          <Column header="Ảnh" style="width: 20%">
            <template #body="slotProps">
              <img
                :src="slotProps.data.img"
                :alt="slotProps.data.name"
                class="product-image"
              />
            </template>
          </Column>
          <Column header="Size" style="width: 20%">
            <template #body="slotProps">
              {{ slotProps.data.size }}
            </template>
          </Column>
          <Column header="Giá" sortable style="width: 20%">
            <template #body="slotProps">
              {{ formatPrice(slotProps.data.price) }}
            </template>
          </Column>
          <Column header="Số lượng" style="width: 20%">
            <template #body="slotProps">
              {{ slotProps.data.quantity }}
            </template>
          </Column>
        </DataTable>
      </OverlayPanel>
    </router-link>
    <div class="account">
      <div class="flex align-items-center justify-content-center">
        <router-link to="/account" class="login no-underline"
          >Đăng nhập</router-link
        >
        <span class="line" />
        <router-link to="/account" class="login no-underline"
          >Đăng ký</router-link
        >
      </div>
      <div class="user">user</div>
    </div>
  </div>
</template>
<script lang="ts">
import { computed, defineComponent, ref } from "vue";
import { useStore } from "vuex";
import InputText from "primevue/inputtext";
import OverlayPanel from "primevue/overlaypanel";
import Column from "primevue/column";
import { ICart, IProduct } from "@/interface/product/product.state";
import { getCartList } from "@/function/getCartList";
import { formatPrice } from "@/function/common";

export default defineComponent({
  components: {
    InputText,
    OverlayPanel,
    Column,
  },
  setup() {
    const store = useStore();
    const listMenu = ref([
      { name: "NIKE", link: "nike" },
      { name: "ADIDAS", link: "adidas" },
      { name: "JORDAN", link: "jordan" },
      { name: "YEZZY", link: "yeezy" },
      { name: "SALE", link: "sale" },
      { name: "DÂY GIÀY", link: "day-giay" },
      { name: "LIÊN HỆ", link: "contact" },
    ]);
    const op = ref();
    const cartList = computed(() => {
      const listProduct: IProduct[] =
        store.getters["product/getProducts"] || [];
      const cartItem: ICart[] = store.getters["auth/getCart"] || [];

      return getCartList(listProduct, cartItem);
    });

    const cartQuantity = computed(() => {
      return store.getters["auth/getCart"] || [];
    });

    const showCart = (e: any) => {
      op.value.toggle(e);
    };

    return {
      listMenu,
      cartQuantity,
      cartList,
      op,
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
  z-index: 2;
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

  &:hover {
    background-color: var(--primary-color);
  }
}

.icon {
  position: relative;
  color: var(--white-color);
  padding: 10px;
  transform: rotate(10deg);

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
  display: none;
}
</style>

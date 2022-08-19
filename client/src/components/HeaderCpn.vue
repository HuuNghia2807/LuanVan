<template>
    <div class="header h-9rem flex align-items-center">
        <div class="h-6rem">
            <img src="@/assets/img/logo.png" alt="logo" class="h-6rem" />
        </div>
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
        <div class="icon-cart mx-4">
            <i class="icon pi pi-shopping-cart" style="font-size: 2.2rem">
                <span class="badge-icon-cart">6</span>
            </i>
        </div>
        <div class="account flex-1">
            <div class="flex align-items-center justify-content-center">
                <span class="login">Đăng nhập</span>
                <span class="line" />
                <span class="login">Đăng ký</span>
            </div>
            <div class="user">user</div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref } from "vue";

import InputText from "primevue/inputtext";

export default defineComponent({
    components: {
        InputText,
    },
    setup() {
        const listMenu = ref([
            { name: "NIKE", link: "nike" },
            { name: "ADIDAS", link: "adidas" },
            { name: "JORDAN", link: "jordan" },
            { name: "YEZZY", link: "yeezy" },
            { name: "SALE", link: "sale" },
            { name: "DÂY GIÀY", link: "day-giay" },
            { name: "LIÊN HỆ", link: "contact" },
        ]);

        return {
            listMenu,
        };
    },
});
</script>

<style lang="scss" scoped>
.header {
    padding: 0 5rem;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
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
    font-size: 1.4rem;
    font-weight: 800;
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

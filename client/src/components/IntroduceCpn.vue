<template>
    <div class="introduce">
        <div class="introduce__block">
            <img class="icon" src="@/assets/img/icons/contract.png" alt="" />
            <h2 class="introduce__title">CAM KẾT CHÍNH HÃNG</h2>
            <span class="introduce__context">100 % Authentic</span>
            <span class="text"
                >Cam kết sản phẩm chính hãng từ Châu Âu, Châu Mỹ...</span
            >
        </div>
        <div class="introduce__block">
            <img
                class="icon"
                src="@/assets/img/icons/delivery-truck.png"
                alt=""
            />
            <h2 class="introduce__title">GIAO HÀNG HỎA TỐC</h2>
            <span class="introduce__context">Express delivery</span>
            <span class="text"
                >SHIP hỏa tốc 1h nhận hàng trong nội thành HCM</span
            >
        </div>
        <div class="introduce__block">
            <img class="icon" src="@/assets/img/icons/phone-call.png" alt="" />
            <h2 class="introduce__title">HỖ TRỢ 24/24</h2>
            <span class="introduce__context">Supporting 24/24</span>
            <span class="text">Gọi ngay</span>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    setup() {
        return {};
    },
});
</script>

<style lang="scss" scoped>
.introduce {
    height: 21rem;
    display: flex;
    justify-content: space-around;
    align-items: center;
    background-color: #f0f0f0;

    .introduce__block {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
    }

    .icon {
        width: 5.5rem;
    }

    .introduce__title {
        color: rgb(255, 195, 0);
        margin: 1rem 0 0.5rem 0;
    }

    .introduce__context {
        color: var(--black-color);
        font-size: 1.3rem;
        font-weight: bold;
    }

    .text {
        font-size: 1.3rem;
        color: var(--black-color);
        margin-top: 0.5rem;
    }
}
</style>

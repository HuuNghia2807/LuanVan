<template>
  <div class="report-head">
    <div class="input">
      <div class="input-head">
        <img
          class="icon-image"
          src="@/Dashboard/assets/icon-images/wallet_today.png"
          alt=""
        />
      </div>
      <div class="content">
        <div class="detail">
          <label>Thu Nhập Hôm nay</label>
          <span>{{ formatPrice(total || 0) }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { formatPrice } from "@/function/common";
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    total: { type: Number },
  },
  setup() {
    return {
      formatPrice,
    };
  },
});
</script>

<style lang="scss" scoped>
@import "@/assets/styles/headReport.scss";
</style>

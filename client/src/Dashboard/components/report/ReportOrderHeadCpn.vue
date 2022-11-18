<template>
  <div class="report-head">
    <div class="input">
      <div class="input-head">
        <img
          class="icon-image"
          src="@/Dashboard/assets/icon-images/received.png"
          alt=""
        />
      </div>
      <div class="content">
        <div class="detail">
          <label>Mới</label>
          <span class="text-teal-600">{{ summaryOrder?.new }}</span>
        </div>
        <div class="detail">
          <label>Đơn Hàng</label>
          <span>{{ summaryOrder?.total }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { IHead } from "@/interface/order/order.state";
import { defineComponent, PropType } from "vue";

export default defineComponent({
  props: {
    summaryOrder: { type: Object as PropType<IHead> },
  },
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
@import "@/assets/styles/headReport.scss";
</style>

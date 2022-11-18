<template>
  <div class="report-head">
    <div class="input">
      <div class="input-head">
        <img
          class="icon-image"
          src="@/Dashboard/assets/icon-images/users-report.png"
          alt=""
        />
      </div>
      <div class="content">
        <div class="detail">
          <label>Mới</label>
          <span class="text-teal-600">{{ summaryCustomer?.new }}</span>
        </div>
        <div class="detail">
          <label>Khách Hàng</label>
          <span>{{ summaryCustomer?.total }}</span>
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
    summaryCustomer: { type: Object as PropType<IHead> },
  },
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
@import "@/assets/styles/headReport.scss";
</style>

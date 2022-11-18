<template>
  <ReportDashboardView />
</template>

<script lang="ts">
import { defineComponent } from "vue";
import ReportDashboardView from "./ReportDashboardView.vue";

export default defineComponent({
  components: {
    ReportDashboardView,
  },
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
.dashboard {
  padding: 2rem;
  width: 100%;
  height: 100%;
  background-color: rgb(255, 215, 140);
}
</style>

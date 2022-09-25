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
          <label>New</label>
          <span>1</span>
        </div>
        <div class="detail">
          <label>Users</label>
          <span>2</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    info: { type: Object },
  },
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
.report-head {
  position: relative;
  width: 30%;
  border: 1px solid #ccc;
  border-radius: 10px;
  height: 10rem;
  background-color: #fff;

  .input-head {
    position: absolute;
    left: 1rem;
    top: -3rem;
    border: 1px solid #ccc;
    background-color: orange;
    border-radius: 10px;
    padding: 1rem 2rem;
  }
  .icon-image {
    width: 4rem;
    height: 4rem;
  }

  .content {
    display: flex;
    justify-content: flex-end;
    margin: 4.5rem 2rem 0 0;

    .detail {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
      margin: 0 1rem;

      label {
        transform: translateY(-100%);
      }
    }
  }
}
</style>

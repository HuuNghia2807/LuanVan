<template>
  <div class="header-admin">
    <div class="title">
      <Button
        icon="pi pi-bars"
        class="p-button-rounded p-button-secondary p-button-text"
      />
      <h3 class="text">Dashboard</h3>
    </div>
    <div class="nav-list">
      <InputText
        class="input"
        type="text"
        v-model="search"
        placeholder="Search"
      />
      <div class="notification">
        <i
          class="pi pi-bell p-text-warning"
          style="font-size: 2rem"
          v-badge="2"
        ></i>
      </div>
      <Button
        icon="pi pi-user"
        class="p-button-rounded p-button-info p-button-outlined"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import InputText from "primevue/inputtext";
import Button from "primevue/button";

export default defineComponent({
  components: {
    InputText,
    Button,
  },
  setup() {
    const search = ref("");
    return {
      search,
    };
  },
});
</script>

<style lang="scss" scoped>
.header-admin {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 6rem;
  background-color: #fff;
  padding: 0 2rem;

  .title {
    display: flex;
    align-items: center;

    .text {
      font-size: 1.7rem;
      font-weight: 400;
      margin-left: 1.5rem;
    }

    :deep(.pi) {
      font-size: 1.8rem;
      color: #10182b;
    }
  }

  .nav-list {
    display: flex;
    align-items: center;
    margin-right: 2rem;

    .input {
      padding: 0.6rem 1rem;
      font-size: 1.5rem;
    }

    .notification {
      cursor: pointer;
      margin: 0 2rem;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 50%;
      padding: 0.6rem;

      &:hover {
        background-color: #ccc;
      }
    }

    :deep(.p-button-rounded) {
      height: 3.5rem;
      width: 3.5rem;
    }
    :deep(.pi-user) {
      font-size: 1.5rem;
    }
  }
}
</style>

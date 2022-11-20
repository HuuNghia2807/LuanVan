<template>
  <my-dialog
    :visible="isCategoryModal"
    @update:visible="closeModal"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '40vw' }"
    :modal="true"
  >
    <template #header>
      <h1>Quản Lý Danh Mục Sản Phẩm</h1>
    </template>
    <ScrollPanel style="width: 100%; height: 60vh" class="custombar1">
      <div class="category-name">
        <div class="item head">
          <span class="code">Mã Danh Mục</span>
          <span class="name">Tên Danh Mục</span>
          <div class="action"></div>
        </div>
        <div
          class="item"
          v-for="category in categories"
          :key="category.categoryId"
        >
          <span class="code">{{ category.categoryId }}</span>
          <span class="name">{{ category.categoryName.toUpperCase() }}</span>
          <div class="action">
            <my-button
              label="Đổi tên"
              class="p-button-success p-button-text mr-6"
              @click="handleUpdateCategory(category)"
            />
            <my-button
              label="Xóa"
              class="p-button-danger p-button-text"
              @click="handleDeleteCategory($event, category.categoryId)"
            />
          </div>
        </div>
        <div class="item mt-4" v-if="isAddcategory">
          <div class="w-8">
            <my-inputText
              v-model="categoryVal"
              placeholder="Nhập tên danh mục"
              class="w-full"
              :class="{ 'p-invalid': categoryVal === '' && submitted }"
            />
            <small
              v-if="categoryVal === '' && submitted"
              :class="{ error: categoryVal === '' && submitted }"
              >Vui lòng nhập tên nhanh mục!</small
            >
          </div>
          <div class="flex-1 text-center">
            <my-button
              label="Lưu"
              class="p-button-outlined p-button-secondary"
              @click="addCategory"
            />
          </div>
        </div>
      </div>
    </ScrollPanel>
    <div class="text-right mt-4 mr-4" v-if="!isAddcategory">
      <my-button
        label="Thêm Danh Mục"
        class="p-button-outlined p-button-info"
        @click="handleAddCategory"
      />
    </div>
    <div class="text-right mt-4 mr-4" v-else>
      <my-button
        label="Cancel"
        class="p-button-outlined p-button-warning"
        @click="handleCancel"
      />
    </div>
    <my-toast />
    <ConfirmPopup></ConfirmPopup>
    <UpdateCategory
      v-if="isCategoryUpdate"
      :is-category-update="isCategoryUpdate"
      :category="categoryUpdate"
      @close-modal="closeModalUpdate"
      @handle-update-category="updateCategory"
    />
  </my-dialog>
</template>

<script lang="ts">
import { ICategory, IProduct } from "@/interface/product/product.state";
import { defineComponent, onMounted, ref } from "vue";
import { useStore } from "vuex";
import ScrollPanel from "primevue/scrollpanel";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import ConfirmPopup from "primevue/confirmpopup";
import UpdateCategory from "@/Dashboard/components/modal/UpdateCategory.vue";

export default defineComponent({
  props: {
    isCategoryModal: { type: Boolean, default: false },
  },
  components: {
    ScrollPanel,
    ConfirmPopup,
    UpdateCategory,
  },
  setup(_props, { emit }) {
    const store = useStore();
    const toast = useToast();
    const confirm = useConfirm();
    const categories = ref([] as ICategory[]);
    const categoryVal = ref("");
    const isCategoryUpdate = ref(false);
    const categoryUpdate = ref<ICategory>();
    const isAddcategory = ref(false);
    const submitted = ref(false);

    const closeModal = () => {
      emit("close-modal");
    };

    const handleAddCategory = () => {
      isAddcategory.value = true;
    };

    const addCategory = async () => {
      submitted.value = true;

      if (categoryVal.value !== "") {
        await store.dispatch("product/addCategory", categoryVal.value);
        const err = store.getters["product/getError"];
        if (err) {
          toast.add({
            severity: "error",
            summary: "Thất bại",
            detail: err.data.message,
            life: 5000,
          });
          return;
        }
        toast.add({
          severity: "success",
          summary: "Thành công",
          detail: "Thêm danh mục thành công",
          life: 4000,
        });
        handleCancel();
        loadCategories();
      }
    };

    const handleDeleteCategory = (event: any, id: number) => {
      const product = (store.getters["product/getProducts"] ||
        []) as IProduct[];
      if (product.some((ele) => ele.categoryId === id)) {
        toast.add({
          severity: "error",
          summary: "Thất bại",
          detail: "Có sản phẩm thuộc danh mục này, không thể xóa!",
          life: 5000,
        });
        return;
      }
      confirm.require({
        target: event.currentTarget,
        message: "Bạn muốn xóa danh mục này?",
        icon: "pi pi-exclamation-triangle",
        acceptClass: "p-button-danger",
        accept: async () => {
          await store.dispatch("product/deleteCategory", id);
          toast.add({
            severity: "success",
            summary: "Thành công",
            detail: "Xóa danh mục thành công",
            life: 3000,
          });
          loadCategories();
        },
        reject: () => {
          // toast.add({
          //   severity: "error",
          //   summary: "Rejected",
          //   detail: "You have rejected",
          //   life: 3000,
          // });
        },
      });
    };

    const handleUpdateCategory = (cate: ICategory) => {
      categoryUpdate.value = cate;
      isCategoryUpdate.value = true;
    };

    const updateCategory = async (name: string) => {
      await store.dispatch("product/updateCategory", {
        category_id: categoryUpdate.value?.categoryId,
        category_name: name,
      });
      const err = store.getters["product/getError"];
      console.log("---", err);

      if (err) {
        toast.add({
          severity: "error",
          summary: "Thất bại",
          detail: err.data.message,
          life: 5000,
        });
        return;
      }
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Sửa danh mục thành công",
        life: 4000,
      });
      closeModalUpdate();
      loadCategories();
    };

    const handleCancel = () => {
      submitted.value = false;
      categoryVal.value = "";
      isAddcategory.value = false;
    };

    const closeModalUpdate = () => {
      isCategoryUpdate.value = false;
      categoryUpdate.value = undefined;
    };

    const loadCategories = async () => {
      categories.value = await store.dispatch("product/getCategories");
    };

    onMounted(() => {
      loadCategories();
    });

    return {
      categories,
      categoryVal,
      submitted,
      isAddcategory,
      categoryUpdate,
      isCategoryUpdate,
      updateCategory,
      handleUpdateCategory,
      closeModalUpdate,
      handleDeleteCategory,
      handleCancel,
      addCategory,
      handleAddCategory,
      closeModal,
    };
  },
});
</script>

<style lang="scss" scoped>
:deep(.p-component) {
  font-size: 1.6rem !important;
}
::v-deep(.p-scrollpanel) {
  &.custombar1 {
    .p-scrollpanel-wrapper {
      border-right: 9px solid var(--surface-ground);
    }

    .p-scrollpanel-bar {
      background-color: rgb(202, 202, 202);
      opacity: 1;
      transition: background-color 0.2s;

      &:hover {
        background-color: rgb(158, 158, 158);
      }
    }
  }
}
.category-name {
  font-size: 1.6rem;
  display: flex;
  flex-direction: column;
  border-top: 1px solid #ccc;
  padding: 2rem;

  .head {
    background-color: #ccc;
    font-weight: 700;
    border-radius: 10px;
  }
  .item {
    display: flex;
    align-items: center;

    .code {
      width: 30%;
      padding: 1rem;
    }

    .name {
      width: 30%;
      padding: 1rem;
      font-weight: 700;
    }

    .action {
      width: 40%;
      padding: 1rem;
      display: flex;
      justify-content: flex-end;
    }
    .error {
      color: red;
      font-size: 1.2rem;
      font-style: italic;
    }
  }
}
</style>

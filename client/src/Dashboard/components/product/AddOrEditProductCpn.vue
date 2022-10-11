<template>
  <my-toast />
  <form @submit.prevent="handleSubmit(!v$.$invalid)" class="p-fluid">
    <div class="title">THÊM SẢN PHẨM</div>
    <div class="container">
      <div class="modal-left">
        <div class="field">
          <div class="p-float-label">
            <my-inputText
              id="name"
              v-model="v$.name.$model"
              :class="{
                'p-invalid': v$.name.$invalid && submitted,
              }"
            />
            <label
              for="name"
              class="label"
              :class="{
                'p-error': v$.name.$invalid && submitted,
              }"
            >
              Tên sản phẩm
            </label>
          </div>
          <small
            v-if="(v$.name.$invalid && submitted) || v$.name.$pending"
            class="p-error"
          >
            {{ v$.name.required.$message }}
          </small>
        </div>
        <div class="field">
          <div class="p-float-label">
            <AutoComplete
              id="category"
              v-model="v$.category.$model"
              :suggestions="filterCategory"
              @complete="searchCategory($event)"
              :class="{
                'p-invalid': v$.category.$invalid && submitted,
              }"
            />
            <label
              for="category"
              class="label"
              :class="{
                'p-error': v$.category.$invalid && submitted,
              }"
            >
              Danh mục sản phẩm
            </label>
          </div>
          <small
            v-if="(v$.category.$invalid && submitted) || v$.category.$pending"
            class="p-error"
          >
            {{ v$.category.required.$message }}
          </small>
        </div>
        <div class="field">
          <div class="p-float-label">
            <my-inputNumber
              inputId="integeronly"
              v-model="v$.price.$model"
              :class="{ 'p-invalid': v$.price.$invalid && submitted }"
              aria-describedby="price-error"
            />
            <label
              for="price"
              class="label"
              :class="{ 'p-error': v$.price.$invalid && submitted }"
            >
              Giá sản phẩm
            </label>
          </div>
          <span v-if="v$.price.$error && submitted">
            <span
              id="price-error"
              v-for="(error, index) of v$.price.$errors"
              :key="index"
            >
              <small class="p-error">{{ error.$message }}</small>
            </span>
          </span>
          <small
            v-else-if="(v$.price.$invalid && submitted) || v$.price.$pending"
            class="p-error"
            >{{ v$.price.required.$message }}</small
          >
        </div>
        <div class="field">
          <div class="size-container">
            <div class="size-wrap" v-for="(size, i) in sizeData" :key="i">
              <div class="size">
                <label for="size">Size</label>
                <AutoComplete
                  id="size"
                  v-model="size.size"
                  :suggestions="filterSize"
                  @complete="searchSize($event)"
                />
              </div>
              <div class="size-quantity">
                <label>Số Lượng</label>
                <my-inputText v-model="size.size_quantity" type="number" />
              </div>
            </div>
          </div>
        </div>
        <div class="btn-add-size">
          <my-button
            icon="pi pi-plus"
            class="p-button-rounded p-button-success p-button-outlined"
            @click="handleAddSize"
          />
        </div>
      </div>
      <div class="modal-right">
        <small v-if="messageImageValid" class="p-error">
          {{ messageImageValid }}
        </small>
        <div class="review-images">
          <Galleria
            :value="images"
            :responsiveOptions="responsiveOptions"
            :numVisible="5"
            containerStyle="max-width: 40rem"
          >
            <template #item="slotProps">
              <Image
                :src="slotProps.item.itemImageSrc"
                :alt="slotProps.item.alt"
                preview
              />
            </template>
            <template #thumbnail="slotProps">
              <img
                :src="slotProps.item.thumbnailImageSrc"
                :alt="slotProps.item.alt"
                style="width: 100%; display: block"
              />
            </template>
          </Galleria>
        </div>
        <div class="btn-upload">
          <input
            style="display: none"
            id="upload-image"
            type="file"
            accept="image/*"
            multiple
            @change="onUpload($event)"
          />
          <label class="upload-image" for="upload-image">
            <i class="pi pi-image mr-2" style="font-size: 2rem"></i>
            Chọn ảnh sản phẩm
          </label>
        </div>
      </div>
    </div>
    <div>
      <my-button label="Add" type="submit" icon="pi pi-plus" />
    </div>
  </form>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, reactive, ref } from "vue";

import { useStore } from "vuex";
import { IProductParams, ISizeParams } from "@/interface/product/product.state";
import { helpers, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import AutoComplete from "primevue/autocomplete";
import Galleria from "primevue/galleria";
import Image from "primevue/image";
import { convertToBase64 } from "@/function/convertImage";
import { useToast } from "primevue/usetoast";

export default defineComponent({
  components: {
    AutoComplete,
    Galleria,
    Image,
  },
  setup() {
    const toast = useToast();
    const store = useStore();
    const fileImages = ref([] as any[]);
    const submitted = ref(false);
    const category = ref([]);
    const filterCategory = ref([] as any[]);
    const filterSize = ref([] as ISizeParams[]);
    const responsiveOptions = ref([
      {
        breakpoint: "1024px",
        numVisible: 5,
      },
      {
        breakpoint: "768px",
        numVisible: 3,
      },
      {
        breakpoint: "560px",
        numVisible: 1,
      },
    ]);
    const state = reactive({
      name: "",
      category: "",
      price: null,
    });
    const sizes = ref([]);
    const sizeData = ref<ISizeParams[]>([
      {
        size: null,
        size_quantity: null,
      },
    ]);

    const rules = {
      name: {
        required: helpers.withMessage("Vui lòng nhập tên sản phẩm", required),
      },
      category: {
        required: helpers.withMessage(
          "Vui lòng chọn danh mục sản phẩm",
          required
        ),
      },
      price: {
        required: helpers.withMessage("Vui lòng nhập giá sản phẩm", required),
      },
    };

    const messageImageValid = ref("");

    const images = ref([] as any[]);

    const v$ = useVuelidate(rules, state);
    const handleSubmit = (isFormValid: any) => {
      submitted.value = true;
      if (fileImages.value.length < 1) {
        messageImageValid.value = "Vui lòng thêm ít nhất 1 ảnh sản phẩm";
        return;
      }
      if (isFormValid && sizeData.value[0].size != 0) {
        const product: IProductParams = {
          product_name: state.name,
          product_price: state.price,
          category: state.category,
          sizes: sizeData.value,
          images: fileImages.value,
        };
        addProduct(product);
        return;
      }
    };

    const addProduct = async (product: IProductParams) => {
      await store.dispatch("product/addProduct", product);
      if (store.getters["product/getError"]) {
        toast.add({
          severity: "error",
          summary: "Thất bại",
          detail: "Thêm sản phẩm bị lỗi vui lòng kiểm tra và thử lại",
          life: 3000,
        });
        return;
      }
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Thêm sản phẩm thành công",
        life: 3000,
      });
      resetForm();
    };

    const resetForm = () => {
      state.name = "";
      state.category = "";
      state.price = null;
      submitted.value = false;
      fileImages.value = [];
      images.value = [];
      sizeData.value = [
        {
          size: null,
          size_quantity: null,
        },
      ];
      messageImageValid.value = "";
    };

    const searchCategory = (event: any) => {
      setTimeout(() => {
        if (!event.query.trim().length) {
          filterCategory.value = [...category.value];
        } else {
          filterCategory.value = category.value.filter((category) => {
            return (category as string)
              .toLowerCase()
              .startsWith(event.query.toLowerCase());
          });
        }
      }, 250);
    };
    const searchSize = (event: any) => {
      setTimeout(() => {
        if (!event.query.trim().length) {
          filterSize.value = [...sizes.value];
        } else {
          filterSize.value = sizes.value.filter((size) => {
            return size;
          });
        }
      }, 250);
    };

    const onUpload = (e: any) => {
      images.value = [];
      const target = [...e.target.files];
      target.forEach((ele) => {
        const img = URL.createObjectURL(ele);
        images.value.push({
          itemImageSrc: img,
          thumbnailImageSrc: img,
          alt: "Description for Image 5",
          title: "Title 5",
        });
        convertToBase64(ele).then((res) => {
          fileImages.value.push(res);
        });
      });
    };

    const handleAddSize = () => {
      sizeData.value.push({
        size: null,
        size_quantity: null,
      });
    };

    onMounted(async () => {
      sizes.value = await store.dispatch("product/getSizes");
      category.value = await store.dispatch("product/getCategories");
    });
    return {
      v$,
      state,
      submitted,
      category,
      filterCategory,
      images,
      responsiveOptions,
      sizes,
      sizeData,
      filterSize,
      fileImages,
      messageImageValid,
      searchSize,
      handleSubmit,
      searchCategory,
      onUpload,
      handleAddSize,
    };
  },
});
</script>

<style lang="scss" scoped>
form {
  font-size: 1.6rem;
  min-height: 35rem;

  .title {
    font-size: 2.5rem;
    display: flex;
    justify-content: center;
    color: #495057;
    font-weight: 700;
    margin-bottom: 2rem;
  }

  .container {
    display: flex;
  }
  .modal-left {
    width: 55%;

    .field {
      margin-bottom: 2.5rem;
    }

    .size-container {
      display: flex;
      flex-wrap: wrap;
    }

    .size-wrap {
      width: 50%;
      display: flex;
      align-items: center;
      padding-right: 2rem;

      .size {
        width: 40%;
        padding-right: 1rem;
      }

      label {
        font-weight: 600;
      }

      .size-quantity {
        width: 60%;

        :deep(.p-inputtext) {
          width: 100%;
          background-color: rgb(206, 206, 206);
          font-weight: 600;
        }
      }
      .option-item {
        font-size: 1.6rem !important;
      }
    }

    .btn-add-size {
      margin-bottom: 1rem;
    }

    :deep(.p-float-label input:focus ~ label) {
      font-size: 1.4rem;
      top: -1.2rem;
      font-weight: 600;
      color: #495057;
    }
    :deep(.p-inputwrapper-focus ~ label) {
      font-size: 1.4rem;
      top: -1.2rem;
      font-weight: 600;
      color: #495057;
    }
    :deep(.p-inputtext) {
      height: 4rem;
      font-size: 1.5rem;
      padding-left: 1rem;
    }
    :deep(.p-float-label label) {
      line-height: 1rem;
      font-size: 1.5rem;
      color: #495057;
    }
  }

  .modal-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;

    :deep(.p-galleria-item-wrapper img) {
      width: 35rem;
      height: 25rem;
      object-fit: cover;
    }

    :deep(.p-galleria-thumbnail-container) {
      background-color: transparent;
      color: #000;
    }
    :deep(.p-galleria-thumbnail-container button) {
      color: #000;
    }
    :deep(.p-galleria-thumbnail-items) {
      height: 6rem;
    }
    :deep(.p-galleria-thumbnail-item) {
      flex: none !important;
      width: 6rem;
    }
    :deep(.p-galleria-thumbnail-item img) {
      width: 6rem;
      height: 5rem;
      object-fit: cover;
    }

    .btn-upload {
      margin: 0.5rem 0 2rem 0;

      .upload-image {
        border: 1px solid orange;
        border-radius: 5px;
        color: orange;
        padding: 1rem 2rem;
        display: flex;
        align-items: center;
        cursor: pointer;
        font-weight: 700;

        &:hover {
          background-color: aliceblue;
        }
      }
    }
  }
  :deep(.p-button) {
    height: 4rem;
  }
}
</style>

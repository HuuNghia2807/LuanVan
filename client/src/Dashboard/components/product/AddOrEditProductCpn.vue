<template>
  <my-toast />
  <form @submit.prevent="handleSubmit(!v$.$invalid)" class="p-fluid">
    <div class="title">
      {{ productAction ? "CHỈNH SỬA SẢN PHẨM" : "THÊM SẢN PHẨM" }}
    </div>
    <div class="btn-edit" v-if="productAction">
      <my-button
        icon="pi pi-pencil"
        class="p-button-rounded p-button-info"
        @click="handleEdit"
      />
    </div>
    <div class="container">
      <div class="modal-left">
        <div class="field">
          <div class="p-float-label">
            <my-inputText
              id="name"
              v-model="v$.name.$model"
              :disabled="!isEdit"
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
              :disabled="!isEdit"
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
              :readonly="!isEdit"
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
                  :disabled="!isEdit"
                />
              </div>
              <div class="size-quantity">
                <label>Số Lượng</label>
                <my-inputText
                  v-model="size.size_quantity"
                  type="number"
                  :disabled="!isEdit"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="btn-add-size">
          <my-button
            icon="pi pi-plus"
            class="p-button-rounded p-button-success p-button-outlined"
            @click="handleAddSize"
            :disabled="!isEdit"
          />
        </div>
      </div>
      <div class="modal-right">
        <small v-if="messageImageValid" class="p-error">
          {{ messageImageValid }}
        </small>
        <ScrollPanel
          style="width: 100%; height: 35rem"
          class="review-images custombar1"
          v-if="productAction"
        >
          <div class="image-edit" v-for="img in images" :key="img.title">
            <Image :src="img.itemImageSrc" :alt="img.alt" preview />
            <span class="action-delete" v-if="isEdit"
              ><i class="pi pi-times" style="font-size: 1rem"></i
            ></span>
          </div>
        </ScrollPanel>
        <div class="review-images" v-else>
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
          <label class="upload-image" :for="isEdit ? 'upload-image' : ''">
            <i class="pi pi-image mr-2" style="font-size: 2rem"></i>
            Chọn ảnh sản phẩm
          </label>
        </div>
      </div>
    </div>
    <div>
      <my-button
        :label="productAction ? 'LƯU' : 'THÊM SẢN PHẨM'"
        type="submit"
        icon="pi pi-plus"
        :disabled="!isEdit"
      />
    </div>
  </form>
</template>

<script lang="ts">
import {
  computed,
  defineComponent,
  onMounted,
  PropType,
  reactive,
  ref,
} from "vue";

import { useStore } from "vuex";
import {
  IProduct,
  IProductParams,
  ISizeParams,
} from "@/interface/product/product.state";
import { helpers, required } from "@vuelidate/validators";
import { convertToBase64 } from "@/function/convertImage";
import { useToast } from "primevue/usetoast";
import useVuelidate from "@vuelidate/core";
import AutoComplete from "primevue/autocomplete";
import Galleria from "primevue/galleria";
import Image from "primevue/image";
import ScrollPanel from "primevue/scrollpanel";

export default defineComponent({
  props: {
    productAction: { type: Object as PropType<IProduct> },
  },
  components: {
    AutoComplete,
    Galleria,
    Image,
    ScrollPanel,
  },
  setup(props, { emit }) {
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
    const isEdit = ref(!props.productAction);
    const state = reactive({
      name: props.productAction?.productName || "",
      category: props.productAction?.category || "",
      price: props.productAction?.productPrice || null,
    });
    const sizes = ref([]);
    const sizeEdit = computed(() => {
      return (
        props.productAction?.sizes.map((ele) => {
          return {
            size: ele.size,
            size_quantity: ele.productSizeQuantity,
          };
        }) || null
      );
    });
    const sizeData = ref<ISizeParams[]>(
      sizeEdit.value || [
        {
          size: null,
          size_quantity: null,
        },
      ]
    );

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
    const imageEdit = computed(() => {
      return props.productAction?.images.map((ele) => {
        return {
          itemImageSrc: ele.product_image_link,
          thumbnailImageSrc: ele.product_image_link,
          alt: ele.product_image_name,
          title: "Title 5",
        };
      });
    });
    const images = ref<any[]>(imageEdit.value || []);

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

    const handleEdit = () => {
      isEdit.value = true;
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
      isEdit,
      handleEdit,
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

  .btn-edit {
    display: flex;
    width: 100%;
    justify-content: flex-end;
    padding: 0 2rem 1rem 0;
  }

  .container {
    display: flex;
  }
  .modal-left {
    width: 55%;
    padding-right: 2rem;

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

    :deep(.p-component:disabled) {
      opacity: 1 !important;
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

    .image-edit {
      position: relative;
      width: 33.3333%;

      .action-delete {
        position: absolute;
        display: flex;
        width: 2rem;
        height: 2rem;
        top: 0;
        right: 0;
        color: var(--white-color);
        background-color: rgb(245, 74, 74);
        border-radius: 50%;
        cursor: pointer;
        padding: 0.5rem;
      }

      :deep(.p-image-preview-container > img) {
        width: 100%;
        height: 13rem;
        object-fit: contain;
      }
    }
    ::v-deep(.p-scrollpanel) {
      &.custombar1 {
        .p-scrollpanel-wrapper {
          border-right: 5px solid var(--surface-ground);
          padding-top: 0.5rem;
        }

        .p-scrollpanel-bar {
          background-color: var(--primary-color);
          opacity: 1;
          transition: background-color 0.2s;

          &:hover {
            background-color: #007ad9;
          }
        }
      }
    }
    ::v-deep(.p-scrollpanel-content) {
      display: flex;
      flex-wrap: wrap;
      margin-right: -0.5rem;
      margin-left: -0.5rem;
      margin-top: -0.5rem;
    }

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

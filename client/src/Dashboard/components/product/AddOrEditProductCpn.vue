<template>
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
              optionLabel="name"
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
            <my-inputText
              id="price"
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
                <label>Size</label>
                <my-dropdown
                  v-model="size.sizeId"
                  :options="sizes"
                  optionLabel="size"
                  optionValue="size_id"
                  placeholder="Select a Size"
                >
                  <template #option="slotProps">
                    <div class="option-item">{{ slotProps.option.size }}</div>
                  </template></my-dropdown
                >
              </div>
              <div class="size-quantity">
                <label>Số Lượng</label>
                <my-inputText v-model="size.sizeQuantity" type="number" />
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
import { defineComponent, onMounted, reactive, ref } from "vue";

import { helpers, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import AutoComplete from "primevue/autocomplete";
import Galleria from "primevue/galleria";
import Image from "primevue/image";
import { useStore } from "vuex";

export default defineComponent({
  components: {
    AutoComplete,
    Galleria,
    Image,
  },
  setup() {
    const store = useStore();
    const submitted = ref(false);
    const category = ref([
      {
        name: "Nike",
        code: "nike",
      },
      {
        name: "Adidas",
        code: "adidas",
      },
    ]);
    const filterCategory = ref([] as any[]);
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
      price: "",
    });
    const sizes = ref([]);
    const sizeData = ref([
      {
        sizeId: null,
        sizeQuantity: null,
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

    const images = ref([
      {
        itemImageSrc:
          "https://www.primefaces.org/primevue/demo/images/galleria/galleria1.jpg",
        thumbnailImageSrc:
          "https://www.primefaces.org/primevue/demo/images/galleria/galleria1.jpg",
        alt: "Description for Image 1",
        title: "Title 1",
      },
    ]);

    const v$ = useVuelidate(rules, state);
    const handleSubmit = (isFormValid: any) => {
      console.log("======", sizeData.value);

      submitted.value = true;

      if (!isFormValid) {
        return;
      }
    };

    const searchCategory = (event: any) => {
      setTimeout(() => {
        if (!event.query.trim().length) {
          filterCategory.value = [...category.value];
        } else {
          filterCategory.value = category.value.filter((category) => {
            return category.name
              .toLowerCase()
              .startsWith(event.query.toLowerCase());
          });
        }
      }, 250);
    };

    const onUpload = (e: any) => {
      // this.$toast.add({severity: 'info', summary: 'Success', detail: 'File Uploaded', life: 3000});
      console.log("toast", e.target.files);
      const target = [...e.target.files];

      const imageDemo = target.map((ele) => {
        const img = URL.createObjectURL(ele);
        return {
          itemImageSrc: img,
          thumbnailImageSrc: img,
          alt: "Description for Image 5",
          title: "Title 5",
        };
      });
      imageDemo.forEach((ele) => {
        images.value.push(ele);
      });
      console.log("check", target, imageDemo, images.value);
    };

    const handleAddSize = () => {
      sizeData.value.push({
        sizeId: null,
        sizeQuantity: null,
      });
    };

    onMounted(async () => {
      sizes.value = await store.dispatch("product/getSizes");
      console.log(sizes.value);
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
  margin-bottom: 2rem;
  font-size: 1.6rem;
  min-height: 50rem;

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

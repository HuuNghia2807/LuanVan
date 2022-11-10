<template>
  <my-toast />
  <div class="product-detail">
    <div class="container">
      <div class="list-img-wrap">
        <div class="list-img">
          <img
            v-for="image in product?.images"
            :key="image.product_image_name"
            class="imgs"
            :src="image.product_image_link"
            :alt="image.product_image_name"
          />
        </div>
      </div>
      <div class="show-img">
        <img
          class="img"
          :src="product?.images[0].product_image_link"
          :alt="product?.images[0].product_image_name"
        />
      </div>
      <div class="content">
        <div>
          <Rating :disabled="true" />
          <span class="product-name">{{ product?.productName }}</span>
          <span class="product-code">
            Mã SP: <span class="sup-code">{{ product?.productCode }}</span>
          </span>
          <span class="product-price">{{
            formatPrice(product?.productPrice || 9999999999)
          }}</span>
        </div>
        <div class="product-size">
          <div v-for="(size, i) of sizes" :key="i" class="field-radiobutton">
            <RadioButton
              :inputId="size.name"
              name="size"
              :value="size.value"
              v-model="selectedSize"
              :disabled="size.quantity == 0"
            />
            <label :for="size.name">{{ size.name }}</label>
          </div>
        </div>
        <div>
          <div class="mt-4">
            <InputNumber
              inputId="horizontal"
              v-model="quantity"
              mode="decimal"
              showButtons
              buttonLayout="horizontal"
              :min="0"
              :max="40"
              decrementButtonClass="p-button-secondary"
              incrementButtonClass="p-button-secondary"
              incrementButtonIcon="pi pi-plus"
              decrementButtonIcon="pi pi-minus"
            />
          </div>
          <div class="mt-5">
            <my-button
              label="Thêm vào giỏ hàng"
              icon="pi pi-shopping-cart"
              class="p-button-lg p-button-warning p-button-rounded"
              @click="handleAddCart"
            />
            <my-button
              label="Mua Ngay"
              icon="pi pi-chevron-right"
              class="p-button-lg p-button-danger p-button-rounded"
              iconPos="right"
              @click="handleAddCart"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="other mb-8">
      <div class="my-3">
        ⚡️ KINGSHOES.VN
        <span class="font-normal">
          ✓15 Ngày Đổi Hàng ✓Giao Hàng Miễn Phí ✓Thanh Toán Khi Nhận Hàng ✓Bảo
          Hành Hàng Chính Hãng Trọn Đời.!!!</span
        >
      </div>
      <div class="my-3">KINGSHOES.VN "You're King In Your Way".!!!</div>
      <router-link to="/" class="linkCheck my-3"
        >KING'S & QUEEN'S Check in Tại KINGSHOES.VN</router-link
      >
      <div class="my-3">
        192/2 Nguyễn Thái Bình, phường 12, quận Tân Bình, thành phố Hồ Chí Minh
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { ICart, IProduct } from "@/interface/product/product.state";
import { formatPrice } from "@/function/common";
import { useToast } from "primevue/usetoast";
import { addProductToCart, setStateCart } from "@/function/handleLocalStorage";
import Rating from "primevue/rating";
import RadioButton from "primevue/radiobutton";
import InputNumber from "primevue/inputnumber";

export default defineComponent({
  components: {
    Rating,
    RadioButton,
    InputNumber,
  },
  setup() {
    const store = useStore();
    const route = useRoute();
    const toast = useToast();
    const rate = ref(3);
    const product = ref<IProduct>();
    const sizes = ref([] as any);
    const selectedSize = ref<number>();
    const quantity = ref(1);
    const handleAddCart = () => {
      addCart();
    };

    const addCart = async () => {
      const sizeId = selectedSize.value;
      if (!sizeId) {
        toast.add({
          severity: "error",
          summary: "Thất bại",
          detail: "Vui lòng chọn size khi thêm vào giỏ hàng",
          life: 3000,
        });
        return;
      }
      const item: ICart = {
        productId: product.value?.productId || undefined,
        productSizeId: sizeId,
        quantity: quantity.value,
      };
      addProductToCart(item);
      setStateCart(store);
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Thêm thành công",
        life: 3000,
      });
    };

    onMounted(async () => {
      await store.dispatch("product/getProducts");
      const list = store.getters["product/getProducts"] as IProduct[];
      const id = route.params.id as any;
      product.value = list && list.find((ele) => ele.productId == id);
      sizes.value = product.value?.sizes.map((ele) => {
        return {
          name: ele.size,
          value: ele.productSizeId,
          quantity: ele.productSizeQuantity,
        };
      });
    });
    return {
      rate,
      sizes,
      selectedSize,
      quantity,
      product,
      formatPrice,
      handleAddCart,
    };
  },
});
</script>

<style lang="scss" scoped>
.product-detail {
  min-height: 20rem;

  .container {
    padding: 4rem 12rem;
    display: flex;

    .list-img-wrap {
      width: 15%;
    }

    .list-img {
      display: flex;
      flex-direction: column;
      align-items: center;
      height: 40rem !important;
      overflow: auto;

      .imgs {
        width: 45%;
        margin-bottom: 2rem;
        border: 0.3rem solid #8d8d8d;
      }
    }

    .show-img {
      width: 46%;

      .img {
        width: 59rem;
        height: 59rem;
        object-fit: contain;
      }
    }

    .content {
      width: 49%;
      padding-left: 4rem;

      .img-star {
        width: 3rem;
      }

      .product-name {
        display: block;
        font-size: 3rem;
        font-weight: 700;
        color: #313131;
        text-transform: uppercase;
        margin: 1rem 0 1.5rem 0;
      }

      .product-code {
        display: block;
        font-size: 1.8rem;
        color: #5b5b5b;

        .sup-code {
          font-weight: bold;
        }
      }

      .product-price {
        display: block;
        font-size: 2.4rem;
        font-weight: bold;
        color: #fb0000;
        margin: 3rem 0;
      }

      .product-size {
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        display: flex;
        flex-wrap: wrap;
        padding: 3rem 0;
        font-size: 2rem;
      }
    }

    :deep(.p-rating-icon) {
      font-size: 2.5rem;
      color: yellow !important;
    }

    :deep(.p-rating-icon.pi-star-fill) {
      color: yellow !important;
    }

    :deep(.field-radiobutton) {
      flex-direction: column;
      justify-content: center;
      margin: 0 1.2rem;
      font-size: 2rem;
    }

    :deep(.p-radiobutton) {
      margin-bottom: 0.5rem;
    }

    :deep(.p-inputtext) {
      font-size: 2rem;
      width: 6rem;
      text-align: center;
    }

    :deep(.p-button-icon-only) {
      padding: 1rem 2rem;
    }

    :deep(.p-button.p-button-lg) {
      padding: 1rem 2rem;
      font-size: 2rem;
      font-weight: 550;
      margin-right: 2rem;
      border-radius: 5rem;
    }

    :deep(.p-button.p-button-lg .p-button-icon) {
      font-size: 2rem;
    }
  }

  .other {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #5b5b5b;
    font-weight: 600;

    .linkCheck {
      text-decoration: none;
      color: #5b5b5b;

      &:hover {
        color: var(--primary-color);
      }
    }
  }
}
</style>

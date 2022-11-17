<template>
  <my-toast />
  <div class="product-detail">
    <div class="container">
      <div class="list-img-wrap">
        <ScrollPanel style="width: 100%; height: 40rem" class="custombar1">
          <div class="list-img">
            <img
              v-for="image in product?.images"
              class="imgs"
              :class="{
                'active-imgs':
                  imageShow.product_image_id === image.product_image_id,
              }"
              :key="image.product_image_id"
              :src="image.product_image_link"
              :alt="image.product_image_name"
              @click="handleClickImage(image)"
            />
          </div>
        </ScrollPanel>
      </div>
      <div class="show-img">
        <img
          class="img"
          :src="imageShow.product_image_link"
          :alt="imageShow.product_image_name"
        />
        <span class="tag-sale" v-if="product?.discountId !== 0"
          >{{ product?.discountPercent }}%</span
        >
      </div>
      <div class="content">
        <div>
          <Rating
            :modelValue="product?.productRating"
            :readonly="true"
            :cancel="false"
          />
          <span class="product-name">{{ product?.productName }}</span>
          <span class="product-code">
            Mã SP: <span class="sup-code">{{ product?.productCode }}</span>
          </span>
          <span class="product-price" v-if="product?.discountId === 0">{{
            formatPrice(product?.productPrice || 9999999999)
          }}</span>
          <div class="flex" v-else>
            <span class="product-price">{{
              formatPrice(
                caculatorSale(
                  product?.productPrice || 9999999,
                  product?.discountPercent || 0
                )
              )
            }}</span>
            <span class="product-price-sale">{{
              formatPrice(product?.productPrice || 9999999)
            }}</span>
          </div>
        </div>
        <div class="product-size">
          <div v-for="(size, i) of sizes" :key="i" class="list-size">
            <span
              class="size"
              :class="{
                active: size.sizeId === selectedSize.sizeId,
              }"
              @click="handleClickSize(size)"
              >{{ size.size }}</span
            >
          </div>
        </div>
        <div>
          <div class="quantity">
            <span>Số Lượng Còn Lại:</span>
            <span class="font-bold ml-2">{{
              selectedSize.productSizeQuantity
            }}</span>
          </div>
          <div class="mt-4">
            <InputNumber
              inputId="horizontal"
              v-model="quantity"
              mode="decimal"
              showButtons
              buttonLayout="horizontal"
              :min="1"
              :max="selectedSize.productSizeQuantity"
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
              @click="addCart"
            />
            <my-button
              label="Mua Ngay"
              icon="pi pi-chevron-right"
              class="p-button-lg p-button-danger p-button-rounded"
              iconPos="right"
              @click="goToCart"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="comment">
      <CommentCpn v-if="product" :product="product" />
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
import { useRoute, useRouter } from "vue-router";
import { ICart, IProduct } from "@/interface/product/product.state";
import { caculatorSale, formatPrice } from "@/function/common";
import { useToast } from "primevue/usetoast";
import { addProductToCart, setStateCart } from "@/function/handleLocalStorage";
import Rating from "primevue/rating";
import InputNumber from "primevue/inputnumber";
import ScrollPanel from "primevue/scrollpanel";
import CommentCpn from "@/components/CommentCpn.vue";

export default defineComponent({
  components: {
    Rating,
    InputNumber,
    ScrollPanel,
    CommentCpn,
  },
  setup() {
    const store = useStore();
    const route = useRoute();
    const router = useRouter();
    const toast = useToast();
    const rate = ref(3);
    const product = ref<IProduct>();
    const sizes = ref([] as any);
    const imageShow = ref({} as any);
    const selectedSize = ref({} as any);
    const quantity = ref(1);

    const addCart = async () => {
      const sizeId = selectedSize.value.productSizeId;
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
        maxQuantity: selectedSize.value.productSizeQuantity,
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

    const handleClickImage = (img: any) => {
      imageShow.value = img;
    };
    const handleClickSize = (szie: any) => {
      selectedSize.value = szie;
    };

    const goToCart = () => {
      addCart();
      router.push("/gio-hang");
    };

    onMounted(async () => {
      await store.dispatch("product/getProducts");
      const list = store.getters["product/getProducts"] as IProduct[];
      const code = route.params.code as any;
      product.value = list && list.find((ele) => ele.productCode == code);
      sizes.value = product.value?.sizes;
      selectedSize.value = sizes.value[0];
      imageShow.value = product.value?.images[0];
    });
    return {
      rate,
      sizes,
      selectedSize,
      quantity,
      product,
      imageShow,
      goToCart,
      handleClickSize,
      handleClickImage,
      caculatorSale,
      formatPrice,
      addCart,
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

    .list-img {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      justify-content: flex-end;
    }
    .imgs {
      width: 60%;
      margin-bottom: 2rem;
      border: 0.2rem solid #8d8d8d;
      cursor: pointer;
    }
    .active-imgs {
      border: 0.3rem solid rgb(255, 188, 132);
    }

    .show-img {
      width: 46%;
      position: relative;

      .img {
        width: 59rem;
        height: 59rem;
        object-fit: cover;
      }

      .tag-sale {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 7.5rem;
        height: 3rem;
        font-weight: 700;
        top: 0;
        right: 0;
        background-color: #cf5050;
        color: #fff;
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

      .product-price-sale {
        display: block;
        font-size: 2.4rem;
        font-weight: bold;
        color: #bcbcbc;
        margin: 3rem 1rem 0;
        text-decoration: line-through;
      }

      .product-size {
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        display: flex;
        flex-wrap: wrap;
        padding: 3rem 0;
        font-size: 2rem;
      }

      .quantity {
        margin-top: 2rem;
        display: flex;
        align-items: center;

        span {
          font-size: 1.6rem;
          color: #888;
        }
      }

      .list-size {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        .size {
          display: flex;
          align-items: center;
          justify-content: center;
          border: 1px solid #ccc;
          padding: 0.6rem 2rem;
          margin: 1rem 2rem 1rem 0;
          box-shadow: 0px 2px 5px rgb(0 0 0 / 50%);
          font-weight: bold;
          cursor: pointer;

          &:hover {
            color: #888;
          }
        }

        .active {
          border: 2px solid var(--primary-color);
          box-shadow: none;
        }
      }
    }

    :deep(.p-rating-icon) {
      font-size: 2.5rem;
      color: yellow !important;
    }

    :deep(.p-rating-icon.pi-star-fill) {
      color: yellow !important;
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

  .comment {
    width: 100%;
    min-height: 50rem;
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

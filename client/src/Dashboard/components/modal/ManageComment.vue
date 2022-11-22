<template>
  <my-dialog
    header="Cập nhật thông tin"
    :visible="isShowComment"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '60vw' }"
    :modal="true"
    @update:visible="closeModal"
  >
    <div class="main-comment">
      <div class="product">
        <div class="wrap">
          <img
            :src="product?.images[0].product_image_link"
            :alt="product?.productName"
            class="img-product"
          />
          <div class="product-context">
            <span class="name-product">{{ product?.productName }}</span>
            <div class="list-rate">
              <img
                src="@/assets/img/icons/star.png"
                class="icon"
                v-for="(_star, i) in product?.productRating"
                :key="i"
              />
            </div>
            <div class="product-price">
              <span class="price">{{
                formatPrice(product?.productPrice || 9999999)
              }}</span>
            </div>
          </div>
        </div>
      </div>
      <ScrollPanel style="width: 100%; height: 57vh" class="custombar1">
        <div class="list-comment" v-if="listComment?.length">
          <div
            class="item-cmt"
            v-for="cmt in listComment"
            :key="`cmt${cmt.commentId}`"
          >
            <div class="flex justify-content-between">
              <div class="header">
                <div class="avatar">
                  <img
                    :src="
                      cmt.customerAvatar ||
                      require('@/assets/img/avatar_default/default-avatar.png')
                    "
                    alt="avt"
                  />
                </div>
                <div class="name-rating">
                  <span>{{ cmt.customerFullname }}</span>
                  <Rating
                    :modelValue="cmt.commentRating"
                    :readonly="true"
                    :cancel="false"
                  />
                </div>
              </div>
              <div>
                <my-button
                  icon="pi pi-times"
                  class="p-button-rounded p-button-danger p-button-text"
                  @click="handleDeleteComment($event, cmt.commentId)"
                />
              </div>
            </div>
            <div class="date-time">
              <span>{{ translateUnixTimeToFullTime(cmt.timeComment) }}</span>
            </div>
            <div class="content">
              {{ cmt.comment }}
            </div>
          </div>
        </div>
        <div v-else class="list-comment empty">
          Chưa có bình luận gì về sản phẩm này!
        </div>
      </ScrollPanel>
    </div>
    <ConfirmPopup></ConfirmPopup>
    <my-toast />
  </my-dialog>
</template>

<script lang="ts">
import { defineComponent, onMounted, PropType, ref } from "vue";
import Rating from "primevue/rating";
import { useStore } from "vuex";
import { IComment, IProduct } from "@/interface/product/product.state";
import { formatPrice, translateUnixTimeToFullTime } from "@/function/common";
import ScrollPanel from "primevue/scrollpanel";
import ConfirmPopup from "primevue/confirmpopup";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

export default defineComponent({
  props: {
    product: { type: Object as PropType<IProduct> },
    isShowComment: { type: Boolean, default: false },
  },
  components: {
    Rating,
    ScrollPanel,
    ConfirmPopup,
  },
  setup(props, { emit }) {
    const store = useStore();
    const confirm = useConfirm();
    const toast = useToast();
    const comment = ref("");
    const showLoading = ref(false);
    const listComment = ref([] as IComment[]);

    const handleDeleteComment = (event: any, comment_id: number) => {
      confirm.require({
        target: event.currentTarget,
        message: "Do you want to delete this record?",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        accept: async () => {
          await store.dispatch("auth/deleteComment", comment_id);
          toast.add({
            severity: "success",
            summary: "Thành Công",
            detail: "Xóa thành công",
            life: 3000,
          });
          loadComment();
        },
        reject: () => {
          // toast.add({severity:'error', summary:'Rejected', detail:'You have rejected', life: 3000});
        },
      });
    };

    const closeModal = () => {
      emit("close-modal");
    };

    const loadComment = async () => {
      listComment.value = await store.dispatch(
        "product/getComment",
        props.product?.productId
      );
    };

    onMounted(() => {
      loadComment();
    });

    return {
      comment,
      showLoading,
      listComment,
      handleDeleteComment,
      formatPrice,
      closeModal,
      translateUnixTimeToFullTime,
    };
  },
});
</script>

<style lang="scss" scoped>
:deep(.p-component) {
  font-size: 1.6rem !important;
}
.product {
  position: relative;
  margin-bottom: 1rem;
  color: var(--black-color);

  .product-context {
    margin-left: 1.4rem;
  }

  .wrap {
    display: flex;
    border: 1px solid transparent;
    height: 100%;
  }

  .img-product {
    width: 100%;
    height: 10rem;
    width: 10rem;
    object-fit: contain;
  }

  .name-product {
    display: block;
    margin-top: 1.5rem;
    font-size: 1.4rem;
    font-weight: 700;
    color: #5b5b5b;
    line-height: 1.4rem;
    height: 2.8rem;
    overflow: hidden;

    &:hover {
      color: var(--primary-color);
    }
  }

  .list-rate {
    margin-top: 0.5rem;
  }

  .icon {
    width: 1.4rem;
  }

  .product-price {
    margin: 0.6rem 0 1rem 0;
  }

  .price {
    display: block;
    font-size: 1.4rem;
    font-weight: 700;
    color: #5b5b5b;
  }

  .price-sale {
    display: block;
    font-size: 1.4rem;
    font-weight: 700;
    margin-top: 0.1rem;
    text-decoration: line-through;
    color: #bcbcbc;
  }
}
.main-comment {
  // background-color: #ccc;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
  border-radius: 10px;
  background-color: rgb(255, 255, 255);
  padding: 2rem 5rem 3rem;

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
  .list-comment {
    padding: 0 2rem;
    border-top: 1px solid #ccc;
  }

  .empty {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem 0;
    color: #888;
  }

  .item-cmt {
    padding: 2rem 0;
    border-bottom: 1px solid #ccc;

    .header {
      display: flex;
      align-items: center;

      .avatar {
        margin-right: 2rem;

        img {
          width: 5rem;
          height: 5rem;
          border-radius: 50%;
          border: 1px solid #ccc;
          object-fit: cover;
        }
      }

      .name-rating {
        span {
          display: block;
          font-size: 1.5rem;
          margin-bottom: 0.5rem;
        }

        :deep(.p-rating-icon) {
          font-size: 1.5rem;
          color: #d0011b !important;
        }
      }
    }

    .date-time {
      margin-left: 7rem;
      span {
        font-size: 1.4rem;
      }
    }

    .content {
      margin-top: 2rem;
      margin-left: 7rem;
      font-size: 1.7rem;
    }
  }
}
</style>

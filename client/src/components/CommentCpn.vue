<template>
  <div class="main-comment">
    <div class="container">
      <h3>ĐÁNH GIÁ SẢN PHẨM</h3>
      <div class="enter-comment">
        <div class="my-4">
          <Rating v-model="rating" :cancel="false" />
        </div>
        <div class="mb-5">
          <Textarea
            v-model="comment"
            :autoResize="true"
            placeholder="Hãy nhập gì đó về sản phẩm này..."
            rows="5"
            cols="30"
            class="w-full text-3xl"
          />
          <div class="my-2 text-right">
            <my-button
              label="Bình Luận"
              :loading="showLoading"
              @click="handleComment"
            />
          </div>
        </div>
      </div>
      <div v-if="msgError" class="error-comment">
        <span>{{ msgError }}</span>
      </div>
      <div class="list-comment" v-if="listComment?.length">
        <div
          class="item-cmt"
          v-for="cmt in listComment"
          :key="`cmt-${cmt.commentId}`"
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
            <div v-if="customer?.id === cmt.customerId">
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
    </div>
  </div>
  <ConfirmPopup />
  <my-toast />
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, PropType, ref } from "vue";
import Textarea from "primevue/textarea";
import Rating from "primevue/rating";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { getItemLocal } from "@/function/handleLocalStorage";
import { ICustomer } from "@/interface/auth/authentication.state";
import { IComment, IProduct } from "@/interface/product/product.state";
import { translateUnixTimeToFullTime } from "@/function/common";
import ConfirmPopup from "primevue/confirmpopup";
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

export default defineComponent({
  props: {
    product: { type: Object as PropType<IProduct> },
  },
  components: {
    Textarea,
    Rating,
    ConfirmPopup,
  },
  setup(props, { emit }) {
    const store = useStore();
    const confirm = useConfirm();
    const toast = useToast();
    const router = useRouter();
    const comment = ref("");
    const rating = ref(0);
    const showLoading = ref(false);
    const msgError = ref("");
    const listComment = ref([] as IComment[]);

    const customer = computed(() => {
      return (store.getters["auth/getUser"] ||
        getItemLocal("customer") ||
        null) as ICustomer;
    });

    const handleComment = () => {
      msgError.value = "";
      if (store.getters["auth/getIslogged"]) {
        addComment();
        return;
      }
      router.push("/account");
    };

    const addComment = async () => {
      if (rating.value === 0 || comment.value === "") {
        msgError.value = "Vui lòng đánh giá và nhập bình luận!";
        return;
      }
      showLoading.value = true;
      const cmt = {
        customer_id: customer.value?.id,
        product_id: props.product?.productId,
        comment: comment.value,
        rating: rating.value,
      };
      listComment.value = await store.dispatch("product/addComment", cmt);
      showLoading.value = false;
      if (store.getters["product/getError"]) {
        msgError.value = "Đã xảy ra lỗi vui lòng thử lại sau!";
        return;
      }
      clearForm();
    };

    const clearForm = () => {
      comment.value = "";
      rating.value = 0;
    };

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

    const loadComment = async () => {
      listComment.value = await store.dispatch(
        "product/getComment",
        props.product?.productId
      );
    };

    onMounted(async () => {
      if (props.product?.productId) {
        await loadComment();
      }
    });

    return {
      comment,
      rating,
      showLoading,
      msgError,
      listComment,
      customer,
      handleDeleteComment,
      handleComment,
      translateUnixTimeToFullTime,
    };
  },
});
</script>

<style lang="scss" scoped>
.main-comment {
  margin: 3rem 20rem;
  // background-color: #ccc;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);

  :deep(.p-component) {
    font-size: 1.6rem !important;
  }

  :deep(.p-rating-icon) {
    font-size: 2.7rem;
    color: yellow !important;
  }

  :deep(.p-rating-icon.pi-star-fill) {
    color: yellow !important;
  }

  .container {
    border-radius: 10px;
    background-color: rgb(255, 255, 255);
    padding: 2rem 5rem 3rem;
    min-height: 70rem;

    .error-comment {
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 2rem 0;

      span {
        font-size: 1.6rem;
        color: red;
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
}
@media screen and (min-width: 1px) and (max-width: 1179px) {
  .main-comment {
    margin: 0 !important;
  }
  :deep(.p-inputtextarea-resizable) {
    height: auto !important;
  }
}
</style>

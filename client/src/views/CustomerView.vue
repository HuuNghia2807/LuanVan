<template>
  <div class="customer">
    <div class="left-info">
      <div class="avt-wrap">
        <div class="img">
          <Image
            src="data:image/jpeg;base64,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"
            alt="Image"
            preview
          />
          <div class="btn-upload">
            <label class="upload" for="avatar">
              Upload
              <i class="pi pi-image" style="font-size: 1.6rem"></i>
            </label>
            <input id="avatar" type="file" class="hidden" />
          </div>
        </div>
        <div class="name">
          <span class="name-info">Nguyen Huu Nghia</span>
          <span class="email">nghia@gmail.com</span>
        </div>
      </div>

      <div class="order">don hang</div>
    </div>
    <div class="right-info">thoong tin ca nhan</div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import Image from "primevue/image";

export default defineComponent({
  components: {
    Image,
  },
  setup() {
    return {};
  },
});
</script>

<style lang="scss" scoped>
.customer {
  min-height: 90vh;
  display: flex;
  padding: 5rem;
  background-color: #efe4d3;

  .left-info {
    width: 60%;

    margin-right: 1rem;

    .avt-wrap {
      display: flex;
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 2rem;

      .img {
        margin-left: 4rem;
      }

      :deep(.p-image-preview-container > img),
      :deep(.p-image-preview-indicator) {
        width: 15rem;
        height: 15rem;
        object-fit: cover;
        border-radius: 50%;
      }

      .btn-upload {
        margin: 1rem 0;
        display: flex;
        justify-content: center;
        align-items: center;

        .upload {
          border: 1px solid #ccc;
          background-color: var(--green-400);
          color: #fff;
          font-weight: 600;
          padding: 0.5rem 2rem;
          border-radius: 5px;
        }
      }

      .name {
        display: flex;
        flex-direction: column;
        margin: 4rem 0 0 2.5rem;

        span {
          display: block;
          font-size: 1.8rem;
          color: var(--black-color);
          margin-bottom: 0.5rem;
        }

        .name-info {
          font-weight: 700;
          font-size: 2.5rem;
        }

        .email {
          margin-left: 1rem;
          color: #efe4d3;
        }
      }
    }
  }
}
</style>

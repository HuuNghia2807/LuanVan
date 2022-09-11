<template>
  <div class="checkouts">
    <div class="left">
      <div class="wrap">
        <router-link to="/">
          <img class="img" src="@/assets/img/logo.png" alt="" />
        </router-link>
        <div class="breadcrumb">
          <router-link class="text text-cart" to="/gio-hang"
            >Giỏ hàng</router-link
          >
          <i class="pi pi-angle-right mx-2" style="font-size: 1.3rem"></i>
          <span class="text">Thông tin giao hàng </span>
          <i class="pi pi-angle-right mx-2" style="font-size: 1.3rem"></i>
          <span class="text text-disabled">Phương thức thanh toán</span>
        </div>
        <div class="form-info">
          <h3 class="title">Thông tin giao hàng</h3>
          <form @submit.prevent="handleSubmit(!v$.$invalid)" class="p-fluid">
            <!-- Tên và số điện thoại -->
            <div class="flex align-items-center">
              <div class="field w-7">
                <div class="p-float-label">
                  <InputText
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
                    Họ và tên
                  </label>
                </div>
                <small
                  v-if="(v$.name.$invalid && submitted) || v$.name.$pending"
                  class="p-error"
                >
                  {{ v$.name.required.$message }}
                </small>
              </div>
              <div class="field w-5 ml-3">
                <div class="p-float-label">
                  <InputText
                    id="phone"
                    v-model="v$.phone.$model"
                    :class="{
                      'p-invalid': v$.phone.$invalid && submitted,
                    }"
                  />
                  <label
                    for="phone"
                    class="label"
                    :class="{
                      'p-error': v$.phone.$invalid && submitted,
                    }"
                  >
                    Số điện thoại
                  </label>
                </div>
                <small
                  v-if="(v$.phone.$invalid && submitted) || v$.phone.$pending"
                  class="p-error"
                >
                  {{ v$.phone.required.$message }}
                </small>
              </div>
            </div>
            <!-- Tỉnh quận huyện -->
            <div class="flex align-items-center justify-content-between mt-3">
              <div class="field w-19rem">
                <div class="p-float-label">
                  <Dropdown
                    id="city"
                    v-model="v$.city.$model"
                    :filter="true"
                    :options="city"
                    optionLabel="name"
                    optionValue="code"
                    :class="{
                      'p-invalid': v$.city.$invalid && submitted,
                    }"
                  >
                    <template #option="slotProps">
                      <div class="text-2xl">{{ slotProps.option.name }}</div>
                    </template>
                  </Dropdown>
                  <label
                    for="city"
                    class="label"
                    :class="{
                      'p-error': v$.city.$invalid && submitted,
                    }"
                  >
                    Tỉnh/Thành
                  </label>
                </div>
                <small
                  v-if="(v$.city.$invalid && submitted) || v$.city.$pending"
                  class="p-error"
                >
                  {{ v$.city.required.$message }}
                </small>
              </div>
              <div class="field w-19rem">
                <div class="p-float-label">
                  <Dropdown
                    id="district"
                    v-model="v$.district.$model"
                    :options="district"
                    optionLabel="name"
                    optionValue="code"
                    :class="{
                      'p-invalid': v$.district.$invalid && submitted,
                    }"
                  >
                    <template #option="slotProps">
                      <div class="text-2xl">{{ slotProps.option.name }}</div>
                    </template>
                  </Dropdown>
                  <label
                    for="district"
                    class="label"
                    :class="{
                      'p-error': v$.district.$invalid && submitted,
                    }"
                  >
                    Quận/Huyện
                  </label>
                </div>
                <small
                  v-if="
                    (v$.district.$invalid && submitted) || v$.district.$pending
                  "
                  class="p-error"
                >
                  {{ v$.district.required.$message }}
                </small>
              </div>
              <div class="field w-19rem">
                <div class="p-float-label">
                  <Dropdown
                    id="ward"
                    v-model="v$.ward.$model"
                    :options="ward"
                    optionLabel="name"
                    optionValue="code"
                    :class="{
                      'p-invalid': v$.ward.$invalid && submitted,
                    }"
                  >
                    <template #option="slotProps">
                      <div class="text-2xl">{{ slotProps.option.name }}</div>
                    </template>
                  </Dropdown>
                  <label
                    for="ward"
                    class="label"
                    :class="{
                      'p-error': v$.phone.$invalid && submitted,
                    }"
                  >
                    Phường Xã
                  </label>
                </div>
                <small
                  v-if="(v$.ward.$invalid && submitted) || v$.ward.$pending"
                  class="p-error"
                >
                  {{ v$.ward.required.$message }}
                </small>
              </div>
            </div>
            <!-- Địa chỉ -->
            <div class="flex align-items-center mt-3">
              <div class="field flex-1">
                <div class="p-float-label">
                  <InputText
                    id="address"
                    v-model="v$.address.$model"
                    :class="{
                      'p-invalid': v$.address.$invalid && submitted,
                    }"
                  />
                  <label
                    for="address"
                    class="label"
                    :class="{
                      'p-error': v$.address.$invalid && submitted,
                    }"
                  >
                    Địa chỉ
                  </label>
                </div>
                <small
                  v-if="
                    (v$.address.$invalid && submitted) || v$.address.$pending
                  "
                  class="p-error"
                >
                  {{ v$.address.required.$message }}
                </small>
              </div>
            </div>
            <!-- Email -->
            <div class="flex align-items-center mt-3">
              <div class="field flex-1">
                <div class="p-float-label p-input-icon-right">
                  <i class="pi pi-envelope" />
                  <InputText
                    id="email"
                    v-model="v$.email.$model"
                    :class="{ 'p-invalid': v$.email.$invalid && submitted }"
                    aria-describedby="email-error"
                  />
                  <label
                    for="email"
                    class="label"
                    :class="{ 'p-error': v$.email.$invalid && submitted }"
                    >Email</label
                  >
                </div>
                <span v-if="v$.email.$error && submitted">
                  <span
                    id="email-error"
                    v-for="(error, index) of v$.email.$errors"
                    :key="index"
                  >
                    <small class="p-error">{{ error.$message }}</small>
                  </span>
                </span>
                <small
                  v-else-if="
                    (v$.email.$invalid && submitted) || v$.email.$pending
                  "
                  class="p-error"
                  >{{ v$.email.required.$message }}</small
                >
              </div>
            </div>
            <!-- Noi dung -->
            <div class="flex align-items-center mt-3">
              <div class="field flex-1">
                <div class="p-float-label">
                  <Textarea
                    id="content"
                    v-model="v$.content"
                    :autoResize="true"
                    rows="2"
                  />
                  <label for="content" class="label content">Nội dung</label>
                </div>
              </div>
            </div>
            <div class="btn">
              <router-link to="/gio-hang" class="btn-back">
                <i class="pi pi-angle-left" style="font-size: 1.8rem"></i>
                <span class="text">Giỏ hàng</span>
              </router-link>
              <Button
                type="submit"
                label="Tiếp tục đến phương thức thanh toán"
                class="btn-submit"
              />
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="right">
      <div class="list-product">
        <ProductCheckoutsCpn
          v-for="(product, i) in productCheckout"
          :key="i"
          :product="product"
        />
      </div>
      <div class="price-temp">
        <div class="temp">
          <span class="text">Tam tinh</span>
          <span class="price">54,300,000 đ</span>
        </div>
        <div class="temp">
          <span class="text">Phí vận chuyển</span>
          <span class="price">Chưa bao gồm</span>
        </div>
      </div>
      <div class="total-price">
        <span class="text">Tổng cộng</span>
        <span class="total">54,300,000 đ</span>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, ref } from "vue";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import ProductCheckoutsCpn from "@/components/Product/ProductCheckoutsCpn.vue";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown";
import Textarea from "primevue/textarea";

import { city, district, ward } from "@/function/addressData";

export default defineComponent({
  components: {
    ProductCheckoutsCpn,
    InputText,
    Button,
    Dropdown,
    Textarea,
  },
  setup() {
    const productCheckout = ref([
      {
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        price: "2.200.000 đ",
        size: 41,
        quantity: 2,
      },
      {
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD DDDD FFFFF",
        price: "9.500.000 đ",
        size: 42,
        quantity: 3,
      },
      {
        img: "https://kingshoes.vn/data/upload/media/SNEAKER-315122-111-AIR-FORCE-1-07-NIKE-KINGSHOES.VN-TPHCM-TANBINH-17-logo-1551924204-.jpg",
        name: "AIR FORCE 1",
        price: "2.200.000 đ",
        size: 40,
        quantity: 1,
      },
      {
        img: "https://kingshoes.vn/data/upload/media/gia%CC%80y-nike-air-jordan-1-low-shattered-backboard-553558-128-king-shoes-sneaker-real-hcm-10-1637120327.jpeg",
        name: "AIR JORDAN 1 LOW SHATTERED BACKBOARD",
        price: "9.500.000 đ",
        size: 43,
        quantity: 1,
      },
    ]);
    const submitted = ref(false);
    const state = reactive({
      name: "",
      phone: "",
      city: "",
      district: "",
      ward: "",
      address: "",
      email: "",
      content: "",
    });
    const rules = {
      name: {
        required: helpers.withMessage("Vui lòng nhập họ tên", required),
      },
      phone: {
        required: helpers.withMessage("Số điện thoại không đúng", required),
        maxLength: maxLength(10),
        minLengthValue: minLength(10),
      },
      city: {
        required: helpers.withMessage("Vui lòng chọn Tỉnh/Thành", required),
      },
      district: {
        required: helpers.withMessage("Vui lòng chọn Quận/Huyện", required),
      },
      ward: {
        required: helpers.withMessage("Vui lòng chọn Phường/Xã", required),
      },
      address: {
        required: helpers.withMessage("Vui lòng nhập địa chỉ", required),
      },
      email: {
        required: helpers.withMessage("Vui lòng nhập Email", required),
      },
    };

    const v$ = useVuelidate(rules, state);
    const handleSubmit = (isFormValid: any) => {
      submitted.value = true;

      if (!isFormValid) {
        return;
      }
    };
    return {
      v$,
      state,
      submitted,
      productCheckout,
      city,
      district,
      ward,
      handleSubmit,
    };
  },
});
</script>

<style lang="scss" scoped>
.checkouts {
  display: flex;
  min-height: 100vh;

  .left {
    width: 55%;
    min-height: 100%;
    background-color: #efe4d3;
    padding: 6rem 4rem 2rem 20rem;

    .img {
      width: 32rem;
    }

    .breadcrumb {
      margin-top: 2rem;
      .text {
        text-decoration: none;
        font-size: 1.3rem;
        font-weight: 400;
        color: #4d4d4d;
      }

      .text-cart {
        color: #338dbc;
      }

      .text-disabled {
        color: #999;
      }
    }

    .form-info {
      margin-top: 2rem;

      .title {
        font-size: 1.8rem;
        color: #333;
        font-weight: 400;
      }

      form {
        margin-top: 1.8rem;
      }

      .field {
        margin-bottom: 1.5rem;
        height: 5rem;
      }

      .label {
        margin-top: -1.1rem;
        font-size: 1.5rem;
        color: #999;
        font-weight: 400;
        line-height: 1.5;
        padding-left: 0.6rem;
      }

      :deep(.p-inputtext) {
        font-size: 1.6rem;
        height: 4rem;
        line-height: 1.6;
        padding-left: 1rem;
      }

      .btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 2rem;

        .btn-back {
          display: flex;
          align-items: center;
          text-decoration: none;
          color: #338dbc;
        }

        .text {
          display: block;
          font-size: 1.6rem;
        }

        .btn-submit {
          width: 50%;
          height: 5rem;
          font-size: 1.6rem;
          color: var(--white-color);
        }
      }
    }

    @media screen and (max-width: 960px) {
      .card {
        width: 80%;
      }
    }
  }

  .right {
    flex: 1;
    padding: 5rem 16rem 2rem 4rem;

    .list-product {
      border-bottom: 1px solid #ccc;
    }

    .price-temp {
      margin-top: 3rem;
      border-bottom: 1px solid #ccc;
      padding-bottom: 1.5rem;

      .temp {
        display: flex;
        align-items: center;
        justify-content: space-around;
        margin-bottom: 1rem;

        .text {
          display: block;
          flex: 1;
          color: #737373;
          font-size: 1.4rem;
        }

        .price {
          display: block;
          flex: 1;
          text-align: end;
          font-weight: 500;
          color: #4b4b4b;
          font-size: 1.5rem;
        }
      }
    }
    .total-price {
      display: flex;
      align-items: center;
      margin-top: 1.5rem;
      padding-bottom: 1.5rem;

      .text {
        flex: 1;
        font-size: 1.6rem;
        color: #4b4b4b;
      }

      .total {
        flex: 1;
        text-align: end;
        font-size: 2rem;
        color: var(--red-500);
      }
    }
  }
}
</style>

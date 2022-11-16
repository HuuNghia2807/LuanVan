<template>
  <my-toast />
  <TheLoader :is-loading="showLoading" />
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
          <span
            class="text"
            :class="{ 'text-active': payment }"
            @click="payment = false"
            >Thông tin giao hàng
          </span>
          <i class="pi pi-angle-right mx-2" style="font-size: 1.3rem"></i>
          <span class="text" :class="{ 'text-disabled': !payment }"
            >Phương thức thanh toán</span
          >
        </div>
        <template v-if="!payment">
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
                      disabled
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
              <div>
                <div class="field">
                  <div class="p-float-label">
                    <my-dropdown
                      id="oldAddr"
                      v-model="oldAddr"
                      :options="listAddress"
                      optionLabel="addFull"
                      @change="handleChangeAddress"
                      :class="{
                        'p-invalid': errorOldAddress(),
                      }"
                    >
                      <template #option="slotProps">
                        <div class="text-2xl">
                          {{ slotProps.option.addFull }}
                        </div>
                      </template>
                    </my-dropdown>
                    <label
                      for="oldAddr"
                      class="label"
                      :class="{
                        'p-error': errorOldAddress(),
                      }"
                    >
                      Địa chỉ
                    </label>
                  </div>
                  <small v-if="errorOldAddress()" class="p-error">
                    Vui lòng chọn địa chỉ trước khi đặt hàng!
                  </small>
                </div>
              </div>
              <div v-if="addNewAddress">
                <!-- Tỉnh quận huyện -->
                <div
                  class="flex align-items-center justify-content-between mt-3"
                >
                  <div class="field w-19rem">
                    <div class="p-float-label">
                      <my-dropdown
                        id="city"
                        v-model="state.city"
                        :filter="true"
                        :options="city"
                        optionLabel="city"
                        optionValue="id"
                        @change="selectProvince('city')"
                      >
                        <template #option="slotProps">
                          <div class="text-2xl">
                            {{ slotProps.option.city }}
                          </div>
                        </template>
                      </my-dropdown>
                      <label for="city" class="label"> Tỉnh/Thành </label>
                    </div>
                  </div>
                  <div class="field w-19rem">
                    <div class="p-float-label">
                      <my-dropdown
                        id="district"
                        v-model="state.district"
                        :options="district"
                        :filter="true"
                        @change="selectProvince('district')"
                        optionLabel="district"
                        optionValue="id"
                      >
                        <template #option="slotProps">
                          <div class="text-2xl">
                            {{ slotProps.option.district }}
                          </div>
                        </template>
                      </my-dropdown>
                      <label for="district" class="label"> Quận/Huyện </label>
                    </div>
                  </div>
                  <div class="field w-19rem">
                    <div class="p-float-label">
                      <my-dropdown
                        id="ward"
                        v-model="v$.ward.$model"
                        :options="ward"
                        :filter="true"
                        optionLabel="ward"
                        optionValue="id"
                        :class="{
                          'p-invalid': v$.ward.$invalid && submitted,
                        }"
                      >
                        <template #option="slotProps">
                          <div class="text-2xl">
                            {{ slotProps.option.ward }}
                          </div>
                        </template>
                      </my-dropdown>
                      <label
                        for="ward"
                        class="label"
                        :class="{
                          'p-error': v$.ward.$invalid && submitted,
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
                        (v$.address.$invalid && submitted) ||
                        v$.address.$pending
                      "
                      class="p-error"
                    >
                      {{ v$.address.required.$message }}
                    </small>
                  </div>
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
                      disabled
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
                      v-model="note"
                      :autoResize="true"
                      rows="2"
                    />
                    <label for="content" class="label content">Ghi chú</label>
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
        </template>
        <template v-else>
          <div class="payment">
            <div class="transport">
              <h4>Phương Thức Vận Chuyển</h4>
              <div class="wrap">
                <div class="field-checkbox">
                  <my-checkbox
                    inputId="transport"
                    name="transport"
                    value="transport"
                    v-model="transport"
                  />
                  <label for="transport">Giao Hàng Tận Nơi</label>
                </div>
                <span>Đã bao gồm phí</span>
              </div>
            </div>
            <div class="pay">
              <h4>Phương Thức Thanh Toán</h4>
              <div class="wrap">
                <my-dropdown
                  v-model="selectedTypePay"
                  :options="payments"
                  optionLabel="payment_name"
                  optionValue="id"
                  placeholder="Chọn Phương Thức Thanh Toán"
                >
                  <template #option="slotProps">
                    <div class="option-item">
                      {{ slotProps.option.payment_name }}
                    </div>
                  </template>
                </my-dropdown>
              </div>
            </div>
            <template v-if="selectedTypePay === 2">
              <PayPal
                :total-price="totalOrder"
                @complete-paypal="orderPaypal"
              />
            </template>

            <div
              v-if="msgError"
              class="flex align-items-center justify-content-center my-3"
            >
              <span class="text-red">{{ msgError }}</span>
            </div>
            <div class="btn-payment" v-if="selectedTypePay === 1">
              <Button
                type="submit"
                label="Đặt Hàng"
                class="btn-submit"
                @click="handleOrder"
                :disabled="!selectedTypePay"
              />
            </div>
          </div>
        </template>
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
          <span class="text">Tạm tính</span>
          <span class="price">{{ formatPrice(totalOrder || 999999) }}</span>
        </div>
        <div class="temp">
          <span class="text">Phí vận chuyển</span>
          <span class="price">{{ formatPrice(transportFee) }}</span>
        </div>
      </div>
      <div class="total-price">
        <span class="text">Tổng cộng</span>
        <span class="total">{{ formatPrice(totalOrder) }}</span>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, reactive, ref } from "vue";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import ProductCheckoutsCpn from "@/components/Product/ProductCheckoutsCpn.vue";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Textarea from "primevue/textarea";
import { useToast } from "primevue/usetoast";
import { ICart, ICartList, IProduct } from "@/interface/product/product.state";
import { useStore } from "vuex";
import { getCartList } from "@/function/getCartList";
import {
  getItemLocal,
  removeItemLocal,
  setCustomerLogin,
  setStateCart,
} from "@/function/handleLocalStorage";
import { formatPrice } from "@/function/common";
import {
  ICity,
  IDistrict,
  IOrderParams,
  IWard,
} from "@/interface/order/order.state";
import { ICustomer } from "@/interface/auth/authentication.state";
import { useRouter } from "vue-router";
import TheLoader from "@/components/common/TheLoader.vue";
import PayPal from "@/components/common/PayPal.vue";

export default defineComponent({
  components: {
    ProductCheckoutsCpn,
    InputText,
    Button,
    Textarea,
    TheLoader,
    PayPal,
  },
  setup() {
    const store = useStore();
    const toast = useToast();
    const payment = ref(false);
    const transport = ref("transport");
    const showLoading = ref(false);
    const router = useRouter();
    const selectedTypePay = ref();
    const addNewAddress = ref(false);
    const listAddress = ref([] as any[]);
    const oldAddr = ref();
    const msgError = ref("");
    const productCheckout = computed(() => {
      const listProduct: IProduct[] =
        store.getters["product/getProducts"] || [];
      const cartItem: ICart[] = store.getters["auth/getCart"] || [];

      return getCartList(listProduct, cartItem) as ICartList[];
    });
    const customer = computed(() => {
      return (store.getters["auth/getUser"] ||
        getItemLocal("customer") ||
        null) as ICustomer;
    });
    const totalOrder = computed(() => {
      return (
        productCheckout.value.reduce(
          (prev, cur) => prev + (cur?.price || 0) * (cur?.quantity || 0),
          0
        ) + transportFee.value
      );
    });
    const transportFee = computed(() => {
      return (
        city.value.find((ele) => ele.id === state.city)?.transport_fee || 0
      );
    });
    const submitted = ref(false);
    const state = reactive({
      name: customer.value?.fullName,
      phone: customer.value?.phone,
      city: null,
      district: null,
      ward: null,
      address: "",
      email: customer.value?.email,
    });
    const note = ref("");
    const rules = {
      name: {
        required: helpers.withMessage("Vui lòng nhập họ tên", required),
      },
      phone: {
        required: helpers.withMessage("Số điện thoại không đúng", required),
        maxLength: maxLength(10),
        minLengthValue: minLength(10),
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
      if (!addNewAddress.value) {
        state.address = oldAddr.value.address;
        state.ward = oldAddr.value.wardId;
      }
      if (isFormValid) {
        payment.value = true;
        return;
      }
    };
    const city = computed(() => {
      return (store.getters["order/getCities"] as ICity[]) || [];
    });
    const district = computed(() => {
      return (
        (city.value.length &&
          (store.getters["order/getDistricts"] as IDistrict[]).filter(
            (ele) => ele.city_id === state.city
          )) ||
        []
      );
    });
    const ward = computed(() => {
      return (
        (district.value.length &&
          (store.getters["order/getWards"] as IWard[]).filter(
            (ele) => ele.district_id === state.district
          )) ||
        []
      );
    });

    const selectProvince = (type: string) => {
      switch (type) {
        case "city":
          state.district = null;
          state.ward = null;
          break;
        case "district":
          state.ward = null;
          break;
        default:
          break;
      }
    };

    const payments = ref([]);

    const handleOrder = () => {
      const dataOrder: IOrderParams = {
        customer_id: customer.value.id,
        customer_name: state.name,
        note: note.value,
        total_price: totalOrder.value,
        address_order: {
          address: state.address,
          ward: state.ward,
        },
        product_order: productCheckout.value,
        payment_id: selectedTypePay.value,
      };
      if (!selectedTypePay.value) {
        msgError.value = "Vui lòng chọn phương thức thanh toán";
        return;
      }
      order(dataOrder);
    };

    const order = async (order: IOrderParams) => {
      showLoading.value = true;
      await store.dispatch("order/order", order);
      const error = store.getters["order/getError"];
      if (error) {
        toast.add({
          severity: "error",
          summary: "Thất bại",
          detail: "Đặt hàng thất bại vui lòng thử lại!",
          life: 3000,
        });
        showLoading.value = false;
        return;
      }
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Đặt hàng thành công!",
        life: 3000,
      });
      removeItemLocal("cart");
      showLoading.value = false;
      router.push("/profile");
    };

    const orderPaypal = async () => {
      const dataOrder: IOrderParams = {
        customer_id: customer.value.id,
        customer_name: state.name,
        note: note.value,
        total_price: totalOrder.value,
        address_order: {
          address: state.address,
          ward: state.ward,
        },
        product_order: productCheckout.value,
        payment_id: selectedTypePay.value,
      };
      order(dataOrder);
    };

    const handleChangeAddress = () => {
      if (oldAddr.value.wardId === 0) {
        addNewAddress.value = true;
        return;
      }
      addNewAddress.value = false;
    };

    const errorOldAddress = () => {
      return (
        oldAddr.value &&
        oldAddr.value.address === "" &&
        !addNewAddress.value &&
        submitted.value
      );
    };

    onMounted(async () => {
      showLoading.value = true;
      await store.dispatch("product/getProducts");
      await store.dispatch("order/getProvince");
      payments.value = await store.dispatch("order/getPayment");
      setStateCart(store);
      setCustomerLogin(store);
      listAddress.value =
        customer.value.address?.map((ele) => {
          return {
            address: ele.address || "",
            wardId: ele.ward.id || 0,
            addFull: `${ele.address}, ${ele.ward.ward}, ${ele.district.district}, ${ele.city.city}`,
          };
        }) || [];
      listAddress.value.push({
        address: "",
        wardId: 0,
        addFull: "+ Thêm địa chỉ mới",
      });
      oldAddr.value = listAddress.value[0];
      showLoading.value = false;
    });
    return {
      v$,
      state,
      submitted,
      productCheckout,
      city,
      district,
      ward,
      totalOrder,
      transportFee,
      payment,
      transport,
      selectedTypePay,
      payments,
      note,
      showLoading,
      msgError,
      addNewAddress,
      customer,
      listAddress,
      oldAddr,
      errorOldAddress,
      handleChangeAddress,
      orderPaypal,
      handleOrder,
      selectProvince,
      formatPrice,
      handleSubmit,
    };
  },
});
</script>

<style lang="scss" scoped>
:deep(.p-inputtext) {
  font-size: 1.6rem;
  height: 4rem;
  line-height: 1.6;
  padding-left: 1rem;
}
.option-item {
  font-size: 1.6rem !important;
}
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
      .text-active {
        color: #338dbc;
        cursor: pointer;
      }
      .text-disabled {
        color: #999;
      }
    }
    .payment {
      margin-top: 3rem;
      .wrap {
        margin-top: 1rem;
        background-color: var(--white-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2.5rem 2rem;
        border-radius: 10px;
      }
    }
    .transport {
      margin-top: 2rem;
      .field-checkbox {
        margin-bottom: 0;
      }
    }
    .pay {
      margin-top: 2rem;

      :deep(.p-dropdown) {
        width: 25rem;
      }
    }
    .text-red {
      color: red;
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
      }
      :deep(.p-component:disabled) {
        opacity: 1 !important;
      }
    }
    .btn-payment {
      display: flex;
      justify-content: flex-end;
      margin-top: 2rem;
    }
    .btn-submit {
      width: 50%;
      height: 5rem;
      font-size: 1.6rem;
      color: var(--white-color);
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

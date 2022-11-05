<template>
  <my-dialog
    header="Cập nhật địa chỉ"
    :visible="displayModal"
    @update:visble="displayModal"
    :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
    :style="{ width: '50vw' }"
    :modal="true"
  >
    <form @submit.prevent="handleSubmit(!v$.$invalid)" class="p-fluid">
      <!-- Tỉnh quận huyện -->
      <div class="flex align-items-center justify-content-between mt-3">
        <div class="field w-4">
          <div class="p-float-label">
            <Dropdown
              id="city"
              v-model="v$.city.$model"
              :filter="true"
              :options="city"
              optionLabel="city"
              optionValue="id"
              @change="selectProvince('city')"
              :class="{
                'p-invalid': v$.city.$invalid && submitted,
              }"
            >
              <template #option="slotProps">
                <div class="text-2xl">{{ slotProps.option.city }}</div>
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
        <div class="field w-4 mx-3">
          <div class="p-float-label">
            <Dropdown
              id="district"
              v-model="v$.district.$model"
              :options="district"
              :filter="true"
              @change="selectProvince('district')"
              optionLabel="district"
              optionValue="id"
              :class="{
                'p-invalid': v$.district.$invalid && submitted,
              }"
            >
              <template #option="slotProps">
                <div class="text-2xl">
                  {{ slotProps.option.district }}
                </div>
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
            v-if="(v$.district.$invalid && submitted) || v$.district.$pending"
            class="p-error"
          >
            {{ v$.district.required.$message }}
          </small>
        </div>
        <div class="field w-4">
          <div class="p-float-label">
            <Dropdown
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
                <div class="text-2xl">{{ slotProps.option.ward }}</div>
              </template>
            </Dropdown>
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
            <my-inputText
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
            v-if="(v$.address.$invalid && submitted) || v$.address.$pending"
            class="p-error"
          >
            {{ v$.address.required.$message }}
          </small>
        </div>
      </div>
    </form>
    <template #footer>
      <my-button
        label="No"
        icon="pi pi-times"
        @click="closeModal"
        class="p-button-text"
      />
      <my-button label="Yes" icon="pi pi-check" @click="closeModal" autofocus />
    </template>
  </my-dialog>
</template>

<script lang="ts">
import { defineComponent, PropType, reactive, ref } from "vue";
import Dropdown from "primevue/dropdown";
import { helpers, required } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import { computed } from "@vue/reactivity";
import { useStore } from "vuex";
import { ICity, IDistrict, IWard } from "@/interface/order/order.state";
import { IAddressCustomer } from "@/interface/auth/authentication.state";

export default defineComponent({
  components: {
    Dropdown,
  },
  props: {
    displayModal: { type: Boolean, default: false },
    address: { type: Object as PropType<IAddressCustomer> },
  },
  setup(props, { emit }) {
    const store = useStore();
    const submitted = ref(false);
    const state = reactive({
      city: props.address?.city.id,
      district: props.address?.district.id,
      ward: props.address?.ward.id,
      address: props.address?.address,
    });
    const rules = {
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
    };

    const v$ = useVuelidate(rules, state);
    const handleSubmit = (isFormValid: any) => {
      submitted.value = true;

      if (isFormValid) {
        return;
      }
    };

    const selectProvince = (type: string) => {
      switch (type) {
        case "city":
          state.district = undefined;
          state.ward = undefined;
          break;
        case "district":
          state.ward = undefined;
          break;
        default:
          break;
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
    const closeModal = () => {
      emit("close-modal");
    };

    return {
      submitted,
      state,
      v$,
      ward,
      district,
      city,
      selectProvince,
      handleSubmit,
      closeModal,
    };
  },
});
</script>

<style lang="scss" scoped>
:deep(.p-component) {
  font-size: 1.6rem !important;
}
.option-item {
  font-size: 1.6rem !important;
}
:deep(.p-inputtext) {
  font-size: 1.6rem;
  height: 4rem;
  line-height: 1.6;
  padding-left: 1rem;
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

:deep(.p-component:disabled) {
  opacity: 1 !important;
}
</style>

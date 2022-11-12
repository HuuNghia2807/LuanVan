<template>
  <div ref="paypal" class="paypal1"></div>
</template>

<script lang="ts">
import { formatPrice } from "@/function/common";
import { computed, defineComponent, onMounted, ref } from "vue";

export default defineComponent({
  props: {
    totalPrice: { type: Number },
  },
  setup(props, { emit }) {
    const paidFor = ref(false);
    const loaded = ref(false);
    const paypal = ref(null);

    const USDprice = computed(() => {
      return ((props.totalPrice || 99999999) / 24815).toFixed(2);
    });

    const setLoaded = () => {
      loaded.value = true;
      (window as any).paypal
        .Buttons({
          createOrder: (_data: any, actions: any) => {
            return actions.order.create({
              purchase_units: [
                {
                  description: "Hóa đơn thanh toán Kingshoes",
                  amount: {
                    currency_code: "USD",
                    value: USDprice.value,
                  },
                },
              ],
            });
          },
          onApprove: async (_data: any, actions: any) => {
            const order = await actions.order.capture();
            paidFor.value = true;
            // console.log(order.purchase_units[0].amount.value);
            emit("complete-paypal", order.purchase_units[0].amount.value);
          },
          onError: (err: any) => {
            console.log(err);
          },
        })
        .render(paypal.value);
    };

    onMounted(() => {
      const script = document.createElement("script");
      script.src =
        "https://www.paypal.com/sdk/js?client-id=Aceq9OSh9s3sayfuxmXCzKoCz1m-iDD2GFhToJTYDbCLxYuAfh0M3o_VZvoLLLozApW5NjmIbo6mEv-p";
      script.addEventListener("load", setLoaded);
      document.body.appendChild(script);
    });

    return {
      paidFor,
      loaded,
      paypal,
      USDprice,
      formatPrice,
    };
  },
});
</script>

<style lang="scss" scoped>
.paypal1 {
  width: 55%;
  height: 10rem;
  margin: 1rem 0;
}
</style>

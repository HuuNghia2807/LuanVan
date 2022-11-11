<template>
  <my-toast />
  <TheLoader :is-loading="showLoading" />
  <div class="order">
    <TabView ref="tabview1">
      <TabPanel header="Đơn Hàng Mới">
        <TableOrderAdminCpn
          :orders="orderNew"
          option="new"
          @accept-order="acceptOrder"
          @cancel-order="cancelOrder"
        />
      </TabPanel>
      <TabPanel header="Đơn Hàng Đã Xác Nhận">
        <TableOrderAdminCpn
          :orders="orderProcessing"
          option="processing"
          @delivery-order="deliveryOrder"
          @cancel-order="cancelOrder"
        />
      </TabPanel>
      <TabPanel header="Đơn Hàng Đang Vận Chuyển">
        <TableOrderAdminCpn
          :orders="orderShipping"
          option="shipping"
          @complete-order="completeOrder"
          @cancel-order="cancelOrder"
        />
      </TabPanel>
      <TabPanel header="Đơn Hàng Đã Giao">
        <TableOrderAdminCpn
          :orders="orderDelivered"
          option="delivered"
          @cancel-order="cancelOrder"
        />
      </TabPanel>
      <TabPanel header="Đơn Hàng Đã Hủy">
        <TableOrderAdminCpn :orders="orderCanceled" option="canceled" />
      </TabPanel>
      <TabPanel header="Tất Cả Đơn Hàng">
        <TableOrderAdminCpn :orders="orders" option="all" />
      </TabPanel>
    </TabView>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent, onMounted, ref } from "vue";
import { useStore } from "vuex";
import {
  IOrders,
  IUpdateStatusOrderParams,
} from "@/interface/order/order.state";
import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";
import TableOrderAdminCpn from "../components/order/TableOrderAdminCpn.vue";
import { useToast } from "primevue/usetoast";
import TheLoader from "@/components/common/TheLoader.vue";

export default defineComponent({
  components: {
    TabView,
    TabPanel,
    TableOrderAdminCpn,
    TheLoader,
  },
  setup() {
    const store = useStore();
    const toast = useToast();
    const showLoading = ref(false);
    const orders = computed(() => {
      const order = store.getters["order/getOrders"] as IOrders[];
      return order || [];
    });

    const orderNew = computed(() => {
      const order = store.getters["order/getOrders"] as IOrders[];
      return (order && order.filter((ele) => ele.status === "new")) || [];
    });

    const orderProcessing = computed(() => {
      const order = store.getters["order/getOrders"] as IOrders[];
      return (
        (order && order.filter((ele) => ele.status === "processing")) || []
      );
    });

    const orderShipping = computed(() => {
      const order = store.getters["order/getOrders"] as IOrders[];
      return (order && order.filter((ele) => ele.status === "shipping")) || [];
    });

    const orderDelivered = computed(() => {
      const order = store.getters["order/getOrders"] as IOrders[];
      return (order && order.filter((ele) => ele.status === "delivered")) || [];
    });

    const orderCanceled = computed(() => {
      const order = store.getters["order/getOrders"] as IOrders[];
      return (order && order.filter((ele) => ele.status === "canceled")) || [];
    });

    const acceptOrder = (orderId: number) => {
      const data = {
        order_id: orderId,
        status_id: 2,
      } as IUpdateStatusOrderParams;
      updateStatus(data);
    };

    const deliveryOrder = (orderId: number) => {
      const data = {
        order_id: orderId,
        status_id: 3,
      } as IUpdateStatusOrderParams;
      updateStatus(data);
    };

    const completeOrder = (orderId: number) => {
      const data = {
        order_id: orderId,
        status_id: 4,
      } as IUpdateStatusOrderParams;
      updateStatus(data);
    };

    const cancelOrder = (orderId: number) => {
      const data = {
        order_id: orderId,
        status_id: 5,
      } as IUpdateStatusOrderParams;
      updateStatus(data);
    };

    const updateStatus = async (data: IUpdateStatusOrderParams) => {
      await store.dispatch("order/handleStatusOrder", data);
      const error = store.getters["order/getError"];
      if (error) {
        toast.add({
          severity: "error",
          summary: "Thất bại",
          detail: "Xử lý thất bại!",
          life: 3000,
        });
        return;
      }
      toast.add({
        severity: "success",
        summary: "Thành công",
        detail: "Xử lý thành công!",
        life: 3000,
      });
      getOrders();
    };

    const getOrders = async () => {
      showLoading.value = true;
      await store.dispatch("order/getOrder");
      showLoading.value = false;
    };
    onMounted(() => {
      getOrders();
    });
    return {
      acceptOrder,
      deliveryOrder,
      completeOrder,
      cancelOrder,
      showLoading,
      orderCanceled,
      orderDelivered,
      orderShipping,
      orders,
      orderNew,
      orderProcessing,
    };
  },
});
</script>

<style lang="scss" scoped>
.order {
  padding: 3rem 2rem 5rem 2rem;
  background-color: rgb(235, 235, 235);
  min-height: 90vh;

  :deep(.p-component) {
    font-size: 1.6rem !important;
  }
}
</style>

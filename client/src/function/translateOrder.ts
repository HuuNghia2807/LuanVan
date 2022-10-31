import { IOrderResponse, IOrders } from "@/interface/order/order.state";
import { translateCustomer } from "./translateCustomer";

export const translateOrders = (listOrder: IOrderResponse[]): IOrders[] => {
  return listOrder.map((ele) => {
    return {
      orderId: ele.order_id,
      address:
        ele.address.address +
        ", " +
        ele.address.ward +
        ", " +
        ele.address.district +
        ", " +
        ele.address.city,
      customer: translateCustomer(ele.customer),
      employee: ele.employee,
      orderNote: ele.order_note,
      orderTime: ele.order_time,
      payment: ele.payment.payment_name,
      receiveTime: ele.receive_time,
      status: ele.order_status.order_status,
      totalPrice: ele.total_price,
    };
  });
};

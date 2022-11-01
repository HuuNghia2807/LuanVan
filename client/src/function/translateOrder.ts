import {
  IOrderDetailResponse,
  IOrderResponse,
  IOrders,
  IProductOrder,
} from "@/interface/order/order.state";
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
      productOrder: translateProductOrder(ele.product_order),
    };
  });
};

export const translateProductOrder = (
  productOrder: IOrderDetailResponse[]
): IProductOrder[] => {
  return productOrder.map((ele) => {
    return {
      orderDetailId: ele.order_detail_id,
      productCode: ele.product.product_code,
      productImage: ele.product.product_image[0].product_image_link,
      productName: ele.product.product_name,
      productPrice: ele.product.product_price,
      productOrderQuantity: ele.product_quantity,
      productSize: ele.size,
    };
  });
};

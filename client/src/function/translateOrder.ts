import {
  IHeadReport,
  IHeadReportResponse,
  IOrderDetailResponse,
  IOrderResponse,
  IOrders,
  IProductOrder,
  IReportByMonth,
  IReportByMonthResponse,
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
      payment_id: ele.payment.id,
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
      sale: ele.sale,
    };
  });
};

export const translateHeadReport = (res: IHeadReportResponse): IHeadReport => {
  return {
    customer: res.customer,
    order: res.order,
    priceToday: res.price_today,
  };
};

export const translateReportByMonth = (
  res: IReportByMonthResponse[]
): IReportByMonth[] => {
  const listDate = [] as IReportByMonthResponse[];

  res.forEach((ele) => {
    const date = new Date(ele.date);
    const dateItem = listDate.find((eleS) => {
      const date2 = new Date(eleS.date);
      if (date2.getDate() === date.getDate()) return eleS;
    });
    if (dateItem) {
      dateItem.total_price += ele.total_price;
    } else {
      listDate.push(ele);
    }
  });

  return listDate.map((ele) => {
    return {
      date: ele.date,
      total: ele.total_price,
    };
  });
};

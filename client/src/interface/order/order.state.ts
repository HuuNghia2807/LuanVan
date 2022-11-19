import { Nullable } from "primevue/ts-helpers";
import { ICustomer, IUserResponse } from "../auth/authentication.state";
import { ICartList, IProductResponse } from "../product/product.state";

// interface state
export interface IStateOrder {
  orders: any[] | null;
  cities: ICity[] | null;
  districts: IDistrict[] | null;
  wards: IWard[] | null;
  error: Nullable<any>;
}

// addressMap interface

export interface IProvinceResponse {
  cities: ICity[];
  districts: IDistrict[];
  wards: IWard[];
}

export interface ICity {
  id: number;
  city: string;
  transport_fee: number;
}

export interface IDistrict {
  id: number;
  district: string;
  city_id: number;
}

export interface IWard {
  id: number;
  ward: string;
  district_id: number;
}

// order interface
export interface IOrders {
  address: string;
  customer: ICustomer;
  employee: any;
  orderId: number;
  orderNote: string;
  status: string;
  orderTime: number;
  payment: string;
  payment_id: number;
  receiveTime: number;
  totalPrice: number;
  productOrder: IProductOrder[];
}

export interface IProductOrder {
  orderDetailId: number;
  productOrderQuantity: number;
  productName: string;
  productCode: string;
  productImage: string;
  productPrice: number;
  productSize: number;
  sale: number;
}

export interface IOrderParams {
  customer_id: number;
  customer_name: string;
  note: string;
  total_price: number;
  address_order: IAddressOrder;
  product_order: ICartList[];
  payment_id: number;
}

export interface IAddressOrder {
  address: string;
  ward: number | null;
}

export interface IOrderResponse {
  address: IAddressResponse;
  customer: IUserResponse;
  employee: any;
  order_id: number;
  order_note: string;
  order_status: IOrderStatusResponse;
  order_time: number;
  payment: IPaymentResponse;
  receive_time: number;
  total_price: number;
  product_order: IOrderDetailResponse[];
}

export interface IAddressResponse {
  address: string;
  ward: string;
  district: string;
  city: string;
}
export interface IOrderStatusResponse {
  id: number;
  order_status: string;
}

export interface IOrderDetailResponse {
  order_detail_id: number;
  product_quantity: number;
  product_size_id: number;
  size: number;
  product: IProductResponse;
  sale: number;
}

export interface IPaymentResponse {
  id: number;
  payment_name: string;
}

export interface IUpdateStatusOrderParams {
  order_id: number;
  status_id: number;
  note?: string;
}

export interface IHeadReportResponse {
  customer: IHead;
  order: IHead;
  price_today: number;
}

export interface IHeadReport {
  customer: IHead;
  order: IHead;
  priceToday: number;
}

export interface IHead {
  new: number;
  total: number;
}

export interface IReportResponse {
  bar: IReportByBarResponse[];
  pie: IReportByPieResponse[];
}

export interface IReportByBarResponse {
  date: string;
  total_price: number;
}

export interface IReportByPieResponse {
  category: string;
  total: number;
}

export interface IReport {
  bar: IReportBar[];
  pie: IReportPie[];
}
export interface IReportBar {
  date: string;
  total: number;
}
export interface IReportPie {
  category: string;
  total: number;
}

export interface IDataBar {
  labels: string[];
  datasets: IDatasets[];
}

export interface IDatasets {
  label: string;
  backgroundColor: string;
  data: number[];
}

import { Nullable } from "primevue/ts-helpers";
import { ICustomer, ICustomerResponse } from "../auth/authentication.state";
import { ICartList } from "../product/product.state";

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
  receiveTime: string;
  totalPrice: number;
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
  district: number | null;
  city: number | null;
}

export interface IOrderResponse {
  address: IAddressResponse;
  customer: ICustomerResponse;
  employee: any;
  order_id: number;
  order_note: string;
  order_status: IOrderStatusResponse;
  order_time: number;
  payment: IPaymentResponse;
  receive_time: string;
  total_price: number;
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

export interface IPaymentResponse {
  id: number;
  payment_name: string;
}

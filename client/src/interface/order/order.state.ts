import { Nullable } from "primevue/ts-helpers";
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

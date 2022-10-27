import { Nullable } from "primevue/ts-helpers";

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

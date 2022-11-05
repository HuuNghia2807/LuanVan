import { Nullable } from "primevue/ts-helpers";
import { ICity, IDistrict, IWard } from "../order/order.state";
import { ICart } from "../product/product.state";

export interface IAuthentication {
  isLogged: boolean;
  userId: Nullable<number>;
  userInfo: any;
  cart: ICart[] | null;
  role: Nullable<Role>;
  error: Nullable<any>;
}

export interface ILoginParams {
  email: string;
  password: string;
}

export interface ICustomerResponse {
  id: number;
  email: string;
  user_status_id: number;
  user_status: string;
  first_name: string;
  last_name: string;
  phone: string;
  birth: string | null;
  gender: string;
  avatar: string;
  address: IAddressCustomerResponse[];
}

export interface ICustomer {
  id: number;
  email: string;
  status: string;
  firstName: string;
  lastName: string;
  fullName: string;
  phone: string;
  birth: string | null;
  gender: string | null;
  avatar: string;
  address: IAddressCustomer[];
}

export interface IAddressCustomerResponse {
  id: number;
  customer_id: number;
  address: string;
  ward: IWard;
  district: IDistrict;
  city: ICity;
}

export interface IAddressCustomer {
  address_id: number;
  address: string;
  ward: IWard;
  district: IDistrict;
  city: ICity;
}

export type Role = "admin" | "user";

import { Nullable } from "primevue/ts-helpers";
import { ICity, IDistrict, IWard } from "../order/order.state";
import { ICart } from "../product/product.state";

export interface IAuthentication {
  isLogged: boolean;
  userId: Nullable<number>;
  isLoggedDashboard: boolean;
  userDashboard: null;
  userInfo: any;
  cart: ICart[] | null;
  role: Nullable<Role>;
  error: Nullable<any>;
}

export interface ILoginParams {
  email: string;
  password: string;
}

export interface IUserResponse {
  id: number;
  email: string;
  user_status_id?: number;
  user_status?: string;
  role?: string;
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
  status: string | null;
  firstName: string;
  lastName: string;
  fullName: string;
  phone: string;
  birth: string | null;
  gender: string | null;
  avatar: string;
  address: IAddressCustomer[];
}

export interface IEmployee {
  id: number;
  email: string;
  role: string | null;
  firstName: string;
  lastName: string;
  fullName: string;
  phone: string;
  birth: string | null;
  gender: string | null;
  avatar: string;
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

export interface IUpdateInfoCustomerParams {
  customer_id: number;
  info: IInfoParams;
}

export interface IInfoParams {
  first_name: string;
  last_name: string;
  email: string;
  gender: string;
  birth: string;
  phone: string;
  avatar: string;
}

export interface IChangePassParams {
  customer_id: number;
  new_pass: string;
  old_pass: string;
  new_pass_confirmation: string;
}

export interface IUpdateAddressParams {
  address_id: number;
  address: string;
  ward_id: number;
}

export interface IAllUserResponse {
  customer: IUserResponse[];
  employee: IUserResponse[];
}

export interface IAllUser {
  customer: ICustomer[];
  employee: IEmployee[];
}

export type Role = "admin" | "user";

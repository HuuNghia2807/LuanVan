import { Nullable } from "primevue/ts-helpers";
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

export type Role = "admin" | "user";

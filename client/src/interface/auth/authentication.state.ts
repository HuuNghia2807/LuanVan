import { Nullable } from "primevue/ts-helpers";

export interface IAuthentication {
  isLogged: boolean;
  userId: Nullable<number>;
  userInfo: any;
  role: Nullable<Role>;
  error: Nullable<any>;
}

export interface ILoginParams {
  userName: string;
  password: string;
}

export type Role = "admin" | "user";

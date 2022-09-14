import { Nullable } from "primevue/ts-helpers";

export interface IAuthentication {
  userId: Nullable<number>;
  userName: string;
  role: Nullable<Role>;
  error: Nullable<any>;
}

export interface ILoginParams {
    userName: string;
    password: string;
}

export type Role = "admin" | "user";

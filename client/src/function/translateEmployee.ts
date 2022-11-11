import {
  IAllUser,
  IAllUserResponse,
  IEmployee,
  IUserResponse,
} from "@/interface/auth/authentication.state";
import { translateAddress, translateCustomer } from "./translateCustomer";

export const translateEmployee = (emp: IUserResponse): IEmployee => {
  return {
    id: emp.id,
    firstName: emp.first_name,
    lastName: emp.last_name,
    fullName: `${emp.first_name} ${emp.last_name}`,
    email: emp.email,
    birth: emp.birth,
    gender: emp.gender,
    phone: emp.phone,
    role: emp.role || null,
    avatar: emp.avatar,
    address: translateAddress(emp.address),
  };
};

export const translateUser = (all: IAllUserResponse): IAllUser => {
  return {
    customer: all.customer.map((ele) => translateCustomer(ele)),
    employee: all.employee.map((ele) => translateEmployee(ele)),
  };
};

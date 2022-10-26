import {
  ICustomer,
  ICustomerResponse,
} from "@/interface/auth/authentication.state";

export const translateCustomer = (user: ICustomerResponse): ICustomer => {
  return {
    id: user.id,
    firstName: user.first_name,
    lastName: user.last_name,
    fullName: `${user.first_name} ${user.last_name}`,
    email: user.email,
    birth: user.birth,
    gender: user.gender,
    phone: user.phone,
    status: user.user_status,
    avatar: user.avatar,
  };
};

import {
  IAddressCustomer,
  IAddressCustomerResponse,
  ICustomer,
  IUserResponse,
} from "@/interface/auth/authentication.state";

export const translateCustomer = (user: IUserResponse): ICustomer => {
  return {
    id: user.id,
    firstName: user.first_name,
    lastName: user.last_name,
    fullName: `${user.first_name} ${user.last_name}`,
    email: user.email,
    birth: user.birth,
    gender: user.gender,
    phone: user.phone,
    status: user.user_status || null,
    avatar: user.avatar,
    address: translateAddress(user.address),
  };
};

export const translateAddress = (
  address: IAddressCustomerResponse[]
): IAddressCustomer[] => {
  return address.map((ele) => {
    return {
      address_id: ele.id,
      address: ele.address,
      ward: ele.ward,
      district: ele.district,
      city: ele.city,
    };
  });
};

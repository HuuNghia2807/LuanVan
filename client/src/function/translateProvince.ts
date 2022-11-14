import { IProvinceResponse } from "@/interface/order/order.state";

export const translateProvinceResponse = (
  add: IProvinceResponse
): IProvinceResponse => {
  return {
    cities: add.cities,
    districts: add.districts,
    wards: add.wards,
  };
};

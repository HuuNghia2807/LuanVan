import { IProvinceResponse } from "@/interface/order/order.state";
import { district } from "./addressData";

export const translateProvinceResponse = (
  add: IProvinceResponse
): IProvinceResponse => {
  return {
    cities: add.cities,
    districts: add.districts,
    wards: add.wards,
  };
};

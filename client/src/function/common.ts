import { IProduct } from "@/interface/product/product.state";
import { AxiosError } from "axios";
import moment from "moment";

export const handleError = (error: AxiosError) => {
  return {
    status: error.response?.status || 500,
    message: error.message,
    data: error.response?.data,
    name: error.code || "",
  };
};

export const formatPrice = (price: number) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(price);
};

export const translateUnixTimeToFullTime = (unixTime: number) => {
  return moment.unix(Number(unixTime)).format("MMM DD YYYY HH:mm:ss");
};

export const caculatorSale = (price: number, sale: number) => {
  return (price * (100 - sale)) / 100;
};

export const sortMax = (array: IProduct[]) => {
  for (let i = 0; i < array.length; i++) {
    for (let x = 0; x < array.length - 1 - i; x++) {
      if ((array[x].productPrice || 0) < (array[x + 1].productPrice || 0)) {
        [array[x], array[x + 1]] = [array[x + 1], array[x]];
      }
    }
  }
  return array;
};

export const sortMin = (array: IProduct[]) => {
  for (let i = 0; i < array.length; i++) {
    for (let x = 0; x < array.length - 1 - i; x++) {
      if ((array[x].productPrice || 0) > (array[x + 1].productPrice || 0)) {
        [array[x], array[x + 1]] = [array[x + 1], array[x]];
      }
    }
  }
  return array;
};

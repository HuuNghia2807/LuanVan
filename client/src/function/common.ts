import { AxiosError } from "axios";

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

import { AxiosError } from "axios";

export const handleError = (error: AxiosError) => {
  return {
    status: error.response?.status || 500,
    message: error.message,
    data: error.response?.data,
    name: error.code || "",
  };
};

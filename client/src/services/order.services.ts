import { AxiosError } from "axios";
import { http } from "@/api/axiosClient";
import { handleError } from "@/function/common";
import {
  IOrderParams,
  IUpdateStatusOrderParams,
} from "@/interface/order/order.state";

class OrderService {
  async getProvince() {
    try {
      const response = await http.get("province");
      if (response.data.data) {
        return response.data.data;
      } else {
        throw new Error("Wrong credential");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async getPayment() {
    try {
      const response = await http.get("payment");
      if (response.data.data) {
        return response.data.data;
      } else {
        throw new Error("Wrong credential");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async order(payload: IOrderParams) {
    try {
      const response = await http.post("order", payload);
      return response.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async getOrder() {
    try {
      const response = await http.get("order");
      if (response.data.data) {
        return response.data.data;
      } else {
        throw new Error("Wrong credential");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async handleStatusOrder(payload: IUpdateStatusOrderParams) {
    try {
      const response = await http.post("order/status/update", payload);
      return response.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
}

export default new OrderService();

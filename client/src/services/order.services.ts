import { AxiosError } from "axios";
import { http } from "@/api/axiosClient";
import { handleError } from "@/function/common";

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
}

export default new OrderService();

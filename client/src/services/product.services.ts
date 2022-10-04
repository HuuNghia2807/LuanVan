import { AxiosError } from "axios";
import { http } from "@/api/axiosClient";
import { handleError } from "@/function/common";

class ProductServices {
  async getSizes() {
    try {
      // const loginParams = translateLoginParams(credential);
      const response = await http.get("sizes");
      if (response.data.data) {
        return response.data.data;
      } else {
        throw new Error("Don't get");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
}

export default new ProductServices();

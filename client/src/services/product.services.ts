import { AxiosError } from "axios";
import { http } from "@/api/axiosClient";
import { handleError } from "@/function/common";
import { IProductParams } from "@/interface/product/product.state";

class ProductServices {
  async getSizes() {
    try {
      const response = await http.get("size");
      if (response.data.data) {
        return response.data.data;
      } else {
        throw new Error("Don't get");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async getCategories() {
    try {
      const response = await http.get("category");
      if (response.data.data) {
        return response.data.data;
      } else {
        throw new Error("Don't get");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async addProduct(product: IProductParams) {
    try {
      const path = "product/add";
      const response = await http.post(path, product);
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

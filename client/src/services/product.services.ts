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
      if (response.data) {
        return response.data;
      } else {
        throw new Error("Don't get");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async getProducts() {
    try {
      const path = "product";
      const response = await http.get(path);
      if (response.data) {
        return response.data.data;
      } else {
        throw new Error("Don't get");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async getHomeProduct() {
    try {
      const path = "product/home";
      const response = await http.get(path);
      return response.data.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async deleteProduct(listId: number[]) {
    try {
      const path = "product/delete";
      const body = { product_ids: listId };
      const response = await http.post(path, body);
      if (response.data) {
        return response.data.data;
      } else {
        throw new Error("Don't get");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async updateProduct(payload: any) {
    try {
      const path = "product/update";
      const response = await http.put(path, payload);
      if (response.data) {
        return response.data.data;
      } else {
        throw new Error("Don't get");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async addDiscount(payload: any) {
    try {
      const path = "discount/add";
      const response = await http.post(path, payload);
      return response.data.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async getDiscount() {
    try {
      const path = "discount";
      const response = await http.get(path);
      return response.data.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async deleteDiscount(payload: number[]) {
    try {
      const path = "discount/delete";
      await http.post(path, payload);
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async addDiscountToProduct(payload: any) {
    try {
      const path = "discount/product";
      const response = await http.post(path, payload);
      return response.data.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async removeDiscountToProduct(payload: any) {
    try {
      const path = "discount/remove";
      const response = await http.post(path, payload);
      return response.data.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
}

export default new ProductServices();

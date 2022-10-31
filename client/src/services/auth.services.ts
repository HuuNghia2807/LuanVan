import { AxiosError } from "axios";
import { http } from "@/api/axiosClient";
import { handleError } from "@/function/common";
import { ILoginParams } from "@/interface/auth/authentication.state";
import { translateCustomer } from "@/function/translateCustomer";
import { setItemLocal } from "@/function/handleLocalStorage";

export interface ICredential {
  username: string;
  password: string;
}

class AuthService {
  async login(credential: ILoginParams) {
    try {
      // const loginParams = translateLoginParams(credential);
      const response = await http.post("customer/login", credential);
      if (response.data.data) {
        const customer = translateCustomer(response.data.data);
        setItemLocal("customer", customer);
        // sessionStorage.setItem("user", JSON.stringify(response.data.data));
        return customer;
      } else {
        throw new Error("Wrong credential");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async register(newCustomer: any) {
    try {
      const response = await http.post("customer/register", newCustomer);
      return response;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async loginDashboard(employee: ILoginParams) {
    try {
      const response = await http.post("employee/login", employee);
      return response;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
}

export default new AuthService();

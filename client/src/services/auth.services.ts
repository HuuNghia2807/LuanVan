import { AxiosError } from "axios";
import { http } from "@/api/axiosClient";
import { handleError } from "@/function/common";
import {
  IChangePassParams,
  ILoginParams,
  IUpdateAddressParams,
  IUpdateInfoCustomerParams,
} from "@/interface/auth/authentication.state";
import { translateCustomer } from "@/function/translateCustomer";
import { setItemLocal } from "@/function/handleLocalStorage";
import { translateEmployee } from "@/function/translateEmployee";

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
      if (response.data.data) {
        const employee = translateEmployee(response.data.data);
        setItemLocal("userDashboard", employee);
        return employee;
      } else {
        throw new Error("Wrong credential");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async getPersonalAddress(customer_id: number) {
    try {
      const response = await http.get(`customer/address/${customer_id}`);
      return response.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async updateInfo(payload: IUpdateInfoCustomerParams) {
    try {
      const response = await http.put("customer/update", payload);
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
  async getCustomer(cus_id: number) {
    try {
      const response = await http.get(`customer/${cus_id}`);
      if (response.data.data) {
        const customer = translateCustomer(response.data.data);
        setItemLocal("customer", customer);
        return customer;
      } else {
        throw new Error("Wrong credential");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async changePass(payload: IChangePassParams) {
    try {
      const response = await http.put(`customer/password`, payload);
      return response.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async updateAddress(payload: IUpdateAddressParams) {
    try {
      const response = await http.put("customer/address", payload);
      return response.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async addEmployee(payload: any) {
    try {
      const response = await http.post("employee/add", payload);
      return response.data.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
  async getAllUser() {
    try {
      const response = await http.get("employee/all");
      return response.data.data;
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
}

export default new AuthService();

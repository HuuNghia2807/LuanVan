import { AxiosError } from "axios";
import { http } from "@/api/axiosClient";
import { handleError } from "@/function/common";
import { ILoginParams } from "@/interface/auth/authentication.state";

export interface ICredential {
  username: string;
  password: string;
}

class AuthService {
  async login(credential: ILoginParams) {
    try {
      // const loginParams = translateLoginParams(credential);
      const response = await http.post("auth/login", credential);
      if (response.data.data) {
        //   sessionStorage.setItem("user", JSON.stringify(user));
        return response.data.data;
      } else {
        throw new Error("Wrong credential");
      }
    } catch (error) {
      throw handleError(error as AxiosError);
    }
  }
}

export default new AuthService();

/* eslint-disable @typescript-eslint/no-explicit-any */
import axios, { AxiosInstance, AxiosRequestConfig, AxiosResponse } from "axios";
import { Config } from "@/config";

enum StatusCode {
  Unauthorized = 401,
  Forbidden = 403,
  TooManyRequests = 429,
  InternalServerError = 500,
}

const headers: Readonly<Record<string, string>> = {
  Accept: "application/json",
  AccessControlallowOrigin: "http://localhost:8000",
};

class Http {
  private instance: AxiosInstance | null = null;

  private get http(): AxiosInstance {
    // return this.instance != null ? this.instance : this.initHttp()
    return this.initHttp();
  }

  initHttp() {
    const http = axios.create({
      baseURL: Config.API_URL,
      headers,
    });

    this.instance = http;
    return http;
  }

  request<T = any, R = AxiosResponse<T>>(
    config: AxiosRequestConfig
  ): Promise<R> {
    return this.http.request(config);
  }

  get(url: string, config?: AxiosRequestConfig): Promise<AxiosResponse> {
    return this.http.get(url, config);
  }

  post(
    url: string,
    data?: any,
    config?: AxiosRequestConfig
  ): Promise<AxiosResponse> {
    return this.http.post(url, data, config);
  }

  put(
    url: string,
    data?: any,
    config?: AxiosRequestConfig
  ): Promise<AxiosResponse> {
    return this.http.put(url, data, config);
  }

  delete(url: string, config?: AxiosRequestConfig): Promise<AxiosResponse> {
    return this.http.delete(url, config);
  }

  // Handle global app errors
  // We can handle generic app errors depending on the status code
  private async handleError(error: { response: { status: any }; config: any }) {
    const { status } = error.response;
    switch (status) {
      case StatusCode.InternalServerError: {
        // Handle InternalServerError
        break;
      }
      case StatusCode.Forbidden: {
        // Handle Forbidden
        break;
      }
      case StatusCode.TooManyRequests: {
        // Handle TooManyRequests
        break;
      }
    }

    return Promise.reject(error);
  }
}

export const http = new Http();

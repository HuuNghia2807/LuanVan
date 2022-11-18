// import axios from "axios"

import {
  translateHeadReport,
  translateOrders,
  translateReportByMonth,
} from "@/function/translateOrder";
import { translateProvinceResponse } from "@/function/translateProvince";
import { IAuthentication } from "@/interface/auth/authentication.state";
import {
  IOrderParams,
  IStateOrder,
  IUpdateStatusOrderParams,
} from "@/interface/order/order.state";
import orderServices from "@/services/order.services";
import { ActionTree, GetterTree, MutationTree } from "vuex";

const initDefaultState = (): IStateOrder => {
  return {
    orders: null,
    cities: null,
    districts: null,
    wards: null,
    error: null,
  };
};

const state = initDefaultState();
const getters: GetterTree<IStateOrder, IAuthentication> = {
  getOrders: (state) => state.orders,
  getCities: (state) => state.cities,
  getDistricts: (state) => state.districts,
  getWards: (state) => state.wards,
  getError: (state) => state.error,
};

const mutations: MutationTree<IStateOrder> = {
  setError(state, payload) {
    state.error = payload.error;
  },
  setOrders(state, payload) {
    state.orders = payload;
  },
  setCities(state, payload) {
    state.cities = payload;
  },
  setDistricts(state, payload) {
    state.districts = payload;
  },
  setWards(state, payload) {
    state.wards = payload;
  },
};
const actions: ActionTree<IStateOrder, IAuthentication> = {
  async getProvince({ commit }) {
    try {
      commit("setError", {});
      const add = await orderServices.getProvince();
      const province = translateProvinceResponse(add);
      commit("setCities", province.cities);
      commit("setDistricts", province.districts);
      commit("setWards", province.wards);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getPayment({ commit }) {
    try {
      commit("setError", {});
      const pay = await orderServices.getPayment();
      return pay;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async order({ commit }, payload: IOrderParams) {
    try {
      commit("setError", {});
      const res = await orderServices.order(payload);
      return res;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getOrder({ commit }) {
    try {
      commit("setError", {});
      const res = await orderServices.getOrder();
      const orders = translateOrders(res);
      commit("setOrders", orders);
      return res;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async handleStatusOrder({ commit }, payload: IUpdateStatusOrderParams) {
    try {
      commit("setError", {});
      const res = await orderServices.handleStatusOrder(payload);
      return res;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getPersonalOrder({ commit }, customer_id: number) {
    try {
      commit("setError", {});
      const res = await orderServices.getPersonalOrder(customer_id);
      const orders = translateOrders(res.data);
      return orders;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getHeadReport({ commit }) {
    try {
      commit("setError", {});
      const res = await orderServices.getHeadReport();
      const orders = translateHeadReport(res);
      return orders;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getReportByMonth({ commit }, payload: any) {
    try {
      commit("setError", {});
      const res = await orderServices.getReportByMonth(payload);
      const dataMonth = translateReportByMonth(res);
      return dataMonth;
    } catch (error) {
      commit("setError", { error });
    }
  },
};

const order = {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};

export default order;

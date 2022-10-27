// import axios from "axios"

import { translateProvinceResponse } from "@/function/translateProvince";
import { IAuthentication } from "@/interface/auth/authentication.state";
import { IStateOrder } from "@/interface/order/order.state";
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
      commit("setDistricts", province.cities);
      commit("setWards", province.wards);
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

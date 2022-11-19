// import axios from "axios"

import { removeItemLocal } from "@/function/handleLocalStorage";
import { translateUser } from "@/function/translateEmployee";
import {
  IAuthentication,
  IChangePassParams,
  ILoginParams,
  IUpdateAddressParams,
  IUpdateInfoCustomerParams,
} from "@/interface/auth/authentication.state";
import authServices from "@/services/auth.services";
import { ActionTree, GetterTree, MutationTree } from "vuex";

const initDefaultState = (): IAuthentication => {
  return {
    userId: null,
    isLogged: false,
    isLoggedDashboard: false,
    userDashboard: null,
    userInfo: null,
    cart: null,
    role: null,
    error: null,
  };
};

const state = initDefaultState();
const getters: GetterTree<IAuthentication, IAuthentication> = {
  getUserId: (state) => state.userId,
  getIslogged: (state) => state.isLogged,
  getIsloggedDashboard: (state) => state.isLoggedDashboard,
  getUser: (state) => state.userInfo,
  getUserDashboard: (state) => state.userDashboard,
  getCart: (state) => state.cart,
  getError: (state) => state.error,
};

const mutations: MutationTree<IAuthentication> = {
  setError(state, payload) {
    state.error = payload.error;
  },
  setCart(state, payload) {
    state.cart = payload;
  },
  setUser(state, payload) {
    state.userInfo = payload;
    if (payload) {
      state.isLogged = true;
      return;
    }
    state.isLogged = false;
  },
  setUserDashboard(state, payload) {
    state.userDashboard = payload;
    if (payload) {
      state.isLoggedDashboard = true;
      return;
    }
    state.isLoggedDashboard = false;
  },
};

const actions: ActionTree<IAuthentication, IAuthentication> = {
  async login({ commit }, user: ILoginParams) {
    try {
      commit("setError", { error: null });
      const u = await authServices.login(user);
      commit("setUser", u);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async logout({ commit }) {
    try {
      commit("setUser", null);
      removeItemLocal("customer");
    } catch (error) {
      commit("setError", { error });
    }
  },
  async logoutDashboard({ commit }) {
    try {
      commit("setUserDashboard", null);
      removeItemLocal("userDashboard");
    } catch (error) {
      commit("setError", { error });
    }
  },
  async addCart({ commit }, data: any) {
    try {
      commit("setCart", data);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async register({ commit }, newCustomer: any) {
    try {
      commit("setError", { error: null });
      const res = await authServices.register(newCustomer);
      return res;
      //call api
    } catch (error) {
      commit("setError", { error });
    }
  },
  async loginDashboard({ commit }, employee: ILoginParams) {
    try {
      commit("setError", { error: null });
      const emp = await authServices.loginDashboard(employee);
      commit("setUserDashboard", emp);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getPersonalAddress({ commit }, customer_id: number) {
    try {
      commit("setError", { error: null });
      const res = await authServices.getPersonalAddress(customer_id);
      return res;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async updateInfo({ commit }, payload: IUpdateInfoCustomerParams) {
    try {
      commit("setError", { error: null });
      const res = await authServices.updateInfo(payload);
      commit("setUser", res);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getCustomer({ commit }, cus_id: number) {
    try {
      commit("setError", { error: null });
      const res = await authServices.getCustomer(cus_id);
      commit("setUser", res);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async changePass({ commit }, payload: IChangePassParams) {
    try {
      commit("setError", { error: null });
      await authServices.changePass(payload);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async updateAddress({ commit }, payload: IUpdateAddressParams) {
    try {
      commit("setError", { error: null });
      await authServices.updateAddress(payload);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async addEmployee({ commit }, payload: any) {
    try {
      commit("setError", { error: null });
      await authServices.addEmployee(payload);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getAllUser({ commit }) {
    try {
      commit("setError", { error: null });
      const all = await authServices.getAllUser();
      return translateUser(all);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async deleteAddress({ commit }, address_id: number) {
    try {
      commit("setError", { error: null });
      await authServices.deleteAddress(address_id);
    } catch (error) {
      commit("setError", { error });
    }
  },
};

const auth = {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};

export default auth;

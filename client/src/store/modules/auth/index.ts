// import axios from "axios"

import {
  IAuthentication,
  ILoginParams,
} from "@/interface/auth/authentication.state";
import authServices from "@/services/auth.services";
import { ActionTree, GetterTree, MutationTree } from "vuex";

const initDefaultState = (): IAuthentication => {
  return {
    userId: null,
    isLogged: false,
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
  getUserName: (state) => state.userInfo,
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
    Object.assign(state, {
      ...state,
      userId: payload.userId,
      userName: payload.userName,
      role: payload.role,
    });
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
  async addCart({ commit }, data: any) {
    try {
      commit("setCart", data);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async register({ commit }, newCustomer: any) {
    try {
      commit("setError", {});
      const res = await authServices.register(newCustomer);
      return res;
      //call api
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

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
    userName: "",
    role: null,
    error: null,
  };
};

const state = initDefaultState();
const getters: GetterTree<IAuthentication, IAuthentication> = {
  getUserId: (state) => state.userId,
  getUserName: (state) => state.userName,
  getError: (state) => state.error,
};

const mutations: MutationTree<IAuthentication> = {
  setError(state, payload) {
    state.error = payload.error;
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
      commit("setUser", { userId: 1, userName: user.userName, role: "admin" });
      const u = await authServices.login(user);
      commit("setError", { error: null });
      console.log(u);
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

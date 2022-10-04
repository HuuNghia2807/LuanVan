// import axios from "axios"

import { IProduct } from "@/interface/product/product.state";
import productServices from "@/services/product.services";
import { ActionTree, GetterTree, MutationTree } from "vuex";

const initDefaultState = (): IProduct => {
  return {
    productId: null,
    productName: "",
    productRating: null,
    error: null,
  };
};

const state = initDefaultState();
const getters: GetterTree<IProduct, IProduct> = {
  getUserId: (state) => state.productId,
  getUserName: (state) => state.productName,
  getError: (state) => state.productRating,
};

const mutations: MutationTree<IProduct> = {
  setError(state, payload) {
    state.error = payload.error;
  },
};
const actions: ActionTree<IProduct, IProduct> = {
  async getSizes({ commit }) {
    try {
      const s = await productServices.getSizes();
      return s;
      //call api
    } catch (error) {
      commit("setError", { error });
    }
  },
};

const product = {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};

export default product;

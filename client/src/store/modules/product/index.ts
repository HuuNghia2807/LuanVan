// import axios from "axios"

import { translateCategoryResponse } from "@/function/translateCategory";
import { translateSizeResponse } from "@/function/translateSize";
import { IAuthentication } from "@/interface/auth/authentication.state";
import { IProduct, IProductParams } from "@/interface/product/product.state";
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
const getters: GetterTree<IProduct, IAuthentication> = {
  getUserId: (state) => state.productId,
  getUserName: (state) => state.productName,
  getError: (state) => state.error,
};

const mutations: MutationTree<IProduct> = {
  setError(state, payload) {
    state.error = payload.error;
  },
};
const actions: ActionTree<IProduct, IAuthentication> = {
  async getSizes({ commit }) {
    try {
      const s = await productServices.getSizes();
      const sizeRes = translateSizeResponse(s);
      return sizeRes;

      //call api
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getCategories({ commit }) {
    try {
      const c = await productServices.getCategories();
      const cateRes = translateCategoryResponse(c);
      return cateRes;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async addProduct({ commit }, payload: IProductParams) {
    try {
      const res = await productServices.addProduct(payload);
      return res;
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

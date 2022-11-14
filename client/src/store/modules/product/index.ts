// import axios from "axios"

import { translateCategoryResponse } from "@/function/translateCategory";
import {
  translateDiscount,
  translateProductsHomeResponse,
  translateProductsResponse,
} from "@/function/translateProduct";
import { translateSizeResponse } from "@/function/translateSize";
import { IAuthentication } from "@/interface/auth/authentication.state";
import {
  IStateProduct,
  IProductParams,
} from "@/interface/product/product.state";
import productServices from "@/services/product.services";
import { ActionTree, GetterTree, MutationTree } from "vuex";

const initDefaultState = (): IStateProduct => {
  return {
    products: null,
    productName: "",
    productRating: null,
    error: null,
  };
};

const state = initDefaultState();
const getters: GetterTree<IStateProduct, IAuthentication> = {
  getProducts: (state) => state.products,
  getUserName: (state) => state.productName,
  getError: (state) => state.error,
};

const mutations: MutationTree<IStateProduct> = {
  setError(state, payload) {
    state.error = payload.error;
  },
  setProducts(state, payload) {
    state.products = payload;
  },
};
const actions: ActionTree<IStateProduct, IAuthentication> = {
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
      commit("setError", {});
      const res = await productServices.addProduct(payload);
      return res;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getProducts({ commit }) {
    try {
      commit("setError", {});
      const res = await productServices.getProducts();
      const products = translateProductsResponse(res);
      commit("setProducts", products);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getHomeProduct({ commit }) {
    try {
      commit("setError", {});
      const res = await productServices.getHomeProduct();
      const products = translateProductsHomeResponse(res);
      return products;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async deleteProduct({ commit }, listId: number[]) {
    try {
      commit("setError", {});
      const res = await productServices.deleteProduct(listId);
      return res;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async updateProduct({ commit }, payload: any) {
    try {
      commit("setError", {});
      const res = await productServices.updateProduct(payload);
      return res;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async addDiscount({ commit }, payload: any) {
    try {
      commit("setError", {});
      const res = await productServices.addDiscount(payload);
      return res;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async getDiscount({ commit }) {
    try {
      commit("setError", {});
      const res = await productServices.getDiscount();
      const dis = translateDiscount(res);
      return dis;
    } catch (error) {
      commit("setError", { error });
    }
  },
  async deleteDiscount({ commit }, payload: number[]) {
    try {
      commit("setError", {});
      await productServices.deleteDiscount(payload);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async addDiscountToProduct({ commit }, payload: any) {
    try {
      commit("setError", {});
      const res = await productServices.addDiscountToProduct(payload);
      const products = translateProductsResponse(res);
      commit("setProducts", products);
    } catch (error) {
      commit("setError", { error });
    }
  },
  async removeDiscountToProduct({ commit }, payload: any) {
    try {
      commit("setError", {});
      const res = await productServices.removeDiscountToProduct(payload);
      const products = translateProductsResponse(res);
      commit("setProducts", products);
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

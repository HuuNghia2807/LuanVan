// import axios from "axios"

import { IProduct } from "@/interface/product/product.interface";
import { ActionTree, GetterTree, MutationTree } from "vuex";

const initDefaultState = (): IProduct => {
  return {
    productId: null,
    productName: "",
    productRating: null,
  };
};

const state = initDefaultState();

const product = {
  namespaced: true,
  state,
};

export default product;

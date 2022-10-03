import { createStore } from "vuex";
import auth from "./modules/auth";
import product from "./modules/product";

export default createStore({
  modules: {
    auth,
    product,
  },
});

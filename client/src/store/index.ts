import { createStore } from "vuex";
import auth from "./modules/auth";
import product from "./modules/product";
import order from "./modules/order";

export default createStore({
  modules: {
    auth,
    product,
    order,
  },
});

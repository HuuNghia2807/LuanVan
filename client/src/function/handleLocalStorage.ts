import { ICart } from "@/interface/product/product.state";
import { useStore } from "vuex";
const store = useStore();

export const setItemLocal = (name: string, item: any) => {
  localStorage.setItem(name, JSON.stringify(item));
};

export const getItemLocal = (name: string) => {
  return JSON.parse(localStorage.getItem(name) as string);
};

export const removeItemLocal = (name: string) => {
  localStorage.removeItem(name);
};

export const addProductToCart = (item: ICart, assign?: boolean) => {
  const arrCart: ICart[] = getItemLocal("cart") || [];
  if (arrCart.length) {
    let check = false;
    arrCart.forEach((itemCart) => {
      if (itemCart.productSizeId === item.productSizeId) {
        if (assign) {
          itemCart.quantity = item.quantity;
        } else {
          itemCart.quantity += item.quantity;
        }
        check = true;
      }
    });
    if (!check) {
      arrCart.push(item);
    }
    setItemLocal("cart", arrCart);
    check = false;
    return;
  }
  setItemLocal("cart", [item]);
};

export const removeProductToCart = (id: number) => {
  const arrCart: ICart[] = getItemLocal("cart") || [];
  const newCart = arrCart.filter((item) => item.productSizeId !== id);
  setItemLocal("cart", newCart);
};

export const setStateCart = (st: any) => {
  const listItem = getItemLocal("cart");
  st.dispatch("auth/addCart", listItem);
};

export const setCustomerLogin = (st: any) => {
  const user = getItemLocal("customer");
  st.commit("auth/setUser", user);
};

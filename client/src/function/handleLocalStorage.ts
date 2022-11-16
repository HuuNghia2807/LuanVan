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
    const check = arrCart.find((itemCart) => {
      if (itemCart.productSizeId === item.productSizeId) {
        if (item.quantity < itemCart.maxQuantity)
          if (assign) {
            itemCart.quantity = item.quantity;
          } else {
            itemCart.quantity += item.quantity;
          }
        return itemCart;
      }
    });
    if (!check) {
      arrCart.push(item);
    }
    setItemLocal("cart", arrCart);
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
export const setEmployeeLogin = (st: any) => {
  const user = getItemLocal("userDashboard");
  st.commit("auth/setUserDashboard", user);
};

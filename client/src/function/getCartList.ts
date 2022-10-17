import { ICart, IProduct } from "@/interface/product/product.state";

export const getCartList = (listProduct: IProduct[], cartItem: ICart[]) => {
  return cartItem.map((item) => {
    const pro = listProduct.find(
      (product) => product.productId === item.productId
    );
    if (pro) {
      return {
        productId: pro.productId,
        img: pro.images[0].product_image_link,
        name: pro.productName,
        price: pro.productPrice,
        productSizeId: item.productSizeId,
        size: pro.sizes.find((ele) => ele.productSizeId === item.productSizeId)
          ?.size,
        quantity: item.quantity,
      };
    }
  });
};

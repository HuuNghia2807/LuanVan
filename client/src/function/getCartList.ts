import { ICart, ICartList, IProduct } from "@/interface/product/product.state";
import { caculatorSale } from "./common";

export const getCartList = (listProduct: IProduct[], cartItem: ICart[]) => {
  return cartItem.map((item) => {
    const pro = listProduct.find(
      (product) => product.productId === item.productId
    );
    if (pro) {
      return {
        productId: pro.productId,
        productCode: pro.productCode,
        img: pro.images[0].product_image_link,
        name: pro.productName,
        price: handlePrice(pro),
        sale: pro.discountId !== 0 ? pro.discountPercent : 0,
        productSizeId: item.productSizeId,
        size: pro.sizes.find((ele) => ele.productSizeId === item.productSizeId)
          ?.size,
        quantity: item.quantity,
      };
    }
  });
};

const handlePrice = (pro: IProduct) => {
  if (pro.discountId !== 0) {
    return caculatorSale(pro.productPrice || 999999, pro.discountPercent || 0);
  }
  return pro.productPrice;
};

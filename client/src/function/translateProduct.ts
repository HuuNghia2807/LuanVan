import { IProduct, IProductResponse } from "@/interface/product/product.state";
import { translateSize } from "./translateSize";

export const translateProductsResponse = (
  product_list: IProductResponse[]
): IProduct[] => {
  return product_list.map((prd) => {
    return {
      productId: prd.product_id,
      categoryId: prd.category_id,
      productName: prd.product_name,
      productPrice: prd.product_price,
      productRating: prd.product_rating,
      sizes: translateSize(prd.sizes),
      images: prd.product_image,
      category: prd.category,
    };
  });
};

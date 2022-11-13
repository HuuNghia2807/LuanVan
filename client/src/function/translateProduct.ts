import {
  IDiscount,
  IProduct,
  IProductResponse,
} from "@/interface/product/product.state";
import { translateSize } from "./translateSize";

export const translateProductsResponse = (
  product_list: IProductResponse[]
): IProduct[] => {
  return product_list.map((prd) => {
    return {
      productId: prd.product_id,
      categoryId: prd.category_id,
      productName: prd.product_name,
      productCode: prd.product_code.toUpperCase(),
      productPrice: prd.product_price,
      productRating: prd.product_rating,
      discountId: prd.discount?.discount_id || 0,
      discountPercent: prd.discount?.percent || 0,
      sizes: translateSize(prd.sizes),
      images: prd.product_image,
      category: prd.category,
    };
  });
};

export const translateDiscount = (dis: any[]): IDiscount[] => {
  return dis.map((ele) => {
    return {
      discountId: ele.discount_id,
      title: ele.title,
      percent: ele.percent,
      dateCreate: ele.time_create,
    };
  });
};

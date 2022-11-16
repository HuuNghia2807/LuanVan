import {
  IComment,
  IDiscount,
  IProduct,
  IProductHome,
  IProductHomeResponse,
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
      productRating: parseInt(prd.product_rating.toString()),
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

export const translateProductsHomeResponse = (
  res: IProductHomeResponse
): IProductHome => {
  return {
    productNew: translateProductsResponse(res.new_product),
    productSale: translateProductsResponse(res.sale_product),
    productHot: translateProductsResponse(res.hot_product),
  };
};

export const translateComment = (res: any[]): IComment[] => {
  return res.map((ele) => {
    return {
      commentId: ele.comment_id,
      productId: ele.product_id,
      comment: ele.comment,
      commentRating: ele.comment_rating,
      customerId: ele.customer.id,
      customerAvatar: ele.customer.avatar,
      customerFullname: `${ele.customer.first_name} ${ele.customer.last_name}`,
    };
  });
};

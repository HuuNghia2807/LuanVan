import { IProduct, IProductResponse } from "@/interface/product/product.state";

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
      sizes: prd.sizes,
      images: prd.product_image,
    };
  });
};

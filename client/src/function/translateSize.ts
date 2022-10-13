import { ISize, ISizeResponse } from "@/interface/product/product.state";

export const translateSizeResponse = (arrS: ISizeResponse[]) => {
  return arrS.map((size) => {
    return size.size;
  });
};

export const translateSize = (arrS: ISizeResponse[]): ISize[] => {
  return arrS.map((ele) => {
    return {
      size: ele.size,
      sizeId: ele.size_id,
      productSizeId: ele.product_size_id,
      productSizeQuantity: ele.product_size_quantity,
    };
  });
};

import { ISizeResponse } from "@/interface/product/product.state";

export const translateSizeResponse = (arrS: ISizeResponse[]) => {
  return arrS.map((size) => {
    return size.size;
  });
};

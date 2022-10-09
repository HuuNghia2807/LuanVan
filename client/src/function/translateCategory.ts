import { ICategoryResponse } from "@/interface/product/product.state";

export const translateCategoryResponse = (category: ICategoryResponse[]) => {
  return category.map((cate) => {
    return cate.category_name;
  });
};

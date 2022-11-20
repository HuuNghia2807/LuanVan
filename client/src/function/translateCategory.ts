import {
  ICategory,
  ICategoryResponse,
} from "@/interface/product/product.state";

export const translateCategoryResponse = (
  category: ICategoryResponse[]
): ICategory[] => {
  return category.map((cate) => {
    return {
      categoryId: cate.id,
      categoryName: cate.category_name,
    };
  });
};

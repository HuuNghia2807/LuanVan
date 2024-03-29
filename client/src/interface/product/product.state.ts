import { Nullable } from "primevue/ts-helpers";

// interface state
export interface IStateProduct {
  products: any[] | null;
  categories: any[] | null;
  newProducts: any[] | null;
  productRating: Nullable<number>;
  error: Nullable<any>;
}

// product interface

export interface IProduct {
  productId: number;
  productName: string;
  productPrice: number | null;
  productCode: string;
  productRating: number;
  categoryId: number;
  category: string;
  discountId: number | null;
  discountPercent: number | null;
  sizes: ISize[];
  images: IProductImageResponse[];
  display?: string;
}

export interface IOptionProduct {
  type?: string;
  title: string;
  supTitle: string;
  products: IProduct[];
}

export interface IProductParams {
  product_name: string;
  product_price: number | null;
  category: string;
  sizes: ISizeParams[];
  images: any[];
}

export interface IProductResponse {
  product_id: number;
  product_name: string;
  product_price: number;
  product_code: string;
  category_id: number;
  product_rating: number;
  category: string;
  discount: IDiscountResponse;
  sizes: ISizeResponse[];
  product_image: IProductImageResponse[];
}

export interface IProductHomeResponse {
  new_product: IProductResponse[];
  sale_product: IProductResponse[];
  hot_product: IProductResponse[];
}

export interface IProductHome {
  productNew: IProduct[];
  productSale: IProduct[];
  productHot: IProduct[];
}

export interface IFilterProduct {
  category: string;
  size: number;
  price: number;
  sort: string;
}

// product image interface

export interface IProductImageResponse {
  product_image_id?: number;
  product_image_link: string;
  product_image_name: string;
}

// sizes interface
export interface ISizeResponse {
  size_id: number;
  size: number;
  product_size_id: number;
  product_size_quantity: number;
}

export interface ISize {
  sizeId: number;
  productSizeId: number;
  productSizeQuantity: number;
  size: number;
}

export interface ISizeParams {
  size_id: number | null;
  size: number | null;
  size_quantity: number | null;
}

//category interface

export interface ICategoryResponse {
  id: number;
  category_name: string;
}

export interface ICategory {
  categoryId: number;
  categoryName: string;
}

export interface ICategoryRouting {
  name: string;
  link: string;
}

// cart interface

export interface ICart {
  productId: number | undefined;
  productSizeId: number;
  quantity: number;
  maxQuantity: number;
}

export interface ICartList {
  productId: number;
  img: string;
  name: string;
  price: number;
  sale: number;
  productSizeId: number;
  size: number;
  quantity: number;
}

// discount interface

export interface IDiscount {
  discountId: number;
  title: string;
  percent: number;
  dateCreate: number;
}

export interface IDiscountResponse {
  discount_id: number;
  title: string;
  percent: number;
  time_create: number;
}

// interface comment

export interface IComment {
  commentId: number;
  comment: string;
  commentRating: number;
  productId: number;
  customerId: number;
  customerAvatar: string;
  customerFullname: string;
  timeComment: number;
}

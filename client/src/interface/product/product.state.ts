import { Nullable } from "primevue/ts-helpers";

// interface state
export interface IStateProduct {
  products: any[] | null;
  productName: string;
  productRating: Nullable<number>;
  error: Nullable<any>;
}

// product interface

export interface IProduct {
  productId: number;
  productName: string;
  productPrice: number | null;
  productRating: number;
  categoryId: number;
  sizes: ISizeResponse[];
  images: IProductImageResponse[];
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
  category_id: number;
  product_rating: number;
  sizes: ISizeResponse[];
  product_image: IProductImageResponse[];
}

// product image interface

export interface IProductImageResponse {
  product_image_link: string;
  product_image_name: string;
}

// sizes interface
export interface ISizeResponse {
  size_id: number;
  size: number;
}

export interface ISize {
  size: number;
}

export interface ISizeParams {
  size: number | null;
  size_quantity: number | null;
}

//category interface

export interface ICategoryResponse {
  id: number;
  category_name: string;
}

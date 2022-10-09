import { Nullable } from "primevue/ts-helpers";

// interface state
export interface IProduct {
  productId: Nullable<number>;
  productName: string;
  productRating: Nullable<number>;
  error: Nullable<any>;
}

// product interface

export interface IProductParams {
  product_name: string;
  product_price: number;
  category: string;
  sizes: ISizeParams[];
  file_images: any;
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
  size: number;
  size_quantity: number;
}

//category interface

export interface ICategoryResponse {
  id: number;
  category_name: string;
}

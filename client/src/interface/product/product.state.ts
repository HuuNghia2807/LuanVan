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
  productCode: string;
  productRating: number;
  categoryId: number;
  category: string;
  sizes: ISize[];
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
  product_code: string;
  category_id: number;
  product_rating: number;
  category: string;
  sizes: ISizeResponse[];
  product_image: IProductImageResponse[];
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

// cart interface

export interface ICart {
  productId: number | undefined;
  productSizeId: number;
  quantity: number;
}

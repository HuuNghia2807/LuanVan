import { Nullable } from "primevue/ts-helpers";

export interface IProduct {
  productId: Nullable<number>;
  productName: string;
  productRating: Nullable<number>;
}

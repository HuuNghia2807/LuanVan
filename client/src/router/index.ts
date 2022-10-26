import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
// client
import MainLayout from "@/Layout/MainLayout.vue";

import HomeView from "@/views/HomeView.vue";
import OrderView from "@/views/OrderView.vue";
import ProductTypeView from "@/views/ProductTypeView.vue";
import ProductDetail from "@/views/ProductDetail.vue";
import CartView from "@/views/CartView.vue";
import CheckoutsView from "@/views/CheckoutsView.vue";
import CustomerView from "@/views/CustomerView.vue";

import AccountView from "@/views/AccountView.vue";

// admin
import AdminMainLayout from "@/Dashboard/layout/AdminMainLayout.vue";

import HomeDashboardView from "@/Dashboard/views/HomeDashboardView.vue";
import ProductDashboardView from "@/Dashboard/views/ProductDashboardView.vue";
import ReportDashboardView from "@/Dashboard/views/ReportDashboardView.vue";
import OrderDashboardView from "@/Dashboard/views/OrderDashboardView.vue";
import UserDashboardView from "@/Dashboard/views/UserDashboardView.vue";
import PersonalDashboardView from "@/Dashboard/views/PersonalDashboardView.vue";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    name: "home",
    component: MainLayout,
    children: [
      {
        path: "",
        component: HomeView,
      },
      {
        path: "/san-pham",
        component: ProductTypeView,
      },
      {
        path: "/nike",
        component: ProductTypeView,
      },
      {
        path: "/adidas",
        component: ProductTypeView,
      },
      {
        path: "/jordan",
        component: ProductTypeView,
      },
      {
        path: "/yeezy",
        component: ProductTypeView,
      },
      {
        path: "/day-giay",
        component: ProductTypeView,
      },
      {
        path: "/contact",
        component: ProductTypeView,
      },
      {
        path: "/order",
        component: OrderView,
      },
      {
        path: "/san-pham/:id",
        name: "showDetail",
        component: ProductDetail,
      },
      {
        path: "/gio-hang",
        component: CartView,
      },
      {
        path: "/profile",
        component: CustomerView,
      },
    ],
  },
  {
    path: "/checkouts",
    name: "checkouts",
    component: CheckoutsView,
  },
  {
    path: "/account",
    name: "account",
    component: AccountView,
  },
  {
    path: "/dashboard",
    name: "dashboard",
    component: AdminMainLayout,
    children: [
      {
        path: "",
        component: HomeDashboardView,
      },
      {
        path: "products",
        component: ProductDashboardView,
      },
      {
        path: "report",
        component: ReportDashboardView,
      },
      {
        path: "order",
        component: OrderDashboardView,
      },
      {
        path: "user",
        component: UserDashboardView,
      },
      {
        path: "personal",
        component: PersonalDashboardView,
      },
    ],
  },
  // {
  //   path: '/about',
  //   name: 'about',
  //   // route level code-splitting
  //   // this generates a separate chunk (about.[hash].js) for this route
  //   // which is lazy-loaded when the route is visited.
  //   component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  // }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;

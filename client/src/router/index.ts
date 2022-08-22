import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import MainLayout from "@/Layout/MainLayout.vue";

import HomeView from "@/views/HomeView.vue";
import OrderView from "@/views/OrderView.vue";
import ProductTypeView from "@/views/ProductTypeView.vue";

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

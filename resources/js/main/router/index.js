
import { createRouter, createWebHistory } from 'vue-router';
import { includes } from "lodash-es";
import store from '../store';

import AuthRoutes from './auth';
import DashboardRoutes from './dashboard';
import UserRoutes from './users';
import CampaignRoutes from './campaigns';
import ExpensesRoutes from './expenses';
import ProductsRoutes from './products';
import MessagingRoutes from './messaging';
import FormRoutes from './forms';
import LeadCallRoutes from './leadsCalls';
import SettingRoutes from './settings';
import BookingRoutes from './bookings';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "",
            redirect: "/admin/login",
        },
        ...AuthRoutes,
        ...DashboardRoutes,
        ...UserRoutes,
        ...SettingRoutes,
        ...CampaignRoutes,
        ...MessagingRoutes,
        ...FormRoutes,
        ...LeadCallRoutes,
        ...BookingRoutes,
        ...ExpensesRoutes,
        ...ProductsRoutes,
    ],
    scrollBehavior: () => ({ left: 0, top: 0 }),
});

router.beforeEach((to, from, next) => {
  const user = store.state.auth.user;
   if(to.meta.requireAuth && to.meta.permission && user && from.name !== "admin.login") {
      const permissions = user.roles[0].permissions.map((perm) => perm.name);

      if(!includes(permissions, to.meta.permission) && user.roles[0].name !== "admin") {
        next({ name: from.name });
      }
   }

    const routeParts = to.name.split(".");
    if (routeParts.length > 0 && routeParts[0] == "admin") {
        if (to.meta.requireAuth && !store.getters["auth/isLoggedIn"]) {
            store.dispatch("auth/logout");
            next({ name: "admin.login" });
        }
    }
    next();
});

export default router;
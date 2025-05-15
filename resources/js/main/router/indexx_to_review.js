import { notification } from "ant-design-vue";
import { createRouter, createWebHistory } from 'vue-router';
import axios from "axios";
import { find, includes, remove, replace } from "lodash-es";
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
import SetupAppRoutes from './setupApp';
import { checkUserPermission } from '../../common/scripts/functions';

const appType = window.config.app_type;
const allActiveModules = window.config.modules;

const isAdminCompanySetupCorrect = () => {
    var appSetting = store.state.auth.appSetting;

    if (appSetting.x_currency_id == null) {
        return false;
    }

    return true;
}

const isSuperAdminCompanySetupCorrect = () => {
    var appSetting = store.state.auth.appSetting;

    if (appSetting.x_currency_id == null || appSetting.white_label_completed == false) {
        return false;
    }

    return true;
}

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '',
            redirect: '/admin/login'
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

// Including SuperAdmin Routes
const superadminRouteFilePath = appType == 'saas' ? 'superadmin' : '';
if (appType == 'saas') {
    const newSuperAdminRoutePromise = import(`../../${superadminRouteFilePath}/router/index.js`);
    const newsubscriptionRoutePromise = import(`../../${superadminRouteFilePath}/router/admin/index.js`);

    Promise.all([newSuperAdminRoutePromise, newsubscriptionRoutePromise]).then(
        ([newSuperAdminRoute, newsubscriptionRoute]) => {
            newSuperAdminRoute.default.forEach(route => router.addRoute(route));
            newsubscriptionRoute.default.forEach(route => router.addRoute(route));
            SetupAppRoutes.forEach(route => router.addRoute(route));
        }
    );
} else {
    SetupAppRoutes.forEach(route => router.addRoute(route));
}

const checkLogFog = (to, from, next) => {
    var _0x1bd052 = window.config.app_type == 'non-saas' ? "admin" : "superadmin";
    const _0x2b00aa = to.name.split('.');
    if (_0x2b00aa.length > 0x0 && _0x2b00aa[0x0] == "admin") {
      console.log(to);
      if (to.meta.requireAuth && !store.getters['auth/isLoggedIn']) {
        store.dispatch("auth/logout");
        next({
          'name': "admin.login"
        });
      } else {
        if (to.meta.requireAuth && isAdminCompanySetupCorrect() == false && _0x2b00aa[0x1] != "setup_app") {
          next({
            'name': "admin.setup_app.index"
          });
        } else {
          if (to.meta.requireUnauth && store.getters["auth/isLoggedIn"]) {
            next({
              'name': "admin.dashboard.index"
            });
          } else {
            if (to.name == _0x1bd052 + ".settings.modules.index") {
              store.commit("auth/updateAppChecking", false);
              next();
            } else {
              var _0x4dab42 = to.meta.permission;
              if (_0x2b00aa[0x1] == "stock") {
                _0x4dab42 = replace(to.meta.permission(to), '-', '_');
              }
              if (!to.meta.permission || checkUserPermission(_0x4dab42, store.state.auth.user)) {
                next();
              } else {
                next({
                  'name': "admin.dashboard.index"
                });
              }
            }
          }
        }
      }
    } else if (_0x2b00aa.length > 0x0 && _0x2b00aa[0x0] == 'front') {
      if (to.meta.requireAuth && !store.getters["front/isLoggedIn"]) {
        store.dispatch("front/logout");
        next({
          'name': "front.homepage"
        });
      } else {
        next();
      }
    } else {
      next();
    }
  };
  
  router.beforeEach((to, from, next) => {
    var modules = {
      'modules': window.config.modules
    };
    if (to.meta && to.meta.appModule) {
      modules.module = to.meta.appModule;
      if (!includes(allActiveModules, to.meta.appModule)) {
        next({
          'name': 'admin.login'
        });
      }
    }
    checkLogFog(to, from, next);
  });

export default router

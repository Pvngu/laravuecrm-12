import SalesIndex from '../views/sales/index.vue';
import TakeSaleAction from '../views/sales/TakeSaleAction.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/sales',
                component: SalesIndex,
                name: 'admin.sales.index',
                meta: {
                    requireAuth: true,
                    menuParent: "sales",
                    menuKey: 'sales',
                    permission: "sales_view"
                },
                children: [
                    {
                        path: '/admin/sales/:id',
                        component: TakeSaleAction,
                        name: 'admin.sales.details'
                    },
                ]
            },
        ]
    }
]

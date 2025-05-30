<template>
    <div class="setting-sidebar">
        <perfect-scrollbar
            :options="{
                wheelSpeed: 1,
                swipeEasing: true,
                suppressScrollX: true,
            }"
        >
            <a-menu v-model:selectedKeys="selectedKeys">
                <a-menu-item
                    key="company"
                    v-if="
                        permsArray.includes('companies_edit') ||
                        permsArray.includes('admin')
                    "
                    @click="$router.push({ name: 'admin.settings.company.index' })"
                >
                    <template #icon>
                        <LaptopOutlined />
                    </template>
                    {{ $t("menu.company") }}
                </a-menu-item>
                <a-menu-item
                    key="profile"
                    @click="$router.push({ name: 'admin.settings.profile.index' })"
                >
                    <template #icon>
                        <UserOutlined />
                    </template>
                    {{ $t("menu.profile") }}
                </a-menu-item>
                <a-menu-item
                    key="roles"
                    v-if="
                        permsArray.includes('roles_view') || permsArray.includes('admin')
                    "
                    @click="$router.push({ name: 'admin.settings.roles.index' })"
                >
                    <template #icon>
                        <SolutionOutlined />
                    </template>
                    {{ $t("menu.roles") }}
                </a-menu-item>
                <a-menu-item
                    key="currencies"
                    v-if="
                        permsArray.includes('currencies_view') ||
                        permsArray.includes('admin')
                    "
                    @click="$router.push({ name: 'admin.settings.currencies.index' })"
                >
                    <template #icon>
                        <DollarOutlined />
                    </template>
                    {{ $t("menu.currencies") }}
                </a-menu-item>
                <a-menu-item
                    key="email_settings"
                    v-if="
                        (permsArray.includes('email_edit') ||
                            permsArray.includes('admin'))
                    "
                    @click="$router.push({ name: 'admin.settings.email.index' })"
                >
                    <template #icon>
                        <MailOutlined />
                    </template>
                    {{ $t("menu.email_settings") }}
                </a-menu-item>
            </a-menu>
        </perfect-scrollbar>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from "vue";
import {
    LaptopOutlined,
    UserOutlined,
    TranslationOutlined,
    ShopOutlined,
    SolutionOutlined,
    ScheduleOutlined,
    DollarOutlined,
    AccountBookOutlined,
    AppstoreAddOutlined,
    ApartmentOutlined,
    FolderOpenOutlined,
    MailOutlined,
    HistoryOutlined,
    FormOutlined,
    DatabaseOutlined,
} from "@ant-design/icons-vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import common from "../../../common/composable/common";

export default defineComponent({
    components: {
        LaptopOutlined,
        UserOutlined,
        TranslationOutlined,
        ShopOutlined,
        SolutionOutlined,
        ScheduleOutlined,
        DollarOutlined,
        AccountBookOutlined,
        AppstoreAddOutlined,
        ApartmentOutlined,
        FolderOpenOutlined,
        MailOutlined,
        HistoryOutlined,
        FormOutlined,
        DatabaseOutlined,
    },
    setup() {
        const { permsArray } = common();
        const route = useRoute();
        const selectedKeys = ref([]);

        onMounted(() => {
            const menuKey =
                typeof route.meta.menuKey == "function"
                    ? route.meta.menuKey(route)
                    : route.meta.menuKey;

            selectedKeys.value = [menuKey.replace("-", "_")];
        });

        watch(route, (newVal, oldVal) => {
            const menuKey =
                typeof newVal.meta.menuKey == "function"
                    ? newVal.meta.menuKey(newVal)
                    : newVal.meta.menuKey;

            selectedKeys.value = [menuKey.replace("-", "_")];
        });

        return {
            permsArray,

            selectedKeys,
        };
    },
});
</script>

<style lang="less">
.setting-sidebar .ps {
    height: calc(100vh - 145px);
}
</style>

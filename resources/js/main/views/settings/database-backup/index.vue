<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.database_backup`)" class="p-0!" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" class="text-xs">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.settings`) }}
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.database_backup`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <a-row>
        <a-col :xs="24" :sm="24" :md="24" :lg="4" :xl="4" class="bg-setting-sidebar">
            <SettingSidebar />
        </a-col>
        <a-col :xs="24" :sm="24" :md="24" :lg="20" :xl="20">
            <DatabaseSettings ref="settingRef">
                <template #actionButtons>
                    <a-space>
                        <GenerateDbBackup @success="backupCreated" />
                        <BackupCommandSetting />
                    </a-space>
                </template>
            </DatabaseSettings>
        </a-col>
    </a-row>
</template>

<script>
import { ref } from "vue";
import SettingSidebar from "../SettingSidebar.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import DatabaseSettings from "../../common/settings/database-backup/index.vue";
import GenerateDbBackup from "../../common/settings/database-backup/GenerateDbBackup.vue";
import BackupCommandSetting from "../../common/settings/database-backup/BackupCommandSetting.vue";

export default {
    components: {
        SettingSidebar,
        AdminPageHeader,
        DatabaseSettings,
        GenerateDbBackup,
        BackupCommandSetting,
    },
    setup() {
        const settingRef = ref(null);

        const backupCreated = (response) => {
            settingRef.value.backupCreated(response);
        };

        return {
            backupCreated,
            settingRef,
        };
    },
};
</script>

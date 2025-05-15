<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.campaigns`)" class="p-0!" />
        </template>
        <template #actions>
            <a-space>
                <template
                    v-if="
                        permsArray.includes('campaigns_create') ||
                        permsArray.includes('admin')
                    "
                >
                    <a-button type="primary" @click="addItem">
                        <PlusOutlined />
                        {{ $t("campaign.add") }}
                    </a-button>
                </template>
                <a-button
                    v-if="
                        table.selectedRowKeys.length > 0 &&
                        (permsArray.includes('campaigns_delete') ||
                            permsArray.includes('admin'))
                    "
                    type="primary"
                    @click="showSelectedDeleteConfirm"
                    danger
                >
                    <template #icon><DeleteOutlined /></template>
                    {{ $t("common.delete") }}
                </a-button>
            </a-space>
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.campaigns`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-table-content>
        <AddEdit
            :addEditType="addEditType"
            :visible="addEditVisible"
            :url="addEditUrl"
            @addEditSuccess="addEditSuccess"
            @closed="onCloseAddEdit"
            :formData="formData"
            :data="viewData"
            :pageTitle="pageTitle"
            :successMessage="successMessage"
        />

        <a-row
            v-if="
                permsArray.includes('view_completed_campaigns') ||
                permsArray.includes('admin')
            "
        >
            <a-col :span="24">
                <a-tabs
                    v-model:activeKey="extraFilters.campaign_status"
                    @change="setUrlData"
                >
                    <a-tab-pane key="active">
                        <template #tab>
                            <span>
                                <PlayCircleOutlined />
                                {{ $t("campaign.active_campaign") }}
                            </span>
                        </template>
                    </a-tab-pane>

                    <a-tab-pane key="completed">
                        <template #tab>
                            <span>
                                <StopOutlined />
                                {{ $t("campaign.completed_campaign") }}
                            </span>
                        </template>
                    </a-tab-pane>
                </a-tabs>
            </a-col>
        </a-row>

        <a-row>
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                        :row-selection="{
                            selectedRowKeys: table.selectedRowKeys,
                            onChange: onRowSelectChange,
                            getCheckboxProps: (record) => ({
                                disabled: false,
                                name: record.xid,
                            }),
                        }"
                        :columns="columns"
                        :row-key="(record) => record.xid"
                        :data-source="table.data"
                        :pagination="table.pagination"
                        :loading="table.loading"
                        @change="handleTableChange"
                        bordered
                        size="middle"
                    >
                        <template #title>
                            <a-row justify="end" align="center" class="table-header">
                                <a-col 
                                    :xs="24"
                                    :sm="18"
                                    :md="18"
                                    :lg="12"
                                    :xl="8"
                                >
                                    <a-input-search
                                        v-model:value="table.searchString"
                                        show-search
                                        @change="onTableSearch"
                                        @search="onTableSearch"
                                        :loading="table.filterLoading"
                                        :placeholder="
                                            $t('common.select_default_text', [
                                                $t('menu.campaigns')
                                            ])
                                        "
                                    />
                                </a-col>
                            </a-row>
                        </template>
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'progress'">
                                <CampaignProgress :campaign="record" @success="fetch" />
                            </template>
                            <template v-if="column.dataIndex === 'form'">
                                {{
                                    record.x_form_id != "" &&
                                    record.form &&
                                    record.form.xid
                                        ? record.form.name
                                        : "-"
                                }}
                            </template>
                            <template v-if="column.dataIndex === 'members'">
                                <CampaignMembers :members="record.campaign_users" />
                            </template>
                            <template v-if="column.dataIndex === 'started_on'">
                                {{
                                    record.started_on && record.started_on != ""
                                        ? formatDateTime(record.started_on)
                                        : "-"
                                }}
                            </template>
                            <template v-if="column.dataIndex === 'completed_on'">
                                {{
                                    record.completed_on && record.completed_on != ""
                                        ? formatDateTime(record.completed_on)
                                        : "-"
                                }}
                            </template>
                            <template v-if="column.dataIndex === 'completed_by'">
                                {{
                                    record.x_completed_by &&
                                    record.completed_by &&
                                    record.completed_by.name
                                        ? record.completed_by.name
                                        : "-"
                                }}
                            </template>
                            <template v-if="column.dataIndex === 'last_actioner'">
                                {{
                                    record.last_actioner && record.last_actioner.name
                                        ? record.last_actioner.name
                                        : "-"
                                }}
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-space>
                                    <ExportButton
                                        :campaignXId="record.xid"
                                        :leadStatuses="leadStatuses"
                                    />
                                    <AddLead
                                        :campaign="record"
                                        btnType="primary"
                                        @success="setUrlData"
                                    >
                                        <template #icon>
                                            <PlusOutlined />
                                        </template>
                                    </AddLead>
                                    <a-tooltip :title="$t('common.edit')">
                                        <a-button
                                            v-if="
                                                permsArray.includes('campaigns_edit') ||
                                                permsArray.includes('admin')
                                            "
                                            type="primary"
                                            @click="editItem(record)"
                                        >
                                            <template #icon><EditOutlined /></template>
                                        </a-button>
                                    </a-tooltip>
                                    <a-tooltip :title="$t('common.delete')">
                                        <a-button
                                            v-if="
                                                (permsArray.includes(
                                                    'campaigns_delete'
                                                ) ||
                                                    permsArray.includes('admin')) &&
                                                (!record.children ||
                                                    record.children.length == 0)
                                            "
                                            type="primary"
                                            @click="showDeleteConfirm(record.xid)"
                                        >
                                            <template #icon><DeleteOutlined /></template>
                                        </a-button>
                                    </a-tooltip>
                                </a-space>
                            </template>
                        </template>
                    </a-table>
                </div>
            </a-col>
        </a-row>
    </admin-page-table-content>
</template>

<script>
import { onMounted, ref } from "vue";
import {
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
    PlayCircleOutlined,
    StopOutlined,
} from "@ant-design/icons-vue";
import crud from "../../../common/composable/crud";
import common from "../../../common/composable/common";
import fields from "./fields";
import AddEdit from "./AddEdit.vue";
import ExportButton from "./ExportButton.vue";
import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
import AddLead from "./AddLead.vue";
import CampaignMembers from "./CampaignMembers.vue";
import CampaignProgress from "./CampaignProgress.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        PlayCircleOutlined,
        StopOutlined,
        AddEdit,
        AdminPageHeader,
        AddLead,
        CampaignMembers,
        CampaignProgress,
        ExportButton,
    },
    setup() {
        const { permsArray, appSetting, formatDateTime } = common();
        const {
            url,
            addEditUrl,
            initData,
            columns,
            filterableColumns,
            hashableColumns,
            extraFilters,
        } = fields();
        const crudVariables = crud();
        const leadStatuses = ref([]);

        onMounted(() => {
            setUrlData();

            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "campaign";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
            crudVariables.hashableColumns.value = [...hashableColumns];
        });

        const setUrlData = () => {
            axiosAdmin.get('lead-statuses?limit=1000').then((res) => {
                leadStatuses.value = res.data;
            });

            crudVariables.tableUrl.value = {
                url,
                extraFilters,
            };

            crudVariables.table.filterableColumns = filterableColumns;

            crudVariables.fetch({
                page: 1,
            });
        };

        return {
            permsArray,
            formatDateTime,
            appSetting,
            columns,
            ...crudVariables,
            filterableColumns,
            extraFilters,
            setUrlData,
            leadStatuses,
        };
    },
};
</script>

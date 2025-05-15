<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.sales`)" class="p-0!" />
        </template>
        <template #actions>
            <a-space>
                    <ExportButton
                        v-if="
                            !route.params.id &&
                            (permsArray.includes('admin') ||
                            permsArray.includes('reports_view'))
                        " 
                        :filters="filters"
                    />
                    <template
                        v-if="
                            permsArray.includes('sales_create') ||
                            permsArray.includes('admin') &&
                            !route.params.id
                        "
                    >
                        <a-button type="primary" @click="addItem">
                            <PlusOutlined />
                            {{ $t("sales.add") }}
                        </a-button>
                    </template>
                    <!-- <a-button
                        v-if="
                            table.selectedRowKeys.length > 0 &&
                            (permsArray.includes('sales_delete') ||
                                permsArray.includes('admin'))
                        "
                        type="primary"
                        @click="showSelectedDeleteConfirm"
                        danger
                    >
                        <template #icon><DeleteOutlined /></template>
                        {{ $t("common.delete") }}
                    </a-button> -->
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
                    <router-link v-if="route.params.id" :to="{ name: 'admin.sales.index' }" @click.native="setUrlData">
                        {{ $t(`menu.sale_manager`) }}
                    </router-link>
                    <span v-else >
                        {{ $t(`menu.sale_manager`) }}
                    </span>
                </a-breadcrumb-item>
                <a-breadcrumb-item v-if="route.params.id && currentSaleName">
                    <span v-if="route.name === 'admin.sales.details'">
                        {{ currentSaleName ? currentSaleName : $t(`sales.sale_details`) }}
                    </span>
                    <router-link v-else :to="{ name: 'admin.sales.details', params: { id: route.params.id } }">
                        {{ currentSaleName == '' ? currentSaleName : $t(`sales.sale_details`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item v-if="route.params.id && route.name === 'admin.sales.debts'">
                    {{ $t(`debt.client_debts`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-table-content>
        <router-view @updateName="handleUpdateName" />
        <a-row v-if="!route.params.id" class="mt-5" >
            <a-col :span="24">
                <div
                    class="table-responsive"
                    v-if="columns && columns.length > 0"
                >
                    <a-table
                        :columns="columns"
                        :row-selection="permsArray.includes('admin') || permsArray.includes('reports_view') ? {
                            selectedRowKeys: table.selectedRowKeys,
                            onChange: onRowSelectChange,
                            getCheckboxProps: (record) => ({
                                name: record.xid,
                            }),
                        } : null"
                        :row-key="(record) => record.xid"
                        :data-source="table.data"
                        :loading="table.loading || table.filterLoading"
                        @change="handleTableChange"
                        :pagination="table.pagination"
                        bordered
                        size="middle"
                    >
                    <template #title>
                        <a-row justify="end" align="center">
                            <a-col 
                                :xs="21"
                                :sm="16"
                                :md="16"
                                :lg="12"
                                :xl="8"
                            >
                                <a-input-group compact>
                                    <a-select
                                        style="width: 25%"
                                        v-model:value="table.searchColumn"
                                        :placeholder="$t('common.select_default_text', [''])"
                                    >
                                        <a-select-option
                                            v-for="filterableColumn in filterableColumns"
                                            :key="filterableColumn.key"
                                        >
                                            {{ filterableColumn.value }}
                                        </a-select-option>
                                    </a-select>
                                    <a-input-search
                                        style="width: 75%"
                                        v-model:value="table.searchString"
                                        :placeholder="
                                            $t('common.select_default_text', [
                                                $t('lead.reference_number') + ', ' + $t('common.name') + ', ' + $t('common.email') + ', ' + $t('lead.phone_number'),
                                            ])
                                        "
                                        show-search
                                        :onChange="!route.params.id ? onSearch : null"
                                        @search="() => { onSearch(); router.push({ name: 'admin.sales.index' }); }"
                                        :loading="table.filterLoading"
                                    />
                                </a-input-group>
                            </a-col>
                            <a-col class="ml-2">
                                <Filters @onReset="resetFilters">
                                    <a-col
                                            :span="24"
                                        v-if="allCampaigns.length > 1 && !route.params.id"
                                    >
                                        <a-form-item
                                            :label="$t('lead.campaign')"
                                        >
                                            <a-select
                                                v-model:value="filters.campaign_id"
                                                :placeholder="
                                                    $t('common.select_default_text', [
                                                        $t('lead.campaign'),
                                                    ])
                                                "
                                                :allowClear="true"
                                                style="width: 100%"
                                                optionFilterProp="title"
                                                show-search
                                                @change="setUrlData"
                                            >
                                                <a-select-option
                                                    v-for="allCampaign in allCampaigns"
                                                    :key="allCampaign.xid"
                                                    :title="allCampaign.name"
                                                    :value="allCampaign.xid"
                                                    :campaign="allCampaign"
                                                >
                                                    {{ allCampaign.name }}
                                                </a-select-option>
                                            </a-select>
                                        </a-form-item>
                                    </a-col>
                                    <a-col
                                        v-if="
                                            permsArray.includes('sales_view_all') ||
                                            permsArray.includes('admin') &&
                                            !route.params.id
                                        "
                                        :span="24"
                                    >
                                        <a-form-item
                                            :label="$t('sales.assigned_to')"
                                        >
                                            <UserSelect
                                                @onChange="(id) => {
                                                    filters.assigned_to = id;
                                                    setUrlData();
                                                }"
                                                :fetchUserData="false"
                                                :data="allUsers"
                                            />
                                        </a-form-item>
                                    </a-col>
                                    <a-col 
                                        :span="24"
                                        v-if="
                                            !route.params.id
                                        "
                                    >
                                        <a-form-item
                                            :label="$t('sales.sale_status')"
                                        >
                                            <a-select
                                                v-model:value="filters.sale_status_id"
                                                :placeholder="$t('common.select_default_text', [$t('sales.sale_status')])"
                                                :allowClear="true"
                                                style="width: 100%"
                                                optionFilterProp="title"
                                                show-search
                                                @change="setUrlData"
                                            >
                                                <a-select-option
                                                    v-for="status in saleStatuses"
                                                    :key="status.xid"
                                                    :title="status.name"
                                                    :value="status.xid"
                                                >
                                                    {{ status.name }}
                                                </a-select-option>
                                            </a-select>
                                        </a-form-item>
                                    </a-col>
                                </Filters>
                            </a-col>
                        </a-row>
                    </template>
                    <template #bodyCell="{ column,record }">
                            <template v-if="column.dataIndex === 'created_at'">
                                <span>{{ formatDateTime(record.created_at) }}</span>
                            </template>
                            <template v-if="column.dataIndex === 'status'">
                                <span v-if="record.sale_status">{{ record.sale_status.name }}</span>
                            </template>
                            <template v-if="column.dataIndex === 'assigned_to'">
                                <span v-if="record.assigned_user">{{ record.assigned_user.name }}</span>
                            </template>
                            <template v-if="column.dataIndex === 'email'">
                                <SendMail
                                    :email="record.individual.email"
                                    :leadFormData="record.individual"
                                    :allEmailTemplates="allEmailTemplates"
                                    :extraLeadFormData="{
                                        lead_data: [
                                            {
                                                field_name: 'first_name',
                                                field_value: record.individual.first_name,
                                            },
                                            {
                                                field_name: 'last_name',
                                                field_value: record.individual.last_name,
                                            },
                                            {
                                                field_name: 'SSN',
                                                field_value: record.individual.SSN,
                                            },
                                            {
                                                field_name: 'date_of_birth',
                                                field_value: record.individual.date_of_birth,
                                            },
                                            {
                                                field_name: 'phone_number',
                                                field_value: record.individual.phone_number,
                                            },
                                            {
                                                field_name: 'home_phone',
                                                field_value: record.individual.home_phone,
                                            },
                                            {
                                                field_name: 'email',
                                                field_value: record.individual.email,
                                            },
                                            {
                                                field_name: 'language',
                                                field_value: record.individual.language,
                                            },
                                            {
                                                field_name: 'original_profile_id',
                                                field_value: record.individual.original_profile_id,
                                            },
                                            {
                                                field_name: 'co_first_name',
                                                field_value: record.individual.co_applicant ? record.individual.co_applicant.first_name : '',
                                            },
                                            {
                                                field_name: 'co_last_name',
                                                field_value: record.individual.co_applicant ? record.individual.co_applicant.last_name : '',
                                            },
                                            {
                                                field_name: 'co_SSN',
                                                field_value: record.individual.co_applicant ? record.individual.co_applicant.SSN : '',
                                            },
                                            {
                                                field_name: 'co_date_of_birth',
                                                field_value: record.individual.co_applicant ? record.individual.co_applicant.date_of_birth : '',
                                            },
                                            {
                                                field_name: 'co_phone_number',
                                                field_value: record.individual.co_applicant ? record.individual.co_applicant.phone_number : '',
                                            },
                                            {
                                                field_name: 'co_home_phone',
                                                field_value: record.individual.co_applicant ? record.individual.co_applicant.home_phone : '',
                                            },
                                            {
                                                field_name: 'co_email',
                                                field_value: record.individual.co_applicant ? record.individual.co_applicant.email : '',
                                            },
                                            {
                                                field_name: 'co_language',
                                                field_value: record.individual.co_applicant ? record.individual.co_applicant.language : '',
                                            }
                                        ]
                                    }
                                    "
                                    @success="
                                        () => (refreshTimeLine = true)
                                    "
                                />
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        (permsArray.includes('sales_edit') ||
                                            permsArray.includes('admin'))"
                                    type="primary"
                                    @click="() => {
                                        currentSaleName = null;
                                        router.push({ name: 'admin.sales.details', params: { id: record.xid } });
                                    }"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><EditOutlined /></template>
                                </a-button>
                                <a-button
                                    v-if="
                                        (permsArray.includes('sales_delete') ||
                                            permsArray.includes('admin'))
                                    "
                                    type="primary"
                                    @click="showDeleteConfirm(record.xid)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><DeleteOutlined /></template>
                                </a-button>
                            </template>
                    </template>
                    </a-table>
                </div>
            </a-col>
        </a-row>
    </admin-page-table-content>
    <AddSale
        :addEditType="addEditType"
        :visible="addEditVisible"
        @addEditSuccess="addEditSuccess"
        @closed="onCloseAddEdit"
        :pageTitle="pageTitle"
        :successMessage="successMessage"
        :allCampaigns="allCampaigns"
        :allUsers="allUsers"
    />
</template>

<script>
import { onMounted, ref, watch } from "vue";
import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
import fields from "./fields";
import { EditOutlined, DeleteOutlined, PlusOutlined, DownOutlined } from "@ant-design/icons-vue";
import crud from "../../../common/composable/crud";
import common from "../../../common/composable/common";
import SendMail from "../call-manager/SendMail.vue";
import { useRoute, useRouter } from "vue-router";
import AddSale from "./AddSale.vue";
import DateRangePicker from "../../../common/components/common/calendar/DateRangePicker.vue";
import UserSelect from "../../../common/components/common/select/UserSelect.vue";
import Filters from "../../../common/components/common/select/Filters.vue";
import ExportButton from "./ExportButton.vue";

export default {
    components: {
        AdminPageHeader,
        EditOutlined,
        DeleteOutlined,
        PlusOutlined,
        DownOutlined,
        SendMail,
        AddSale,
        DateRangePicker,
        UserSelect,
        ExportButton,
        Filters,
    },
    setup() {
        const router = useRouter();
        const {
            permsArray,
            formatDateTime,
        } = common();
        const currentSaleName = ref("");
        const extraFilters = ref({
            lead_field_name: "",
            lead_field_value: "",
            dates: [],
        });

        const {
            url,
            columns,
            addEditUrl,
            allUsers,
            allEmailTemplates,
            getPrefetchData,
            filterableColumns,
            allCampaigns,
            saleStatuses,
            hashableColumns
        } = fields();
        const filters = ref({
            campaign_id: undefined,
            assigned_to: undefined,
            sale_status_id: undefined,
        });
        const crudVariables = crud();
        const route = useRoute();

        onMounted(() => {
            if(!route.params.id) {
                fetchInitData();
            }
        });

        const fetchInitData = () => {
            getPrefetchData().then(() => {
                filters.value = {
                    ...filters.value,
                };

                crudVariables.crudUrl.value = addEditUrl;
                crudVariables.langKey.value = "sales";
                crudVariables.hashableColumns.value = [...hashableColumns];

                setUrlData();
            });
        };

        const setUrlData = () => {
            crudVariables.tableUrl.value = {
                url,
                filters,
                extraFilters,
            };

            crudVariables.table.filterableColumns = filterableColumns;

            crudVariables.fetch({
                page: 1,
            });
        };

        const handleUpdateName = (name) => {
            if(name) {
                currentSaleName.value = name;
            } else {
                currentSaleName.value = $t(`sales.sale_details`);
            }
        };

        const onSearch = () => {
            if(crudVariables.table.searchColumn == 'full_name') {
                extraFilters.value = {
                    lead_field_name: 'full_name',
                    lead_field_value: crudVariables.table.searchString,
                };
            }

            crudVariables.onTableSearch();
        };

        const resetFilters = () => {
            filters.value = {
                campaign_id: undefined,
                assigned_to: undefined,
                sale_status_id: undefined,
            };
            setUrlData();
        };

        watch(() =>route.params.id, (newId, oldId) => {
            if((newId !== oldId) && crudVariables.table.data.length === 0) {
                fetchInitData();
            }
        });

        return {
            permsArray,
            url,
            columns,
            addEditUrl,
            formatDateTime,
            ...crudVariables,
            allUsers,
            allEmailTemplates,
            filters,
            getPrefetchData,
            filterableColumns,
            setUrlData,
            allCampaigns,
            route,
            router,
            currentSaleName,
            handleUpdateName,
            extraFilters,
            onSearch,
            saleStatuses,
            resetFilters,
        }
    }
};
</script>
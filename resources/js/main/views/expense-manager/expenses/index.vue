<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.expenses`)" class="p-0!" />
        </template>
        <template #actions>
            <a-space>
                <template v-if="permsArray.includes('expenses_create') || permsArray.includes('admin')">
                    <a-button type="primary" @click="addItem">
                        <PlusOutlined />
                        {{ $t("expense.add") }}
                    </a-button>
                </template>
                <a-button
                    v-if="table.selectedRowKeys.length > 0 && (permsArray.includes('expenses_delete') || permsArray.includes('admin'))"
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
                    {{ $t(`menu.expense_manager`) }}
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.expenses`) }}
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
        <a-row class="mt-5">
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
                            <a-row justify="end" align="middle" class="table-header">
                                <a-col :xs="24" :sm="24" :md="8" :lg="8">
                                    <a-input-group compact>
                                        <a-select
                                            style="width: 30%"
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
                                            style="width: 70%"
                                            v-model:value="table.searchString"
                                            show-search
                                            @change="onTableSearch"
                                            @search="onTableSearch"
                                            :loading="table.filterLoading"
                                        />
                                    </a-input-group>
                                </a-col>
                                <a-col class="ml-2">
                                    <Filters 
                                        @onReset="resetFilters"
                                        :filters="filters"
                                    >
                                        <a-col :span="24">
                                            <a-form-item :label="$t('expense.expense_category')">
                                                <a-select
                                                    v-model:value="filters.expense_category_id"
                                                    :placeholder="$t('common.select_default_text', [$t('expense.expense_category')])"
                                                    :allowClear="true"
                                                    style="width: 100%"
                                                    optionFilterProp="label"
                                                    show-search
                                                    @change="reFetchDatatable"
                                                >
                                                    <a-select-option
                                                        v-for="expenseCategory in preFetchData.expenseCategories"
                                                        :key="expenseCategory.xid"
                                                        :value="expenseCategory.xid"
                                                        :label="expenseCategory.name"
                                                    >
                                                        {{ expenseCategory.name }}
                                                    </a-select-option>
                                                </a-select>
                                            </a-form-item>
                                            <a-form-item :label="$t('expense.user')">
                                                <a-select
                                                    v-model:value="filters.user_id"
                                                    :placeholder="$t('common.select_default_text', [$t('expense.user')])"
                                                    :allowClear="true"
                                                    style="width: 100%"
                                                    optionFilterProp="label"
                                                    show-search
                                                    @change="reFetchDatatable"
                                                >
                                                    <a-select-option
                                                        v-for="staffMember in preFetchData.staffMembers"
                                                        :key="staffMember.xid"
                                                        :value="staffMember.xid"
                                                        :label="staffMember.name"
                                                    >
                                                        {{ staffMember.name }}
                                                    </a-select-option>
                                                </a-select>
                                            </a-form-item>
                                        </a-col>
                                    </Filters>
                                </a-col>
                            </a-row>
                        </template>
                        <template #bodyCell="{ column, text, record }">
                            <template v-if="column.dataIndex === 'expense_category_id'">
                                {{ record.expense_category.name }}
                            </template>
                            <template v-if="column.dataIndex === 'user_id'">
                                {{ record.user.name }}
                            </template>
                            <template v-if="column.dataIndex === 'amount'">
                                {{ formatAmountCurrency(text) }}
                            </template>
                            <template v-if="column.dataIndex === 'date'">
                                {{ formatDate(record.date) }}
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="permsArray.includes(`expenses_edit`) || permsArray.includes('admin')"
                                    type="primary"
                                    @click="editItem(record)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><EditOutlined /></template>
                                </a-button>
                                <a-button
                                    v-if="permsArray.includes(`expenses_delete`) || permsArray.includes('admin')"
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
</template>

<script>
import { onMounted } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import AddEdit from "./AddEdit.vue";
import fields from "./fields";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import Filters from "../../../../common/components/common/select/Filters.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
        AdminPageHeader,
        Filters,
    },
    setup() {
        const {
            url,
            addEditUrl,
            initData,
            columns,
            filters,
            preFetchData,
            getPreFetchData,
            hashableColumns,
            filterableColumns,
        } = fields();
        const crudVariables = crud();
        const { appSetting, permsArray, formatDate, formatAmountCurrency } = common();

        onMounted(() => {
            getPreFetchData();

            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "expense";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
            crudVariables.hashableColumns.value = [...hashableColumns];

            reFetchDatatable();
        });

        const reFetchDatatable = () => {
            crudVariables.tableUrl.value = {
                url,
                filters,
            };

            crudVariables.fetch({
                page: 1,
            });
        };

        const resetFilters = () => {
            filters.expense_category_id = undefined;
            filters.user_id = undefined;
            reFetchDatatable();
        };

        return {
            columns,
            appSetting,
            formatDate,
            ...crudVariables,
            filterableColumns,
            filters,
            preFetchData,
            reFetchDatatable,
            permsArray,
            formatAmountCurrency,
            resetFilters,
        };
    },
};
</script>

<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.expense_categories`)" class="p-0!" />
        </template>
        <template #actions>
            <a-space>
                <template
                    v-if="
                        permsArray.includes('expense_categories_create') ||
                        permsArray.includes('admin')
                    "
                >
                    <a-button type="primary" @click="addItem">
                        <PlusOutlined />
                        {{ $t("expense_category.add") }}
                    </a-button>
                </template>
                <a-button
                    v-if="
                        table.selectedRowKeys.length > 0 &&
                        (permsArray.includes('expense_categories_delete') ||
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
                    {{ $t(`menu.expense_manager`) }}
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.expense_categories`) }}
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
                                            :placeholder="$t('common.search')"
                                            show-search
                                            @search="onTableSearch"
                                            @change="onTableSearch"
                                            :loading="table.loading"
                                        />
                                    </a-input-group>
                                </a-col>
                            </a-row>
                        </template>
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        permsArray.includes('expense_categories_edit') ||
                                        permsArray.includes('admin')
                                    "
                                    type="primary"
                                    @click="editItem(record)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><EditOutlined /></template>
                                </a-button>
                                <a-button
                                    v-if="
                                        permsArray.includes(
                                            'expense_categories_delete'
                                        ) || permsArray.includes('admin')
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
</template>
<script>
import { onMounted } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import fields from "./fields";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import AddEdit from "./AddEdit.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
        AdminPageHeader,
    },
    setup() {
        const { addEditUrl, initData, columns, filterableColumns } = fields();
        const crudVariables = crud();
        const { permsArray } = common();

        onMounted(() => {
            crudVariables.tableUrl.value = {
                url: "expense-categories?fields=id,xid,name,description",
            };
            crudVariables.table.filterableColumns = filterableColumns;

            crudVariables.fetch({
                page: 1,
            });

            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "expense_category";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
        });

        return {
            columns,
            ...crudVariables,
            filterableColumns,
            permsArray,
        };
    },
};
</script>

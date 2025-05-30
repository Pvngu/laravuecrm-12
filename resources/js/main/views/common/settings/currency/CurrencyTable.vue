<template>
    <div>
        <AddEdit
            :addEditType="addEditType"
            :visible="addEditVisible"
            :url="addEditUrl"
            @addEditSuccess="addOrEditSuccess"
            @closed="onCloseAddEdit"
            :formData="formData"
            :data="viewData"
            :pageTitle="pageTitle"
            :successMessage="successMessage"
        />

        <a-row v-if="showFilter" :gutter="[15, 15]" class="mb-5">
            <a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
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
                        show-search
                        @change="onTableSearch"
                        @search="onTableSearch"
                        :loading="table.filterLoading"
                    />
                </a-input-group>
            </a-col>
        </a-row>

        <a-row class="mt-1">
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                        :columns="columns"
                        :row-key="(record) => record.xid"
                        :data-source="table.data"
                        :pagination="table.pagination"
                        :loading="table.loading"
                        @change="handleTableChange"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        (panelType == 'admin' &&
                                            (permsArray.includes('currencies_edit') ||
                                                permsArray.includes('admin'))) ||
                                        panelType == 'superadmin'
                                    "
                                    type="primary"
                                    @click="editItem(record)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><EditOutlined /></template>
                                </a-button>
                                <a-button
                                    v-if="
                                        ((panelType == 'admin' &&
                                            (permsArray.includes('currencies_delete') ||
                                                permsArray.includes('admin'))) ||
                                            panelType == 'superadmin') &&
                                        appSetting.x_currency_id != record.xid
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
    </div>
</template>
<script>
import { onMounted } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import crud from "../../../../../common/composable/crud";
import common from "../../../../../common/composable/common";
import fields from "./fields";
import AddEdit from "./AddEdit.vue";

export default {
    props: {
        showFilter: {
            default: true,
        },
        panelType: {
            type: String,
            default: "admin",
        },
    },
    emits: ["addOrEditSuccess"],
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
    },
    setup(props, { emit }) {
        const { permsArray, appSetting } = common();
        const { url, addEditUrl, initData, columns, filterableColumns } = fields(
            props.panelType
        );
        const crudVariables = crud();

        onMounted(() => {
            crudVariables.tableUrl.value = {
                url,
            };
            crudVariables.table.filterableColumns = filterableColumns;

            crudVariables.fetch({
                page: 1,
            });

            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "currency";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
        });

        const addOrEditSuccess = (xid) => {
            crudVariables.addEditSuccess(xid);
            emit("addOrEditSuccess", {
                action_type: crudVariables.addEditType.value,
                xid: xid,
            });
        };

        return {
            appSetting,
            permsArray,
            columns,
            ...crudVariables,
            filterableColumns,
            addOrEditSuccess,
        };
    },
};
</script>

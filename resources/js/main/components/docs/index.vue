<template>
    <div>
        <!-- Add Edit Component -->
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
        

        <!-- Upload Section -->
        <UploadFileBig
            :acceptFormat="'image/*,.pdf'"
            :formData="uploadFormData"
            folder="documents"
            uploadField="file"
            @onFileUploaded="onFileUploaded"
        />

        <!-- Files Table -->
        <div class="mt-5">
            <a-table
                :columns="columns"
                :data-source="table.data"
                :row-key="record => record.id"
                bordered
                size="middle"
            >
                <template #bodyCell="{ column, record }">
                    <template v-if="column.dataIndex === 'name'">
                        <a-button>
                            <template #icon>
                                <component :is="getFileIcon(record.fileType)" style="font-size: 14px;" />
                            </template>
                        </a-button>
                        {{ record.name }}
                    </template>
                    <template v-if="column.dataIndex === 'uploaded_by'">
                        {{ record.uploadedBy }}
                    </template>
                    <template v-if="column.dataIndex === 'size'">
                        {{ record.size }}
                    </template>
                    <template v-if="column.dataIndex === 'created_at'">
                        {{ record.createdAt }}
                    </template>
                    <template v-if="column.dataIndex === 'action'">
                        <a-dropdown>
                            <a-button type="text" shape="circle" icon>
                                <EllipsisOutlined />
                            </a-button>
                            <template #overlay>
                                <a-menu>
                                    <a-menu-item key="view" @click="onView(record)">
                                        <EyeOutlined /> {{ $t("common.view") }}
                                    </a-menu-item>
                                    <a-menu-item key="edit" @click="editItem(record)">
                                        <EditOutlined /> {{ $t("common.edit") }}
                                    </a-menu-item>
                                    <a-menu-item key="delete" @click="showDeleteConfirm(record.xid)">
                                        <DeleteOutlined /> {{ $t("common.delete") }}
                                    </a-menu-item>
                                </a-menu>
                            </template>
                        </a-dropdown>
                    </template>
                </template>
            </a-table>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import UploadFileBig from "../../../common/core/ui/file/UploadFileBig.vue";
import { FileOutlined, PictureOutlined, EyeOutlined, EditOutlined, DeleteOutlined, EllipsisOutlined, FilePdfOutlined, PlusOutlined } from "@ant-design/icons-vue";
import AddEdit from "./AddEdit.vue";
import fields from "./fields";
import crud from "../../../common/composable/crud";
import apiAdmin from "../../../common/composable/apiAdmin";
import { useI18n } from "vue-i18n";

export default {
    props: {
        individualId: {
            type: String,
            default: "",
        },
    },
    components: {
        UploadFileBig,
        FileOutlined,
        PictureOutlined,
        EyeOutlined,
        EditOutlined,
        DeleteOutlined,
        EllipsisOutlined,
        FilePdfOutlined,
        PlusOutlined,
        AddEdit,
    },
    setup(props, { emit }) {
        // Get data from fields and crud
        const { initData, url, addEditUrl, columns, filterableColumns } = fields();
        const { addEditRequestAdmin } = apiAdmin();
        const crudVariables = crud();
        const { t } = useI18n();

        // Define uploadFormData for UploadFileBig component
        const uploadFormData = ref({ file: undefined, file_url: undefined });

        // Map file type to icon
        const getFileIcon = (type) => {
            if (type === "pdf") return "FilePdfOutlined";
            if (type === "image") return "PictureOutlined";
            return "FileOutlined";
        };

        onMounted(() => {
            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "documents";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };

            setUrlData();
        });

        const setUrlData = () => {
            crudVariables.tableUrl.value = {
                url: url,
            };
            crudVariables.table.filterableColumns = filterableColumns;

            crudVariables.fetch({
                page: 1,
            });
        };

        const onFileUploaded = (file) => {
            addEditRequestAdmin({
                url: url,
                data: {
                    file: file.file,
                    name: file.file.replace(/\.[^/.]+$/, ""),
                    individual_id: props.individualId,
                },
                successMessage: t('docs.created'),
                success: (res) => {
                    setUrlData();
                    emit("addEditSuccess", res.xid);
                },
            });
        };

        // Handlers for records
        const onView = (record) => {};

        return {
            columns,
            uploadFormData,
            getFileIcon,
            onView,
            onFileUploaded,
            ...crudVariables,
        };
    },
};
</script>

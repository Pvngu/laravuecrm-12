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
                :data-source="files"
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
                                    <a-menu-item key="edit" @click="onEdit(record)">
                                        <EditOutlined /> {{ $t("common.edit") }}
                                    </a-menu-item>
                                    <a-menu-item key="delete" @click="onDelete(record)">
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
import { ref } from "vue";
import UploadFileBig from "../../../common/core/ui/file/UploadFileBig.vue";
import { FileOutlined, PictureOutlined, EyeOutlined, EditOutlined, DeleteOutlined, EllipsisOutlined, FilePdfOutlined, PlusOutlined } from "@ant-design/icons-vue";
import AddEdit from "./AddEdit.vue";
import fields from "./fields";
import crud from "../../../common/composable/crud";

export default {
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
    setup() {
        // Get data from fields and crud
        const { initData, addEditUrl, columns } = fields();
        const crudVariables = crud();
        
        // Set up crud variables
        crudVariables.crudUrl.value = addEditUrl;
        crudVariables.langKey.value = "docs";
        crudVariables.initData.value = { ...initData };
        crudVariables.formData.value = { ...initData };

        // Define uploadFormData for UploadFileBig component
        const uploadFormData = ref({ file: undefined, file_url: undefined });

        // Dummy data for demonstration
        const files = ref([
            {
                id: 1,
                fileType: "pdf",
                name: "Document1.pdf",
                uploadedBy: "Alice",
                size: "1.2 MB",
                createdAt: "2024-06-01",
            },
            {
                id: 2,
                fileType: "image",
                name: "Photo.png",
                uploadedBy: "Bob",
                size: "500 KB",
                createdAt: "2024-06-02",
            },
        ]);

        // Map file type to icon
        const getFileIcon = (type) => {
            if (type === "pdf") return "FilePdfOutlined";
            if (type === "image") return "PictureOutlined";
            return "FileOutlined";
        };

        // Add item function
        const addItem = () => {
            crudVariables.addEditType.value = "add";
            crudVariables.pageTitle.value = crudVariables.langKey.value + ".add";
            crudVariables.formData.value = { ...crudVariables.initData.value };
            crudVariables.successMessage.value = crudVariables.langKey.value + ".created";
            crudVariables.addEditVisible.value = true;
        };

        // Handle file upload
        const onFileUploaded = (file) => {
            // Open the AddEdit drawer with the file already loaded
            crudVariables.addEditType.value = "add";
            crudVariables.pageTitle.value = crudVariables.langKey.value + ".add";
            crudVariables.formData.value = { 
                ...crudVariables.initData.value,
                file: file.file,
                file_url: file.file_url
            };
            crudVariables.successMessage.value = crudVariables.langKey.value + ".created";
            crudVariables.addEditVisible.value = true;
        };

        // Handlers for records
        const onView = (record) => {};
        
        const onEdit = (record) => {
            crudVariables.addEditType.value = "edit";
            crudVariables.pageTitle.value = crudVariables.langKey.value + ".edit";
            crudVariables.formData.value = { ...record };
            crudVariables.successMessage.value = crudVariables.langKey.value + ".updated";
            crudVariables.addEditVisible.value = true;
        };
        
        const onDelete = (record) => {
            // Implement delete functionality here if needed
        };

        // Handle AddEdit events
        const addEditSuccess = (xid) => {
            // In a real app, you would fetch the updated data
            // For now, we'll just close the drawer
            crudVariables.addEditVisible.value = false;
            
            // Clear form data
            crudVariables.formData.value = { ...crudVariables.initData.value };
        };

        const onCloseAddEdit = () => {
            crudVariables.addEditVisible.value = false;
            crudVariables.formData.value = { ...crudVariables.initData.value };
        };

        return {
            files,
            columns,
            uploadFormData,
            getFileIcon,
            onFileUploaded,
            onView,
            onEdit,
            onDelete,
            addItem,
            addEditSuccess,
            onCloseAddEdit,
            ...crudVariables,
        };
    },
};
</script>

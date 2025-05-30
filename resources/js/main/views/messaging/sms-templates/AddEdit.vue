<template>
    <a-drawer
        :title="pageTitle"
        :width="drawerWidth"
        :open="visible"
        :body-style="{ paddingBottom: '80px' }"
        :footer-style="{ textAlign: 'right' }"
        :maskClosable="false"
        @close="onClose"
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="12" :md="16" :lg="16">
                    <a-form-item
                        :label="$t('sms_template.name')"
                        name="name"
                        :help="rules.name ? rules.name.message : null"
                        :validateStatus="rules.name ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.name"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('sms_template.name'),
                                ])
                            "
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="12" :md="8" :lg="8">
                    <a-form-item
                        name="sharable"
                        :help="rules.sharable ? rules.sharable.message : null"
                        :validateStatus="rules.sharable ? 'error' : null"
                    >
                        <template #label>
                            <a-space>
                                {{ $t("sms_template.sharable") }}
                                <a-tooltip>
                                    <template #title>
                                        {{ $t("sms_template.sharable_message") }}
                                    </template>
                                    <InfoCircleOutlined />
                                </a-tooltip>
                            </a-space>
                        </template>
                        <a-radio-group
                            v-model:value="formData.sharable"
                            button-style="solid"
                            size="small"
                        >
                            <a-radio-button :value="1">
                                {{ $t("common.yes") }}
                            </a-radio-button>
                            <a-radio-button :value="0">
                                {{ $t("common.no") }}
                            </a-radio-button>
                        </a-radio-group>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('sms_template.body')"
                        name="body"
                        :help="rules.body ? rules.body.message : null"
                        :validateStatus="rules.body ? 'error' : null"
                    >
                        <a-textarea 
                            v-model:value="formData.body" 
                            showCount 
                            :maxlength="160" 
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('sms_template.body'),
                                ])
                            "
                            style="height: 100px"
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row
                :gutter="16"
                v-if="
                    selectedForm &&
                    selectedForm.form_fields &&
                    selectedForm.form_fields.length > 0
                "
                class="mb-20"
            >
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                    v-for="selectedFormField in selectedForm.form_fields"
                    :key="selectedFormField.id"
                >
                    <a-button
                        @click="insertFormText(selectedFormField.name)"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ selectedFormField.name }}
                    </a-button>
                </a-col>
            </a-row>

            <a-row :gutter="16" class="mt-2">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('sms_template.status')"
                        name="status"
                        :help="rules.status ? rules.status.message : null"
                        :validateStatus="rules.status ? 'error' : null"
                    >
                        <a-radio-group
                            v-model:value="formData.status"
                            button-style="solid"
                            size="small"
                        >
                            <a-radio-button :value="1">
                                {{ $t("common.active") }}
                            </a-radio-button>
                            <a-radio-button :value="0">
                                {{ $t("common.inactive") }}
                            </a-radio-button>
                        </a-radio-group>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-space>
                <a-button
                    key="submit"
                    type="primary"
                    :loading="loading"
                    @click="onSubmit"
                >
                    <template #icon>
                        <SaveOutlined />
                    </template>
                    {{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
                </a-button>
                <a-button key="back" @click="onClose">
                    {{ $t("common.cancel") }}
                </a-button>
            </a-space>
        </template>
    </a-drawer>
</template>

<script>
import { defineComponent } from "vue";
import {
    PlusOutlined,
    LoadingOutlined,
    SaveOutlined,
    InfoCircleOutlined,
} from "@ant-design/icons-vue";
import { QuillEditor } from "@vueup/vue-quill";
import '@vueup/vue-quill/dist/vue-quill.bubble.css';
import apiAdmin from "../../../../common/composable/apiAdmin";
import FormAddButton from "../../forms/forms/AddButton.vue";

export default defineComponent({
    props: [
        "formData",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        InfoCircleOutlined,
        QuillEditor,
        FormAddButton,
    },
    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();

        const onSubmit = () => {
            addEditRequestAdmin({
                url: props.url,
                data: props.formData,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        };

        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        return {
            loading,
            rules,
            onClose,
            onSubmit,

            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>

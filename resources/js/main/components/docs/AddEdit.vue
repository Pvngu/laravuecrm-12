<template>
    <a-modal
        :open="visible"
        :closable="false"
        :centered="true"
        :title="pageTitle"
        @ok="onSubmit"
    >
    <a-form layout="vertical">
        <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-form-item
                    :label="$t('common.name')"
                    name="name"
                    :help="
                        rules.name
                            ? rules.name.message
                            : null
                    "
                    :validateStatus="
                        rules.name ? 'error' : null
                    "
                    class="required"
                >
                <a-input
                    v-model:value="formData.name"
                    :placeholder="
                        $t(
                            'common.placeholder_default_text',
                            [$t('docs.name')]
                        )
                    "
                />
                </a-form-item>
            </a-col>
        </a-row>
    </a-form>
    <template #footer>
        <a-button key="submit" type="primary" :loading="loading" @click="onSubmit">
            <template #icon>
                <SaveOutlined />
            </template>
            {{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
        </a-button>
        <a-button key="back" @click="onClose">
            {{ $t("common.cancel") }}
        </a-button>
    </template>
    </a-modal>
</template>
<script>
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined, CloudUploadOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../common/composable/apiAdmin";


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
        CloudUploadOutlined
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
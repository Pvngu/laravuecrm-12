<template>
    <a-tooltip :title="$t('lead.send_email')">
        <a-button type="primary" :disabled="!isValidEmail(email)" @click="sendEmail">
            <template #icon>
                <MailOutlined />
            </template>
        </a-button>
    </a-tooltip>

    <a-drawer
        :title="$t('lead.send_email')"
        :width="drawerWidth"
        :open="visible"
        :body-style="{ paddingBottom: '80px' }"
        :footer-style="{ textAlign: 'right' }"
        :maskClosable="false"
        @close="hideModal"
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('campaign.email_template')"
                        name="email_template_id"
                        :help="
                            rules.email_template_id
                                ? rules.email_template_id.message
                                : null
                        "
                        :validateStatus="rules.email_template_id ? 'error' : null"
                        class="required"
                    >
                        <span style="display: flex">
                            <a-select
                                v-model:value="formData.email_template_id"
                                :placeholder="
                                    $t('common.select_default_text', [
                                        $t('campaign.email_template'),
                                    ])
                                "
                                @change="emailTemplateChanged"
                            >
                                <a-select-option
                                    v-for="allEmailTemplate in allEmailTemplates"
                                    :key="allEmailTemplate.xid"
                                    :value="allEmailTemplate.xid"
                                >
                                    {{ allEmailTemplate.name }}
                                </a-select-option>
                            </a-select>
                            <EmailTemplateAddButton @onAddSuccess="emailTemplateAdded" />
                        </span>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('common.email')"
                        name="email"
                        :help="rules.email ? rules.email.message : null"
                        :validateStatus="rules.email ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.email"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('common.email'),
                                ])
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('email_template.subject')"
                        name="subject"
                        :help="rules.subject ? rules.subject.message : null"
                        :validateStatus="rules.subject ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.subject"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('email_template.subject'),
                                ])
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('email_template.message')"
                        name="message"
                        :help="rules.message ? rules.message.message : null"
                        :validateStatus="rules.message ? 'error' : null"
                        class="required"
                    >
                        <QuillEditor
                            ref="textEditor"
                            v-model:content="formData.message"
                            :content="formData.message"
                            contentType="html"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('email_template.message'),
                                ])
                            "
                            style="height: 200px"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-typography-title :level="5">
                {{ $t("lead.applicant") }}
            </a-typography-title>
            <a-row
                :gutter="16"
                v-if="
                    leadFormData
                "
                class="mb-20"
            >
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('first_name')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.first_name') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('last_name')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.last_name') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('SSN')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.SSN') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('date_of_birth')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.date_of_birth') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('phone_number')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.phone_number') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('home_phone')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.home_phone') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('email')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.email') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('language')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.language') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('original_profile_id')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.original_profile_id') }}
                    </a-button>
                </a-col>
            </a-row>
            <a-typography-title :level="5">
                {{ $t("lead.co_applicant") }}
            </a-typography-title>
            <a-row
                :gutter="16"
                v-if="
                    leadFormData
                "
                class="mb-20"
            >
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('co_first_name')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.first_name') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('co_last_name')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.last_name') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('co_SSN')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.SSN') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('co_date_of_birth')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.date_of_birth') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('co_phone_number')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.phone_number') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('co_home_phone')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.home_phone') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('co_email')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.email') }}
                    </a-button>
                </a-col>
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                >
                    <a-button
                        @click="insertFormText('co_language')"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ $t('lead.language') }}
                    </a-button>
                </a-col>
            </a-row>
            <a-typography-title :level="5">
                {{ $t("email_template.additionals") }}
            </a-typography-title>
            <a-row
                :gutter="16"
                v-if="
                    leadFormData &&
                    leadFormData.campaign &&
                    leadFormData.campaign.form &&
                    leadFormData.campaign.form.form_fields &&
                    leadFormData.campaign.form.form_fields.length > 0
                "
                class="mb-20"
            >
                <a-col
                    :xs="8"
                    :sm="8"
                    :md="6"
                    :lg="4"
                    v-for="selectedFormField in leadFormData.campaign.form.form_fields"
                    :key="selectedFormField.id"
                >
                    <a-button
                        @click="insertFormText(selectedFormField.name, true)"
                        type="link"
                        style="padding: 0px"
                    >
                        {{ selectedFormField.name }}
                    </a-button>
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
                        <SendOutlined />
                    </template>
                    {{ $t("common.send") }}
                </a-button>
                <a-button key="back" @click="hideModal">
                    {{ $t("common.cancel") }}
                </a-button>
            </a-space>
        </template>
    </a-drawer>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from "vue";
import { SendOutlined, MailOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { notification } from "ant-design-vue";
import { find, forEach, replace } from "lodash-es";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import apiAdmin from "../../../common/composable/apiAdmin";
import EmailTemplateAddButton from "../messaging/email-templates/AddButton.vue";

export default defineComponent({
    props: {
        leadFormData: {
            default: {},
        },
        extraLeadFormData: {
            default: {},
        },
        email: {
            default: null,
        },
        allEmailTemplates: {
            default: [],
        },
    },
    emits: ["success"],
    components: {
        SendOutlined,
        MailOutlined,

        EmailTemplateAddButton,
        QuillEditor,
    },
    methods: {
        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
    },
    setup(props, { emit }) {
        const { t } = useI18n();
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const visible = ref(false);
        const formData = ref({
            lead_id: props.leadFormData.xid,
            email_template_id: undefined,
            email: props.email,
            subject: "",
            message: "",
        });
        const emailTemplateUrl = "email-templates/all";
        const allEmailTemplates = ref([]);
        const textEditor = ref(null);

        onMounted(() => {
            if(props.allEmailTemplates.length > 0) {
                allEmailTemplates.value = props.allEmailTemplates;
            } else {
                const emailTemplatesPromise = axiosAdmin.get(emailTemplateUrl);

                Promise.all([emailTemplatesPromise]).then(([emailTemplatesResponse]) => {
                    allEmailTemplates.value = emailTemplatesResponse.data.email_templates;

                    resetToCampaignEmailTemplate();
                    emailTemplateChanged();
                });
            }
        });

        const editor = computed(() => {
            return textEditor.value.getQuill();
        });

        const insertFormText = (mergeFieldText, extraForm) => {
            
            const selectedPointArray = editor.value.getSelection(true);

            if(extraForm) {
                var leadDataInleadDataField = find(props.leadFormData, [
                "field_name",
                mergeFieldText,
            ]);
            } else {
                var leadDataInleadDataField = find(props.extraLeadFormData, [
                "field_name",
                mergeFieldText,
            ]);
            }

            if (
                leadDataInleadDataField != undefined &&
                leadDataInleadDataField.field_value != undefined &&
                leadDataInleadDataField.field_value != ""
            ) {
                var newFieldTextValue =
                    selectedPointArray.length > 0
                        ? `${leadDataInleadDataField.field_value}`
                        : `${leadDataInleadDataField.field_value}`;
            } else {
                var newFieldTextValue =
                    selectedPointArray.length > 0
                        ? `##${insertedTextField}##`
                        : `##${insertedTextField}##`;
            }

            editor.value.deleteText(selectedPointArray.index, selectedPointArray.length);
            editor.value.insertText(selectedPointArray.index, newFieldTextValue);
        };

        const resetToCampaignEmailTemplate = () => {
            if (
                props.leadFormData &&
                props.leadFormData.campaign &&
                props.leadFormData.campaign.email_template_xid
            ) {
                formData.value = {
                    ...formData.value,
                    email_template_id: props.leadFormData.campaign.email_template_xid,
                };
            }
        };

        const emailTemplateChanged = () => {
            const selectedEmailTemplate = find(allEmailTemplates.value, [
                "xid",
                formData.value.email_template_id,
            ]);

            if (selectedEmailTemplate && selectedEmailTemplate.body) {
                var bodyMessage = selectedEmailTemplate.body;

                forEach(props.leadFormData, (leadDataValue, leadDataKey) => {
                    if (
                        leadDataValue.field_value != undefined &&
                        leadDataValue.field_value != ""
                    ) {
                        bodyMessage = replace(
                            bodyMessage,
                            `##${leadDataValue.field_name}##`,
                            leadDataValue.field_value
                        );
                    }
                });

                forEach(props.extraLeadFormData, (leadDataValue, leadDataKey) => {
                    if (
                        leadDataValue.field_value != undefined &&
                        leadDataValue.field_value != ""
                    ) {
                        bodyMessage = replace(
                            bodyMessage,
                            `##${leadDataValue.field_name}##`,
                            leadDataValue.field_value
                        );
                    }
                });

                formData.value = {
                    ...formData.value,
                    message: bodyMessage,
                    subject: selectedEmailTemplate.subject,
                };

                // Not execute at onMounted
                if (textEditor.value != undefined) {
                    textEditor.value.setHTML(bodyMessage);
                }
            }
        };

        const emailTemplateAdded = () => {
            axiosAdmin.get(emailTemplateUrl).then((response) => {
                allEmailTemplates.value = response.data.email_templates;
            });
        };

        const sendEmail = () => {
            formData.value = {
                ...formData.value,
                email: props.email,
            };

            emailTemplateChanged();

            visible.value = true;
        };

        const onSubmit = () => {
            addEditRequestAdmin({
                url: "leads/send-email",
                data: formData.value,
                success: (res) => {
                    if (res && res.success) {
                        visible.value = false;
                        notification.success({
                            message: t("common.success"),
                            description: t("email_template.mail_send_successfully"),
                            placement: "bottomRight",
                        });
                        emit("success");
                    } else {
                        notification.error({
                            message: t("common.error"),
                            description: t("email_template.mail_send_error_message"),
                            placement: "bottomRight",
                        });
                    }
                },
            });
        };

        const hideModal = () => {
            visible.value = false;
            loading.value = false;
            rules.value = {};

            resetToCampaignEmailTemplate();
        };

        return {
            loading,
            visible,
            rules,
            formData,

            sendEmail,
            onSubmit,
            hideModal,

            textEditor,
            allEmailTemplates,
            emailTemplateAdded,
            emailTemplateChanged,
            insertFormText,

            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>

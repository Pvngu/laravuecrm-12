<template>
    <a-row>
        <a-col
            class="settings-sidebar-content-container mt-1 mb-5"
            :xs="24"
            :sm="24"
            :md="24"
            :lg="24"
            :xl="24"
        >
            <div v-if="formData.mail_driver == 'smtp'">
                <a-alert
                    v-if="verified"
                    :description="verifiedMessage"
                    type="success"
                    show-icon
                />
                <a-alert v-else :description="verifiedMessage" type="error" show-icon />
            </div>
            <a-card class="settings-sidebar-content-container">
                <a-form layout="vertical">
                    <a-row :gutter="16">
                        <a-col :span="24">
                            <a-form-item
                                :label="$t('mail_settings.mail_driver')"
                                name="mail_driver"
                                :help="
                                    rules.mail_driver ? rules.mail_driver.message : null
                                "
                                :validateStatus="rules.mail_driver ? 'error' : null"
                            >
                                <a-select
                                    v-model:value="formData.mail_driver"
                                    :placeholder="
                                        $t('common.select_default_text', [
                                            $t('mail_settings.mail_driver'),
                                        ])
                                    "
                                >
                                    <a-select-option key="none" value="none">
                                        {{ $t(`mail_settings.none`) }}
                                    </a-select-option>

                                    <a-select-option key="smtp" value="smtp">
                                        {{ $t(`mail_settings.smtp`) }}
                                    </a-select-option>
                                </a-select>
                            </a-form-item>
                        </a-col>
                    </a-row>

                    <div
                        v-if="
                            formData.mail_driver == 'mail' ||
                            formData.mail_driver == 'smtp'
                        "
                    >
                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-form-item
                                    :label="$t('mail_settings.from_name')"
                                    name="from_name"
                                    :help="
                                        rules.from_name ? rules.from_name.message : null
                                    "
                                    :validateStatus="rules.from_name ? 'error' : null"
                                    class="required"
                                >
                                    <a-input
                                        v-model:value="formData.from_name"
                                        :placeholder="
                                            $t('common.placeholder_default_text', [
                                                $t('mail_settings.from_name'),
                                            ])
                                        "
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-form-item
                                    :label="$t('mail_settings.from_email')"
                                    name="from_email"
                                    :help="
                                        rules.from_email ? rules.from_email.message : null
                                    "
                                    :validateStatus="rules.from_email ? 'error' : null"
                                    class="required"
                                >
                                    <a-input
                                        v-model:value="formData.from_email"
                                        :placeholder="
                                            $t('common.placeholder_default_text', [
                                                $t('mail_settings.from_email'),
                                            ])
                                        "
                                    />
                                </a-form-item>
                            </a-col>
                        </a-row>
                    </div>

                    <div v-if="formData.mail_driver == 'smtp'">
                        <a-row :gutter="16">
                            <a-col
                                :xs="24"
                                :sm="24"
                                :md="24"
                                :lg="formData.mail_driver == 'smtp' ? 12 : 24"
                                v-if="formData.mail_driver == 'smtp'"
                            >
                                <a-form-item
                                    :label="$t('mail_settings.enable_mail_queue')"
                                    name="enable_mail_queue"
                                    :help="
                                        rules.enable_mail_queue
                                            ? rules.enable_mail_queue.message
                                            : null
                                    "
                                    :validateStatus="
                                        rules.enable_mail_queue ? 'error' : null
                                    "
                                    class="required"
                                >
                                    <a-select
                                        v-model:value="formData.enable_mail_queue"
                                        :placeholder="
                                            $t('common.select_default_text', [
                                                $t('mail_settings.enable_mail_queue'),
                                            ])
                                        "
                                        :allowClear="true"
                                    >
                                        <a-select-option key="yes" value="yes">
                                            {{ $t("common.yes") }}
                                        </a-select-option>
                                        <a-select-option key="no" value="no">
                                            {{ $t("common.no") }}
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                            <a-col
                                :xs="24"
                                :sm="24"
                                :md="24"
                                :lg="formData.mail_driver == 'smtp' ? 12 : 24"
                                v-if="formData.mail_driver == 'smtp'"
                            >
                                <a-form-item
                                    :label="$t('mail_settings.host')"
                                    name="host"
                                    :help="rules.host ? rules.host.message : null"
                                    :validateStatus="rules.host ? 'error' : null"
                                    class="required"
                                >
                                    <a-input
                                        v-model:value="formData.host"
                                        :placeholder="
                                            $t('common.placeholder_default_text', [
                                                $t('mail_settings.host'),
                                            ])
                                        "
                                    />
                                </a-form-item>
                            </a-col>
                        </a-row>

                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-form-item
                                    :label="$t('mail_settings.port')"
                                    name="port"
                                    :help="rules.port ? rules.port.message : null"
                                    :validateStatus="rules.port ? 'error' : null"
                                    class="required"
                                >
                                    <a-input
                                        v-model:value="formData.port"
                                        :placeholder="
                                            $t('common.placeholder_default_text', [
                                                $t('mail_settings.port'),
                                            ])
                                        "
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-form-item
                                    :label="$t('mail_settings.encryption')"
                                    name="encryption"
                                    :help="
                                        rules.encryption ? rules.encryption.message : null
                                    "
                                    :validateStatus="rules.encryption ? 'error' : null"
                                >
                                    <a-select
                                        v-model:value="formData.encryption"
                                        :placeholder="
                                            $t('common.select_default_text', [
                                                $t('mail_settings.encryption'),
                                            ])
                                        "
                                        :allowClear="true"
                                    >
                                        <a-select-option key="tls" value="tls">
                                            tls
                                        </a-select-option>
                                        <a-select-option key="ssl" value="ssl">
                                            ssl
                                        </a-select-option>
                                    </a-select>
                                </a-form-item>
                            </a-col>
                        </a-row>

                        <a-row :gutter="16">
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-form-item
                                    :label="$t('mail_settings.username')"
                                    name="username"
                                    :help="rules.username ? rules.username.message : null"
                                    :validateStatus="rules.username ? 'error' : null"
                                    class="required"
                                >
                                    <a-input
                                        v-model:value="formData.username"
                                        :placeholder="
                                            $t('common.placeholder_default_text', [
                                                $t('mail_settings.username'),
                                            ])
                                        "
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                                <a-form-item
                                    :label="$t('mail_settings.password')"
                                    name="password"
                                    :help="rules.password ? rules.password.message : null"
                                    :validateStatus="rules.password ? 'error' : null"
                                    class="required"
                                >
                                    <a-input-password
                                        v-model:value="formData.password"
                                        :placeholder="
                                            $t('common.placeholder_default_text', [
                                                $t('mail_settings.password'),
                                            ])
                                        "
                                    />
                                </a-form-item>
                            </a-col>
                        </a-row>
                    </div>

                    <a-row :gutter="16">
                        <a-col :xs="24" :sm="24" :md="24" :lg="24">
                            <a-form-item>
                                <a-space>
                                    <a-button
                                        type="primary"
                                        @click="onSubmit"
                                        :loading="loading"
                                    >
                                        <template #icon>
                                            <SaveOutlined />
                                        </template>
                                        {{ $t("common.update") }}
                                    </a-button>
                                    <TestMail :formData="formData" :verified="verified" />
                                </a-space>
                            </a-form-item>
                        </a-col>
                    </a-row>
                </a-form>
            </a-card>
        </a-col>
    </a-row>
</template>
<script>
import { onMounted, ref } from "vue";
import { SaveOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import apiAdmin from "../../../../../common/composable/apiAdmin";
import TestMail from "./TestMail.vue";
import SendMailSetting from "./SendMailSetting.vue";
import { getUrlByAppType } from "../../../../../common/scripts/functions";

export default {
    components: {
        SaveOutlined,
        TestMail,
        SendMailSetting,
    },
    setup() {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const newAdminRequest = apiAdmin();
        const { t } = useI18n();
        const formData = ref({});
        const verified = ref(false);
        const verifiedMessage = ref("");
        const sendMailSettings = ref([]);

        onMounted(() => {
            axiosAdmin.get(getUrlByAppType("settings/email")).then((response) => {
                formData.value = response.data.settings;
                sendMailSettings.value = response.data.sendMailSettings;

                verified.value = response.data.verified;
                verifiedMessage.value = response.data.verified
                    ? t("messages.stmp_success_message")
                    : t("messages.stmp_error_message");
            });
        });

        const sendMailForClicked = (checkedValue) => {
            newAdminRequest.addEditRequestAdmin({
                url: getUrlByAppType(`settings/email/send-mail-settings`),
                data: { name_key: "company", send_mail_settings: checkedValue },
                success: (res) => {},
            });
        };

        const onSubmit = () => {
            addEditRequestAdmin({
                url: getUrlByAppType(`settings/email/update`),
                data: formData.value,
                successMessage: t("mail_settings.updated"),
                success: (res) => {
                    verified.value = res.verified;
                    verifiedMessage.value = res.verified
                        ? t("messages.stmp_success_message")
                        : res.message;
                },
            });
        };

        return {
            formData,
            rules,
            loading,

            onSubmit,
            verified,
            verifiedMessage,
            sendMailSettings,
        };
    },
};
</script>

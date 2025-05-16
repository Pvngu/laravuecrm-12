<template>
    <div v-if="permsArray.includes('leads_create') || permsArray.includes('admin')">
        <a-tooltip :title="$t('lead.add')">
            <a-button :type="btnType" :class="btnClass" @click="showAdd">
                <template #icon>
                    <slot name="icon"></slot>
                </template>
                <slot></slot>
            </a-button>
        </a-tooltip>

        <a-drawer
            :title="$t('lead.add')"
            :width="drawerWidth"
            :open="visible"
            :body-style="{ paddingBottom: '80px' }"
            :footer-style="{ textAlign: 'right' }"
            :maskClosable="false"
            @close="onClose"
        >
            <a-form layout="vertical">
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item
                            :label="$t('lead.first_name')"
                            name="first_name"
                        >
                            <a-input v-model:value="leadDetailsFormData.first_name" :placeholder="$t('common.placeholder_default_text', [$t('lead.first_name')])"></a-input>
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item
                            :label="$t('lead.last_name')"
                            name="last_name"
                        >
                            <a-input v-model:value="leadDetailsFormData.last_name" :placeholder="$t('common.placeholder_default_text', [$t('lead.last_name')])"></a-input>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item :label="$t('lead.SSN')" name="SSN">
                            <a-input v-model:value="leadDetailsFormData.SSN" :placeholder="$t('common.placeholder_default_text', [$t('lead.SSN')])"></a-input>
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item :label="$t('lead.date_of_birth')" name="date_of_birth">
                            <a-date-picker v-model:value="leadDetailsFormData.date_of_birth" :placeholder="$t('common.placeholder_default_text', [$t('lead.date_of_birth')])" style="width: 100%"></a-date-picker>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item :label="$t('lead.phone_number')" name="phone_number">
                            <a-input v-model:value="leadDetailsFormData.phone_number" :placeholder="$t('common.placeholder_default_text', [$t('lead.phone_number')])"></a-input>
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item :label="$t('lead.home_phone')" name="home_phone">
                            <a-input v-model:value="leadDetailsFormData.home_phone" :placeholder="$t('common.placeholder_default_text', [$t('lead.home_phone')])"></a-input>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item :label="$t('lead.email')" name="email">
                            <a-input v-model:value="leadDetailsFormData.email" :placeholder="$t('common.placeholder_default_text', [$t('lead.email')])"></a-input>
                        </a-form-item>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item :label="$t('lead.language')" name="language">
                            <a-select
                                v-model:value="leadDetailsFormData.language"
                                show-search
                                :placeholder="
                                    $t(
                                        'common.placeholder_default_text',
                                        [$t('lead.language')]
                                    )"
                            >
                                <a-select-option
                                    v-for="option in optionLanguages"
                                    :key="option.id"
                                    :value="language.key"
                                >
                                    {{ $t(option.key) }}
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                </a-row>
                <a-row :gutter="16">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item :label="$t('lead.original_profile_id')" name="original_profile_id">
                            <a-input v-model:value="leadDetailsFormData.original_profile_id" :placeholder="$t('common.placeholder_default_text', [$t('lead.original_profile_id')])"></a-input>
                        </a-form-item>
                    </a-col>
                </a-row>

                <a-row :gutter="16">
                    <a-col
                        v-for="leadData in formData.lead_data"
                        :key="leadData.id"
                        :xs="24"
                        :sm="24"
                        :md="12"
                        :lg="12"
                    >
                        <a-form-item
                            :label="leadData.field_name"
                            :name="leadData.field_name"
                            :help="rules.name ? rules.name.message : null"
                            :validateStatus="rules.name ? 'error' : null"
                        >
                            <a-input
                                v-model:value="leadData.field_value"
                                :placeholder="
                                    $t('common.placeholder_default_text', [
                                        leadData.field_name,
                                    ])
                                "
                            />
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
                        {{ $t("common.create") }}
                    </a-button>
                    <a-button key="back" @click="onClose">
                        {{ $t("common.cancel") }}
                    </a-button>
                </a-space>
            </template>
        </a-drawer>
    </div>
</template>
<script>
import { defineComponent, ref, onMounted } from "vue";
import { SaveOutlined } from "@ant-design/icons-vue";
import { forEach } from "lodash-es";
import { useI18n } from "vue-i18n";
import common from "../../../common/composable/common";
import apiAdmin from "../../../common/composable/apiAdmin";

export default defineComponent({
    props: {
        btnType: {
            default: "default",
        },
        btnClass: {
            default: "",
        },
        tooltip: {
            default: true,
        },
        campaign: {
            default: {},
        },
    },
    emits: ["success"],
    components: {
        SaveOutlined,
    },
    setup(props, { emit }) {
        const { permsArray } = common();
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const visible = ref(false);
        const formData = ref({});
        const { t } = useI18n();
        const referenceNumber = ref("");
        const leadDetailsFormData = ref({
            first_name: "",
            last_name: "",
        });
        const optionLanguages = ref([]);

        onMounted(() => {
            axiosAdmin.get('select-options/language').then((res) => {
                optionLanguages.value = res.data;
            })

            resetFormData();
        });

        const resetFormData = () => {
            var newLeadDataArray = [];

            if (
                props.campaign &&
                props.campaign.form &&
                props.campaign.form.form_fields
            ) {
                forEach(props.campaign.form.form_fields, (fieldValue) => {
                    newLeadDataArray.push({
                        id: Math.random().toString(36).slice(2),
                        field_name: fieldValue.name,
                        field_value: "",
                    });
                });
            }

            formData.value = {
                campaign_id: props.campaign.xid,
                lead_data: newLeadDataArray,
            };
        };

        const showAdd = () => {
            visible.value = true;
        };

        const onSubmit = () => {
            addEditRequestAdmin({
                url: "leads/create-lead",
                data: {
                    ...formData.value,
                    first_name: leadDetailsFormData.value.first_name,
                    last_name: leadDetailsFormData.value.last_name,
                    SSN: leadDetailsFormData.value.SSN,
                    date_of_birth: leadDetailsFormData.value.date_of_birth,
                    phone_number: leadDetailsFormData.value.phone_number,
                    home_phone: leadDetailsFormData.value.home_phone,
                    email: leadDetailsFormData.value.email,
                    language: leadDetailsFormData.value.language,
                    original_profile_id: leadDetailsFormData.value.original_profile_id,
                },
                successMessage: t("lead.created"),
                success: (res) => {
                    emit("success");
                    onClose();
                },
            });
        };

        const onClose = () => {
            resetFormData();
            visible.value = false;
        };

        return {
            permsArray,
            visible,
            formData,
            loading,
            rules,

            onSubmit,
            onClose,
            showAdd,

            referenceNumber,
            leadDetailsFormData,
            optionLanguages,

            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>

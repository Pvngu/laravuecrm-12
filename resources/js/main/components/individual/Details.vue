<template>
    <perfect-scrollbar
        :options="{
            wheelSpeed: 1,
            swipeEasing: true,
            suppressScrollX: true,
        }"
        :class="{ 'callmanager-details' : !isSale }"
    >
        <a-form layout="vertical" class="mt-20">
            <a-row :gutter="16" justify="end" v-if="isSale">
                <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-form-item
                        :label="$t('sales.assigned_to')"
                        name="assigned_to"
                        :help="rules.assigned_to ? rules.assigned_to.message : null"
                        :validateStatus="rules.assigned_to ? 'error' : null"
                    >
                        <UserSelect
                            :value="formData.assigned_user_xid"
                            @onChange="(id) => {
                                formData.assigned_user_xid = id;
                            }"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.reference_number')"
                        name="reference_number"
                        :help="rules.reference_number ? rules.reference_number.message : null"
                        :validateStatus="rules.reference_number ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.reference_number"
                            :placeholder="$t('common.placeholder_default_text', [$t('lead.reference_number')])"
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('sales.sale_status')"
                        name="sale_status"
                        :help="rules.sale_status ? rules.sale_status.message : null"
                        :validateStatus="rules.sale_status ? 'error' : null"
                        v-if="isSale"
                    >
                        <a-select
                            v-model:value="formData.sale_status_id"
                            :placeholder="$t('common.select_default_text', [$t('sales.sale_status')])"
                        >
                            <a-select-option
                                v-for="status in statuses"
                                :key="status.id"
                                :value="status.id"
                                :title="status.name"
                            >
                                {{ status.name }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                    <a-form-item
                        :label="$t('lead.lead_status')"
                        name="lead_status"
                        :help="
                            rules.lead_status
                                ? rules.lead_status.message
                                : null
                        "
                        :validateStatus="
                            rules.lead_status ? 'error' : null
                        "
                        v-else
                    >
                        <a-select
                            v-model:value="formData.lead_status"
                            :placeholder="
                                $t('common.select_default_text', [
                                    $t('lead.lead_status'),
                                ])
                            "
                        >
                            <a-select-option
                                v-for="status in statuses"
                                :key="status.id"
                                :value="status.id"
                                :title="status.name"
                            >
                                {{ status.name }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12" align="center">
                    <a-typography-text strong>
                        {{ $t("lead.applicant") }}
                    </a-typography-text>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" align="center">
                    <a-typography-text strong>
                        {{ $t("lead.co_applicant") }}
                    </a-typography-text>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.first_name')"
                        name="first_name"
                        :help="rules.first_name ? rules.first_name.message : null"
                        :validateStatus="rules.first_name ? 'error' : null"
                    >
                        <a-input v-model:value="formData.first_name" :placeholder="$t('common.placeholder_default_text', [$t('lead.first_name')])"></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.first_name')"
                        name="co_first_name"
                        :help="rules.co_first_name ? rules.co_first_name.message : null"
                        :validateStatus="rules.co_first_name ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-input v-model:value="formData.co_first_name" :placeholder="$t('common.placeholder_default_text', [$t('lead.first_name')])"></a-input>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.last_name')"
                        name="last_name"
                        :help="rules.last_name ? rules.last_name.message : null"
                        :validateStatus="rules.last_name ? 'error' : null"
                    >
                        <a-input v-model:value="formData.last_name" :placeholder="$t('common.placeholder_default_text', [$t('lead.last_name')])"></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.last_name')"
                        name="co_last_name"
                        :help="rules.co_last_name ? rules.co_last_name.message : null"
                        :validateStatus="rules.co_last_name ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-input v-model:value="formData.co_last_name" :placeholder="$t('common.placeholder_default_text', [$t('lead.last_name')])"></a-input>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.SSN')"
                        name="SSN"
                        :help="rules.SSN ? rules.SSN.message : null"
                        :validateStatus="rules.SSN ? 'error' : null"
                    >
                        <a-input v-model:value="formData.SSN" :placeholder="$t('common.placeholder_default_text', [$t('lead.SSN')])"></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.SSN')"
                        name="co_SSN"
                        :help="rules.co_SSN ? rules.co_SSN.message : null"
                        :validateStatus="rules.co_SSN ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-input v-model:value="formData.co_SSN" :placeholder="$t('common.placeholder_default_text', [$t('lead.SSN')])"></a-input>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.date_of_birth')"
                        name="date_of_birth"
                        :help="rules.date_of_birth ? rules.date_of_birth.message : null"
                        :validateStatus="rules.date_of_birth ? 'error' : null"
                    >
                        <DateTimePicker
                            :dateTime="formData.date_of_birth"
                            :isFutureDateDisabled="false"
                            :showTime="false"
                            :onlyDate="true"
                            @dateTimeChanged="(changedDateTime) => (formData.date_of_birth = changedDateTime)"
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.date_of_birth')"
                        name="co_date_of_birth"
                        :help="rules.co_date_of_birth ? rules.co_date_of_birth.message : null"
                        :validateStatus="rules.co_date_of_birth ? 'error' : null"
                        class="hidden-label"
                    >
                        <DateTimePicker
                            :dateTime="formData.co_date_of_birth"
                            :isFutureDateDisabled="false"
                            :showTime="false"
                            :onlyDate="true"
                            @dateTimeChanged="(changedDateTime) => (formData.co_date_of_birth = changedDateTime)"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.home_phone')"
                        name="home_phone"
                        :help="rules.home_phone ? rules.home_phone.message : null"
                        :validateStatus="rules.home_phone ? 'error' : null"
                    >
                        <a-input v-model:value="formData.home_phone" :placeholder="$t('common.placeholder_default_text', [$t('lead.home_phone')])"></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.home_phone')"
                        name="co_home_phone"
                        :help="rules.co_home_phone ? rules.co_home_phone.message : null"
                        :validateStatus="rules.co_home_phone ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-input v-model:value="formData.co_home_phone" :placeholder="$t('common.placeholder_default_text', [$t('lead.home_phone')])"></a-input>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.phone_number')"
                        name="phone_number"
                        :help="rules.phone_number ? rules.phone_number.message : null"
                        :validateStatus="rules.phone_number ? 'error' : null"
                    >
                        <a-input v-model:value="formData.phone_number" :placeholder="$t('common.placeholder_default_text', [$t('lead.phone_number')])"></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.phone_number')"
                        name="co_phone_number"
                        :help="rules.co_phone_number ? rules.co_phone_number.message : null"
                        :validateStatus="rules.co_phone_number ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-input v-model:value="formData.co_phone_number" :placeholder="$t('common.placeholder_default_text', [$t('lead.phone_number')])"></a-input>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.email')"
                        name="email"
                        :help="rules.email ? rules.email.message : null"
                        :validateStatus="rules.email ? 'error' : null"
                    >
                    <a-input-group compact>
                        <a-input 
                            v-model:value="formData.email" 
                            :placeholder="$t('common.placeholder_default_text', [$t('lead.email')])"
                            style="width: calc(100% - 32px);"
                        >
                        </a-input>
                        <SendMail
                            :email="formData.email"
                            :leadFormData="formData"
                            :extraLeadFormData="formData.template_form"
                        />
                    </a-input-group>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.email')"
                        name="co_email"
                        :help="rules.co_email ? rules.co_email.message : null"
                        :validateStatus="rules.co_email ? 'error' : null"
                        class="hidden-label"
                    >
                    <a-input-group compact>
                        <a-input 
                            v-model:value="formData.co_email" 
                            :placeholder="$t('common.placeholder_default_text', [$t('lead.email')])"
                            style="width: calc(100% - 32px);"
                        >
                        </a-input>
                        <SendMail
                            :email="formData.co_email"
                            :leadFormData="formData"
                            :extraLeadFormData="formData.template_form"
                        />
                    </a-input-group>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.language')"
                        name="language"
                        :help="rules.language ? rules.language.message : null"
                        :validateStatus="rules.language ? 'error' : null"
                    >
                        <a-select
                            v-model:value="formData.language"
                            show-search
                            :placeholder="$t('common.placeholder_default_text', [$t('lead.language')])"
                        >
                            <a-select-option
                                v-for="language in optionLanguages"
                                :key="language.id"
                                :value="language.key"
                            >
                                {{ $t(language.key) }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.language')"
                        name="co_language"
                        :help="rules.co_language ? rules.co_language.message : null"
                        :validateStatus="rules.co_language ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-select
                            v-model:value="formData.co_language"
                            show-search
                            :placeholder="$t('common.placeholder_default_text', [$t('lead.language')])"
                        >
                            <a-select-option
                                v-for="language in optionLanguages"
                                :key="language.id"
                                :value="language.key"
                            >
                                {{ $t(language.value) }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.original_profile_id')"
                        name="original_profile_id"
                        :help="rules.original_profile_id ? rules.original_profile_id.message : null"
                        :validateStatus="rules.original_profile_id ? 'error' : null"
                    >
                        <a-input v-model:value="formData.original_profile_id" :placeholder="$t('common.placeholder_default_text', [$t('lead.original_profile_id')])"></a-input>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16" style="padding-bottom: 53px;">
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
                        <a-input-group compact>
                            <a-input
                                v-model:value="leadData.field_value"
                                :placeholder="$t('common.placeholder_default_text', [leadData.field_name])"
                                :style="{
                                    width:
                                        getLeadDataFieldType(leadData.field_name) == 'email' ||
                                        getLeadDataFieldType(leadData.field_name) == 'phone'
                                            ? 'calc(100% - 32px)'
                                            : '100%',
                                }"
                            />
                            <SendMail
                                v-if="getLeadDataFieldType(leadData.field_name) == 'email'"
                                :email="leadData.field_value"
                                :leadFormData="formData"
                                :extraLeadFormData="formData.template_form"
                            />
                            <a-button
                                v-if="getLeadDataFieldType(leadData.field_name) == 'phone'"
                                :href="leadData.field_value ? `tel:${leadData.field_value}` : 'javascript:void(0)'"
                                type="primary"
                            >
                                <template #icon>
                                    <MobileOutlined />
                                </template>
                            </a-button>
                        </a-input-group>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
    </perfect-scrollbar>
    <div
        :style="{
            position: 'absolute',
            right: 0,
            bottom: 0,
            width: '100%',
            borderTop: '1px solid #e9e9e9',
            padding: '10px 16px',
            background: '#fff',
            zIndex: 1,
        }"
    >
        <a-row justify="end">
            <a-col>
                <a-button
                    type="primary"
                    :loading="saveLoading"
                    @click="onSubmit"
                >
                    <template #icon>
                        <SaveOutlined />
                    </template>
                    {{ $t("common.save") }}
                </a-button>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import { SaveOutlined, MobileOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../common/composable/apiAdmin";
import { notification } from "ant-design-vue";
import { useI18n } from "vue-i18n";
import SendMail from "../../views/call-manager/SendMail.vue";
import UserSelect from "../../../common/components/common/select/UserSelect.vue";
import DateTimePicker from "../../../common/components/common/calendar/DateTimePicker.vue";

export default {
    props: {
        formData: {
            default: {},
        },
        id: {
            default: undefined,
        },
        isSale: {
            default: false,
        }
    },
    components: {
        SaveOutlined,
        SendMail,
        UserSelect,
        DateTimePicker,
        MobileOutlined,
    },
    emits: ["success"],
    setup(props, { emit }) {
        const { addEditRequestAdmin, rules } = apiAdmin();
        const saveLoading = ref(false);
        const optionLanguages = ref([]);
        const statuses = ref([]);
        const { t } = useI18n();

        onMounted(() => {
            const statusesUrl = props.isSale ? "sale-statuses" : "lead-statuses";
            const selectOptionsPromise = axiosAdmin.get('select-options/language');
            const statusesPromise = axiosAdmin.get(statusesUrl);

            Promise.all([selectOptionsPromise, statusesPromise]).then(([selectOptionsResponse, statusesResponse]) => {
                optionLanguages.value = selectOptionsResponse.data;
                statuses.value = statusesResponse.data;
            });
        });

        const onSubmit = () => {
            saveLoading.value = true;
            const url = props.isSale ? "campaigns/update-actioned-sale" : "campaigns/update-actioned-lead";
            console.log("formData", props.formData);
            addEditRequestAdmin({
                url: url,
                data:{
                    ...props.formData,
                    x_sale_lead_id: props.id,
                },
                success: (res) => {
                    saveLoading.value = false;

                    notification.success({
                        message: t("common.success"),
                        description: t("sales.updated"),
                        placement: "bottomRight",
                    });

                    emit("success");
                }
            });
        }

        const getLeadDataFieldType = (fieldName) => {
            var fieldType = "text";

            if (
                props.formData.campaign &&
                props.formData.campaign.form &&
                props.formData.campaign.form.form_fields &&
                props.formData.campaign.form.form_fields.length > 0
            ) {
                var newResult = find(props.formData.campaign.form.form_fields, {
                    name: fieldName,
                });

                if (newResult && newResult.type) {
                    fieldType = newResult.type;
                }
            }

            return fieldType;
        };

        return {
            rules,
            optionLanguages,
            statuses,
            onSubmit,
            saveLoading,
            getLeadDataFieldType
        }
    }
}
</script>

<style>
.callmanager-details {
    height: calc(100vh - 240px);
}
</style>
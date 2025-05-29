<template>
    <a-drawer
        :title="$t('sales.add')"
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
                        :label="$t('sales.campaign')"
                        name="campaign_id"
                        :help="
                            rules.campaign_id ? rules.campaign_id.message : null
                        "
                        :validateStatus="rules.campaign_id ? 'error' : null"
                        class="required"
                    >
                        <a-select
                            v-model:value="formData.campaign_id"
                            :placeholder="
                                $t('common.select_default_text', [
                                    $t('lead.campaign'),
                                ])
                            "
                            optionFilterProp="title"
                            @change="resetFormData"
                            show-search
                        >
                            <a-select-option
                                v-for="allCampaign in allCampaigns"
                                :key="allCampaign.xid"
                                :title="allCampaign.name"
                                :value="allCampaign.xid"
                                :campaign="allCampaign"
                            >
                                {{ allCampaign.name }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('sales.assigned_to')"
                        name="assigned_user_id"
                        :help="
                            rules.assigned_user_id
                                ? rules.assigned_user_id.message
                                : null
                        "
                        :validateStatus="
                            rules.assigned_user_id ? 'error' : null
                        "
                    >
                        <UserSelect
                            @onChange="(id) => (formData.assigned_user_id = id)"
                            :fetchUserData="false"
                            :data="allUsers"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.first_name')"
                        name="first_name"
                        :help="
                            rules.first_name ? rules.first_name.message : null
                        "
                        :validateStatus="rules.first_name ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.first_name"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.first_name'),
                                ])
                            "
                        ></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.last_name')"
                        name="last_name"
                        :help="rules.last_name ? rules.last_name.message : null"
                        :validateStatus="rules.last_name ? 'error' : null"
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.last_name"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.last_name'),
                                ])
                            "
                        ></a-input>
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
                        <a-input
                            v-model:value="formData.SSN"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.SSN'),
                                ])
                            "
                        ></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.date_of_birth')"
                        name="date_of_birth"
                        :help="
                            rules.date_of_birth
                                ? rules.date_of_birth.message
                                : null
                        "
                        :validateStatus="rules.date_of_birth ? 'error' : null"
                    >
                        <a-date-picker
                            v-model:value="formData.date_of_birth"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.date_of_birth'),
                                ])
                            "
                            style="width: 100%"
                        ></a-date-picker>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.phone_number')"
                        name="phone_number"
                        :help="
                            rules.phone_number
                                ? rules.phone_number.message
                                : null
                        "
                        :validateStatus="rules.phone_number ? 'error' : null"
                    >
                        <PhoneInput
                            v-model="formData.phone_number"
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.home_phone')"
                        name="home_phone"
                        :help="
                            rules.home_phone ? rules.home_phone.message : null
                        "
                        :validateStatus="rules.home_phone ? 'error' : null"
                    >
                        <a-input
                            v-model:value="formData.home_phone"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.home_phone'),
                                ])
                            "
                        ></a-input>
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
                        <a-input
                            v-model:value="formData.email"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.email'),
                                ])
                            "
                        ></a-input>
                    </a-form-item>
                </a-col>
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
                            :placeholder="
                                $t('common.select_default_text', [
                                    $t('lead.language'),
                                ])
                            "
                            :options="optionLanguages"
                        ></a-select>
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
</template>
<script>
import { SaveOutlined } from "@ant-design/icons-vue";
import { defineComponent, ref } from "vue";
import apiAdmin from "../../../common/composable/apiAdmin";
import common from "../../../common/composable/common";
import { useRouter } from "vue-router";
import UserSelect from "../../../common/components/common/select/UserSelect.vue";
import PhoneInput from "../../../common/components/common/input/PhoneInput.vue";
import { forEach } from "lodash";

export default defineComponent({
    props: [
        "visible",
        "addEditType",
        "pageTitle",
        "successMessage",
        "allCampaigns",
        "allUsers",
    ],
    components: {
        SaveOutlined,
        UserSelect,
        PhoneInput,
    },
    setup(props, { emit }) {
        const router = useRouter();
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { optionLanguages } = common();
        const formData = ref({
            campaign_id: undefined,
            assigned_user_id: undefined,
            first_name: "",
            last_name: "",
            SSN: "",
            date_of_birth: "",
            phone_number: "",
            home_phone: "",
            email: "",
        });

        const onSubmit = () => {
            addEditRequestAdmin({
                url: "sales/create-sale",
                data: formData.value,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                    router.push({
                        name: "admin.sales.details",
                        params: { id: res.sale_id },
                    });
                },
            });
        };

        const resetFormData = (xid) => {
            const campaign = props.allCampaigns.find(
                (campaign) => campaign.xid === xid
            );

            var newLeadDataArray = [];
            if (campaign && campaign.form && campaign.form.form_fields) {
                forEach(campaign.form.form_fields, (fieldValue) => {
                    newLeadDataArray.push({
                        id: Math.random().toString(36).slice(2),
                        field_name: fieldValue.name,
                        field_value: "",
                    });
                });
            }

            formData.value = {
                ...formData.value,
                lead_data: newLeadDataArray,
            };
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
            formData,
            optionLanguages,
            resetFormData,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>

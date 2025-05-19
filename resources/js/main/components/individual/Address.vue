<template>
    <a-form layout="vertical" class="mt-5">
        <a-row :gutter="16" v-if="coApplicantRequired">
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
        <a-row :gutter="16" class="mt-2">
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-form-item
                    :label="$t('lead.address_line_1')"
                    name="address_line1"
                    :help="rules.address_line1 ? rules.address_line1.message : null"
                    :validateStatus="rules.address_line1 ? 'error' : null"
                >
                    <a-input v-model:value="formData.address_line1" :placeholder="$t('common.placeholder_default_text', [$t('lead.address_line_1')])"></a-input>
                </a-form-item>
            </a-col>
            <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                v-if="coApplicantRequired"
            >
                <a-form-item
                    :label="$t('lead.address_line_1')"
                    name="co_address_line1"
                    :help="rules.co_address_line1 ? rules.co_address_line1.message : null"
                    :validateStatus="rules.co_address_line1 ? 'error' : null"
                    class="hidden-label"
                >
                    <a-input 
                        v-model:value="formData.co_address_line1" 
                        :placeholder="$t('common.placeholder_default_text', [$t('lead.address_line_1')])"
                    ></a-input>
                </a-form-item>
            </a-col>

            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-form-item
                    :label="$t('lead.address_line_2')"
                    name="address_line2"
                    :help="rules.address_line2 ? rules.address_line2.message : null"
                    :validateStatus="rules.address_line2 ? 'error' : null"
                >
                    <a-input v-model:value="formData.address_line2" :placeholder="$t('common.placeholder_default_text', [$t('lead.address_line_2')])"></a-input>
                </a-form-item>
            </a-col>
            <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                v-if="coApplicantRequired"
            >
                <a-form-item
                    :label="$t('lead.address_line_2')"
                    name="co_address_line2"
                    :help="rules.co_address_line2 ? rules.co_address_line2.message : null"
                    :validateStatus="rules.co_address_line2 ? 'error' : null"
                    class="hidden-label"
                >
                    <a-input 
                        v-model:value="formData.co_address_line2" 
                        :placeholder="$t('common.placeholder_default_text', [$t('lead.address_line_2')])"
                    ></a-input>
                </a-form-item>
            </a-col>

            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-form-item
                    :label="$t('lead.city')"
                    name="city"
                    :help="rules.city ? rules.city.message : null"
                    :validateStatus="rules.city ? 'error' : null"
                >
                    <a-input v-model:value="formData.city" :placeholder="$t('common.placeholder_default_text', [$t('lead.city')])"></a-input>
                </a-form-item>
            </a-col>
            <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                v-if="coApplicantRequired"
            >
                <a-form-item
                    :label="$t('lead.city')"
                    name="co_city"
                    :help="rules.co_city ? rules.co_city.message : null"
                    :validateStatus="rules.co_city ? 'error' : null"
                    class="hidden-label"
                >
                    <a-input 
                        v-model:value="formData.co_city" 
                        :placeholder="$t('common.placeholder_default_text', [$t('lead.city')])"
                    ></a-input>
                </a-form-item>
            </a-col>

            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-form-item
                    :label="$t('lead.state')"
                    name="state_id"
                    :help="rules.state_id ? rules.state_id.message : null"
                    :validateStatus="rules.state_id ? 'error' : null"
                >
                    <a-select
                        v-model:value="formData.state_id"
                        :placeholder="
                            $t('common.select_default_text', [
                                $t('lead.state'),
                            ])
                        "
                        :allowClear="true"
                        optionFilterProp="title"
                        show-search
                    >
                        <a-select-option
                            v-for="state in states" 
                            :value="state.id" 
                            :key="state.id"
                            :title="state.name"
                            >{{ state.name }}</a-select-option
                        >
                    </a-select>
                </a-form-item>
            </a-col>
            <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                v-if="coApplicantRequired"
            >
                <a-form-item
                    :label="$t('lead.state')"
                    name="co_state_id"
                    :help="rules.co_state_id ? rules.co_state_id.message : null"
                    :validateStatus="rules.co_state_id ? 'error' : null"
                    class="hidden-label"
                >
                    <a-select
                        v-model:value="formData.co_state_id"
                        :placeholder="
                            $t('common.select_default_text', [
                                $t('lead.state'),
                            ])
                        "
                        :allowClear="true"
                        optionFilterProp="title"
                        show-search
                    >
                        <a-select-option 
                            v-for="state in states" 
                            :value="state.id" 
                            :key="state.id"
                            :title="state.name"
                            >{{ state.name }}</a-select-option
                        >
                    </a-select>
                </a-form-item>
            </a-col>

            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-form-item
                    :label="$t('lead.zip_code')"
                    name="zip_code"
                    :help="rules.zip_code ? rules.zip_code.message : null"
                    :validateStatus="rules.zip_code ? 'error' : null"
                >
                    <a-input v-model:value="formData.zip_code" :placeholder="$t('common.placeholder_default_text', [$t('lead.zip_code')])"></a-input>
                </a-form-item>
            </a-col>
            <a-col
                :xs="24"
                :sm="24"
                :md="12"
                :lg="12"
                v-if="coApplicantRequired"
            >
                <a-form-item
                    :label="$t('lead.zip_code')"
                    name="co_zip_code"
                    :help="rules.co_zip_code ? rules.co_zip_code.message : null"
                    :validateStatus="rules.co_zip_code ? 'error' : null"
                    class="hidden-label"
                >
                    <a-input 
                        v-model:value="formData.co_zip_code" 
                        :placeholder="$t('common.placeholder_default_text', [$t('lead.zip_code')])"
                    ></a-input>
                </a-form-item>
            </a-col>
        </a-row>
    </a-form>
    <div
        :style="{
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
                    {{ $t("lead.save_address") }}
                </a-button>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import { ref, watch } from "vue";
import { SaveOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../common/composable/apiAdmin";
import { notification } from "ant-design-vue";
import { useI18n } from "vue-i18n";
import common from "../../../common/composable/common";

export default {
    props: {
        individualData: {
            type: Object,
            default: () => ({}),
        },
        states: {
            default: [],
        }
    },
    components: {
        SaveOutlined,
    },
    emits: ['success'],
    setup(props, { emit }) {
        const { addEditRequestAdmin, rules } = apiAdmin();
        const saveLoading = ref(false);
        const { t } = useI18n();
        const { coApplicantRequired } = common();

        const formData = ref({
            individual_id: "",
            address_line1: "",
            address_line2: "",
            city: "",
            state_id: "",
            zip_code: "",
            co_address_line1: "",
            co_address_line2: "",
            co_city: "",
            co_state_id: "",
            co_zip_code: "",
        });

        const onSubmit = () => {
            saveLoading.value = true;
            addEditRequestAdmin({
                url: "addresses/save",
                data: formData.value,
                success: (res) => {
                    if(res.success == true) {
                        rules.value = {};
                        notification.success({
                            message: t("common.success"),
                            description: t("lead.address_saved_successfully"),
                            placement: "bottomRight",
                        })
                        emit('success');
                    } else {
                        notification.error({
                            message: t("common.error"),
                            description: t("lead.address_save_error_message"),
                            placement: "bottomRight",
                        })
                    }
                    saveLoading.value = false;
                },
                error: (err) => {
                    saveLoading.value = false;
                }
            });
        }

        watch(
            () => props.individualData,
            (newValue) => {
                if(newValue) {
                    formData.value = {
                        individual_id: newValue.xid,
                        ...newValue.address,
                    }

                    if(coApplicantRequired.value)
                    formData.value = {
                        ...formData.value,
                        co_address_line1: newValue.co_applicant?.address?.address_line1 || "",
                        co_address_line2: newValue.co_applicant?.address?.address_line2 || "",
                        co_city: newValue.co_applicant?.address?.city || "",
                        co_state_id: newValue.co_applicant?.address?.state_id || "",
                        co_zip_code: newValue.co_applicant?.address?.zip_code || "",
                    }
                }
            }, {  
                immediate: true,
                deep: true
            }
        )

        return {
            rules,
            onSubmit,
            saveLoading,
            coApplicantRequired,
            formData
        }
    }
}
</script>
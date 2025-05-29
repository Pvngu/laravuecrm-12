<template>
    <a-tooltip :title="$t('campaign.export_leads')">
        <a-button
            v-if="
                (permsArray.includes('reports_view') ||
                    permsArray.includes('admin'))
            "
            type="primary"
            @click="() => {
                visible = true;
            }"
        >
            <template #icon>
                <CloudDownloadOutlined />
            </template>
        </a-button>
    </a-tooltip>
    <a-modal
        v-model:open="visible"
        :title="$t('campaign.export_leads')"
        @close="handleCancel"
        centered
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :span="24">
                    <a-form-item
                        :label="$t('campaign.export_as')"
                        name="export_type"
                        :help="
                            rules.export_type
                                ? rules.export_type.message
                                : null
                        "
                        :validateStatus="
                            rules.export_type ? 'error' : null
                        "
                        class="required"
                    >
                        <a-select
                            v-model:value="exportData.export_type"
                            :placeholder="$t('common.select_default_text', [''])"
                        >
                            <a-select-option value="xlsx">
                                XLSX
                            </a-select-option>
                            <a-select-option value="csv">
                                CSV
                            </a-select-option>
                            <a-select-option value="pdf">
                                PDF
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :span="24">
                    <a-form-item
                        :label="$t('common.columns')"
                        name="columns"
                        :help="rules.columns ? rules.columns.message : null"
                        :validateStatus="rules.columns ? 'error' : null"
                        class="required"
                    >
                        <a-select
                            v-model:value="exportData.columns"
                            mode="multiple"
                            style="width: 100%"
                            :placeholder="$t('common.select_default_text', [$t('common.columns')])"
                            :options="columnsOptions"
                        />
                    </a-form-item>
                </a-col>
                <a-col :span="24">
                    <a-form-item
                        :label="$t('lead.lead_status') + ' ' + $t('common.include_all_as_default')"
                        name="completed"
                    >
                        <a-select
                            mode="multiple"
                            v-model:value="exportData.status"
                            :placeholder="$t('common.select_default_text', [$t('lead.lead_status')])"
                        >
                            <a-select-option
                                v-for="status in leadStatuses"
                                :key="status.id"
                                :value="status.id"
                            >
                                {{ status.name }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :span="24">
                    <a-form-item
                        :label="$t('lead.started')"
                        name="started"
                    >
                        <a-select
                            v-model:value="exportData.started"
                            :placeholder="$t('common.select_default_text', [''])"
                            allowClear
                        >
                            <a-select-option value="yes" >
                                {{ $t('common.yes') }}
                            </a-select-option>
                            <a-select-option value="no" >
                                {{ $t('common.no') }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :span="24">
                    <a-form-item
                        :label="$t('common.date_range')"
                        name="dates"
                        :help="rules.dates ? rules.dates.message : null"
                        :validateStatus="rules.dates ? 'error' : null"
                    >
                        <DateRangePicker
                            @dateTimeChanged="
                                (changedDateTime) => {
                                    dates = changedDateTime;
                                }
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button key="back" @click="visible = false">
                {{ $t("common.close") }}
            </a-button>
            <a-button
                @click="exportCampaign"
                type="primary"
                :disabled="!exportData.export_type || exportData.columns.length == 0"
            >
                {{ $t("common.export") }}
            </a-button>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent, ref } from 'vue';
import common from "../../../common/composable/common";
import apiAdmin from "../../../common/composable/apiAdmin";
import { useI18n } from "vue-i18n";
import { CloudDownloadOutlined } from "@ant-design/icons-vue";
import DateRangePicker from "../../../common/components/common/calendar/DateRangePicker.vue";

export default defineComponent ({
    props: [
        "campaignXId",
        "leadStatuses",
    ],
    components: {
        CloudDownloadOutlined,
        DateRangePicker,
    },
    setup(props) {
        const { permsArray, exportItems } = common();
        const { rules } = apiAdmin();
        const { t } = useI18n();
        const visible = ref(false);
        const exportData = ref({
            export_type: "",
            columns: [],
            status: [],
            started: null,
        });
        const dates = ref([]);

        const exportCampaign = () => {
            axiosAdmin.get(`campaigns/export-leads/${props.campaignXId}`, { 
                responseType: 'arraybuffer',
                params: {
                    columns: exportData.value.columns,
                    dates: dates.value,
                    format: exportData.value.export_type,
                    status: exportData.value.status,
                    started: exportData.value.started,
                }
            })
            .then((res) => {
                exportItems(res ,exportData.value.export_type, 'leads', dates.value);
                handleCancel();
            });
        }

        const handleCancel = () => {
            visible.value = false;
            rules.value = {};
            exportData.value = {
                export_type: "",
                columns: [],
                status: [],
                started: null,
            };
        };

        const columnsOptions = ref([
            {
                label: t("lead.campaign"),
                value: "campaign_name",
            },
            {
                label: t("lead.reference_number"),
                value: "reference_number",
            },
            {
                label: t("lead.first_name"),
                value: "first_name",
            },
            {
                label: t("lead.last_name"),
                value: "last_name",
            },
            {
                label: t("lead.SSN"),
                value: "SSN",
            },
            {
                label: t("lead.date_of_birth"),
                value: "date_of_birth",
            },
            {
                label: t("lead.phone_number"),
                value: "phone_number",
            },
            {
                label: t("lead.home_phone"),
                value: "home_phone",
            },
            {
                label: t("lead.email"),
                value: "email",
            },
            {
                label: t("lead.language"),
                value: "language",
            },
            {
                label: t("lead.time_taken"),
                value: "time_taken",
            },
            {
                label: t("lead.started"),
                value: "started",
            },
            {
                label: t("lead.lead_status"),
                value: "lead_status",
            },
            {
                label: t("sales.assigned_to"),
                value: "assigned_to",
            },
            {
                label: t("campaign.first_actioner"),
                value: "first_action_by",
            },
            {
                label: t("campaign.last_actioner"),
                value: "last_action_by",
            },
        ]);

        return {
            rules,
            exportCampaign,
            columnsOptions,
            permsArray,
            visible,
            handleCancel,
            exportData,
            dates,
        }
    }
})
</script>
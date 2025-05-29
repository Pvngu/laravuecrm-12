<template>
    <a-button
        v-if="
            (permsArray.includes('reports_view') ||
                permsArray.includes('admin'))
        "
        @click = "visible = true"
    >
        {{ $t('common.export', [$t('menu.sales')]) }}
    </a-button>
    <a-modal
        v-model:open = "visible"
        :title="$t('user.export_users')"
        centered
        @close="handleClose"
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
            <a-button key="back" @click="handleClose">
                {{ $t("common.close") }}
            </a-button>
            <a-button 
                @click="exportUsers(exportData)"
                type="primary"
                :disabled="!exportData.columns.length || !exportData.export_type"
            >
                {{ $t("common.export") }}
            </a-button>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent, ref } from "vue";
import { CloudDownloadOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import apiAdmin from "../../../common/composable/apiAdmin";
import common from "../../../common/composable/common";
import DateRangePicker from "../../../common/components/common/calendar/DateRangePicker.vue";

export default defineComponent({
    props: [
        "filters",
    ],
    components: {
        CloudDownloadOutlined,
        DateRangePicker
    },
    setup(props) {
        const { rules } = apiAdmin();
        const { permsArray, exportItems } = common();
        const exportData = ref({
            export_type: "",
            columns: [],
        });
        const { t } = useI18n();
        const visible = ref(false);
        const dates = ref([]);

        const exportUsers = () => {
            axiosAdmin.get('/sales/export', {
                responseType: 'arraybuffer',
                params: {
                    columns: exportData.value.columns,
                    dates: dates.value,
                    format: exportData.value.export_type,
                    assigned_to: props.filters.assigned_to,
                    campaign_id: props.filters.campaign_id,
                    sale_status_id: props.filters.sale_status_id,
                    selectedRowKeys: []
                }
            }).then((res) => {
                exportItems(res, exportData.value.export_type, 'sales', dates.value);
                visible.value = false;
                resetExportData();
            });
        };

        const handleClose = () => {
            visible.value = false;
            resetExportData();
        }

        const resetExportData = () => {
            exportData.value = {
                export_type: "",
                columns: [],
            };
        }

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
                label: t("sales.sale_status"),
                value: "sale_status",
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
            exportData,
            columnsOptions,
            rules,
            exportUsers,
            permsArray,
            exportItems,
            visible,
            handleClose,
            dates,
        };
    }
});
</script>
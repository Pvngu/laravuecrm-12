<template>
    <a-drawer
        :title="drawerTitle"
        :width="drawerWidth"
        :open="visible"
        :body-style="{ paddingBottom: '80px' }"
        :maskClosable="false"
        @close="onClose"
        :destroyOnClose="true"
    >
        <a-descriptions :title="$t('common.basic_details')">
            <a-descriptions-item :label="$t('lead.reference_number')">
                <span>{{ lead && lead.reference_number ? lead.reference_number : "-" }}</span>
            </a-descriptions-item>
            <a-descriptions-item :label="$t('lead.campaign')">
                <span>{{ lead && lead.campaign ? lead.campaign.name : "-" }}</span>
            </a-descriptions-item>
            <a-descriptions-item :label="$t('campaign.first_actioner')">
                <span>{{ lead && lead.first_actioner && lead.first_actioner.name ? lead.first_actioner.name : "-" }}</span>
            </a-descriptions-item>
            <a-descriptions-item :label="$t('campaign.last_actioner')">
                <span>{{ lead && lead.last_actioner && lead.last_actioner.name ? lead.last_actioner.name : "-" }}</span>
            </a-descriptions-item>
            <a-descriptions-item :label="$t('lead.call_duration')">
                <span>{{ lead && lead.time_taken ? formatTimeDuration(lead.time_taken) : "-" }}</span>
            </a-descriptions-item>
        </a-descriptions>

        <a-tabs v-model:activeKey="activeTabKey" class="mt-20">
            <a-tab-pane key="lead_data">
                <template #tab>
                    <span>
                        <UnorderedListOutlined />
                        {{ $t("lead.lead_data") }}
                    </span>
                </template>
                <a-descriptions
                    v-if="lead"
                    :title="$t('lead.basic_details')"
                >
                    <a-descriptions-item :label="$t('lead.full_name')">
                        <span>{{ lead.first_name }} {{ lead.last_name }}</span>
                    </a-descriptions-item>
                    <a-descriptions-item :label="$t('lead.SSN')">
                        <span>{{ lead && lead.SSN ? lead.SSN : "-" }}</span>
                    </a-descriptions-item>
                    <a-descriptions-item :label="$t('lead.date_of_birth')">
                        <span>{{ lead && lead.date_of_birth ? formatDate(lead.date_of_birth) : "-" }}</span>
                    </a-descriptions-item>
                    <a-descriptions-item :label="$t('lead.home_phone')">
                        <span>{{ lead && lead.home_phone ? lead.home_phone : "-" }}</span>
                    </a-descriptions-item>
                    <a-descriptions-item :label="$t('lead.phone_number')">
                        <span>{{ lead && lead.phone_number ? lead.phone_number : "-" }}</span>
                    </a-descriptions-item>
                    <a-descriptions-item :label="$t('lead.email')">
                        <span>{{ lead && lead.email ? lead.email : "-" }}</span>
                    </a-descriptions-item>
                    <a-descriptions-item :label="$t('lead.language')">
                        <span>{{ lead && lead.language ? lead.language : "-" }}</span>
                    </a-descriptions-item>
                    <a-descriptions-item :label="$t('lead.original_profile_id')">
                        <span>{{ lead && lead.original_profile_id ? lead.original_profile_id : "-" }}</span>
                    </a-descriptions-item>
                </a-descriptions>

                <a-descriptions
                    class="mt-10"
                    v-if="lead && lead.lead_data && lead.lead_data.length > 0"
                    :title="$t('lead.additional_details')"
                >
                    <a-descriptions-item
                        v-for="leadData in lead.lead_data"
                        :key="leadData.id"
                    >
                        <template #label>{{ leadData.field_name }}</template>
                        {{ leadData.field_value ? leadData.field_value : "-" }}
                    </a-descriptions-item>
                </a-descriptions>
            </a-tab-pane>
            <a-tab-pane key="call_logs">
                <template #tab>
                    <span>
                        <PhoneOutlined />
                        {{ $t("menu.call_logs") }}
                    </span>
                </template>
                <IndividualLogTable
                    v-if="lead"
                    :individualId="lead.xid"
                    :showLeadDetails="false"
                    :showTableSearch="false"
                    :showAction="false"
                />
            </a-tab-pane>
            <a-tab-pane key="notes">
                <template #tab>
                    <span>
                        <FileTextOutlined />
                        {{ $t("menu.lead_notes") }}
                    </span>
                </template>
                <LeadNotesTable
                    v-if="lead"
                    :individualId="lead.xid"
                    :showAddButton="
                        lead && lead.campaign && lead.campaign.status != 'completed'
                            ? true
                            : false
                    "
                />
            </a-tab-pane>
        </a-tabs>
    </a-drawer>
</template>
<script>
import { defineComponent, ref, watch, computed } from "vue";
import { useI18n } from "vue-i18n";
import {
    UnorderedListOutlined,
    PhoneOutlined,
    FileTextOutlined,
} from "@ant-design/icons-vue";
import common from "../../../../common/composable/common";
import IndividualLogTable from "../../../components/individual-logs/index.vue";
import LeadNotesTable from "../../../components/lead-notes/index.vue";

export default defineComponent({
    props: {
        visible: {
            default: false,
        },
        title: {
            default: "",
        },
        lead: {
            default: {},
        },
    },
    emits: ["close"],
    components: {
        UnorderedListOutlined,
        PhoneOutlined,
        FileTextOutlined,

        IndividualLogTable,
        LeadNotesTable,
    },
    setup(props, { emit }) {
        const { formatTimeDuration, formatDate } = common();
        const { t } = useI18n();
        const drawerTitle = ref(t("lead.lead_details"));
        const activeTabKey = ref("lead_data");

        const onClose = () => {
            activeTabKey.value = "lead_data";
            emit("close");
        };

        return {
            drawerTitle,
            formatTimeDuration,
            activeTabKey,
            formatDate,

            onClose,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "60%",
        };
    },
});
</script>

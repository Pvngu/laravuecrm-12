<template>
    <a-row
        :gutter="8"
        class="mt-2"
        v-if="saleData.individual"
    >
        <!-- sale details -->
        <a-col :xs="24" :sm="24" :md="24" :lg="6" :xl="6">
            <perfect-scrollbar
                    :options="{
                        wheelSpeed: 1,
                        swipeEasing: true,
                        suppressScrollX: true,
                    }"
            >
                <div>
                    <a-card>
                        <a-row>
                            <a-col :span="24">
                                <a-typography-title :level="3">
                                    {{ saleData.individual.full_name }}
                                </a-typography-title>
                                <a-space direction="vertical" :size="0">
                                    <a-typography-title v-if="saleData.individual.campaign" :level="5">
                                        {{ saleData.individual.campaign.name }}
                                    </a-typography-title>
                                    <a-typography-title :level="5">
                                        {{ $t('sales.status') }}:
                                        <span v-if="saleData.individual.sale_status">{{ saleData.individual.sale_status }}</span>
                                        <span v-else>-</span>
                                    </a-typography-title>
                                </a-space>
                            </a-col>
                        </a-row>
                        <a-divider />
                        <Alerts
                            :individualId="individualId"
                            :language="saleData.individual.language"
                            ref="alerts"
                        />
                        <a-row class="mt-5">
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.language') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ saleData.individual.language }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.reference_number') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ saleData.individual.reference_number }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-divider />
                        <a-row :gutter="[0,8]">
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('common.created_at') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ formatDateTime(saleData.individual.sale_created_at) }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('common.updated_at') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ formatDateTime(saleData.individual.updated_at) }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('sales.assigned_to') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text v-if="saleData.individual.assigned_user">
                                            {{ saleData.individual.assigned_user.name }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row class="mt-2">
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.SSN') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ saleData.individual.SSN }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.date_of_birth') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ formatDate(saleData.individual.date_of_birth)  }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row class="mt-2">
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.email') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ saleData.individual.email }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.home_phone') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ saleData.individual.home_phone }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.phone_number') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ saleData.individual.phone_number }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row class="mt-5">
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.address') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            <span v-if="saleData.individual.full_address !== null">{{ saleData.individual.full_address }}</span>
                                            <span v-else>-</span>
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                    </a-card>
                </div>
            </perfect-scrollbar>
        </a-col>
        <a-col :xs="24" :sm="24" :md="24" :lg="18" style="position: unset">
            <a-card class="callmanager-middle-sidebar" style="position: unset">
                <a-tabs v-model:activeKey="activeKey" class="salemanager-tabs">
                    <!-- <a-tab-pane key="history">
                        <template #tab>
                            <span>
                                <HistoryOutlined />
                                {{ $t("sales.history") }}
                            </span>
                        </template>
                        <ActivityLogTable
                            :individualId="individualId"
                            :refresh="activeKey == 'history' ? true : false"
                        />
                    </a-tab-pane> -->
                    <a-tab-pane key="sale_details">
                        <template #tab>
                            <span>
                                <FileTextOutlined />
                                {{ $t("sales.sale_details") }}
                            </span>
                        </template>
                        <DetailsTabs
                            :saleLeadData="saleData"
                            :states="states"
                            @success="() => (refreshTimeLine = true)"
                            :isSale="true"
                        />
                    </a-tab-pane>
                    <a-tab-pane key="lead_notes">
                        <template #tab>
                            <span>
                                <FileTextOutlined />
                                {{ $t("common.notes") }}
                            </span>
                        </template>
                        <LeadNotesTable
                            pageName="lead_action"
                            :individualId="individualId"
                            @success="(data) => { if(data.alert_type) $refs.alerts.refreshAlerts(data) }"
                            :showAddButton="
                                saleData.individual &&
                                saleData.individual.campaign &&
                                saleData.individual.campaign.status == 'completed'
                                    ? false
                                    : true
                            "
                        />
                    </a-tab-pane>
                    <a-tab-pane key="docs">
                        <template #tab>
                            <span>
                                <FileOutlined />
                                {{ $t("common.docs") }}
                            </span>
                        </template>
                        <DocsTable
                            :individualId="saleData.individual.xid"
                            :scrollStyle="{ y: 'calc(100vh - 320px)' }"
                        />
                    </a-tab-pane>
                </a-tabs>
            </a-card>
        </a-col>
    </a-row>
</template>

<script>
    import { ref, onMounted, onUnmounted } from 'vue';
    import { 
        FileTextOutlined,
        PhoneOutlined,
        HistoryOutlined,
        FileOutlined,
        CreditCardOutlined,
        BankOutlined,
        DollarOutlined,
        FileDoneOutlined,
        MessageOutlined
    } from '@ant-design/icons-vue';
    import { useRoute } from "vue-router";
    import common from "../../../common/composable/common";
    import IndividualLogTable from "../../components/individual-logs/index.vue";
    import LeadNotesTable from "../../components/lead-notes/index.vue";
    import DocsTable from "../../components/docs/index.vue";
    import ActivityLogTable from "../../components/activity-log/index.vue";
    import Alerts from '../../components/individual/Alerts.vue';
    import DetailsTabs from '../../components/individual/DetailsTabs.vue';

    export default {
        components: {
            FileTextOutlined,
            PhoneOutlined,
            HistoryOutlined,
            FileOutlined,
            CreditCardOutlined,
            BankOutlined,
            DollarOutlined,
            MessageOutlined,
            FileDoneOutlined,
            IndividualLogTable,
            LeadNotesTable,
            DocsTable,
            ActivityLogTable,
            Alerts,
            DetailsTabs
        },
        setup() {
            const { formatDateTime, formatDate, formatAmountCurrency } = common();
            const activeKey = ref("sale_details");
            const route = useRoute();
            const states = ref([]);
            const saleData = ref({});
            const saleCallLogDetails = ref({});
            const saleFollowUp = ref({});
            const salesmanBooking = ref({});
            const refreshNotes = ref(false);
            const individualId = ref(undefined);

            onMounted(() => {
                fetchInitData();
            })

            onUnmounted(() => {
                if(document.body.classList.contains("no-scroll"))
                    document.body.classList.remove("no-scroll");
            })
            
            const fetchInitData = () => {
                const campaignUrl = "individual:campaign{id,xid,name},individual:campaign:emailTemplate{id,xid,name},individual:campaign:form{name,form_fields}";
                const saleDetailsUrl = `sales/${route.params.id}?fields=id,xid,sale_status_id,assigned_to,x_assigned_to,created_at,saleStatus{id,name},assignedUser{id,xid,name,email,phone},individual,individual:address,individual:coApplicant,individual:coApplicant:address{id,xid,address_line1,address_line2,city,state_id,zip_code,full_address},individual:lastActioner{id,xid,name},${campaignUrl},individual:individualFollowUp{id,xid,log_type,user_id,x_user_id,date_time,notes},individual:individualFollowUp:user{id,xid,name},individual:salesmanBooking{id,xid,log_type,user_id,x_user_id,date_time,notes},individual:salesmanBooking:user{id,xid,name}`;
                saleCallLogDetails.value = {};
                activeKey.value =  route.query.tab ?? "sale_details";
                saleFollowUp.value = {};
                salesmanBooking.value = {};

                const saleDetailsPromise = axiosAdmin.get(saleDetailsUrl);
                const statesPromise = axiosAdmin.get('states/all');

                Promise.all([saleDetailsPromise, statesPromise]).then(
                    ([saleDetailsResponse, statesResponse]) => {
                        var saleResult = saleDetailsResponse.data;
                        states.value = statesResponse.data;

                        saleData.value = saleResult;

                        saleFollowUp.value = saleResult.individual.individual_follow_up
                            ? saleResult.individual.individual_follow_up
                            : [];
                        salesmanBooking.value = saleResult.individual.salesman_booking
                            ? saleResult.individual.salesman_booking
                            : [];
                    }
                );
            };

            return {
                activeKey,
                states,
                formatDateTime,
                refreshNotes,
                formatDate,
                route,
                formatAmountCurrency,
                individualId,
                saleData,
            }
        }
    }
</script>

<style scoped>
    .callmanager-middle-sidebar {
        min-height: calc(100vh);
    }
</style>

<style>
    .salemanager-tabs .ant-tabs-content-holder .ant-tabs-content {
        position: unset !important;
    }
</style>
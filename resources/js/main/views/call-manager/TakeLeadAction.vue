<template>
    <a-card
        class="page-content-sub-header breadcrumb-left-border"
        :bodyStyle="{ padding: '0px', margin: '0px 16px 0' }"
    >
        <a-row>
            <a-col :span="24">
                <a-page-header @back="saveAndExit()" class="p-0!">
                    <template #title>
                        {{
                            leadDetails.individual &&
                            leadDetails.individual.campaign &&
                            leadDetails.individual.campaign.name ? leadDetails.individual.campaign.name : ""
                        }}
                        {{
                            leadDetails.individual &&
                            leadDetails.individual.reference_number
                                ? ` (${leadDetails.individual.reference_number})`
                                : ""
                        }}
                    </template>
                    <template #extra>
                        <a-button
                            :style="{ background: '#faad14', borderColor: '#faad14' }"
                            type="primary"
                            @click="takeLeadAction('previous')"
                        >
                            <ArrowLeftOutlined />
                            {{ $t("campaign.previous_lead") }}
                        </a-button>
                        <a-button
                            :style="{ background: '#52c41a', borderColor: '#52c41a' }"
                            type="primary"
                            @click="takeLeadAction('next')"
                        >
                            {{ $t("campaign.next_lead") }}
                            <ArrowRightOutlined />
                        </a-button>
                    </template>
                </a-page-header>
            </a-col>
            <a-col :span="24">
                <a-breadcrumb separator="-" style="font-size: 12px">
                    <a-breadcrumb-item>
                        <router-link :to="{ name: 'admin.dashboard.index' }">
                            {{ $t(`menu.dashboard`) }}
                        </router-link>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>
                        <router-link :to="{ name: 'admin.call_manager.index' }">
                            {{ $t(`menu.call_manager`) }}
                        </router-link>
                    </a-breadcrumb-item>
                    <a-breadcrumb-item>
                        {{
                            leadDetails.individual &&
                            leadDetails.individual.campaign &&
                            leadDetails.individual.campaign.name
                                ? leadDetails.individual.campaign.name
                                : ""
                        }}
                    </a-breadcrumb-item>
                </a-breadcrumb>
            </a-col>
        </a-row>
    </a-card>

    <a-row
        :gutter="16"
        class="mt-5"
        style="margin: 10px"
        v-if="leadDetails && leadDetails.xid"
    >
    <!-- timer, lead details, campaing details -->
        <a-col
            :xs="24"
            :sm="24"
            :md="24"
            :lg="6"
            :xl="6"
            class="bg-setting-sidebar h-[calc(100vh-100px)]"
        >
            <perfect-scrollbar
                    :options="{
                        wheelSpeed: 1,
                        swipeEasing: true,
                        suppressScrollX: true,
                    }"
            >
                <div class="h-[calc(100vh-100px)]">
                    <a-card :bordered="false" :bodyStyle="{ paddingBottom: '0px' }">
                        <a-row>
                            <a-col :span="24" class="text-center">
                                <a-typography-title :level="2">
                                    <ClockCircleOutlined />
                                    {{
                                        timer.hours.value < 10
                                            ? `0${timer.hours.value}`
                                            : timer.hours
                                    }}:{{
                                        timer.minutes.value < 10
                                            ? `0${timer.minutes.value}`
                                            : timer.minutes
                                    }}:{{
                                        timer.seconds.value < 10
                                            ? `0${timer.seconds.value}`
                                            : timer.seconds
                                    }}
                                </a-typography-title>
                            </a-col>
                        </a-row>
                        <a-row class="mt-2">
                            <a-col :span="24">
                                <a-space>
                                    <BookingModal
                                        v-if="leadDetails && leadDetails.xid"
                                        key="lead_follow_up"
                                        :individualId="leadDetails.individual.xid"
                                        :lastActionerId="leadDetails.individual.last_actioner.xid"
                                        bookingType="lead_follow_up"
                                        @success="
                                            (resultValue) => {
                                                leadFollowUp = resultValue;
                                                refreshTimeLine = true;
                                            }
                                        "
                                    >
                                        {{ $t("menu.lead_follow_up") }}
                                    </BookingModal>

                                    <!-- <BookingModal
                                        v-if="leadDetails && leadDetails.xid"
                                        key="salesman_bookings"
                                        :individualId="leadDetails.individual.xid"
                                        bookingType="salesman_bookings"
                                        @success="
                                            (resultValue) => {
                                                salesmanBooking = resultValue;
                                                refreshTimeLine = true;
                                            }
                                        "
                                    >
                                        {{ $t("menu.salesman_bookings") }}
                                    </BookingModal> -->
                                </a-space>
                            </a-col>
                        </a-row>

                        <a-divider />
                    </a-card>
                    <Alerts
                        :individualId="leadDetails.individual.xid"
                        :language="leadData.individual.language"
                    />
                    <perfect-scrollbar
                        :options="{
                            wheelSpeed: 1,
                            swipeEasing: true,
                            suppressScrollX: true,
                        }"
                        class="mt-2 h-[calc(100vh-350px)]"
                    >
                        <a-collapse v-model:activeKey="activeLeftPanelKey" :bordered="false">
                            <a-collapse-panel
                                key="lead_details"
                                :style="{ background: 'white' }"
                            >
                                <template #header>
                                    <a-typography-title :level="5">
                                        {{ $t("lead.lead_details") }}
                                    </a-typography-title>
                                </template>
                                <a-skeleton v-if="newPageLoad" active />
                                <template v-else>
                                    <a-row>
                                        <a-col :span="24">
                                            <a-typography-text strong>
                                                {{ $t("lead.lead_number") }}
                                            </a-typography-text>
                                        </a-col>
                                        <a-col :span="24" class="mt-5">
                                            <a-typography-text>
                                                {{ leadNumber }} /
                                                {{ leadDetails.individual.campaign.total_leads }}
                                            </a-typography-text>
                                        </a-col>
                                    </a-row>
                                    <a-row class="mt-5">
                                        <a-col :span="24">
                                            <a-typography-text strong>
                                                {{ $t("campaign.last_actioner") }}
                                            </a-typography-text>
                                        </a-col>
                                        <a-col :span="24" class="mt-5">
                                            <a-typography-text>
                                                {{
                                                    leadDetails.individual.campaign.last_actioner &&
                                                    leadDetails.individual.campaign.last_actioner.name
                                                        ? leadDetails.individual.campaign.last_actioner
                                                            .name
                                                        : "-"
                                                }}
                                            </a-typography-text>
                                        </a-col>
                                    </a-row>

                                    <a-row class="mt-5">
                                        <a-col :span="24">
                                            <a-typography-text strong>
                                                {{ $t("lead.email") }}
                                            </a-typography-text>
                                        </a-col>
                                        <a-col :span="24" class="mt-5">
                                            <a-typography-text
                                                v-if="
                                                    leadData &&
                                                    leadData.individual &&
                                                    leadData.individual.email
                                                "
                                            >
                                                {{ leadData.individual.email }}
                                            </a-typography-text>
                                            <span v-else>-</span>
                                        </a-col>
                                    </a-row>
                                    <a-row class="mt-5">
                                        <a-col :span="24">
                                            <a-typography-text strong>
                                                {{ $t("lead.home_phone") }}
                                            </a-typography-text>
                                        </a-col>
                                        <a-col :span="24" class="mt-5">
                                            <a-typography-text
                                                v-if="
                                                    leadData &&
                                                    leadData.individual &&
                                                    leadData.individual.home_phone
                                                "
                                            >
                                                {{ leadData.individual.home_phone }}
                                            </a-typography-text>
                                            <span v-else>-</span>
                                        </a-col>
                                    </a-row>
                                    <a-row class="mt-5">
                                        <a-col :span="24">
                                            <a-typography-text strong>
                                                {{ $t("lead.phone_number") }}
                                            </a-typography-text>
                                        </a-col>
                                        <a-col :span="24" class="mt-5">
                                            <a-typography-text
                                                v-if="
                                                    leadData &&
                                                    leadData.individual &&
                                                    leadData.individual.phone_number
                                                "
                                            >
                                                {{ leadData.individual.phone_number }}
                                            </a-typography-text>
                                            <span v-else>-</span>
                                        </a-col>
                                    </a-row>

                                    <a-row class="mt-5">
                                        <a-col :span="24">
                                            <a-typography-text strong>
                                                {{ $t("lead.address") }}
                                            </a-typography-text>
                                        </a-col>
                                        <a-col :span="24" class="mt-5">
                                            <a-typography-text
                                                v-if="
                                                    leadData &&
                                                    leadData.individual &&
                                                    leadData.individual.full_address
                                                "
                                            >
                                                {{ leadData.individual.full_address }}
                                            </a-typography-text>
                                            <span v-else>-</span>
                                        </a-col>
                                    </a-row>

                                    <a-row class="mt-5">
                                        <a-col :span="24">
                                            <a-typography-text strong>
                                                {{ $t("lead_follow_up.follow_up") }}
                                            </a-typography-text>
                                        </a-col>
                                        <a-col :span="24" class="mt-5">
                                            <a-typography-text
                                                v-if="
                                                    leadFollowUp &&
                                                    leadFollowUp.user &&
                                                    leadFollowUp.user.name
                                                "
                                            >
                                                {{ leadFollowUp.user.name }} on
                                                {{ formatDateTime(leadFollowUp.date_time) }}
                                            </a-typography-text>
                                            <span v-else>-</span>
                                        </a-col>
                                    </a-row>
                                    <a-row class="mt-5">
                                        <a-col :span="24">
                                            <a-typography-text strong>
                                                {{ $t("salesman_booking.salesman_booking") }}
                                            </a-typography-text>
                                        </a-col>
                                        <a-col :span="24" class="mt-5">
                                            <a-typography-text
                                                v-if="
                                                    salesmanBooking &&
                                                    salesmanBooking.user &&
                                                    salesmanBooking.user.name
                                                "
                                            >
                                                {{ salesmanBooking.user.name }} on
                                                {{
                                                    formatDateTime(salesmanBooking.date_time)
                                                }}
                                            </a-typography-text>
                                            <span v-else>-</span>
                                        </a-col>
                                    </a-row>
                                </template>
                            </a-collapse-panel>
                            <a-collapse-panel
                                key="campaign_details"
                                :style="{ background: 'white' }"
                            >
                                <template #header>
                                    <a-typography-title :level="5">
                                        {{ $t("campaign.campaign_details") }}
                                    </a-typography-title>
                                </template>
                                <a-row
                                    v-for="(
                                        campaignDetails, campaignDetailsKey
                                    ) in leadDetails.individual.campaign.detail_fields"
                                    :key="campaignDetails.id"
                                    :gutter="16"
                                    :class="{ 'mt-25': campaignDetailsKey > 0 }"
                                >
                                    <a-col :span="24">
                                        <a-typography-text strong>
                                            {{ campaignDetails.field_name }}
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="24" class="mt-5">
                                        <a-typography-text>
                                            {{ campaignDetails.field_value }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-collapse-panel>
                        </a-collapse>
                    </perfect-scrollbar>
                </div>
            </perfect-scrollbar>
        </a-col>

        <a-col
            :xs="24"
            :sm="24"
            :md="24"
            :lg="12"
            :xl="12"
            class="h-[calc(100vh-100px)]"
        >
            <a-card :bordered="false">
                <a-tabs v-model:activeKey="activeKey">
                    <a-tab-pane key="lead_details">
                        <template #tab>
                            <span>
                                <FileTextOutlined />
                                {{ $t("lead.lead_details") }}
                            </span>
                        </template>
                        <a-tabs v-model:activeKeyLead="activeKeyLead" type="card" class="address flex justify-end">
                            <a-tab-pane key="details" tab="Details">
                                <Details
                                    :saleLeadData="leadData"
                                    @success="() => (refreshTimeLine = true)"
                                />
                            </a-tab-pane>
                            <a-tab-pane key="address" tab="Address">
                                <Address
                                    :individualData="leadData.individual"
                                    :states="states"
                                    @success="() => (refreshTimeLine = true)"
                                />
                            </a-tab-pane>
                        </a-tabs>
                    </a-tab-pane>
                    <a-tab-pane key="call_logs">
                        <template #tab>
                            <span>
                                <PhoneOutlined />
                                {{ $t("menu.call_logs") }}
                            </span>
                        </template>
                        <IndividualLogTable
                            key="individual_logs"
                            pageName="lead_action"
                            logType="call_log"
                            :individualId="leadDetails.individual.xid"
                            :showTableSearch="false"
                            :showLeadDetails="false"
                            :showAction="false"
                            :scrollStyle="{ y: 'calc(100vh - 320px)' }"
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
                            :individualId="leadDetails.individual.xid"
                            :scrollStyle="{ y: 'calc(100vh - 320px)' }"
                            @success="() => {
                                refreshTimeLine = true;
                                refreshNotes = true;
                            }"
                            :showAddButton="
                                leadDetails &&
                                leadDetails.individual.campaign &&
                                leadDetails.individual.campaign.status == 'completed'
                                    ? false
                                    : true
                            "
                        />
                    </a-tab-pane>
                    <a-tab-pane key="docs" v-if="leadStatus == '1'">
                        <template #tab>
                            <span>
                                <FileOutlined />
                                {{ $t("common.docs") }}
                            </span>
                        </template>
                        <DocsTable
                            pageName="documents"
                            :individualId="leadDetails.individual.xid"
                            :scrollStyle="{ y: 'calc(100vh - 320px)' }"
                            @success="() => (refreshTimeLine = true)"
                            :individualDetails="leadDetails"
                        />
                    </a-tab-pane>
                </a-tabs>
            </a-card>
        </a-col>
        <a-col
            :xs="24"
            :sm="24"
            :md="24"
            :lg="6"
            :xl="6"
            v-if="activeKey !== 'debts'"
            class="bg-setting-sidebar h-[calc(100vh-99px)]"
        >
            <a-card
                :bordered="false"
                class="callmanager-right-sidebar"
                :bodyStyle="{ padding: '15px' }"
            >
                <template #title>
                    <a-typography-title :level="5" type="success" strong>
                        {{ $t("lead.lead_history") }}
                    </a-typography-title>
                </template>
                <a-skeleton v-if="newPageLoad" active />
                <LogTimeline
                    v-else
                    :individualId="leadDetails.individual.xid"
                    :refresh="refreshTimeLine"
                    @dataFetched="() => (refreshTimeLine = false)"
                />
            </a-card>
        </a-col>
    </a-row>

    <SkipLeadModal
        v-if="leadDetails && leadDetails.xid"
        :leadId="leadDetails.xid"
        :visible="showSkipModal"
        @onSkipDelete="skipDeleteSuccess"
        @onSkip="skipSuccess"
        @close="() => (showSkipModal = false)"
    />
</template>

<script>
import { onMounted, ref, createVNode, watch } from "vue";
import {
    ArrowRightOutlined,
    ArrowLeftOutlined,
    ClockCircleOutlined,
    FileTextOutlined,
    BankOutlined,
    CreditCardOutlined,
    FileOutlined,
    MessageOutlined,
    DollarOutlined,
    PhoneOutlined,
    ExclamationCircleOutlined,
    ExclamationCircleFilled
} from "@ant-design/icons-vue";
import { Modal, notification } from "ant-design-vue";
import { useRoute, useRouter } from "vue-router";
import { forEach, find } from "lodash-es";
import { useStopwatch } from "vue-timer-hook";
import { useI18n } from "vue-i18n";
import apiAdmin from "../../../common/composable/apiAdmin";
import common from "../../../common/composable/common";
import BookingModal from "../bookings/BookingModal.vue";
import IndividualLogTable from "../../components/individual-logs/index.vue";
import LogTimeline from "../../components/individual-logs/LogTimeline.vue";
import LeadNotesTable from "../../components/lead-notes/index.vue";
import DocsTable from "../../components/docs/index.vue";
import SkipLeadModal from "./SkipLeadModal.vue";
import Alerts from "../../components/individual/Alerts.vue";
import Address from "../../components/individual/Address.vue";
import Details from "../../components/individual/Details.vue";

export default {
    components: {
        ArrowRightOutlined,
        ArrowLeftOutlined,
        FileTextOutlined,
        BankOutlined,
        CreditCardOutlined,
        FileOutlined,
        MessageOutlined,
        DollarOutlined,
        PhoneOutlined,
        ExclamationCircleOutlined,
        ClockCircleOutlined,

        BookingModal,
        IndividualLogTable,
        LeadNotesTable,
        DocsTable,
        LogTimeline,
        SkipLeadModal,
        ExclamationCircleFilled,
        Alerts,
        Address,
        Details
    },
    setup() {
        const { formatDateTime } = common();
        const { addEditRequestAdmin } = apiAdmin();
        const route = useRoute();
        const router = useRouter();
        const leadDetails = ref({});
        const leadCallLogDetails = ref({});
        const activeKey = ref("lead_details");
        const activeKeyLead = ref("details");
        const activeLeftPanelKey = ref("lead_details");
        const leadFormData = ref({});
        const extraLeadFormData = ref({});
        const timer = useStopwatch(0, true);
        const { t } = useI18n();
        const leadFollowUp = ref({});
        const salesmanBooking = ref({});
        const leadStatus = ref(undefined);
        const leadNumber = ref(0);
        const newPageLoad = ref(true);
        const refreshTimeLine = ref(false);
        const refreshNotes = ref(false);
        const autoSaved = ref(false);
        const saveLoading = ref(false);
        const saveExitLoading = ref(false);
        const showSkipModal = ref(false);
        const leadXid = ref("");
        const states = ref([]);
        const leadData = ref({});

        onMounted(() => {
            fetchInitData();
        });

        const fetchInitData = () => {
            const leadId = route.params.id;
            const campaignUrl = "individual:campaign,individual:campaign:campaignUsers,individual:campaign:campaignUsers:user{id,xid,name,profile_image,profile_image_url},individual:campaign:emailTemplate{id,xid,name},individual:campaign:form{id,xid,name,form_fields},individual:campaign:lastActioner{id,xid,name},individual:campaign:completedBy{id,xid,name}";
            const leadDetailsUrl = `leads/${leadId}?fields=id,xid,individual{id,xid,reference_number,first_name,last_name,SSN,date_of_birth,home_phone,phone_number,email,original_profile_id,language,lead_data,time_taken,last_action_by,x_last_action_by,x_campaign_id,template_form,full_address},individual:address,individual:coApplicant,individual:coApplicant:address,started,lead_status,individual:lastActioner{id,xid,name},${campaignUrl},individual:individualFollowUp{id,xid,log_type,user_id,x_user_id,date_time,notes},individual:individualFollowUp:user{id,xid,name},individual:salesmanBooking{id,xid,log_type,user_id,x_user_id,date_time,notes},individual:salesmanBooking:user{id,xid,name}`;

            leadDetails.value = {};
            leadCallLogDetails.value = {};
            activeKey.value = "lead_details";
            activeKeyLead.value = "details";
            activeLeftPanelKey.value = "lead_details";
            leadFormData.value = {};
            extraLeadFormData.value = {};
            leadFollowUp.value = {};
            salesmanBooking.value = {};
            leadXid.value = "";

            states.value = "";

            leadStatus.value = undefined;
            leadNumber.value = 0;
            newPageLoad.value = true;

            const leadDetailsPromise = axiosAdmin.get(leadDetailsUrl);
            const leadCallLogPromise = axiosAdmin.get(`leads/create-call-log/${leadId}`);
            const statesPromise = axiosAdmin.get('states/all');

            Promise.all([leadDetailsPromise, leadCallLogPromise, statesPromise]).then(
                ([leadDetailsResponse, leadCallLogResponse, statesResponse]) => {
                    var leadResult = leadDetailsResponse.data;
                    states.value = statesResponse.data;

                    leadNumber.value = leadCallLogResponse.data.lead_number;
                    leadCallLogDetails.value = leadCallLogResponse.data.call_log;
                    leadDetails.value = leadResult;

                    leadData.value = leadResult;

                    var newLeadDataArray = [];
                    if (leadResult.individual.lead_data && leadResult.individual.lead_data.length > 0) {
                        forEach(leadResult.individual.lead_data, (fieldData) => {
                            newLeadDataArray.push({
                                id: fieldData.id,
                                field_name: fieldData.field_name,
                                field_value: fieldData.field_value,
                            });
                        });
                    }

                    if (
                        leadResult.campaign &&
                        leadResult.campaign.form &&
                        leadResult.campaign.form.form_fields &&
                        leadResult.campaign.form.form_fields.length > 0
                    ) {
                        forEach(
                            leadResult.campaign.form.form_fields,
                            (leadFormFields) => {
                                var newResult = find(newLeadDataArray, {
                                    field_name: leadFormFields.name,
                                });

                                if (newResult == undefined) {
                                    newLeadDataArray.push({
                                        id: Math.random().toString(36).slice(2),
                                        field_name: leadFormFields.name,
                                        field_value: "",
                                    });
                                }
                            }
                        );
                    }

                    leadFormData.value = {
                        campaign_id: leadResult.x_campaign_id,
                        lead_data: newLeadDataArray,
                    };
                    leadXid.value = leadResult.xid;

                    leadFollowUp.value = leadResult.individual.individual_follow_up
                        ? leadResult.individual.individual_follow_up
                        : [];
                    salesmanBooking.value = leadResult.individual.salesman_booking
                        ? leadResult.individual.salesman_booking
                        : [];
                    leadStatus.value = leadResult.lead_status;

                    timer.reset(leadResult.individual.time_taken, true);

                    newPageLoad.value = false;
                }
            );
        };

        const calculateTotalTimeInSeconds = () => {
            var minutesInSeconds = timer.minutes.value * 60;
            var hoursInSeconds = timer.hours.value * 60 * 60;
            var daysInSeconds = timer.days.value * 24 * 60 * 60;

            return (
                timer.seconds.value + minutesInSeconds + hoursInSeconds + daysInSeconds
            );
        };

        const saveAndExit = () => {
            saveExitLoading.value = true;

            Modal.confirm({
                title: t("common.are_you_sure") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: t("lead.are_you_want_go_to_save_exit"),
                centered: true,
                okText: t("common.yes"),
                okType: "danger",
                cancelText: t("common.no"),
                onOk() {
                    saveData("save_exit")
                },
                onCancel() {
                    saveExitLoading.value = false;
                },
            });
        };

        const saveTime = () => {
            let url = '';
            let data = {};

            url = `campaigns/update-lead-timer`;
            data = {
                call_log_id: leadCallLogDetails.value.xid,
                call_time: calculateTotalTimeInSeconds(),
                x_lead_id: route.params.id,
            }

            addEditRequestAdmin({
                url: url,
                data: data,
                success: (res) => {
                    autoSaved.value = true;
                    saveLoading.value = false;
                },
            });
        }

        const saveData = (saveType = "auto") => {
            if (saveType == "save") {
                saveLoading.value = true;
            } else if (saveType == "save_exit") {
                saveExitLoading.value = true;
            }

            let url = '';
            let data = {};

            url = `campaigns/update-actioned-lead`;
            data = {
                ...leadFormData.value,

                call_log_id: leadCallLogDetails.value.xid,
                call_time: calculateTotalTimeInSeconds(),
                x_sale_lead_id: route.params.id,
                x_lead_id: route.params.id,
            }

            addEditRequestAdmin({
                url: url,
                data: data,
                success: (res) => {
                    autoSaved.value = true;
                    saveLoading.value = false;
                    refreshTimeLine.value = true;

                    if (saveType == "save_exit") {
                        saveExitLoading.value = false;

                        router.push({
                            name: "admin.call_manager.index",
                        });

                        // store.dispatch("auth/showNotificaiton", {});
                    } else {
                        notification.success({
                            message: t("common.success"),
                            description: t("lead.updated"),
                            placement: "bottomRight",
                        });
                    }
                },
            });
        };

        const takeLeadAction = (actionName) => {
            var contentLangText = "";
            if (actionName == "next") {
                contentLangText = t("lead.are_you_want_go_to_next_lead");
            } else if (actionName == "previous") {
                contentLangText = t("lead.are_you_want_go_to_previous_lead");
            }

            Modal.confirm({
                title: t("common.are_you_sure") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: contentLangText,
                centered: true,
                okText: t("common.yes"),
                okType: "danger",
                cancelText: t("common.no"),
                onOk() {
                    nextPreviousLead(actionName);
                },
                onCancel() {},
            });
        };

        const skipSuccess = () => {
            showSkipModal.value = false;
            nextPreviousLead("skip-next");
        };

        const nextPreviousLead = (actionName) => {
            var newActionName = actionName == "skip-next" ? "next" : actionName;

            addEditRequestAdmin({
                url: `campaigns/take-lead-action`,
                data: {
                    ...leadFormData.value,

                    call_log_id: leadCallLogDetails.value.xid,
                    call_time: calculateTotalTimeInSeconds(),
                    action_type: newActionName,
                    x_sale_lead_id: route.params.id,
                    x_lead_id: route.params.id,
                },
                success: (res) => {
                    if (res && res.lead && res.lead.xid) {
                        notification.success({
                            message: t("common.success"),
                            description: t(`lead.you_are_on_${newActionName}_lead`),
                            placement: "bottomRight",
                        });

                        router.push({
                            name: "admin.call_manager.take_action",
                            params: { id: res.lead.xid },
                        });
                    } else {
                        // TODO - No lead exists redirect to campaign page
                        // with No Lead exists message

                        Modal.confirm({
                            title: t("lead.no_lead_exists") + "?",
                            icon: createVNode(ExclamationCircleOutlined),
                            content: t("lead.no_lead_exist_message"),
                            centered: true,
                            okText: t("campaign.save_exit"),
                            okType: "danger",
                            cancelText: t("common.continue"),
                            onOk() {
                                saveData("save_exit", "lead");
                            },
                            onCancel() {
                                saveData("save", "lead");
                            },
                        });
                    }
                },
            });
        };

        const skipDeleteSuccess = (lead) => {
            showSkipModal.value = false;

            if (lead && lead.xid) {
                router.push({
                    name: "admin.call_manager.take_action",
                    params: { id: lead.xid },
                });
            } else {
                router.push({
                    name: "admin.call_manager.index",
                });
            }
        };

        const skipLead = () => {
            showSkipModal.value = true;
        };

        watch(timer.seconds, (newVal, oldVal) => {
            if (timer.seconds.value % 5 == 0) {
                saveTime();
            }
        });

        watch(route, (newVal, oldVal) => {
            if (newVal.params.id != undefined) {
                fetchInitData();
            }
        });

        watch(autoSaved, (newVal, oldVal) => {
            if (newVal) {
                setTimeout(() => (autoSaved.value = false), 3000);
            }
        });

        watch(refreshNotes, (newVal, oldVal) => {
            if (newVal) {
                refreshNotes.value = false;
            }
        });

        return {
            activeLeftPanelKey,
            leadDetails,
            activeKey,
            activeKeyLead,
            leadFormData,
            leadFollowUp,
            salesmanBooking,

            formatDateTime,
            timer,
            leadStatus,
            leadNumber,
            newPageLoad,

            takeLeadAction,
            refreshTimeLine,
            refreshNotes,

            saveAndExit,
            saveData,
            saveExitLoading,
            saveLoading,
            autoSaved,

            skipLead,
            showSkipModal,
            skipDeleteSuccess,
            skipSuccess,

            leadData,
            extraLeadFormData,
            states,

            leadXid
        };
    },
};
</script>
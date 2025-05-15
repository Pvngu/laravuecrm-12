<template>
    <a-row
        :gutter="16"
        class="mt-5"
        style="margin: 10px;"
        v-if="individualId"
    >
        <!-- sale details -->
        <a-col :xs="24" :sm="24" :md="24" :lg="6" :xl="6" class="bg-setting-sidebar">
            <perfect-scrollbar
                    :options="{
                        wheelSpeed: 1,
                        swipeEasing: true,
                        suppressScrollX: true,
                    }"
            >
                <div class="salemanager-left-sidebar">
                    <a-card :bordered="false" :bodyStyle="{ paddingBottom: '0px' }">
                        <a-row>
                            <a-col :span="24">
                                <a-typography-title :level="3">
                                    {{ individualData.first_name }} {{ individualData.last_name }}
                                </a-typography-title>
                                <a-space direction="vertical" :size="0">
                                    <a-typography-title v-if="individualData.campaign" :level="5">
                                        {{ individualData.campaign.name }}
                                    </a-typography-title>
                                    <a-typography-title :level="5">
                                        {{ $t('sales.status') }}:
                                        <span v-if="individualData.sale_status">{{ individualData.sale_status }}</span>
                                        <span v-else>-</span>
                                    </a-typography-title>
                                </a-space>
                            </a-col>
                        </a-row>
                        <a-divider />
                        <Alerts
                            :individualId="individualId"
                            :language="individualData.language"
                            ref="alerts"
                        />
                        <a-row class="mt-20">
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.language') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ individualData.language }}
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
                                            {{ individualData.reference_number }}
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
                                            {{ formatDateTime(individualData.sale_created_at) }}
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
                                            {{ formatDateTime(individualData.updated_at) }}
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
                                        <a-typography-text v-if="individualData.assigned_user">
                                            {{ individualData.assigned_user.name }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row class="mt-10">
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.SSN') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ individualData.SSN }}
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
                                            {{ prettyFormatDate(individualData.date_of_birth)  }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row class="mt-10">
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.email') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ individualData.email }}
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
                                            {{ individualData.home_phone }}
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
                                            {{ individualData.phone_number }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row class="mt-20">
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('lead.address') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            <span v-if="individualData.full_address !== null">{{ individualData.full_address }}</span>
                                            <span v-else>-</span>
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                        <a-row v-if="saleEnrollment" class="mt-20 mb-20">
                            <a-typography-title :level="5">
                                {{ $t('sales.sale_snapshot') }}
                            </a-typography-title>
                            <a-col :span="24">
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('sales.enrolled') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ formatDate(saleEnrollment.created_at) }} - {{ saleEnrollment.creator.name }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                                <!-- <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('sales.settled_debt_average') }} %:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            ?%
                                        </a-typography-text>
                                    </a-col>
                                </a-row> -->
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('sales.debt') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ saleEnrollment.next_payment ? formatAmountCurrency(saleEnrollment.next_payment.balance_amount) : formatAmountCurrency(saleEnrollment.total_debt) }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('sales.plan') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ saleEnrollment.enrollment_plan ? saleEnrollment.enrollment_plan.name : '-' }}
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('sales.length') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            {{ saleEnrollment.enrollment_term }} Mo. ({{ $t('common.monthly') }})
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16" class="mt-10">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('sales.admin_services') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            $250.00
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('sales.legal_fee') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            $50.00
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16" class="mt-10">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('sales.saving_balance') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            $0.00
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('sales.next_payment') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            <span v-if="saleEnrollment.next_payment">
                                                {{ prettyFormatDate(saleEnrollment.next_payment.due_date) }} - {{ formatAmountCurrency(Math.ceil(Number(saleEnrollment.next_payment.interest_amount) + Number(saleEnrollment.next_payment.principal_amount))) }}
                                            </span>
                                            <span v-else>
                                                -
                                            </span>
                                        </a-typography-text>
                                    </a-col>
                                </a-row>
                                <a-row :gutter="16">
                                    <a-col :span="10">
                                        <a-typography-text strong>
                                            {{ $t('sales.last_payment') }}:
                                        </a-typography-text>
                                    </a-col>
                                    <a-col :span="14">
                                        <a-typography-text>
                                            <span v-if="saleEnrollment.last_payment">
                                                {{ saleEnrollment.last_payment ? prettyFormatDate(saleEnrollment.last_payment.due_date) : '-' }}
                                            </span>
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
                    <a-tab-pane key="history">
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
                    </a-tab-pane>
                    <a-tab-pane key="sale_details">
                        <template #tab>
                            <span>
                                <FileTextOutlined />
                                {{ $t("sales.sale_details") }}
                            </span>
                        </template>
                        <a-tabs v-model:activeKeySale="activeKeySale" type="card" class="address">
                            <a-tab-pane key="details" :tab="$t('common.details')">
                                <Details
                                    :formData="individualData"
                                    :id="route.params.id"
                                    :isSale="true"
                                />
                            </a-tab-pane>
                            <a-tab-pane key="address" :tab="$t('common.address')">
                                <Address
                                    :formData="addressesFormData"
                                    :states="states"
                                />
                            </a-tab-pane>
                        </a-tabs>
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
                                individualData &&
                                individualData.campaign &&
                                individualData.campaign.status == 'completed'
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
                            pageName="documents"
                            :individualId="individualId"
                            :scrollStyle="{ y: 'calc(100vh - 320px)' }"
                            :selectedTab="route.query.doc_tab"
                            :individualDetails="individualData"
                        />
                    </a-tab-pane>
                </a-tabs>
            </a-card>
        </a-col>
    </a-row>
</template>

<script>
    import { ref, onMounted, onUnmounted, watch } from 'vue';
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
    import Address from '../../components/individual/Address.vue';
    import Details from '../../components/individual/Details.vue';

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
            Address,
            Details
        },
        setup() {
            const { formatDateTime, formatDate, prettyFormatDate, formatAmountCurrency } = common();
            const activeKey = ref("history");
            const activeKeySale = ref("details");
            const route = useRoute();
            const states = ref([]);
            const individualData = ref({
                campaign: {
                    name: null,
                    status: null,
                    email_template_xid: null,
                    form: {
                        name: null,
                        form_fields: {},
                    },
                },
                assigned_user: {
                    name: null,
                    email: null,
                    phone: null,
                },
                sale_status: null,
                sale_created_at: null,

                reference_number: "",
                first_name: "",
                last_name: "",
                SSN: "",
                date_of_birth: "",
                phone_number: "",
                home_phone: "",
                email: "",
                language: undefined,
                original_profile_id: "",
                lead_status: "",
                assigned_user_xid: "",
                sale_status_id: "",
                lead_data: [],
                updated_at: "",

                co_first_name: "",
                co_last_name: "",
                co_SSN: "",
                co_date_of_birth: "",
                co_phone_number: "",
                co_home_phone: "",
                co_email: "",
                co_language: undefined,
            });
            const addressesFormData = ref({
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
                individual_id: undefined,
            });
            const saleEnrollment = ref({});
            const saleCallLogDetails = ref({});
            const saleFollowUp = ref({});
            const salesmanBooking = ref({});
            const refreshNotes = ref(false);
            const showSmsAlert = ref(false);
            const saleEnrollmentXid = ref(undefined);
            const individualId = ref(undefined);
            const isDebtsFetched = ref(false);

            onMounted(() => {
                fetchInitData();
                watchDebtsData();
            })

            onUnmounted(() => {
                if(document.body.classList.contains("no-scroll"))
                    document.body.classList.remove("no-scroll");
            })
            
            const fetchInitData = () => {
                const campaignUrl = "individual:campaign{id,xid,name},individual:campaign:emailTemplate{id,xid,name},individual:campaign:form{name,form_fields}";
                const enrollmentUrl = 'individual:enrollment{xid,id,total_debt,enrollment_term,created_at},individual:enrollment:creator{xid,id,name},individual:enrollment:enrollmentPlan{xid,id,name},individual:enrollment:nextPayment{xid,id,due_date,principal_amount,interest_amount,balance_amount},individual:enrollment:lastPayment{xid,id,due_date}'
                const saleDetailsUrl = `sales/${route.params.id}?fields=id,xid,sale_status_id,assigned_to,created_at,saleStatus{id,name},assignedUser{id,xid,name,email,phone},individual{id,xid,reference_number,first_name,last_name,SSN,date_of_birth,home_phone,phone_number,email,original_profile_id,language,lead_data,time_taken,last_action_by,x_last_action_by,x_campaign_id,updated_at},individual:address{id,xid,address_line1,address_line2,city,state_id,zip_code},individual:coApplicant{id,xid,first_name,last_name,SSN,date_of_birth,home_phone,phone_number,email,language},individual:coApplicant:address{id,xid,address_line1,address_line2,city,state_id,zip_code,full_address},individual:lastActioner{id,xid,name},${campaignUrl},individual:individualFollowUp{id,xid,log_type,user_id,x_user_id,date_time,notes},individual:individualFollowUp:user{id,xid,name},individual:salesmanBooking{id,xid,log_type,user_id,x_user_id,date_time,notes},individual:salesmanBooking:user{id,xid,name},${enrollmentUrl}`;
                saleCallLogDetails.value = {};
                activeKey.value =  route.query.tab ?? "history";
                activeKeySale.value = "details";
                saleFollowUp.value = {};
                salesmanBooking.value = {};

                const saleDetailsPromise = axiosAdmin.get(saleDetailsUrl);
                const statesPromise = axiosAdmin.get('states/all');

                Promise.all([saleDetailsPromise, statesPromise]).then(
                    ([saleDetailsResponse, statesResponse]) => {
                        var saleResult = saleDetailsResponse.data;
                        states.value = statesResponse.data;

                        saleEnrollment.value = saleResult.individual.enrollment;

                        individualData.value.campaign.name = saleResult.individual.campaign.name;
                        individualData.value.campaign.status = saleResult.individual.campaign.status;
                        individualData.value.sale_status = saleResult.sale_status.name;
                        individualData.value.sale_created_at = saleResult.created_at;

                        if(saleResult.individual.campaign.form) {
                            individualData.value.campaign.form.name = saleResult.individual.campaign.form.name;
                            individualData.value.campaign.form.form_fields = saleResult.individual.campaign.form.form_fields;
                        }

                        if(saleResult.individual.campaign.email_template) {
                            individualData.value.campaign.email_template_xid = saleResult.individual.campaign.email_template.xid;
                        }

                        if(saleResult.assigned_user) {
                            individualData.value.assigned_user.name = saleResult.assigned_user.name;
                            individualData.value.assigned_user.email = saleResult.assigned_user.email;
                            individualData.value.assigned_user.phone = saleResult.assigned_user.phone;
                        }

                        if(saleResult.individual) {
                            individualData.value.reference_number = saleResult.individual.reference_number;
                            individualData.value.first_name = saleResult.individual.first_name;
                            individualData.value.last_name = saleResult.individual.last_name;
                            individualData.value.SSN = saleResult.individual.SSN;
                            individualData.value.date_of_birth = saleResult.individual.date_of_birth;
                            individualData.value.home_phone = saleResult.individual.home_phone;
                            individualData.value.phone_number = saleResult.individual.phone_number;
                            individualData.value.email = saleResult.individual.email;
                            individualData.value.language = saleResult.individual.language;
                            individualData.value.original_profile_id = saleResult.individual.original_profile_id;
                            individualData.value.lead_status = saleResult.lead_status;
                            individualData.value.sale_status_id = saleResult.sale_status_id;
                            individualData.value.full_address = saleResult.individual.address ? saleResult.individual.address.full_address : '';
                            individualData.value.lead_data = saleResult.individual.lead_data;
                            individualData.value.updated_at = saleResult.individual.updated_at;

                            individualId.value = saleResult.individual.xid;
                        }

                        if(saleResult.individual.enrollment) {
                            saleEnrollmentXid.value = saleResult.individual.enrollment.xid;
                        }

                        if(saleResult.individual.address) {
                            addressesFormData.value = saleResult.individual.address;
                        }

                        if (saleResult.assigned_user) {
                            individualData.value.assigned_user_xid = saleResult.assigned_user.xid;
                        }

                        axiosAdmin.get(`smsMessages?fields=id,xid&individual_id=${saleResult.individual.xid}&is_read=0&is_inbound=1`).then((response) => {
                            const messageDetails = response.data;
                            showSmsAlert.value = messageDetails.length ? true : false;
                        });

                        if(saleResult.individual.co_applicant) {
                            individualData.value.co_first_name = saleResult.individual.co_applicant.first_name;
                            individualData.value.co_last_name = saleResult.individual.co_applicant.last_name;
                            individualData.value.co_SSN = saleResult.individual.co_applicant.SSN;
                            individualData.value.co_date_of_birth = saleResult.individual.co_applicant.date_of_birth;
                            individualData.value.co_home_phone = saleResult.individual.co_applicant.home_phone;
                            individualData.value.co_phone_number = saleResult.individual.co_applicant.phone_number;
                            individualData.value.co_email = saleResult.individual.co_applicant.email;
                            individualData.value.co_language = saleResult.individual.co_applicant.language;

                            if(saleResult.individual.co_applicant.address) {
                                addressesFormData.value.co_address_line1 = saleResult.individual.co_applicant.address.address_line1;
                                addressesFormData.value.co_address_line2 = saleResult.individual.co_applicant.address.address_line2;
                                addressesFormData.value.co_city = saleResult.individual.co_applicant.address.city;
                                addressesFormData.value.co_state_id = saleResult.individual.co_applicant.address.state_id;
                                addressesFormData.value.co_zip_code = saleResult.individual.co_applicant.address.zip_code;
                            }
                        }

                        addressesFormData.value.individual_id = saleResult.individual.xid;

                        saleFollowUp.value = saleResult.individual.individual_follow_up
                            ? saleResult.individual.individual_follow_up
                            : [];
                        salesmanBooking.value = saleResult.individual.salesman_booking
                            ? saleResult.individual.salesman_booking
                            : [];
                    }
                );
            };

            const debtsData = ref({
                allDebts: [],
                snapshot: {
                    total_accounts: 0,
                    debt_enrolled: 0,
                    current_debt: 0,
                    average: 0,
                    current_pmts: 0,
                    original_amount: 0,
                },
                debtsEnrolledIds: [],
            });

            const fetchDebtsData = () => {
                axiosAdmin.get(`debts/individual/${individualId.value}`).then((res) => {
                    debtsData.value.allDebts = res.data.debts;

                    debtsData.value.snapshot.total_accounts = res.data.total_debts;
                    debtsData.value.snapshot.debt_enrolled = res.data.debt_enrolled;
                    debtsData.value.debtsEnrolledIds = res.data.debts_enrolled_ids;
                    debtsData.value.snapshot.current_debt = res.data.current_debt;
                    debtsData.value.snapshot.average = debtsData.value.snapshot.current_debt / debtsData.value.snapshot.debt_enrolled;
                    debtsData.value.snapshot.current_pmts = res.data.current_pmts;
                    debtsData.value.snapshot.original_amount = res.data.original_amount;

                    isDebtsFetched.value = true;
                });
            }

            const watchDebtsData = () => {
                watch([activeKey, individualId], ([newActiveKey, newIndividualId]) => {
                    if ((newActiveKey === "debts" || newActiveKey === "enrollment") && !isDebtsFetched.value && newIndividualId) {
                        fetchDebtsData();
                    }
                });
            };

            return {
                activeKey,
                activeKeySale,
                states,
                formatDateTime,
                refreshNotes,
                formatDate,
                prettyFormatDate,
                showSmsAlert,
                route,
                saleEnrollment,
                formatAmountCurrency,
                saleEnrollmentXid,
                addressesFormData,
                individualData,
                fetchDebtsData,
                debtsData,
                individualId
            }
        }
    }
</script>

<style scoped>
    .callmanager-middle-sidebar {
        min-height: calc(100vh);
    }

    .sms-badge {
        position: absolute;
        top: -10px;
        right: -14px;
        transform: scale(0.6);
    }
</style>

<style>
    .salemanager-tabs .ant-tabs-content-holder .ant-tabs-content {
        position: unset !important;
    }
</style>
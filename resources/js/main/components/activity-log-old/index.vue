<template>
    <a-row
        :gutter="[15, 15]"
        class="mb-20"
    >
        <a-col v-if="showTableSearch" :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
            <a-input-search
                style="width: 100%"
                v-model:value="table.searchString"
                :placeholder="
                    $t('common.select_default_text', [
                        $t('lead.reference_number')
                    ])
                "
                show-search
                @change="onTableSearch"
                @search="onTableSearch"
                :loading="table.filterLoading"
            />
        </a-col>
        <a-col v-if="allCampaigns.length > 1" :xs="24" :sm="24" :md="12" :lg="4" :xl="4">
            <a-select
                v-model:value="extraFilters.campaign_id" 
                :placeholder="$t('common.select_default_text', [$t('lead.campaign')])"
                :allowClear="true"
                style="width: 100%"
                optionFilterProp="title"
                show-search
                @change="setUrlData"
            >
                <a-select-option
                    v-for="allCampaign in allCampaigns"
                    :key="allCampaign.xid"
                    :title="allCampaign.name"
                    :value="allCampaign.xid"
                >
                    {{ allCampaign.name }}
                </a-select-option>
            </a-select>
        </a-col>
        <a-col
            :xs="24"
            :sm="24"
            :md="12"
            :lg="6"
            :xl="6"
            v-if="
                permsArray.includes('activity_log_view_all') ||
                permsArray.includes('admin')
            "
        >
            <UserSelect
                @onChange="(id) => {
                    filters.user_id = id;
                    setUrlData();
                }"
            />
        </a-col>
        <a-col
            :xs="24"
            :sm="24"
            :md="12"
            :lg="6"
            :xl="6"
        >
            <DateRangePicker
                @dateTimeChanged="
                    (changedDateTime) => {
                        extraFilters.dates = changedDateTime;
                        setUrlData();
                    }
                "
            />
        </a-col>
    </a-row>
    <a-row class="mt-5">
        <a-col :span="24">
            <div class="table-responsive" v-if="columns && columns.length > 0">
                <a-table
                    :columns="columns"
                    :row-key="(record) => record.xid"
                    :data-source="table.data"
                    :pagination="table.pagination"
                    :loading="table.loading"
                    @change="handleTableChange"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex == 'entity'">
                            <span style="display: flex; gap: 8px;">
                                <a-tag
                                    v-if="record.log_type == 'updated_lead' || 
                                    record.log_type == 'updated_sale_assigned' ||
                                    record.log_type == 'updated_sale_status'||
                                    record.log_type == 'co_applicant' ||
                                    record.log_type == 'updated_co' ||
                                    record.log_type == 'address'||
                                    record.log_type == 'co_address'||
                                    record.log_type == 'updated_address'||
                                    record.log_type == 'updated_co_address'"
                                    color="lime"
                                >
                                    {{ $t('activity_log.lead') }}
                                </a-tag>
                                <a-tag
                                v-if="record.log_type == 'call_log'"
                                    color="blue"
                                >
                                {{ $t('activity_log.call') }}
                                </a-tag>
                                <a-tag
                                    v-if="record.log_type == 'email'"
                                    color="green"
                                >
                                    {{ $t('activity_log.email') }}
                                </a-tag>
                                <a-tag
                                    v-if="record.log_type == 'note' || 
                                    record.log_type == 'deleted_note' || 
                                    record.log_type == 'updated_note' ||
                                    record.log_type == 'debt_note' ||
                                    record.log_type == 'deleted_debt_note' ||
                                    record.log_type == 'updated_debt_note'"
                                    color="orange"
                                >
                                    {{ $t('activity_log.note') }}
                                </a-tag>
                                <a-tag
                                    v-if="record.log_type == 'lead_follow_up' || record.log_type == 'deleted_lead_follow_up'"
                                    color="geekblue"
                                >
                                    {{ $t('activity_log.follow_up') }}
                                </a-tag>
                                <a-tag
                                    v-if="record.log_type == 'salesman_bookings' || record.log_type == 'deleted_salesman_bookings'"
                                    color="red"
                                >
                                    {{ $t('activity_log.salesman_booking') }}
                                </a-tag>
                                <a-tag
                                    v-if="record.log_type == 'sms'"
                                    color="cyan"
                                >
                                    {{ $t('activity_log.sms') }}
                                </a-tag>
                                <a-tag
                                    v-if="record.log_type == 'doc' || 
                                    record.log_type == 'deleted_doc' || 
                                    record.log_type == 'generated_doc'||
                                    record.log_type == 'esign_doc' ||
                                    record.log_type == 'voided_doc'"
                                    color="magenta"
                                >
                                    {{ $t('activity_log.doc') }}
                                </a-tag>
                                <a-tag
                                    v-if="record.log_type == 'credit_card' || record.log_type == 'updated_credit_card'"
                                    color="volcano"
                                >
                                    {{ $t('activity_log.card') }}
                                </a-tag>
                                <a-tag
                                    v-if="record.log_type == 'bank_account' || record.log_type == 'updated_bank_account'"
                                    color="gold"
                                >
                                    {{ $t('activity_log.bank') }}
                                </a-tag>
                                <a-tag
                                    v-if="record.log_type == 'debt' || record.log_type == 'updated_debt'"
                                    color="purple"
                                >
                                    {{ $t('activity_log.debt') }}
                                </a-tag>
                                <a-tag
                                    v-if="record.log_type == 'sales_export' || record.log_type == 'leads_export' || record.log_type == 'staff_members_export'"
                                    color="red"
                                >
                                    {{ $t('common.export') }}
                                </a-tag>
                                <a-tag
                                    v-if="record.log_type == 'enrollment' ||
                                    record.log_type == 'updated_enrollment' ||
                                    record.log_type == 'deleted_enrollment'"
                                    color="red"
                                >
                                    {{ $t('activity_log.enrollment') }}
                                </a-tag>
                            </span>
                        </template>
                        <template v-if="column.dataIndex == 'timestamp'">
                            {{ formatDateTime(record.date_time) }}
                        </template>
                        <template v-if="column.dataIndex == 'user'">
                            {{ record.user.name }}
                        </template>
                        <template v-if="column.dataIndex == 'reference_number'">
                            <a-button
                                type="link"
                                class="p-0!"
                                @click="showViewDrawer(record.individual)"
                            >
                                {{
                                    record.individual.reference_number != "" &&
                                    record.individual.reference_number != undefined
                                        ? record.individual.reference_number
                                        : "---"
                                }}
                            </a-button>
                        </template>
                        <template v-if="column.dataIndex == 'details'">
                            <span 
                                v-if="record.log_type == 'updated_lead'"
                                class="openDetailSpan"
                                @click="detailsModal(record, type='updated')"
                            >
                                {{ $t('activity_log.lead_updated') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span 
                                v-if="record.log_type == 'address'"
                                class="openDetailSpan"
                                @click="detailsModal(record)"
                            >
                                {{ $t('activity_log.address_added') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span 
                                v-if="record.log_type == 'updated_sale_assigned'"
                            >
                                {{ 
                                    $t(
                                        'activity_log.lead_reassigned',
                                        [
                                            JSON.parse(record.notes).old_value,
                                            JSON.parse(record.notes).new_value
                                        ]
                                    ) 
                                }}
                            </span>
                            <span 
                                v-if="record.log_type == 'updated_sale_status'"
                            >
                                {{ 
                                    $t(
                                        'activity_log.lead_update_status',
                                        [JSON.parse(record.notes).new_value]
                                    ) 
                                }}
                            </span>
                            <span 
                                v-if="record.log_type == 'co_address'"
                                class="openDetailSpan"
                                @click="detailsModal(record)"
                            >
                                {{ $t('activity_log.co_address_added') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>

                            <span 
                                v-if="record.log_type == 'updated_co_address'"
                                class="openDetailSpan"
                                @click="detailsModal(record, type='updated')"
                            >
                                {{ $t('activity_log.co_address_updated') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>


                            <span 
                                v-if="record.log_type == 'updated_address'"
                                class="openDetailSpan"
                                @click="detailsModal(record, type='updated')"
                            >
                                {{ $t('activity_log.address_updated') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            
                            <span 
                                v-if="record.log_type == 'co_applicant'"
                                class="openDetailSpan"
                                @click="detailsModal(record)"
                            >
                                {{ $t('activity_log.co_applicant_added') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>

                            <span 
                            v-if="record.log_type == 'updated_co'"
                                class="openDetailSpan"
                                @click="detailsModal(record, type='updated')"
                            >
                                {{ $t('activity_log.co_applicant_updated') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>

                            <span 
                                v-if="record.log_type == 'bank_account'" 
                                class="openDetailSpan"
                                @click="detailsModal(record)"
                            >
                                {{ $t('activity_log.bank_account_created') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span 
                                v-if="record.log_type == 'updated_bank_account'" 
                                class="openDetailSpan"
                                @click="detailsModal(record, type='updated')"
                            >
                                {{ $t('activity_log.bank_account_updated') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>

                            <span 
                                v-if="record.log_type == 'credit_card'" 
                                class="openDetailSpan"
                                @click="detailsModal(record)"
                            >
                                {{ $t('activity_log.card_created') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span 
                                v-if="record.log_type == 'updated_credit_card'" 
                                class="openDetailSpan"
                                @click="detailsModal(record, type='updated')"
                            >
                                {{ $t('activity_log.card_updated') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>

                            <span 
                                v-if="record.log_type == 'note'"
                                class="openDetailSpan"
                                @click="detailsModal(record,undefined)"
                            >
                                {{ $t('activity_log.note_created') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span 
                                v-if="record.log_type == 'updated_note'"
                                class="openDetailSpan"
                                @click="detailsModal(record, type='updated')"
                            >
                                {{ $t('activity_log.note_updated') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span 
                                v-if="record.log_type == 'deleted_note'"
                                class="openDetailSpan"
                                @click="detailsModal(record, undefined)"
                            >
                                {{ $t('activity_log.note_deleted') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>

                            <span 
                                v-if="record.log_type == 'debt_note'"
                                class="openDetailSpan"
                                @click="detailsModal(record,undefined)"
                            >
                                {{ $t('activity_log.debt_note_created') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span 
                                v-if="record.log_type == 'updated_debt_note'"
                                class="openDetailSpan"
                                @click="detailsModal(record, type='updated')"
                            >
                                {{ $t('activity_log.debt_note_updated') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span 
                                v-if="record.log_type == 'deleted_debt_note'"
                                class="openDetailSpan"
                                @click="detailsModal(record, undefined)"
                            >
                                {{ $t('activity_log.debt_note_deleted') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>

                            <span v-if="record.log_type == 'call_log'">
                                {{ $t('activity_log.call_made', [
                                    formatTimeDuration(record.time_taken),
                                ]) }}
                            </span>
                            <span v-if="record.log_type == 'email'">
                                {{ $t('activity_log.email_sent') }}
                            </span>
                            <span 
                                v-if="record.log_type == 'lead_follow_up'"
                                class="openDetailSpan"
                                @click="detailsModal(record, note = true)"
                            >
                            {{ $t('activity_log.follow_up_created') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span 
                                v-if="record.log_type == 'deleted_lead_follow_up'"
                            >
                            {{ $t('activity_log.follow_up_deleted') }}
                            </span>
                            <span 
                                v-if="record.log_type == 'salesman_bookings'"
                                class="openDetailSpan"
                                @click="detailsModal(record, note = true)"
                            >
                            {{ $t('activity_log.salesman_booking_created') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span 
                                v-if="record.log_type == 'deleted_salesman_bookings'"
                            >
                            {{ $t('activity_log.salesman_booking_deleted') }}
                            </span>
                            <span 
                                v-if="record.log_type == 'sms'"
                                class="openDetailSpan"
                                @click="detailsModal(record)"
                            >
                                {{ $t('activity_log.sms_sent') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span
                                v-if="record.log_type == 'doc' || record.log_type == 'deleted_doc' || record.log_type == 'generated_doc' || record.log_type == 'esign_doc'"
                                class="openDetailSpan"
                                @click="detailsModal(record)"
                            >
                                <span v-if="record.log_type === 'doc'">
                                    {{ $t('activity_log.doc_uploaded') }}
                                </span>
                                <span v-else-if="record.log_type === 'generated_doc'">
                                    {{ $t('activity_log.doc_generated') }}
                                </span>
                                <span v-else-if="record.log_type === 'esign_doc'">
                                    {{ $t('activity_log.doc_esign') }}
                                </span>
                                <span v-else>
                                    {{ $t('activity_log.doc_deleted') }}
                                </span>
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span
                                v-if="record.log_type == 'voided_doc'"
                                class="openDetailSpan"
                                @click="detailsModal(record)"
                            >
                                {{ $t('activity_log.document_voided') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>

                            <span 
                                v-if="record.log_type == 'debt'"
                                class="openDetailSpan"
                                @click="detailsModal(record)"
                            >
                                {{ $t('activity_log.debt_created') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span 
                                v-if="record.log_type == 'updated_debt'"
                                class="openDetailSpan"
                                @click="detailsModal(record, type='updated')"
                            >
                                {{ $t('activity_log.debt_updated') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <span v-if="record.log_type == 'sales_export'">
                                {{ $t('activity_log.sales_export') }}
                            </span>
                            <span v-if="record.log_type == 'leads_export'">
                                {{ $t('activity_log.leads_export') }}
                            </span>
                            <span v-if="record.log_type == 'staff_members_export'">
                                {{ $t('activity_log.staff_members_export') }}
                            </span>
                            <span 
                                v-if="record.log_type == 'enrollment'"
                                @click="detailsModal(record)"
                                class="openDetailSpan"
                            >
                                {{ $t('activity_log.enrollment_created') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span>
                            <!-- <span 
                                v-if="record.log_type == 'updated_enrollment'"
                                @click="detailsModal(record, type='updated')"
                                class="openDetailSpan"
                            >
                                {{ $t('activity_log.enrollment_updated') }}
                                <InfoCircleOutlined class="infoCircle"/>
                            </span> -->
                            <span 
                                v-if="record.log_type == 'deleted_enrollment'"
                            >
                                {{ $t('activity_log.enrollment_deleted') }}
                            </span>
                        </template>
                        <template v-if="column.dataIndex == 'action'">
                            <slot :record="record" name="action"></slot>
                        </template>
                    </template>
                </a-table>
            </div>
        </a-col>
    </a-row>

    <a-modal 
        v-model:open="openDetails" 
        :title="$t('activity_log.more_details')" 
        centered 
        open
    >
        <a-row v-if="modalData">
            <a-row v-if="typeof modalData === 'object' && detailsType === 'updated'">
                <a-col 
                    :span="24"
                    v-for="(value, key, index) in modalData"
                    :class="index !== 0 ? 'mt-2' : ''"
                >
                    <a-typography-title :level="5">
                        {{ $t(`activity_log.${key}`) }}:
                    </a-typography-title>
                    <a-row style="padding-left: 8px;">
                        <a-col :span="24">
                            <strong>Old Value: </strong>
                            {{ value.old_value }}
                        </a-col>
                        <a-col :span="24">
                            <strong>New Value: </strong>
                            {{ value.new_value }}
                        </a-col>
                    </a-row>
                </a-col>
            </a-row>
            <a-descriptions v-if="typeof modalData === 'object' && detailsType !== 'updated'">
                <a-descriptions-item
                    v-for="(value, key) in modalData"
                    :label="$t(`activity_log.${key}`)"
                    :span="24"
                >
                    {{ value }}
                </a-descriptions-item>
            </a-descriptions>
            <a-row v-if="typeof modalData === 'string'">
                <a-col :span="24">
                    {{ modalData }}
                </a-col>
            </a-row>
        </a-row>
        <template #footer>
            <a-button
                type="primary"
                @click="openDetails = false"
            >
                {{ $t('common.close') }}
            </a-button>
        </template>
    </a-modal>

    <!-- Global Component -->
    <view-lead-details
    :visible="isViewDrawerVisible"
    :lead="viewDrawerData"
    @close="hideViewDrawer"
    />
</template>

<script>
import { onMounted, ref, watch } from "vue";
import datatable from "../../../common/composable/datatable";
import fields from "./fields";
import common from "../../../common/composable/common";
import viewDrawer from "../../../common/composable/viewDrawer";
import {
    FileTextOutlined,
    PhoneOutlined,
    ShoppingCartOutlined,
    ScheduleOutlined,
    MailOutlined,
    MessageOutlined,
    FileOutlined,
    CreditCardOutlined,
    BankOutlined,
    InfoCircleOutlined,
} from "@ant-design/icons-vue";
import DateRangePicker from "../../../common/components/common/calendar/DateRangePicker.vue";
import UserSelect from "../../../common/components/common/select/UserSelect.vue";

export default {
    props: {
        showIndividualDetails: {
            default: false,
        },
        individualId: {
            default: undefined,
        },
        userId: {
            default: undefined,
        },
        showTableSearch: {
            default: false,
        },
        refresh: {
            default: undefined,
        }
    },
    components: {
        FileTextOutlined,
        PhoneOutlined,
        ShoppingCartOutlined,
        ScheduleOutlined,
        MailOutlined,
        MessageOutlined,
        FileOutlined,
        CreditCardOutlined,
        BankOutlined,
        InfoCircleOutlined,
        DateRangePicker,
        UserSelect,
    },
    methods: {
        detailsModal(record, type= '', note = false) {
            this.openDetails = true;
            this.modalData = !note ? JSON.parse(record.notes) : record.notes;
            this.detailsType = type;
        },
    },
    setup(props) {
        const {
            user,
            formatDateTime,
            formatTimeDuration,
            permsArray,
        } = common();
        const leadDrawer = viewDrawer();
        const newTable = datatable();
        const {
            url,
            columns,
            filterableColumns,
            hashableColumns,
            allCampaigns,
            getPrefetchData,
        } = fields(props);
        const filters = ref({
            log_type: "",
            user_id: undefined,
        });
        const extraFilters = ref({
            campaign_id: undefined,
            individual_id: props.individualId,
            user_id: props.userId,
        });
        const openDetails = ref(false);
        const modalData = ref({});
        const detailsType = ref('');

        onMounted(() => {
            getPrefetchData().then(() => {
                filters.value = {
                    ...filters.value
                };

                newTable.hashable.value = [...hashableColumns];

                setUrlData();
            });
        });

        const setUrlData = () => {
            newTable.tableUrl.value = {
                url,
                filters,
                extraFilters,
            };
            newTable.table.filterableColumns = filterableColumns;
            newTable.hashable.value = [...hashableColumns];

            newTable.table.pagination = { ...newTable.table.pagination, current: 1 };
            newTable.fetch({
                page: 1,
            });
        };

        watch(
            () => props.refresh,
            (newValue) => {
                if (newValue == true) {
                    setUrlData();
                }
            }
        );

        return {
            ...leadDrawer,
            ...newTable,
            columns,
            filters,
            extraFilters,
            formatDateTime,
            formatTimeDuration,
            allCampaigns,
            setUrlData,
            openDetails,
            modalData,
            detailsType,
            permsArray,
        };
    },
};
</script>

<style>
    .infoCircle {
        position: absolute;
        color: #1890ff;
        font-size: .7rem;
        padding-left: 4px;
    }

    .openDetailSpan:hover {
        color: #1890ff;
        cursor: pointer;
    }
</style>
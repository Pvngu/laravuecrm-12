<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.lead_follow_up`)" class="p-0!" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.bookings`) }}
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.lead_follow_up`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">
                <a-button
                    v-if="
                        table.selectedRowKeys.length > 0 &&
                        (permsArray.includes('leads_view_all') ||
                            permsArray.includes('admin'))
                    "
                    type="primary"
                    @click="showSelectedDeleteConfirm"
                    danger
                >
                    <template #icon><DeleteOutlined /></template>
                    {{ $t("common.delete") }}
                </a-button>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                <a-row :gutter="[16, 16]" justify="end">
                    <a-col>
                        <a-button 
                            type="primary" 
                            @click="showCalendar = !showCalendar"
                        >
                            <template #icon>
                                <CalendarOutlined v-if="!showCalendar" @click="onCalendar" />
                                <TableOutlined v-else />
                            </template>
                        </a-button>
                    </a-col>
                </a-row>
            </a-col>
        </a-row>
    </admin-page-filters>

    <admin-page-table-content>
        <a-row>
            <a-col :span="24" v-if="!showCalendar">
                <div class="table-responsive">
                    <a-table
                        :row-selection="{
                            selectedRowKeys: table.selectedRowKeys,
                            onChange: onRowSelectChange,
                            getCheckboxProps: (record) => ({
                                disabled: false,
                                name: record.xid,
                            }),
                        }"
                        :columns="columns"
                        :row-key="(record) => record.xid"
                        :data-source="table.data"
                        :pagination="table.pagination"
                        :loading="table.loading"
                        @change="handleTableChange"
                        bordered
                        size="middle"
                    >
                        <template #title>
                            <a-row justify="end" align="center" class="table-header">
                                <a-col>
                                    <Filters @onReset="resetFilters">
                                        <a-col span="24">
                                            <a-form-item :label="$t('lead.campaign')">
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
                                            </a-form-item>
                                        </a-col>
                                        <a-col
                                            v-if="permsArray.includes('leads_view_all') || permsArray.includes('admin')"
                                            span="24"
                                        >
                                            <a-form-item :label="$t('lead_follow_up.assigned_to')">
                                                <UserSelect
                                                    @onChange="(id) => {
                                                        extraFilters.user_id = id;
                                                        setUrlData();
                                                    }"
                                                />
                                            </a-form-item>
                                        </a-col>
                                        <a-col span="24">
                                            <a-form-item :label="$t('common.date_range')">
                                                <DateRangePicker
                                                    @dateTimeChanged="
                                                        (changedDateTime) => {
                                                            extraFilters.dates = changedDateTime;
                                                            setUrlData();
                                                        }
                                                    "
                                                />
                                            </a-form-item>
                                        </a-col>
                                    </Filters>
                                </a-col>
                            </a-row>
                        </template>
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'reference_number'">
                                <a-button
                                    type="link"
                                    class="p-0!"
                                    @click="showViewDrawer(record)"
                                >
                                    {{
                                        record.reference_number != "" &&
                                        record.reference_number != undefined
                                            ? record.reference_number
                                            : "---"
                                    }}
                                </a-button>
                            </template>
                            <template v-if="column.dataIndex === 'date_time'">
                                {{ formatDateTime(record.individual_follow_up.date_time) }}
                            </template>
                            <template v-if="column.dataIndex === 'campaign'">
                                {{
                                    record.campaign && record.campaign.name
                                        ? record.campaign.name
                                        : "-"
                                }}
                            </template>
                            <template v-if="column.dataIndex === 'actioner'">
                                {{
                                    record.individual_follow_up &&
                                    record.individual_follow_up.user &&
                                    record.individual_follow_up.user.name
                                        ? record.individual_follow_up.user.name
                                        : "-"
                                }}
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-space>
                                    <a-tooltip
                                        v-if="record.individual_follow_up.x_user_id == user.xid"
                                        :title="$t('lead_follow_up.start_follow_up')"
                                    >
                                        <a-button
                                            type="primary"
                                            @click="startFollowUp(record)"
                                        >
                                            <template #icon>
                                                <DoubleRightOutlined />
                                            </template>
                                        </a-button>
                                    </a-tooltip>
                                    <DeleteBooking
                                        v-if="
                                            permsArray.includes('leads_view_all') ||
                                            permsArray.includes('admin')
                                        "
                                        bookingType="lead_follow_up"
                                        @success="setUrlData"
                                        :xIndividualId="record.xid"
                                        :showDeleteText="false"
                                    />
                                </a-space>
                            </template>
                        </template>
                    </a-table>
                </div>
            </a-col>
            <a-col v-else span="24">
                <a-calendar @select="onSelectCalendar">
                    <template #dateCellRender="{ current }">
                        <ul class="events">
                            <li v-for="item in calendarData">
                               <a-badge
                                    v-if="formatDate(item.individual_follow_up.date_time) == formatDate(current)"
                                    color="red"
                                    :text="`${formatTime(item.individual_follow_up.date_time)} ${item.first_name} ${item.last_name}`"
                                    @click="openFollowUpDetails(item)"
                                    /> 
                            </li>
                        </ul>
                    </template>
                </a-calendar>
            </a-col>
        </a-row>
    </admin-page-table-content>
    <a-modal 
        centered 
        v-model:open="isFollowUpDetailOpen" 
        :title="$t('lead_follow_up.follow_up_details')"
        :okText="$t('lead_follow_up.start_follow_up')"
        @ok="startFollowUp(selectedFollowUp)"
    >
        <a-row v-if="selectedFollowUp">
            <a-col span="24">
                <a-typography-title :level="5">
                    {{ $t('lead_follow_up.name') }}:
                </a-typography-title>
                <a-typography-text>
                    {{ selectedFollowUp.first_name }} {{ selectedFollowUp.last_name }}
                </a-typography-text>
            </a-col>
            <a-col span="24" class="mt-20">
                <a-typography-title :level="5">
                    {{ $t('lead_follow_up.assigned_to') }}:
                </a-typography-title>
                <a-typography-text v-if="selectedFollowUp.individual_follow_up">
                    {{ selectedFollowUp.individual_follow_up.user.name ? selectedFollowUp.individual_follow_up.user.name : '-' }}
                </a-typography-text>
            </a-col>
            <a-col span="24" class="mt-20">
                <a-typography-title :level="5">
                    {{ $t('lead_follow_up.date') }}:
                </a-typography-title>
                <a-typography-text v-if="selectedFollowUp.individual_follow_up">
                    {{ formatDateTime(selectedFollowUp.individual_follow_up.date_time) }}
                </a-typography-text>
            </a-col>
            <a-col span="24" class="mt-20">
                <a-typography-title :level="5">
                    {{ $t('lead_follow_up.note') }}:
                </a-typography-title>
                <a-typography-text v-if="selectedFollowUp.individual_follow_up">
                    {{ selectedFollowUp.individual_follow_up.notes ? selectedFollowUp.individual_follow_up.notes : '-' }}
                </a-typography-text>
            </a-col>
        </a-row>
    </a-modal>

    <!-- Global Compaonent -->
    <view-lead-details
        :visible="isViewDrawerVisible"
        :lead="viewDrawerData"
        @close="hideViewDrawer"
    />
</template>

<script>
import { ref, createVNode, onMounted, watch } from "vue";
import { Modal, notification } from "ant-design-vue";
import {
    DoubleRightOutlined,
    ExclamationCircleOutlined,
    DeleteOutlined,
    CalendarOutlined,
    TableOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { forEach } from "lodash-es";
import { useStore } from "vuex";
import apiAdmin from "../../../../common/composable/apiAdmin";
import datatable from "../../../../common/composable/datatable";
import common from "../../../../common/composable/common";
import viewDrawer from "../../../../common/composable/viewDrawer";
import fields from "./fields";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import DateRangePicker from "../../../../common/components/common/calendar/DateRangePicker.vue";
import DeleteBooking from "../DeleteBooking.vue";
import dayjs from 'dayjs';
import UserSelect from "../../../../common/components/common/select/UserSelect.vue";
import Filters from "../../../../common/components/common/select/Filters.vue";

export default {
    components: {
        DoubleRightOutlined,
        DeleteOutlined,
        AdminPageHeader,
        DateRangePicker,
        DeleteBooking,
        CalendarOutlined,
        TableOutlined,
        UserSelect,
        Filters,
    },
    methods: {
        openFollowUpDetails(item) {
            this.selectedFollowUp = item;
            this.isFollowUpDetailOpen = true;
        }
    },
    watch: {
        isFollowUpDetailOpen(val) {
            if (!val) {
                this.selectedFollowUp = [];
            }
        }
    },
    setup() {
        const { permsArray, getCampaignUrl, user, formatDateTime } = common();
        const { url, columns, hashableColumns } = fields();
        const { t } = useI18n();
        const store = useStore();
        const router = useRouter();
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const newTable = datatable();
        const extraFilters = ref({
            campaign_id: undefined,
            dates: [],
            user_id: undefined,
        });
        const allCampaigns = ref([]);
        const allUsers = ref([]);
        const leadDrawer = viewDrawer();
        const showCalendar = ref(false);
        const isFollowUpDetailOpen = ref(false);
        const selectedFollowUp = ref([]);
        const calendarData = ref([]);

        onMounted(() => {
            const campaignsUrl = getCampaignUrl();
            const campaignsPromise = axiosAdmin.get(campaignsUrl);
            const staffMembersPromise = axiosAdmin.get(
                "all-users?log_type=lead_follow_up"
            );

            Promise.all([campaignsPromise, staffMembersPromise]).then(
                ([campaignsResponse, staffMembersResponse]) => {
                    allCampaigns.value = campaignsResponse.data;
                    allUsers.value = staffMembersResponse.data.users;

                    extraFilters.value = {
                        ...extraFilters.value,
                        user_id: user.value.xid,
                    };
                    if (
                        permsArray.value.includes("leads_view_all") ||
                        permsArray.value.includes("admin")
                    ) {
                    }

                    newTable.hashable.value = [...hashableColumns];

                    setUrlData();
                }
            );
        });

        const formatDate = (dateTime) => {
            return dayjs(dateTime).format('DD MMM YYYY');
        };

        const formatTime = (dateTime) => {
            return dayjs(dateTime).format('hh:mm A');
        };

        const setUrlData = () => {
            newTable.tableUrl.value = {
                url,
                extraFilters,
            };
            newTable.hashable.value = [...hashableColumns];

            newTable.table.pagination = { ...newTable.table.pagination, current: 1 };
            newTable.fetch({
                page: 1,
            });
        };

        const onSelectCalendar = (date, { source }) => {
            const month = (dayjs(date).month() + 1) ?? dayjs().month() + 1;
            const year = (dayjs(date).year()) ?? dayjs().year();
            if(source == "month" || source == "year") {
                fetchCalendarData(month, year);
            }
        }

        const onCalendar = () => {
            if(calendarData.value.length == 0) {
                const month = dayjs().month() + 1;
                const year = dayjs().year();

                fetchCalendarData(month, year);
            }
        }

        const fetchCalendarData = (month, year) => {
            axiosAdmin.get(`${url}&month=${month}&year=${year}&limit=1000`).then((res) => {
                calendarData.value = res.data;
            });
        }

        const startFollowUp = (followUpDetails) => {
            Modal.confirm({
                title: t("common.start") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: t(`lead_follow_up.start_follow_up_message`),
                centered: true,
                okText: t("common.yes"),
                okType: "danger",
                cancelText: t("common.no"),
                onOk() {
                    addEditRequestAdmin({
                        url: `lead-follow-ups/take-action`,
                        data: {
                            x_individual_id: followUpDetails.xid,
                            x_lead_id: followUpDetails.lead.xid,
                            booking_type: "lead_follow_up",
                        },
                        success: (res) => {
                            if (res && res.lead_id) {
                                router.push({
                                    name: "admin.call_manager.take_action",
                                    params: {
                                        id: res.lead_id,
                                    },
                                });
                            }
                        },
                    });
                },
                onCancel() {},
            });
        };

        const showSelectedDeleteConfirm = () => {
            Modal.confirm({
                title: t("common.delete") + "?",
                icon: createVNode(ExclamationCircleOutlined),
                content: t(`lead_follow_up.selected_delete_message`),
                centered: true,
                okText: t("common.yes"),
                okType: "danger",
                cancelText: t("common.no"),
                onOk() {
                    const allDeletePromise = [];
                    forEach(newTable.table.selectedRowKeys, (selectedRow) => {
                        allDeletePromise.push(
                            axiosAdmin.post(`lead-follow-ups/delete`, {
                                x_individual_id: selectedRow,
                                booking_type: "lead_follow_up",
                            })
                        );
                    });

                    Promise.all(allDeletePromise).then((successResponse) => {
                        // Update Visible Subscription Modules
                        store.dispatch("auth/updateVisibleSubscriptionModules");

                        newTable.fetch({
                            page: newTable.currentPage.value,
                        });

                        notification.success({
                            message: t("common.success"),
                            description: t(`lead_follow_up.deleted`),
                            placement: "bottomRight",
                        });
                    });
                },
                onCancel() {},
            });
        };

        const resetFilters = () => {
            extraFilters.value = {
                campaign_id: undefined,
                dates: [],
                user_id: undefined,
            };
            setUrlData();
        };

        return {
            ...newTable,
            ...leadDrawer,
            url,
            columns,
            formatDateTime,
            hashableColumns,
            allCampaigns,
            allUsers,
            permsArray,
            extraFilters,
            user,

            startFollowUp,
            setUrlData,
            showSelectedDeleteConfirm,
            showCalendar,
            formatDate,
            formatTime,
            isFollowUpDetailOpen,
            onSelectCalendar,
            calendarData,
            onCalendar,
            resetFilters,
        };
    },
};
</script>

<style scoped>
.events {
  list-style: none;
  margin: 0;
  padding: 0;
}
.events .ant-badge-status {
  overflow: hidden;
  white-space: nowrap;
  width: 100%;
  text-overflow: ellipsis;
  font-size: 12px;
}
</style>
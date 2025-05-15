<template>
    <div class="right-timeline-div">
        <perfect-scrollbar
            :options="{
                wheelSpeed: 1,
                swipeEasing: true,
                suppressScrollX: true,
            }"
        >
            <a-timeline>
                <a-timeline-item v-for="allIndividualLog in allIndividualLogs" :key="allIndividualLog.xid">
                    <template #dot>
                        <PhoneOutlined 
                            v-if="allIndividualLog.log_type == 'call_log'" 
                        />
                        <MailOutlined 
                            class="emailIcon"
                            v-if="allIndividualLog.log_type == 'email'" 
                        />
                        <FileTextOutlined
                            class="noteIcon"
                            v-else-if="allIndividualLog.log_type == 'individual_notes' || 
                            allIndividualLog.log_type == 'updated_individual_notes' || 
                            allIndividualLog.log_type == 'deleted_individual_notes'" 
                        />
                        <UserOutlined 
                            class="userIcon"
                            v-else-if="allIndividualLog.log_type == 'updated_lead'||
                            allIndividualLog.log_type == 'co_applicant' ||
                            allIndividualLog.log_type == 'updated_co' ||
                            allIndividualLog.log_type == 'address' ||
                            allIndividualLog.log_type == 'co_address' ||
                            allIndividualLog.log_type == 'updated_address' ||
                            allIndividualLog.log_type == 'updated_co_address'
                            "
                        />
                        <ScheduleOutlined
                            class="scheduleIcon"
                            v-else-if="allIndividualLog.log_type == 'lead_follow_up'"
                        />
                        <ShoppingCartOutlined
                            class="salesmanBookingIcon"
                            v-else-if="allIndividualLog.log_type == 'salesman_bookings'"
                        />
                        <MessageOutlined
                            class="smsIcon"
                            v-else-if="allIndividualLog.log_type == 'sms'"
                         />
                         <FileOutlined 
                            class="docIcon"
                            v-else-if="allIndividualLog.log_type == 'doc' ||
                            allIndividualLog.log_type == 'deleted_doc' || 
                            allIndividualLog.log_type == 'generated_doc' ||
                            allIndividualLog.log_type == 'esign_doc' ||
                            allIndividualLog.log_type == 'voided_doc'"
                         />
                         <CreditCardOutlined 
                            class="cardIcon"
                            v-else-if="allIndividualLog.log_type == 'card' ||
                            allIndividualLog.log_type == 'updated_card'"
                        />
                        <BankOutlined 
                            class="bankIcon"
                            v-else-if="allIndividualLog.log_type == 'bank' ||
                            allIndividualLog.log_type == 'updated_bank'"
                        />
                    </template>
                    <span v-if="allIndividualLog.log_type == 'call_log'">
                        {{
                            $t("lead.call_log_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'updated_lead'">
                        {{ 
                            $t("lead.lead_timeline_message_updated", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'updated_co'">
                        {{ 
                            $t("lead.co_timeline_message_updated", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'address'">
                        {{ 
                            $t("lead.lead_address_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'co_address'">
                        {{ 
                            $t("lead.co_address_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'co_applicant'">
                        {{ 
                            $t("lead.co_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'updated_address'">
                        {{ 
                            $t("lead.lead_address_timeline_message_updated", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'updated_co_address'">
                        {{ 
                            $t("lead.co_address_timeline_message_updated", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    
                    <span v-else-if="allIndividualLog.log_type == 'email'">
                        {{
                            $t("lead.email_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-else-if="allIndividualLog.log_type == 'individual_notes'">
                        {{
                            $t("lead.notes_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-else-if="allIndividualLog.log_type == 'updated_individual_notes'">
                        {{
                            $t("lead.notes_timeline_message_updated", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-else-if="allIndividualLog.log_type == 'deleted_individual_notes'">
                        {{
                            $t("lead.notes_timeline_message_deleted", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-else-if="allIndividualLog.log_type == 'lead_follow_up'">
                        {{
                            $t("lead_follow_up.follow_up_timeline_message", [
                                allIndividualLog.creator.name,
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-else-if="allIndividualLog.log_type == 'salesman_bookings'">
                        {{
                            $t("salesman_booking.salesman_bookings_timeline_message", [
                                allIndividualLog.creator.name,
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'sms'">
                        {{
                            $t("sms.sms_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>

                    <span v-if="allIndividualLog.log_type == 'doc'">
                        {{
                            $t("docs.doc_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'deleted_doc'">
                        {{
                            $t("docs.doc_timeline_message_deleted", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'generated_doc'">
                        {{
                            $t("docs.generated_doc_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'esign_doc'">
                        {{
                            $t("docs.esign_doc_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'voided_doc'">
                        {{
                            $t("docs.voided_doc_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>

                    <span v-if="allIndividualLog.log_type == 'card'">
                        {{
                            $t("card.card_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'updated_card'">
                        {{
                            $t("card.card_timeline_message_updated", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    
                    <span v-if="allIndividualLog.log_type == 'bank'">
                        {{
                            $t("bank.bank_timeline_message", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                    <span v-if="allIndividualLog.log_type == 'updated_bank'">
                        {{
                            $t("bank.bank_timeline_message_updated", [
                                allIndividualLog.user.name,
                                formatDateTime(allIndividualLog.date_time),
                            ])
                        }}
                    </span>
                </a-timeline-item>
            </a-timeline>
        </perfect-scrollbar>
    </div>
    <div
        :style="{
            position: 'absolute',
            right: 0,
            bottom: 0,
            width: '100%',
            borderTop: '1px solid #e9e9e9',
            padding: '10px 16px',
            background: '#fff',
            zIndex: 1,
            textAlign: 'center',
        }"
    >
        <a-pagination v-model:current="currentPage" simple :total="totalRecords" />
    </div>
</template>

<script>
import { onMounted, ref, watch } from "vue";
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
    UserOutlined,
} from "@ant-design/icons-vue";
import common from "../../../common/composable/common";

export default {
    props: {
        individualId: {
            default: undefined,
        },
        refresh: {
            default: undefined,
        },
    },
    emits: ["dataFetched"],
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
        UserOutlined,
    },
    setup(props, { emit }) {
        const { formatDateTime } = common();
        const url = `individual-logs?fields=id,xid,log_type,time_taken,date_time,user_id,x_user_id,user{id,xid,name},created_by_id,x_created_by_id,creator{id,xid,name},individual{reference_number,lead_data},id,xid,individual{campaign_id,x_campaign_id},individual:campaign{id,xid,name}&log_type=all&page_name=lead_action&individual_id=${props.individualId}&order=id desc`;
        const allIndividualLogs = ref([]);
        const currentPage = ref(1);
        const totalRecords = ref(0);
        const perPageRecords = ref(10);

        onMounted(() => {
            fetchInitData(1);
        });

        const fetchInitData = (pageNumber) => {
            const offset = (pageNumber - 1) * perPageRecords.value;
            var urlPath = `${url}&offset=${offset}&limit=${perPageRecords.value}`;

            axiosAdmin.get(urlPath).then((response) => {
                allIndividualLogs.value = response.data;

                totalRecords.value = response.meta.paging.total;

                emit("dataFetched");
            });
        };

        watch(currentPage, (newValue, oldValue) => {
            fetchInitData(newValue);
        });

        watch(
            () => props.refresh,
            (newValue, oldValue) => {
                if (newValue == true) {
                    fetchInitData(1);
                }
            }
        );

        return {
            allIndividualLogs,
            formatDateTime,

            totalRecords,
            currentPage,
            perPageRecords,
        };
    },
};
</script>

<style>

    .callIcon {
        color: #1890ff;
    }
    .emailIcon {
        color: aquamarine;
    }

    .noteIcon {
        color: #d46b08;
    }
    
    .scheduleIcon {
        color: darkorange;
    }

    .salesmanBookingIcon {
        color: darkorchid;
    }

    .smsIcon {
        color: darkred;
    }

    .docIcon {
        color: #c41d7f;
    }

    .cardIcon {
        color:#d4380d;
    }

    .bankIcon {
        color: #d48806;
    }

    .userIcon {
        color: #7cb305;
    }

</style>

<style lang="less">
.right-timeline-div .ps {
    height: calc(100vh - 205px);
    padding: 5px;
    margin-top: 15px;
}
</style>

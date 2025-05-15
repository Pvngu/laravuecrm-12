import { ref, onBeforeMount } from "vue";
import { useI18n } from "vue-i18n";
import { forEach } from "lodash-es";
import common from "../../../common/composable/common";

const fields = (props) => {
    const { convertStringToKey, getCampaignUrl, user } = common();
    const leadUrl = "individual{id,xid,campaign_id,x_campaign_id,first_name,last_name,email,home_phone,phone_number,language,SSN,date_of_birth,original_profile_id,time_taken,first_action_by,x_first_action_by,last_action_by,x_last_action_by},individual:campaign{id,xid,name,status},individual:firstActioner{id,xid,name},individual:lastActioner{id,xid,name}";
    const formFieldNamesUrl = "form-field-names/all";
    const url = `individual-logs?fields=id,xid,time_taken,individual{reference_number,lead_data},log_type,date_time,user_id,x_user_id,user{id,xid,name,profile_image,profile_image_url},individual_id,x_individual_id,${leadUrl}`;
    const allFormFieldNames = ref([]);
    const hashableColumns = ['individual_id', 'campaign_id', 'user_id'];
    const { t } = useI18n();
    const columns = ref([]);
    const allCampaigns = ref([]);
    const filterableColumns = [];

    const getPrefetchData = () => {
        const campaignsUrl = getCampaignUrl();
        const campaignsPromise = axiosAdmin.get(campaignsUrl);
        const formFieldNamesPromise = axiosAdmin.get(formFieldNamesUrl);

        return Promise.all([formFieldNamesPromise, campaignsPromise]).then(([formFieldNamesResponse, campaignsResponse]) => {
            allFormFieldNames.value = formFieldNamesResponse.data.data;
            allCampaigns.value = campaignsResponse.data;

            var newColumnsArray = [];

            if (props.showLeadDetails) {
                var newColumnsArray = [
                    {
                        title: t("lead.reference_number"),
                        dataIndex: "reference_number",
                    },
                    {
                        title: t("lead.campaign"),
                        dataIndex: "campaign",
                    },
                ];

                // Showing form fields if props.showFormFields
                // sets to true
                if (props.showFormFields) {
                    forEach(formFieldNamesResponse.data.data, (formFieldName) => {
                        newColumnsArray.push({
                            title: formFieldName.field_name,
                            dataIndex: convertStringToKey(formFieldName.field_name),
                        });
                    });
                }

            }

            if (props.logType == 'call_log') {
                newColumnsArray.push(
                    {
                        title: t("lead.call_duration"),
                        dataIndex: "time_taken",
                    }
                );
            }

            var dateTimeColumnTrans = t("lead.called_on");
            var userColumnTrans = t("lead.call_by");
            if (props.logType == 'lead_follow_up') {
                dateTimeColumnTrans = t("lead.follow_up_time");
            } else if (props.logType == 'salesman_booking') {
                dateTimeColumnTrans = t("lead.booking_time");
                userColumnTrans = t("menu.salesman");
            } else if (props.logType == 'notes') {
                dateTimeColumnTrans = t("lead.added_on");
                userColumnTrans = t("lead.added_by");
            }

            if (props.logType == 'notes') {
                newColumnsArray.push(
                    {
                        title: t("common.notes"),
                        dataIndex: "notes",
                    }
                );
            }

            newColumnsArray = [
                ...newColumnsArray,
                {
                    title: dateTimeColumnTrans,
                    dataIndex: "date_time",
                },
                {
                    title: userColumnTrans,
                    dataIndex: "actioner",
                },
            ];

            if (props.showAction) {
                newColumnsArray.push(
                    {
                        title: t('common.action'),
                        dataIndex: "action",
                    }
                );
            }

            columns.value = newColumnsArray;
        });
    };

    return {
        url,
        columns,
        filterableColumns,
        hashableColumns,
        allFormFieldNames,
        allCampaigns,
        getPrefetchData,
    }
}

export default fields;

import { ref } from "vue";
import { useI18n } from "vue-i18n";
import common from "../../../common/composable/common";

const fields = (props) => {
    const { getCampaignUrl } = common();
    const leadUrl = "individual{id,xid,campaign_id,x_campaign_id,reference_number,first_name,last_name,email,home_phone,phone_number,language,SSN,date_of_birth,original_profile_id,lead_data,time_taken,first_action_by,x_first_action_by,last_action_by,x_last_action_by},individual:campaign{id,xid,name,status},individual:firstActioner{id,xid,name},individual:lastActioner{id,xid,name}";
    const formFieldNamesUrl = "form-field-names/all";
    const url = `individual-logs?fields=id,xid,time_taken,notes,log_type,date_time,user_id,x_user_id,user{id,xid,name,profile_image,profile_image_url},individual_id,x_individual_id,${props.showIndividualDetails ? leadUrl : ''}`;
    const allFormFieldNames = ref([]);
    const hashableColumns = ['individual_id', 'campaign_id', 'user_id'];
    const { t } = useI18n();
    const allCampaigns = ref([]);
    const columns = ref([]);

    const filterableColumns = [
        {
            key: "reference_number",
            value: t("activity_log.contact_reference"),
        }
    ];

    const getPrefetchData = () => {
        const campaignsUrl = getCampaignUrl();
        const campaignsPromise = axiosAdmin.get(campaignsUrl);
        const formFieldNamesPromise = axiosAdmin.get(formFieldNamesUrl);

        var newColumnsArray = [];

        newColumnsArray = [
            {
                title: t("activity_log.timestamp"),
                dataIndex: "timestamp",
            },
        ]

        if(props.showIndividualDetails) {
            newColumnsArray.push({
                title: t("activity_log.contact_reference"),
                dataIndex: "reference_number",
            });
        }

        newColumnsArray.push(
            {
                title: t("activity_log.user"),
                dataIndex: "user",
            },
            {
                title: t("activity_log.entity"),
                dataIndex: "entity",
            },
            {
                title: t("activity_log.details"),
                dataIndex: "details",
        });

        columns.value = newColumnsArray;

        return Promise.all([formFieldNamesPromise, campaignsPromise]).then(([formFieldNamesResponse, campaignsResponse]) => {
            allFormFieldNames.value = formFieldNamesResponse.data.data;
            allCampaigns.value = campaignsResponse.data;
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
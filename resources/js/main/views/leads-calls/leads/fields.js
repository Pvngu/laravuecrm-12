import { ref, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import { forEach } from "lodash-es";
import common from "../../../../common/composable/common";

const fields = () => {
    const { convertStringToKey, getCampaignUrl, getCampaignStatsUrl } = common();
    const url = "leads?fields=id,xid,individual{id,xid,reference_number,first_name,last_name,home_phone,phone_number,email,SSN,date_of_birth,language,original_profile_id,lead_data,campaign_id,x_campaign_id,time_taken,first_action_by,x_first_action_by,last_action_by,x_last_action_by},lead_status,started,individual:campaign{id,xid,name,status},individual:firstActioner{id,xid,name},individual:lastActioner{id,xid,name}";
    const addEditUrl = "leads";
    const hashableColumns = ['campaign_id'];
    const { t } = useI18n();
    const allFormFieldNames = ref([]);
    const allCampaigns = ref([]);
    const formFieldNamesUrl = "form-field-names/all";
    const campaignStats = ref({});
    const userCampaigns = ref([]);
    const viewType = ref("self");
    const activeCampaignType = ref("active");
    const leadStatuses = ref([]);

    const initData = {
        reference_number: "",
        first_name: "",
    };

    const columns = ref([]);

    const filterableColumns = [
        {
            key: "reference_number",
            value: t("lead.reference_number")
        },
        {
            key: "first_name",
            value: t("lead.first_name")
        },
        {
            key: "last_name",
            value: t("lead.last_name")
        }
    ];

    onMounted(() => {
        const campaignsUrl = getCampaignUrl(activeCampaignType.value, viewType.value);
        const campaignStatsUrl = getCampaignStatsUrl();
        const formFieldNamesPromise = axiosAdmin.get(formFieldNamesUrl);
        const campaignsPromise = axiosAdmin.get(campaignsUrl);
        const campaignStatsPromise = axiosAdmin.get(campaignStatsUrl);
        const userCampaignsPromise = axiosAdmin.get('campaigns/user-campaigns');
        const leadStatusesPromise = axiosAdmin.get('lead-statuses?fields=id,name');

        Promise.all([formFieldNamesPromise, campaignsPromise, campaignStatsPromise, userCampaignsPromise, leadStatusesPromise])
            .then(([formFieldNamesResponse, campaignsResponse, campaignStatsResponse, userCampaignsResponse, leadStatusesResponse]) => {
                allFormFieldNames.value = formFieldNamesResponse.data.data;
                allCampaigns.value = campaignsResponse.data;
                campaignStats.value = campaignStatsResponse.data;
                userCampaigns.value = userCampaignsResponse.data.user_campaigns;
                leadStatuses.value = leadStatusesResponse.data;

                var newColumnsArray = [
                    {
                        title: t("lead.reference_number"),
                        dataIndex: "reference_number",
                    },
                    {
                        title: t("lead.campaign"),
                        dataIndex: "campaign",
                    },
                    {
                        title: t("lead.full_name"),
                        dataIndex: "full_name",
                    },
                    {
                        title: t("lead.phone_number"),
                        dataIndex: "phone_number",
                        customRender: ({ record }) => record.individual.phone_number,
                    },
                    {
                        title: t("lead.email"),
                        dataIndex: "email",
                        customRender: ({ record }) => record.individual.email,
                    },
                ];

                forEach(formFieldNamesResponse.data.data, (formFieldName) => {
                    newColumnsArray.push({
                        title: formFieldName.field_name,
                        dataIndex: convertStringToKey(formFieldName.field_name),
                    });
                });

                newColumnsArray.push({
                    title: t("common.action"),
                    dataIndex: "action",
                });

                columns.value = newColumnsArray;
            });
    });

    return {
        url,
        addEditUrl,
        initData,
        columns,
        filterableColumns,
        hashableColumns,

        allFormFieldNames,
        convertStringToKey,
        allCampaigns,
        campaignStats,
        userCampaigns,

        activeCampaignType,
        viewType,
        
        leadStatuses,
    }
}

export default fields;

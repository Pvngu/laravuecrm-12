import { ref } from "vue";
import { useI18n } from "vue-i18n";
import { forEach } from "lodash-es";
import common from "../../../common/composable/common";

const fields = (props) => {
    const { convertStringToKey, getCampaignUrl } = common();
    const leadUrl = "individual{id,xid,reference_number,lead_data,first_name,last_name,email,home_phone,phone_number,language,SSN,date_of_birth,original_profile_id,campaign_id,x_campaign_id,time_taken,first_action_by,x_first_action_by,last_action_by,x_last_action_by},individual:campaign{id,xid,name,status},individual:firstActioner{id,xid,name},individual:lastActioner{id,xid,name}";
    const formFieldNamesUrl = "form-field-names/all";
    const url = `notes?fields=id,xid,alert_type,created_at,created_by_id,x_created_by_id,content,creator{id,xid,name,profile_image,profile_image_url},${leadUrl}`;
    const allFormFieldNames = ref([]);
    const addEditUrl = "notes";
    const hashableColumns = ['individual_id', 'campaign_id', 'created_by_id'];
    const { t } = useI18n();
    const initData = {
        content: "",
        note_type: "notes",
        alert_type: "",
    };
    const columns = ref([]);
    const allCampaigns = ref([]);
    const allUsers = ref([]);

    const filterableColumns = [];

    const getPrefetchData = () => {
        const campaignsUrl = getCampaignUrl();
        const campaignsPromise = axiosAdmin.get(campaignsUrl);
        const formFieldNamesPromise = axiosAdmin.get(formFieldNamesUrl);
        const staffMembersPromise = axiosAdmin.get(`all-users?log_type=${props.logType}`);

        return Promise.all([formFieldNamesPromise, campaignsPromise, staffMembersPromise]).then(([formFieldNamesResponse, campaignsResponse, staffMembersResponse]) => {
            allFormFieldNames.value = formFieldNamesResponse.data.data;
            allCampaigns.value = campaignsResponse.data;
            allUsers.value = staffMembersResponse.data.users;

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

            newColumnsArray.push({
                title: t("common.notes"),
                dataIndex: "notes",
                width: props.showLeadDetails && props.showFormFields ? '40%' : '80%',
            });

            if (props.showActionButton) {
                newColumnsArray.push({
                    title: t('common.action'),
                    dataIndex: "action",
                });
            }

            columns.value = newColumnsArray;
        });
    };

    return {
        url,
        addEditUrl,
        initData,
        columns,
        filterableColumns,
        hashableColumns,
        allFormFieldNames,
        allCampaigns,
        allUsers,
        getPrefetchData,
    }
}

export default fields;

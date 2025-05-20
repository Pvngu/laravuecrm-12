import { useI18n } from 'vue-i18n';
import { ref } from "vue";
import common from "../../../common/composable/common";

const fields = () => {
    const { getCampaignUrl } = common();
    const { t } = useI18n();
    const url = 'sales?fields=id,xid,saleStatus{id,xid,name},individual{id,xid,reference_number,first_name,last_name,full_name,home_phone,phone_number,email,SSN,date_of_birth,language,original_profile_id,lead_data,template_form,full_address,updated_at,created_at},individual:coApplicant{id,xid,first_name,last_name,SSN,date_of_birth,home_phone,phone_number,email,language},assignedUser{id,xid,name},individual:address';
    const hashableColumns = [
        'assigned_to',
        'campaign_id',
        'sale_status_id',
    ];
    const addEditUrl = 'sales';
    const allUsers = ref([]);
    const allCampaigns = ref([]);
    const saleStatuses = ref([]);
    const allEmailTemplates = ref([]);

    const getPrefetchData = () => {
        const staffMembersPromise = axiosAdmin.get('all-users?log_type=staff_members');
        const saleStatusesPromise = axiosAdmin.get('sale-statuses?limit=1000');
        const emailTemplatesPromise = axiosAdmin.get('email-templates/all');
        const campaignsPromise = axiosAdmin.get(getCampaignUrl());
        
        return Promise.all([staffMembersPromise, campaignsPromise, saleStatusesPromise, emailTemplatesPromise]).then(([staffMembersResponse, campaignsResponse, saleStatusesResponse, emailTemplatesResponse]) => {
            allUsers.value = staffMembersResponse.data.users;
            allCampaigns.value = campaignsResponse.data;
            saleStatuses.value = saleStatusesResponse.data;
            allEmailTemplates.value = emailTemplatesResponse.data.email_templates;
        });
    }
    
    const columns = [
        {
            title: t('common.name'),
            dataIndex: 'name',
            customRender: ({ record }) => record.individual?.full_name || ''
        },
        {
            title: t('common.phone'),
            dataIndex: 'phone',
            customRender: ({ record }) => record.individual?.phone_number || ''
        },
        {
            title: t('common.address'),
            dataIndex: 'address',
            width: '80px',
            customRender: ({ record }) => record.individual?.full_address || ''
        },
        {
            title: t('lead.date_of_birth'),
            dataIndex: 'date_of_birth',
            customRender: ({ record }) => record.individual?.date_of_birth || ''
        },
        {
            title: t('lead.email'),
            dataIndex: 'email'
        },
        {
            title: t('common.last_activity'),
            dataIndex: 'updated_at',
        },
        {
            title: t('common.registration'),
            dataIndex: 'created_at',
        },
        {
            title: t('sales.action'),
            dataIndex: 'action',
        }
    ];

    const filterableColumns = [
        {
            key: 'reference_number',
            value: t('lead.reference_number')
        },
        {
            key: 'full_name',
            value: t('lead.full_name')
        },
        {
            key: 'first_name',
            value: t('lead.first_name')
        },
        {
            key: 'last_name',
            value: t('lead.last_name')
        },
        {
            key: 'home_phone',
            value: t('lead.home_phone')
        },
        {
            key: 'phone_number',
            value: t('lead.phone_number')
        },
        {
            key: 'email',
            value: t('lead.email')
        },
        {
            key: 'SSN',
            value: t('lead.SSN')
        },
    ];

    return {
        url,
        addEditUrl,
        columns,
        allUsers,
        getPrefetchData,
        filterableColumns,
        hashableColumns,
        allCampaigns,
        saleStatuses,
        allEmailTemplates
    }
}

export default fields;
import { useI18n } from "vue-i18n";

const fields = () => {
    const IndividualLogUrl = "salesmanBooking{id,xid,log_type,time_taken,date_time,individual_id,x_individual_id,user_id,x_user_id,created_by_id,x_created_by_id},salesmanBooking:user{id,xid,name,profile_image,profile_image_url}";
    const url = `salesman-bookings?fields=id,xid,reference_number,first_name,last_name,email,home_phone,phone_number,language,SSN,date_of_birth,original_profile_id,lead_data,campaign_id,x_campaign_id,campaign{id,xid,name,status},time_taken,first_action_by,x_first_action_by,firstActioner{id,xid,name},last_action_by,x_last_action_by,lastActioner{id,xid,name},salesman_booking_id,x_salesman_booking_id,${IndividualLogUrl}`;

    const hashableColumns = ['individual_id', 'campaign_id', 'user_id'];
    const { t } = useI18n();

    const columns = [
        {
            title: t("lead.reference_number"),
            dataIndex: "reference_number",
        },
        {
            title: t("lead.campaign"),
            dataIndex: "campaign",
        },
        {
            title: t("lead.booking_time"),
            dataIndex: "date_time",
        },
        {
            title: t("salesman.salesman"),
            dataIndex: "actioner",
        },
        {
            title: t('common.action'),
            dataIndex: "action",
        }
    ];

    const filterableColumns = [];

    return {
        url,
        columns,
        filterableColumns,
        hashableColumns,
    }
}

export default fields;

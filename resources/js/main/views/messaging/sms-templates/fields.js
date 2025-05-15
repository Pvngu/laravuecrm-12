import { useI18n } from "vue-i18n";

const fields = () => {
    const url = "sms-templates?fields=id,xid,name,body,status,sharable";
    const addEditUrl = "sms-templates";
    const hashableColumns = [];
    const { t } = useI18n();

    const initData = {
        name: "",
        body: "",
        status: 1,
        sharable: 0
    };

    const columns = [
        {
            title: t("sms_template.name"),
            dataIndex: "name",
            width: '75%'
        },
        {
            title: t("sms_template.status"),
            dataIndex: "status",
        },
        {
            title: t("common.action"),
            dataIndex: "action",
        },
    ];

    const filterableColumns = [
        {
            key: "name",
            value: t("sms_template.name")
        },
    ];

    return {
        url,
        addEditUrl,
        initData,
        columns,
        filterableColumns,
        hashableColumns,
    }
}

export default fields;

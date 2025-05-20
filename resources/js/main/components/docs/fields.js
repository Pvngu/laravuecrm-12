import { useI18n } from "vue-i18n";

const fields = () => {
    const { t } = useI18n();
    const addEditUrl = "documents";

    const initData = {
        title: "",
        file: undefined,
        file_url: undefined,
    };

    const columns = [
        { 
            title: t("common.name"), 
            dataIndex: "name", 
            key: "name" 
        },
        {
            title: t("docs.uploaded_by"),
            dataIndex: "uploaded_by",
            key: "uploaded_by",
        },
        { 
            title: t("docs.size"), 
            dataIndex: "size", 
            key: "size" 
        },
        {
            title: t("docs.uploaded_at"),
            dataIndex: "created_at",
            key: "created_at",
        },
        { 
            dataIndex: "action", 
            key: "action", 
            width: 20 
        },
    ];

    return {
        initData,
        columns,
        addEditUrl,
    };
};

export default fields;

import { useI18n } from "vue-i18n";

const fields = () => {
    const url = "documents?fields=id,xid,individual_id,x_individual_id,name,file,file_url,file_type,file_size,created_at,creator";
    const hashableColumns = ["individual_id"];
    const addEditUrl = "documents";
    
    const { t } = useI18n();
    
    const initData = {
        name: "",
        file_path: "",
        file_type: "",
        file_size: "",
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
            dataIndex: "file_size", 
            key: "file_size" 
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

    const filterableColumns = [
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
            title: t("docs.uploaded_at"),
            dataIndex: "created_at",
            key: "created_at",
        },
    ]

    return {
        url,
        addEditUrl,
        initData,
        columns,
        hashableColumns,
        filterableColumns,
    };
};

export default fields;

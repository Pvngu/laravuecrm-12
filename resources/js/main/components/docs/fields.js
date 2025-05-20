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
        { title: "", dataIndex: "icon", key: "icon", width: 40 },
        { title: t("docs.title"), dataIndex: "name", key: "name" },
        {
            title: t("common.created_by"),
            dataIndex: "uploaded_by",
            key: "uploaded_by",
        },
        { title: t("docs.size"), dataIndex: "size", key: "size" },
        {
            title: t("docs.created_at"),
            dataIndex: "created_at",
            key: "created_at",
        },
        {
            title: t("common.action"),
            dataIndex: "action",
            key: "action",
            width: 80,
        },
    ];

    return {
        initData,
        columns,
        addEditUrl,
    };
};

export default fields;

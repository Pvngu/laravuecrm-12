<template>
    <div v-if="permsArray.includes('users_create') || permsArray.includes('admin')">
        <template v-if="customType == 'menu'">
            <a-menu-item @click="showAdd" key="staff_members">
                <template #icon>
                    <slot name="icon"></slot>
                </template>
                <slot></slot>
            </a-menu-item>
        </template>
        <template v-else>
            <a-tooltip
                v-if="tooltip"
                placement="topLeft"
                :title="$t('user.add')"
                arrow-point-at-center
            >
                <a-button @click="showAdd" class="ml-1 no-border-radius" :type="btnType">
                    <template #icon> <PlusOutlined /> </template>
                    <slot></slot>
                </a-button>
            </a-tooltip>
            <a-button
                v-else
                @click="showAdd"
                class="ml-1 no-border-radius"
                :type="btnType"
            >
                <template #icon> <PlusOutlined /> </template>
                <slot></slot>
            </a-button>
        </template>

        <AddEdit
            :addEditType="addEditType"
            :visible="visible"
            :url="addEditUrl"
            @addEditSuccess="addEditSuccess"
            @closed="onClose"
            :formData="formData"
            :data="formData"
            :pageTitle="$t('staff_member.add')"
            :successMessage="$t('staff_member.created')"
        />
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import { PlusOutlined } from "@ant-design/icons-vue";
import AddEdit from "./AddEdit.vue";
import fields from "./fields";
import common from "../../../common/composable/common";

export default defineComponent({
    props: {
        btnType: {
            default: "default",
        },
        tooltip: {
            default: true,
        },
        customType: {
            default: "button",
        },
    },
    emits: ["onAddSuccess"],
    components: {
        PlusOutlined,
        AddEdit,
    },
    setup(props, { emit }) {
        const { permsArray } = common();
        const { initData, addEditUrl } = fields();
        const visible = ref(false);
        const addEditType = ref("add");
        const formData = ref({ ...initData });

        const showAdd = () => {
            visible.value = true;
        };

        const addEditSuccess = () => {
            visible.value = false;
            formData.value = { ...initData };
            emit("onAddSuccess");
        };

        const onClose = () => {
            visible.value = false;
        };

        return {
            permsArray,
            visible,
            addEditType,
            addEditUrl,
            formData,

            addEditSuccess,
            onClose,
            showAdd,
        };
    },
});
</script>

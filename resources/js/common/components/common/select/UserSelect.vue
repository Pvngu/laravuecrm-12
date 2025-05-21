<template>
    <a-select
        v-model:value="model"
        :placeholder="$t('common.select_default_text', [$t('user.user')])"
        :allowClear="true"
        style="width: 100%"
        :dropdown-match-select-width="false"
        optionFilterProp="title"
        show-search
        @change="onChange"
        :disabled="disabled"
    >
        <a-select-opt-group v-for="( users, role ) in allUsers" :key="role" :label="role">
            <a-select-option
                v-for="user in users"
                :key="user.xid"
                :title="user.name"
                :value="user.xid"
                :disabled="disableDisabledUsers && user.status === 'disabled' || user.xid === currentUserId"
                :class="{ warningSelect: showDisabledUserWarning && user.assigned_sales_count > 0 && user.status === 'disabled' }"
            >
                <a-row>
                    <a-col>
                        <a-avatar :src="user.profile_image_url" :size="20" />
                    </a-col>
                    <a-col class="ml-5">
                        {{ user.name }} <span v-if="showAssignedSalesCount">({{ user.assigned_sales_count }})</span>
                    </a-col>
                </a-row>
            </a-select-option>
        </a-select-opt-group>
    </a-select>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
    disabled: {
        default: false
    },
    showDisabledUserWarning: {
        default: false
    },
    showAssignedSalesCount: {
        default: false
    },
    disableDisabledUsers: {
        default: false
    },
    currentUserId: {
        default: undefined
    },
    fetchUserData: {
        default: true
    },
    data: {
        default: null
    }
});

const emit = defineEmits(['onChange']);
const model = defineModel();
const usersUrl = 'all-users?log_type=staff_members';
const allUsers = ref({});

const onChange = (id) => {
    emit('onChange', id);
};

onMounted(() => {
    if (props.fetchUserData) {
        axiosAdmin.get(usersUrl).then((res) => {
            allUsers.value = res.data.users;
        });
    } else if (props.data && Object.keys(props.data).length > 0) {
        allUsers.value = props.data;
    }
});

watch(() => props.data, (newValue) => {
    if (newValue) {
        allUsers.value = newValue;
    }
});
</script>

<style>
    .warningSelect {
        background-color: #fffbe6;
        color: #faad14 !important
    }
</style>
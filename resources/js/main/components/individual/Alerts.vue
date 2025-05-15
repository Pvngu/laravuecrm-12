<template>
    <a-space direction="vertical" style="width: 100%">
        <a-alert v-if="language" :message="language + ' Speaker'" type="info" show-icon />
        <a-alert v-for="alert in alerts" :message="alert.content" :type="alertStatusTypes[alert.alert_type]" show-icon>
            <template v-if="alert.alert_type == 'urgent'" #icon><ExclamationCircleFilled /></template>
        </a-alert>
    </a-space>
</template>

<script>
import { onMounted, ref } from 'vue';
import common from '../../../common/composable/common';
import { ExclamationCircleFilled } from '@ant-design/icons-vue';
export default {
    props: {
        individualId: {
            default: undefined
        },
        language: {
            default: undefined
        }
    },
    components: {
        ExclamationCircleFilled
    },
    methods: {
        refreshAlerts(data) {
            const index = this.alerts.findIndex(alert => (alert.xid === data.id) || (alert.id === data.id));

            if(index == -1 && this.alerts.length < 4) {
                this.alerts.push(data);
            } else {
                this.alerts[index] = data;
            }
        }
    },
    setup(props) {
        const alerts = ref([]);
        const { alertStatusTypes } = common();

        onMounted(() => {
            fetchNotes(props.individualId);
        });

        const fetchNotes = (id) => {
            alerts.value = {};

            const leadNoteUrl = `notes?fields=xid,content,alert_type&individual_id=${id}&only_alerts=true&offset=0&limit=4`;

            axiosAdmin.get(leadNoteUrl).then((response) => {
                alerts.value = response.data;
            });
        };

        return {
            alerts,
            alertStatusTypes,
            fetchNotes
        }
    }
}
</script>
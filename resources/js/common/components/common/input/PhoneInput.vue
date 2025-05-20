<template>
    <vue-tel-input
        v-model="model"
        :placeholder="$t('common.phone_number')"
        :inputOptions="{
            showDialCode: true,
            mode: 'international',
            formatOnDisplay: false
        }"
        :dropdownOptions="{
            showDialCodeInSelection: false,
            showFlags: true
        }"
        :disabled="disabled"
        :onlyCountries="['US', 'CA', 'MX']"
    />
</template>

<script setup>
import { VueTelInput } from "vue-tel-input";
import 'vue-tel-input/vue-tel-input.css';

const props = defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
});

const model = defineModel()
</script>

<style>
.vue-tel-input {
    border-radius: 8px !important;
}
.vue-tel-input .vti__input {
    border-radius: 8px !important;
}
</style>
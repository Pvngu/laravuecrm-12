<template>
    <vue-tel-input
        v-model="model"
        :placeholder="$t('common.phone_number')"
        :inputOptions="{
            mode: 'national',
            formatOnDisplay: false,
        }"
        :dropdownOptions="{
            showDialCodeInSelection: false,
            showFlags: true
        }"
        :disabled="disabled"
        :onlyCountries="['US']"
        defaultCountry="US"
        @on-input="(no, obj) => {
            model = obj.nationalNumber;
        }"
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
.vue-tel-input,
.vue-tel-input .vti__input,
.vti__dropdown-list.below
{
    border-radius: 8px !important;
}

.vti__dropdown-list.below {
    margin-top: 4px !important;
}
</style>
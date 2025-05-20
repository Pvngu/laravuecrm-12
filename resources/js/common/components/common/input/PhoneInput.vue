<template>
    <vue-tel-input
        v-model="internalValue"
        :placeholder="placeholder"
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
        @input="onInput"
    />
</template>

<script setup>
import { ref, watch } from "vue";
import { VueTelInput } from "vue-tel-input";
import 'vue-tel-input/vue-tel-input.css';

const props = defineProps({
    value: {
        type: [String, Number, Object],
        default: "",
    },
    placeholder: {
        type: String,
        default: "",
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:value"]);

const internalValue = ref(props.value);

watch(
    () => props.value,
    (val) => {
        if (val !== internalValue.value) internalValue.value = val;
    }
);

// Emit the full phone object on input
function onInput(phoneObj) {
    emit("update:value", phoneObj);
}
</script>

<style>
.vue-tel-input {
    border-radius: 8px !important;
}
.vue-tel-input .vti__input {
    border-radius: 8px !important;
}
</style>
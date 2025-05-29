<template>
    <a-tabs v-model:activeKey="activeTab" :class="[ {'details-tabs' : !isSale } ]" type="card">
        <a-tab-pane key="details" :tab="$t('common.details')">
            <template #tab>
                <span>
                    <FileTextOutlined />
                    {{ $t("common.details") }}
                </span>
            </template>
            <!-- Details Tab Content -->
            <a-form layout="vertical">
                <perfect-scrollbar
                    :options="{
                        wheelSpeed: 1,
                        swipeEasing: true,
                        suppressScrollX: true,
                    }"
                    :class="{ 'callmanager-details': !isSale }"
                >
                    <a-row :gutter="16" justify="end" v-if="isSale">
                <a-col :xs="24" :sm="24" :md="6" :lg="6">
                    <a-form-item
                        :label="$t('sales.assigned_to')"
                        name="assigned_to"
                        :help="
                            rules.assigned_to ? rules.assigned_to.message : null
                        "
                        :validateStatus="rules.assigned_to ? 'error' : null"
                    >
                        <UserSelect
                            v-model="formData.assigned_to"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.reference_number')"
                        name="reference_number"
                        :help="
                            rules.reference_number
                                ? rules.reference_number.message
                                : null
                        "
                        :validateStatus="
                            rules.reference_number ? 'error' : null
                        "
                        class="required"
                    >
                        <a-input
                            v-model:value="formData.reference_number"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.reference_number'),
                                ])
                            "
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('sales.sale_status')"
                        name="sale_status"
                        :help="
                            rules.sale_status ? rules.sale_status.message : null
                        "
                        :validateStatus="rules.sale_status ? 'error' : null"
                        v-if="isSale"
                    >
                        <a-select
                            v-model:value="formData.sale_status_id"
                            :placeholder="
                                $t('common.select_default_text', [
                                    $t('sales.sale_status'),
                                ])
                            "
                        >
                            <a-select-option
                                v-for="status in statuses"
                                :key="status.id"
                                :value="status.id"
                                :title="status.name"
                            >
                                {{ status.name }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                    <a-form-item
                        :label="$t('lead.lead_status')"
                        name="lead_status"
                        :help="
                            rules.lead_status ? rules.lead_status.message : null
                        "
                        :validateStatus="rules.lead_status ? 'error' : null"
                        v-else
                    >
                        <a-select
                            v-model:value="formData.lead_status"
                            :placeholder="
                                $t('common.select_default_text', [
                                    $t('lead.lead_status'),
                                ])
                            "
                        >
                            <a-select-option
                                v-for="status in statuses"
                                :key="status.id"
                                :value="status.id"
                                :title="status.name"
                            >
                                {{ status.name }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16" v-if="coApplicantRequired">
                <a-col :xs="24" :sm="24" :md="12" :lg="12" align="center">
                    <a-typography-text strong>
                        {{ $t("lead.applicant") }}
                    </a-typography-text>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" align="center">
                    <a-typography-text strong>
                        {{ $t("lead.co_applicant") }}
                    </a-typography-text>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.first_name')"
                        name="first_name"
                        :help="rules.first_name ? rules.first_name.message : null"
                        :validateStatus="rules.first_name ? 'error' : null"
                    >
                        <a-input
                            v-model:value="formData.first_name"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.first_name'),
                                ])
                            "
                        ></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" v-if="coApplicantRequired">
                    <a-form-item
                        :label="$t('lead.first_name')"
                        name="co_first_name"
                        :help="rules.co_first_name ? rules.co_first_name.message : null"
                        :validateStatus="rules.co_first_name ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-input
                            v-model:value="formData.co_first_name"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.first_name'),
                                ])
                            "
                        ></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.last_name')"
                        name="last_name"
                        :help="rules.last_name ? rules.last_name.message : null"
                        :validateStatus="rules.last_name ? 'error' : null"
                    >
                        <a-input
                            v-model:value="formData.last_name"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.last_name'),
                                ])
                            "
                        ></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" v-if="coApplicantRequired">
                    <a-form-item
                        :label="$t('lead.last_name')"
                        name="co_last_name"
                        :help="rules.co_last_name ? rules.co_last_name.message : null"
                        :validateStatus="rules.co_last_name ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-input
                            v-model:value="formData.co_last_name"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.last_name'),
                                ])
                            "
                        ></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.SSN')"
                        name="SSN"
                        :help="rules.SSN ? rules.SSN.message : null"
                        :validateStatus="rules.SSN ? 'error' : null"
                    >
                        <a-input
                            v-model:value="formData.SSN"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.SSN'),
                                ])
                            "
                        ></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" v-if="coApplicantRequired">
                    <a-form-item
                        :label="$t('lead.SSN')"
                        name="co_SSN"
                        :help="rules.co_SSN ? rules.co_SSN.message : null"
                        :validateStatus="rules.co_SSN ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-input
                            v-model:value="formData.co_SSN"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.SSN'),
                                ])
                            "
                        ></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.date_of_birth')"
                        name="date_of_birth"
                        :help="rules.date_of_birth ? rules.date_of_birth.message : null"
                        :validateStatus="rules.date_of_birth ? 'error' : null"
                    >
                        <DateTimePicker
                            :dateTime="formData.date_of_birth"
                            :isFutureDateDisabled="false"
                            :showTime="false"
                            :onlyDate="true"
                            @dateTimeChanged="
                                (changedDateTime) =>
                                    (formData.date_of_birth = changedDateTime)
                            "
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" v-if="coApplicantRequired">
                    <a-form-item
                        :label="$t('lead.date_of_birth')"
                        name="co_date_of_birth"
                        :help="rules.co_date_of_birth ? rules.co_date_of_birth.message : null"
                        :validateStatus="rules.co_date_of_birth ? 'error' : null"
                        class="hidden-label"
                    >
                        <DateTimePicker
                            :dateTime="formData.co_date_of_birth"
                            :isFutureDateDisabled="false"
                            :showTime="false"
                            :onlyDate="true"
                            @dateTimeChanged="
                                (changedDateTime) =>
                                    (formData.co_date_of_birth =
                                        changedDateTime)
                            "
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.phone_number')"
                        name="phone_number"
                        :help="rules.phone_number ? rules.phone_number.message : null"
                        :validateStatus="rules.phone_number ? 'error' : null"
                    >
                        <PhoneInput
                            v-model="formData.phone_number"
                            :disabled="true"
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" v-if="coApplicantRequired">
                    <a-form-item
                        :label="$t('lead.phone_number')"
                        name="co_phone_number"
                        :help="rules.co_phone_number ? rules.co_phone_number.message : null"
                        :validateStatus="rules.co_phone_number ? 'error' : null"
                        class="hidden-label"
                    >
                        <PhoneInput
                            v-model="formData.co_phone_number"
                            :disabled="true"
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.home_phone')"
                        name="home_phone"
                        :help="rules.home_phone ? rules.home_phone.message : null"
                        :validateStatus="rules.home_phone ? 'error' : null"
                    >
                        <PhoneInput
                            v-model="formData.home_phone"
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" v-if="coApplicantRequired">
                    <a-form-item
                        :label="$t('lead.home_phone')"
                        name="co_home_phone"
                        :help="rules.co_home_phone ? rules.co_home_phone.message : null"
                        :validateStatus="rules.co_home_phone ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-input
                            v-model:value="formData.co_home_phone"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.home_phone'),
                                ])
                            "
                        ></a-input>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.email')"
                        name="email"
                        :help="rules.email ? rules.email.message : null"
                        :validateStatus="rules.email ? 'error' : null"
                    >
                        <a-input-group compact>
                            <a-input
                                v-model:value="formData.email"
                                :placeholder="
                                    $t('common.placeholder_default_text', [
                                        $t('lead.email'),
                                    ])
                                "
                                style="width: calc(100% - 32px)"
                            >
                            </a-input>
                            <SendMail
                                :email="formData.email"
                                :leadFormData="formData"
                                :extraLeadFormData="formData.template_form"
                            />
                        </a-input-group>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" v-if="coApplicantRequired">
                    <a-form-item
                        :label="$t('lead.email')"
                        name="co_email"
                        :help="rules.co_email ? rules.co_email.message : null"
                        :validateStatus="rules.co_email ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-input-group compact>
                            <a-input
                                v-model:value="formData.co_email"
                                :placeholder="
                                    $t('common.placeholder_default_text', [
                                        $t('lead.email'),
                                    ])
                                "
                                style="width: calc(100% - 32px)"
                            >
                            </a-input>
                            <SendMail
                                :email="formData.co_email"
                                :leadFormData="formData"
                                :extraLeadFormData="formData.template_form"
                            />
                        </a-input-group>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        :label="$t('lead.language')"
                        name="language"
                        :help="rules.language ? rules.language.message : null"
                        :validateStatus="rules.language ? 'error' : null"
                    >
                        <a-select
                            v-model:value="formData.language"
                            show-search
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.language'),
                                ])
                            "
                        >
                            <a-select-option
                                v-for="language in optionLanguages"
                                :key="language.id"
                                :value="language.key"
                            >
                                {{ $t(language.value) }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12" v-if="coApplicantRequired">
                    <a-form-item
                        :label="$t('lead.language')"
                        name="co_language"
                        :help="rules.co_language ? rules.co_language.message : null"
                        :validateStatus="rules.co_language ? 'error' : null"
                        class="hidden-label"
                    >
                        <a-select
                            v-model:value="formData.co_language"
                            show-search
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('lead.language'),
                                ])
                            "
                        >
                            <a-select-option
                                v-for="language in optionLanguages"
                                :key="language.id"
                                :value="language.key"
                            >
                                {{ $t(language.value) }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16" style="padding-bottom: 53px">
                <a-col
                    v-for="leadData in formData.lead_data"
                    :key="leadData.id"
                    :xs="24"
                    :sm="24"
                    :md="12"
                    :lg="12"
                >
                    <a-form-item
                        :label="leadData.field_name"
                        :name="leadData.field_name"
                        :help="rules.name ? rules.name.message : null"
                        :validateStatus="rules.name ? 'error' : null"
                    >
                        <a-input-group compact>
                            <a-input
                                v-model:value="leadData.field_value"
                                :placeholder="
                                    $t('common.placeholder_default_text', [
                                        leadData.field_name,
                                    ])
                                "
                                :style="{
                                    width:
                                        getLeadDataFieldType(
                                            leadData.field_name
                                        ) == 'email' ||
                                        getLeadDataFieldType(
                                            leadData.field_name
                                        ) == 'phone'
                                            ? 'calc(100% - 32px)'
                                            : '100%',
                                }"
                            />
                            <SendMail
                                v-if="
                                    getLeadDataFieldType(leadData.field_name) ==
                                    'email'
                                "
                                :email="leadData.field_value"
                                :leadFormData="formData"
                                :extraLeadFormData="formData.template_form"
                            />
                            <a-button
                                v-if="
                                    getLeadDataFieldType(leadData.field_name) ==
                                    'phone'
                                "
                                :href="
                                    leadData.field_value
                                        ? `tel:${leadData.field_value}`
                                        : 'javascript:void(0)'
                                "
                                type="primary"
                            >
                                <template #icon>
                                    <MobileOutlined />
                                </template>
                            </a-button>
                        </a-input-group>
                    </a-form-item>
                </a-col>
            </a-row>
                </perfect-scrollbar>
            </a-form>
        </a-tab-pane>
        <a-tab-pane key="address" :tab="$t('common.address')">
            <template #tab>
                <span>
                    <HomeOutlined />
                    {{ $t("common.address") }}
                </span>
            </template>
            <!-- Address Tab Content -->
            <a-form layout="vertical" class="mt-5">
                <a-row :gutter="16" v-if="coApplicantRequired">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12" align="center">
                        <a-typography-text strong>
                            {{ $t("lead.applicant") }}
                        </a-typography-text>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12" align="center">
                        <a-typography-text strong>
                            {{ $t("lead.co_applicant") }}
                        </a-typography-text>
                    </a-col>
                </a-row>
                <a-row :gutter="16" class="mt-2">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item
                            :label="$t('address.address_line_1')"
                            name="address_line1"
                            :help="rules.address_line1 ? rules.address_line1.message : null"
                            :validateStatus="rules.address_line1 ? 'error' : null"
                        >
                            <a-input v-model:value="addressFormData.address_line1" :placeholder="$t('common.placeholder_default_text', [$t('address.address_line_1')])"></a-input>
                        </a-form-item>
                    </a-col>
                    <a-col
                        :xs="24"
                        :sm="24"
                        :md="12"
                        :lg="12"
                        v-if="coApplicantRequired"
                    >
                        <a-form-item
                            :label="$t('address.address_line_1')"
                            name="co_address_line1"
                            :help="rules.co_address_line1 ? rules.co_address_line1.message : null"
                            :validateStatus="rules.co_address_line1 ? 'error' : null"
                            class="hidden-label"
                        >
                            <a-input 
                                v-model:value="addressFormData.co_address_line1"
                                :placeholder="$t('common.placeholder_default_text', [$t('address.address_line_1')])"
                                :disabled="isCoApplicantDisabled"
                            />
                        </a-form-item>
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item
                            :label="$t('address.address_line_2')"
                            name="address_line2"
                            :help="rules.address_line2 ? rules.address_line2.message : null"
                            :validateStatus="rules.address_line2 ? 'error' : null"
                        >
                            <a-input
                                v-model:value="addressFormData.address_line2"
                                :placeholder="$t('common.placeholder_default_text', [$t('address.address_line_2')])"
                            />
                        </a-form-item>
                    </a-col>
                    <a-col
                        :xs="24"
                        :sm="24"
                        :md="12"
                        :lg="12"
                        v-if="coApplicantRequired"
                    >
                        <a-form-item
                            :label="$t('address.address_line_2')"
                            name="co_address_line2"
                            :help="rules.co_address_line2 ? rules.co_address_line2.message : null"
                            :validateStatus="rules.co_address_line2 ? 'error' : null"
                            class="hidden-label"
                        >
                            <a-input
                                v-model:value="addressFormData.co_address_line_2"
                                :placeholder="$t('common.placeholder_default_text', [$t('address.address_line_2')])"
                                :disabled="isCoApplicantDisabled"
                            />
                        </a-form-item>
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item
                            :label="$t('address.city')"
                            name="city"
                            :help="rules.city ? rules.city.message : null"
                            :validateStatus="rules.city ? 'error' : null"
                        >
                            <a-input
                                v-model:value="addressFormData.city"
                                :placeholder="$t('common.placeholder_default_text', [$t('address.city')])"
                            />
                        </a-form-item>
                    </a-col>
                    <a-col
                        :xs="24"
                        :sm="24"
                        :md="12"
                        :lg="12"
                        v-if="coApplicantRequired"
                    >
                        <a-form-item
                            :label="$t('address.city')"
                            name="co_city"
                            :help="rules.co_city ? rules.co_city.message : null"
                            :validateStatus="rules.co_city ? 'error' : null"
                            class="hidden-label"
                        >
                            <a-input
                                v-model:value="addressFormData.co_city"
                                :placeholder="$t('common.placeholder_default_text', [$t('address.city')])"
                                :disabled="isCoApplicantDisabled"
                            />
                        </a-form-item>
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item
                            :label="$t('address.state')"
                            name="state"
                            :help="rules.state ? rules.state.message : null"
                            :validateStatus="rules.state ? 'error' : null"
                        >
                            <a-select
                                v-model:value="addressFormData.state"
                                :placeholder="$t('common.select_default_text', [$t('address.state')])"
                                style="width: 100%"
                            >
                                <a-select-option
                                    v-for="state in states"
                                    :key="state.id"
                                    :value="state.name"
                                >
                                    {{ state.name }}
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                    <a-col
                        :xs="24"
                        :sm="24"
                        :md="12"
                        :lg="12"
                        v-if="coApplicantRequired"
                    >
                        <a-form-item
                            :label="$t('address.state')"
                            name="co_state"
                            :help="rules.co_state ? rules.co_state.message : null"
                            :validateStatus="rules.co_state ? 'error' : null"
                            class="hidden-label"
                        >
                            <a-select
                                v-model:value="addressFormData.co_state"
                                :placeholder="$t('common.select_default_text', [$t('address.state')])"
                                style="width: 100%"
                                :disabled="isCoApplicantDisabled"
                            >
                                <a-select-option
                                    v-for="state in states"
                                    :key="state.id"
                                    :value="state.name"
                                >
                                    {{ state.name }}
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>

                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-form-item
                            :label="$t('address.zip_code')"
                            name="zip_code"
                            :help="rules.zip_code ? rules.zip_code.message : null"
                            :validateStatus="rules.zip_code ? 'error' : null"
                        >
                            <a-input
                                v-model:value="addressFormData.zip_code"
                                :placeholder="$t('common.placeholder_default_text', [$t('address.zip_code')])"
                            />
                        </a-form-item>
                    </a-col>
                    <a-col
                        :xs="24"
                        :sm="24"
                        :md="12"
                        :lg="12"
                        v-if="coApplicantRequired"
                    >
                        <a-form-item
                            :label="$t('address.zip_code')"
                            name="co_zip_code"
                            :help="rules.co_zip_code ? rules.co_zip_code.message : null"
                            :validateStatus="rules.co_zip_code ? 'error' : null"
                            class="hidden-label"
                        >
                            <a-input
                                v-model:value="addressFormData.co_zip_code"
                                :placeholder="$t('common.placeholder_default_text', [$t('address.zip_code')])"
                                :disabled="isCoApplicantDisabled"
                            />
                        </a-form-item>
                    </a-col>
                </a-row>
            </a-form>
        </a-tab-pane>
    </a-tabs>
    <div
        :class="[
            { 'absolute right-0 bottom-0 w-full border-t! border-gray-200! p-2 bg-white z-10': !isSale }
        ]"
    >
        <a-row justify="end">
            <a-col>
                <div :class="[
                    { 'fixed bottom-4 right-6' : isSale },
                ]">
                    <a-button
                        type="primary"
                        :loading="loading"
                        :disabled="addressValidation"
                        @click="onSubmit"
                    >
                        <template #icon>
                            <SaveOutlined />
                        </template>
                        {{ $t("common.save") }}
                    </a-button>
                </div>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import { ref, onMounted, watch, computed } from "vue";
import { SaveOutlined, MobileOutlined, HomeOutlined, FileTextOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../common/composable/apiAdmin";
import common from "../../../common/composable/common";
import { notification } from "ant-design-vue";
import { useI18n } from "vue-i18n";
import SendMail from "../../views/call-manager/SendMail.vue";
import UserSelect from "../../../common/components/common/select/UserSelect.vue";
import DateTimePicker from "../../../common/components/common/calendar/DateTimePicker.vue";
import PhoneInput from "../../../common/components/common/input/PhoneInput.vue";

export default {
    props: {
        saleLeadData: {
            default: {},
        },
        isSale: {
            default: false,
        },
        states: {
            default: [],
        }
    },
    components: {
        SaveOutlined,
        MobileOutlined,
        HomeOutlined,
        FileTextOutlined,
        SendMail,
        UserSelect,
        DateTimePicker,
        PhoneInput,
    },
    emits: ["success"],
    setup(props, { emit }) {
        const { addEditRequestAdmin, rules, loading } = apiAdmin();
        const optionLanguages = ref([]);
        const statuses = ref([]);
        const { t } = useI18n();
        const { coApplicantRequired } = common();
        const activeTab = ref('details');

        // Main formData for Details tab
        const formData = ref({
            assigned_to: null,
            reference_number: "",
            sale_status_id: null,
            lead_status: null,
            first_name: "",
            last_name: "",
            SSN: "",
            date_of_birth: "",
            home_phone: "",
            phone_number: "",
            email: "",
            co_first_name: "",
            co_last_name: "",
            co_SSN: "",
            co_date_of_birth: "",
            co_home_phone: "",
            co_phone_number: "",
            co_email: "",
            co_language: "",
            lead_data: [],
        });

        // Address formData for Address tab
        const addressFormData = ref({
            individual_id: "",
            address_line1: "",
            address_line2: "",
            city: "",
            state: "",
            zip_code: "",
            co_address_line1: "",
            co_address_line2: "",
            co_city: "",
            co_state: "",
            co_zip_code: "",
        });

        // Track if co_applicant is null
        const coApplicantData = ref(null);

        onMounted(() => {
            const statusesUrl = props.isSale
                ? "sale-statuses"
                : "lead-statuses";
            const selectOptionsPromise = axiosAdmin.get("select-options/language");
            const statusesPromise = axiosAdmin.get(statusesUrl);

            Promise.all([selectOptionsPromise, statusesPromise]).then(
                ([selectOptionsResponse, statusesResponse]) => {
                    optionLanguages.value = selectOptionsResponse.data;
                    statuses.value = statusesResponse.data;
                }
            );
        });

        const addressValidation = computed(() => {
            if(activeTab.value !== 'address') {
                return false;
            }
            return (
                !addressFormData.value.address_line1 ||
                !addressFormData.value.city ||
                !addressFormData.value.state ||
                !addressFormData.value.zip_code
            );
        });

        const onSubmit = () => {
            if (activeTab.value === 'details') {
                addEditRequestAdmin({
                    url: "individuals/" + props.saleLeadData.individual.xid,
                    data: {
                        ...formData.value,
                        _method: "PUT"
                    },
                    successMessage: t("common.updated_successfully"),
                    success: (res) => {
                        emit("success");
                    },
                });
            } else if (activeTab.value === 'address') {
                addEditRequestAdmin({
                    url: "addresses/" + (props.saleLeadData.individual.address ? props.saleLeadData.individual.address.xid : ""),
                    data: {
                        ...addressFormData.value,
                        _method: props.saleLeadData.individual.address ? "PUT" : "POST",
                    },
                    successMessage: t("address.updated"),
                    success: (res) => {
                        emit("success");
                    },
                });
            }
        };

        const getLeadDataFieldType = (fieldName) => {
            var fieldType = "text";

            if (
                formData.value.campaign &&
                formData.value.campaign.form &&
                formData.value.campaign.form.form_fields &&
                formData.value.campaign.form.form_fields.length > 0
            ) {
                const formField = formData.value.campaign.form.form_fields.find(
                    (field) => field.field_name === fieldName
                );

                if (formField && formField.field_type) {
                    fieldType = formField.field_type;
                }
            }

            return fieldType;
        };

        watch(
            () => props.saleLeadData,
            (newValue) => {
                if (newValue) {
                    // Set individual details data
                    if (newValue.individual) {
                        formData.value = {
                            first_name: newValue.individual.first_name || "",
                            last_name: newValue.individual.last_name || "",
                            SSN: newValue.individual.SSN || "",
                            date_of_birth: newValue.individual.date_of_birth || "",
                            home_phone: newValue.individual.home_phone || "",
                            phone_number: newValue.individual.phone_number || "",
                            email: newValue.individual.email || "",
                            assigned_to: newValue.x_assigned_to || null,
                            reference_number: newValue.individual.reference_number || "",
                            sale_status_id: newValue.sale_status_id || null,
                            lead_status: newValue.individual.lead_status || null,
                            lead_data: newValue.individual.lead_data || [],
                            // campaign: newValue.individual.campaign || {},
                            individual_id: newValue.individual.xid,
                            sale_id: props.isSale == true ? newValue.xid : null,
                            lead_id: props.isSale == false ? newValue.xid : null,
                        };

                        // Set co-applicant details if exists
                        if (coApplicantRequired.value && newValue.individual.co_applicant) {
                            formData.value.co_first_name = newValue.individual.co_applicant.first_name || "";
                            formData.value.co_last_name = newValue.individual.co_applicant.last_name || "";
                            formData.value.co_SSN = newValue.individual.co_applicant.SSN || "";
                            formData.value.co_date_of_birth = newValue.individual.co_applicant.date_of_birth || "";
                            formData.value.co_home_phone = newValue.individual.co_applicant.home_phone || "";
                            formData.value.co_phone_number = newValue.individual.co_applicant.phone_number || "";
                            formData.value.co_email = newValue.individual.co_applicant.email || "";
                            coApplicantData.value = newValue.individual.co_applicant;
                        } else {
                            coApplicantData.value = null;
                        }

                        // Set address data
                        addressFormData.value.individual_id = newValue.individual.xid;
                        if (newValue.individual.address) {
                            addressFormData.value.address_line1 = newValue.individual.address.address_line1 || "";
                            addressFormData.value.address_line2 = newValue.individual.address.address_line2 || "";
                            addressFormData.value.city = newValue.individual.address.city || "";
                            addressFormData.value.state = newValue.individual.address.state || undefined;
                            addressFormData.value.zip_code = newValue.individual.address.zip_code || "";
                        }

                        // Set co-applicant address if exists
                        if (coApplicantRequired.value && newValue.individual.co_applicant && newValue.individual.co_applicant.address) {
                            addressFormData.value.co_address_line1 = newValue.individual.co_applicant.address.address_line1 || "";
                            addressFormData.value.co_address_line2 = newValue.individual.co_applicant.address.address_line2 || "";
                            addressFormData.value.co_city = newValue.individual.co_applicant.address.city || "";
                            addressFormData.value.co_state = newValue.individual.co_applicant.address.state || undefined;
                            addressFormData.value.co_zip_code = newValue.individual.co_applicant.address.zip_code || "";
                        }
                    }
                }
            },
            { immediate: true, deep: true }
        );

        // Computed property to disable co-applicant address fields
        const isCoApplicantDisabled = computed(() => !coApplicantData.value);

        return {
            rules,
            optionLanguages,
            statuses,
            onSubmit,
            loading,
            getLeadDataFieldType,
            formData,
            addressFormData,
            coApplicantRequired,
            activeTab,
            addressValidation,
            isCoApplicantDisabled,
        };
    },
};
</script>

<style>
.callmanager-details {
    height: calc(100vh - 264px);
}
.hidden-label .ant-form-item-label {
    visibility: hidden;
}
.details-tabs .ant-tabs-nav .ant-tabs-nav-wrap {
    justify-content: end;
}
.details-tabs .ant-tabs-content {
    height: calc(100vh - 260px);
    overflow-y: auto;
}
</style>

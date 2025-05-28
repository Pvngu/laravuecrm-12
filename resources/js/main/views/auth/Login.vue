<template>
    <div class="bg-white h-screen overflow-hidden">
        <a-row class="h-full">
            <a-col :xs="24" :sm="24" :md="12" :lg="10">
                <div class="flex flex-col h-screen px-12 py-8 md:px-8 sm:px-6 max-w-2xl mx-auto">
                    <div class="mb-8 text-left">
                        <img class="w-32 h-auto" :src="globalSetting.light_logo_url" />
                    </div>
                    <div class="my-auto">
                        <h1 class="text-3xl font-semibold mb-2 text-gray-800 text-center">Welcome Back</h1>
                        <p class="text-gray-600 mb-8 text-base text-center">Enter your email and password to access your account.</p>
                        
                        <a-alert
                            v-if="onRequestSend.error != ''"
                            :message="onRequestSend.error"
                            type="error"
                            show-icon
                            class="mb-4 mt-2"
                        />
                        <a-alert
                            v-if="onRequestSend.success"
                            :message="$t('messages.login_success')"
                            type="success"
                            show-icon
                            class="mb-4 mt-2"
                        />
    
                        <div class="flex-1">
                            <a-form layout="vertical">
                                <a-form-item
                                    :label="$t('user.email')"
                                    name="email"
                                    :help="rules.email ? rules.email.message : null"
                                    :validateStatus="rules.email ? 'error' : null"
                                >
                                    <a-input
                                        v-model:value="credentials.email"
                                        @pressEnter="onSubmit"
                                        placeholder="sellorstore@company.com"
                                        size="large"
                                    />
                                </a-form-item>
    
                                <a-form-item
                                    :label="$t('user.password')"
                                    name="password"
                                    :help="rules.password ? rules.password.message : null"
                                    :validateStatus="rules.password ? 'error' : null"
                                >
                                    <a-input-password
                                        v-model:value="credentials.password"
                                        @pressEnter="onSubmit"
                                        placeholder="••••••••"
                                        size="large"
                                    />
                                </a-form-item>
    
                                <div class="flex justify-between items-center mb-6 text-sm">
                                    <a-checkbox v-model:checked="rememberMe">Remember Me</a-checkbox>
                                    <a href="#" class="text-indigo-600 hover:underline" @click="forgotPassword">Forgot Your Password?</a>
                                </div>
    
                                <a-form-item class="mt-5">
                                    <a-button
                                        :loading="loading"
                                        @click="onSubmit"
                                        block
                                        size="large"
                                        type="primary"
                                    >
                                        Log In
                                    </a-button>
                                </a-form-item>
                            </a-form>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-xs text-gray-500 pt-6 border-t border-gray-100 mt-auto">
                        <span>Copyright © 2025 SpotServices.</span>
                        <a href="#" class="hover:underline">Privacy Policy</a>
                    </div>
                </div>
            </a-col>
            
            <a-col :xs="0" :sm="0" :md="12" :lg="14" class="hidden md:block p-0">
                <div class="bg-indigo-600 h-full flex items-center justify-center relative text-white px-12 py-12 rounded-xl">
                    <div class="w-full text-center flex flex-col items-center z-10">
                        <h2 class="text-3xl font-semibold mb-4 max-w-[80%]">Effortlessly manage your team and operations.</h2>
                        <p class="text-lg mb-8 opacity-90">Log in to access your CRM dashboard and manage your team.</p>
                        <div class="w-11/12 max-w-3xl mx-auto bg-white rounded-xl overflow-hidden shadow-lg">
                            <img class="w-full h-auto block" src="/images/lead_preview.png" alt="Dashboard Preview" />
                        </div>
                    </div>
                </div>
            </a-col>
        </a-row>
    </div>
</template>

<script>
import { defineComponent, reactive, ref } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import common from "../../../common/composable/common";
import apiAdmin from "../../../common/composable/apiAdmin";
import { GoogleOutlined, AppleOutlined } from "@ant-design/icons-vue";

export default defineComponent({
    components: {
        GoogleOutlined,
        AppleOutlined,
    },
    setup() {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const { globalSetting } = common();
        const store = useStore();
        const router = useRouter();
        const credentials = reactive({
            email: null,
            password: null,
        });
        const onRequestSend = ref({
            error: "",
            success: "",
        });
        const rememberMe = ref(false);

        const onSubmit = () => {
            onRequestSend.value = {
                error: "",
                success: false,
            };

            addEditRequestAdmin({
                url: "auth/login",
                data: { ...credentials, remember_me: rememberMe.value },
                success: (response) => {
                    if (response && response.status == "success") {
                        const user = response.user;
                        store.commit("auth/updateUser", user);
                        store.commit("auth/updateToken", response.token);
                        store.commit("auth/updateExpires", response.expires_in);
                        store.commit(
                            "auth/updateVisibleSubscriptionModules",
                            response.visible_subscription_modules
                        );
                        if (user.is_superadmin && user.user_type == "super_admins") {
                            store.commit("auth/updateApp", response.app);
                            store.commit(
                                "auth/updateEmailVerifiedSetting",
                                response.email_setting_verified
                            );
                            router.push({
                                name: "superadmin.dashboard.index",
                                params: { success: true },
                            });
                        } else {
                            store.commit("auth/updateApp", response.app);
                            store.commit(
                                "auth/updateEmailVerifiedSetting",
                                response.email_setting_verified
                            );
                            store.commit(
                                "auth/updateAddMenus",
                                response.shortcut_menus.credentials
                            );
                            router.push({
                                name: "admin.dashboard.index",
                                params: { success: true },
                            });
                        }
                    } else {
                        onRequestSend.value = {
                            error: response.message ? response.message : "",
                            success: false,
                        };
                    }
                },
                error: (err) => {},
            });
        };

        const forgotPassword = (e) => {
            e.preventDefault();
            router.push({ name: 'admin.forgot_password' });
        };

        const register = (e) => {
            e.preventDefault();
            router.push({ name: 'admin.register' });
        };

        return {
            loading,
            rules,
            credentials,
            onSubmit,
            onRequestSend,
            globalSetting,
            rememberMe,
            forgotPassword,
            register,
            innerWidth: window.innerWidth,
        };
    },
});
</script>

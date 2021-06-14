<template>
    <jet-authentication-card class="shadow-2xl">
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <form @submit.prevent="submit">
            <div class="mt-20">
                <div
                    v-if="canLogin"
                >
                    <inertia-link
                        v-if="$page.props.user"
                        href="/dashboard"
                        class="text-sm text-gray-700 underline"
                    >
                        Dashboard
                    </inertia-link>

                    <div v-else>
                        <div class="flex flex-col justify-center items-center ">
                            <inertia-link
                                :href="route('login')"
                                class="bg-blue-700 w-3/5 p-2 rounded-3xl text-white font-mono text-center"
                            >
                                Entrar
                            </inertia-link>
                            <inertia-link
                                v-if="canRegister"
                                :href="route('register')"
                                class="bg-gray-800 w-3/5 m-5 p-2 rounded-3xl text-white font-mono text-center"
                            >
                                Criar nova conta
                            </inertia-link>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

export default {
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
    },
    components:{
        Toolbar,
        Button,
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
    },
};
</script>

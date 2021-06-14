<template>
    <jet-form-section @submitted="updatePassword">
        <template #title>
            Atualizar senha
        </template>

        <template #description>
            Certifique-se de que sua conta está usando uma senha longa e aleatória para permanecer segura.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="current_password" value="Senha atual" />
                <jet-input id="current_password" type="password" class="mt-1 block w-full" v-model="form.current_password" ref="current_password" autocomplete="current-password" />
                <jet-input-error :message="form.errors.current_password ? 'Senha atual invalida!' : ''" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="password" value="Nova Senha" />
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" ref="password" autocomplete="new-password" />
                <jet-input-error :message="form.errors.password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="password_confirmation" value="Confirme a Senha" />
                <jet-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" autocomplete="new-password" />
                <jet-input-error :message="form.errors.password_confirmation" class="mt-2" />
                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    <li v-show="error.senha">
                        As senhas são diferentes!
                    </li>
                    <li v-show="error.vazio">
                        As senhas são compos obrigatório!
                    </li>
                </ul>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Salvou.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Salvar
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        data() {
            return {
                form: this.$inertia.form({
                    current_password: '',
                    password: '',
                    password_confirmation: '',
                }),
                error: {
                    senha: false,
                }
            }
        },

        methods: {
            updatePassword() {

                const registro = this.form;
                if(registro.password !== registro.password_confirmation) {
                    this.error.senha = true;
                    return;
                }
                if(
                    registro.password.trim() === '' |
                    registro.current_password.trim() === '' |
                    registro.password_confirmation.trim() === ''
                ) {
                    this.error.vazio = true;
                    return;
                }

                this.form.put(route('user-password.update'), {
                    errorBag: 'updatePassword',
                    preserveScroll: true,
                    onSuccess: () => this.form.reset(),
                    onError: () => {
                        if (this.form.errors.password) {
                            this.form.reset('password', 'password_confirmation')
                            this.$refs.password.focus()
                        }

                        if (this.form.errors.current_password) {
                            this.form.reset('current_password')
                            this.$refs.current_password.focus()
                        }
                    }
                })
            },
        },
    }
</script>

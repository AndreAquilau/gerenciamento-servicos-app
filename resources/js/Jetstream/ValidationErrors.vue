<template>
    <div v-if="hasErrors">
        <div class="font-medium text-red-600">Opa! Algo deu errado.</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            <li v-for="(error, key) in errors" :key="key">
                <p v-show="error === 'These credentials do not match our records.'">
                    Essas credenciais não correspondem aos nossos registros.
                </p>
                <p v-show="error === 'The provided password does not match your current password.'">
                    A senha fornecida não corresponde à sua senha atual.
                </p>
                <p v-show="error === 'The email has already been taken.'">
                    O email já está sendo usado!
                </p>
                <p v-show="error === 'The email has already been taken.'">
                    O email já está sendo usado!
                </p>
                <p v-show="error === 'The current password field is required.'">
                    O campo da senha atual é obrigatório.
                </p>
            </li>
        </ul>

    </div>
</template>

<script>
    export default {
        computed: {
            errors() {
                return this.$page.props.errors
            },

            hasErrors() {
                return Object.keys(this.errors).length > 0;
            },
        }
    }
</script>

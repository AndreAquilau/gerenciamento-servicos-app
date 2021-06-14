<template>
    <jet-action-section>
        <template #title>
            Excluir grupo
        </template>

        <template #description>
            Exclua este grupo permanentemente.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Depois que um grupo é excluída, todos os seus recursos e dados serão excluídos permanentemente. Antes de excluir este grupo, baixe todos os dados ou informações sobre este grupo que você deseja manter.
            </div>

            <div class="mt-5">
                <jet-danger-button @click="confirmTeamDeletion">
                    Excluir grupo
                </jet-danger-button>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <jet-confirmation-modal :show="confirmingTeamDeletion" @close="confirmingTeamDeletion = false">
                <template #title>
                    Excluir grupo
                </template>

                <template #content>
                    Tem certeza que deseja excluir este grupo? Depois que um grupo é excluído, todos os seus recursos e dados serão excluídos permanentemente.
                </template>

                <template #footer>
                    <jet-secondary-button @click="confirmingTeamDeletion = false">
                        Cancelar
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click="deleteTeam" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Excluir grupo
                    </jet-danger-button>
                </template>
            </jet-confirmation-modal>
        </template>
    </jet-action-section>
</template>

<script>
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    export default {
        props: ['team'],

        components: {
            JetActionSection,
            JetConfirmationModal,
            JetDangerButton,
            JetSecondaryButton,
        },

        data() {
            return {
                confirmingTeamDeletion: false,
                deleting: false,

                form: this.$inertia.form()
            }
        },

        methods: {
            confirmTeamDeletion() {
                this.confirmingTeamDeletion = true
            },

            deleteTeam() {
                this.form.delete(route('teams.destroy', this.team), {
                    errorBag: 'deleteTeam'
                });
            },
        },
    }
</script>

<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Usuários
            </h2>
        </template>
        <DataTable
            ref="dt" :value="usuarios" v-model:selection="selectedUsuarios" dataKey="id"
            :paginator="true" :rows="10" :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" :rowsPerPageOptions="[5,10,25]"
            currentPageReportTemplate="Mostrando {first} para {last} de {totalRecords} usuários">
            <Toast position="top-right" />
            <Toolbar class="p-mb-4">
                <template #left>
                    <Button label="Novo" icon="pi pi-plus" class="p-button-success p-mr-2" @click="openNew" />
                </template>

                <template #right>
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Pesquise..." />
                    </span>
                </template>
            </Toolbar>
            <Column class="text-center" field="id" header="Código" sortable></Column>
            <Column field="name" header="Nome" sortable></Column>
            <Column field="email" header="E-mail" sortable></Column>
            <Column>
                <template #body="slotProps">
                        <Button v-show="slotProps.data.inativo" label="Inativo" class="p-button-danger" />
                </template>
            </Column>
            <Column>
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="editUsuario(slotProps.data)" />
                </template>
            </Column>
        </DataTable>

        <Dialog
        v-model:visible="usuarioDialog"
        :style="{width: '35%'}"
        header="Cadastro de Usuário"
        :hide="resetUsuarioCreate()"
        :modal="false" class="p-fluid">
                    <jet-validation-errors class="mb-4" />
                    <div>
                        <jet-label for="name" value="Usuário" />
                        <jet-input id="name" type="text" class="mt-1 block w-full" v-model="usuarioCreate.name" required autofocus autocomplete="name" />
                    </div>

                    <div class="mt-4">
                        <jet-label for="email" value="Email" />
                        <jet-input id="email" type="email" class="mt-1 block w-full" v-model="usuarioCreate.email" required />
                    </div>

                    <div class="mt-4">
                        <jet-label for="password" value="Senha" />
                        <jet-input id="password" type="password" class="mt-1 block w-full" v-model="usuarioCreate.password" required autocomplete="new-password" />
                    </div>

                    <div class="mt-4">
                        <jet-label for="password_confirmation" value="Confirma Senha" />
                        <jet-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="usuarioCreate.password_confirm" required autocomplete="new-password" />
                    </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="hideDialog"/>
                <Button label="Cadastrar" icon="pi pi-check" class="p-button-text" @click="saveUsuario($page.props.user.id)" />
            </template>
        </Dialog>

        <Dialog
        v-model:visible="dadosUsuarioDialog"
        :style="{width: '35%'}"
        header="Dados do Usuário"
        :hide="resetUsuarioCreate()"
        :modal="false" class="p-fluid">
                    <jet-validation-errors class="mb-4" />
                    <div>
                        <jet-label for="name" value="Usuário" />
                        <jet-input id="name" type="text" class="mt-1 block w-full" v-model="usuarioEditInput.name" required autofocus autocomplete="name" />
                    </div>

                    <div class="mt-4">
                        <jet-label for="email" value="Email" />
                        <input id="email" readonly disabled type="email" class="mt-1 block w-full rounded" v-model="usuarioEditInput.email" required />
                    </div>

                    <div class="mt-4">
                        <jet-label for="password_corrent" value="Senha Atual" />
                        <jet-input id="password" type="password" class="mt-1 block w-full" v-model="usuarioEditInput.current_password" required autocomplete=" current_password" />
                    </div>

                    <div class="mt-4">
                        <jet-label for="password" value="Nova Senha" />
                        <jet-input id="password" type="password" class="mt-1 block w-full" v-model="usuarioEditInput.password" required autocomplete="nepassword" />
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="password_confirmation" value="Confirme a Senha" />
                        <jet-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="usuarioEditInput.password_confirmation" autocomplete="confirm-password" />
                    </div>
                    <div v-show="$page.props.user.id !== usuarioEdit.id" class="col-span-6 sm:col-span-4">
                        <jet-label for="inativo" value="Inativo" />
                        <input id="inativo" type="checkbox" v-model="usuarioEditInput.inativo" />
                    </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="hideEditUserDialog()"/>
                <Button label="Salvar" icon="pi pi-check" class="p-button-text" @click="updateUser()" />
            </template>
        </Dialog>

    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import { Inertia } from '@inertiajs/inertia';
import { FilterMatchMode } from 'primevue/api';

const usuarioValueDefault = {
    "id": null,
    "name": null,
    "email": null,
    "email_verified_at": null,
    "current_team_id": null,
    "empresa_id": null,
    "profile_photo_path": null,
    "created_at": null,
    "updated_at": null,
    "profile_photo_url": null,
}

const usuarioCreateValueDefault = {
    "name": null,
    "email": null,
    "password": null,
    "password_confirmation": null,
    "current_password": null,
    "current_team_id": null,
    "empresa_id": null,
    "current_team": null,
}

const usuarioEditValueDefault = {
    email: '',
    name: '',
    inativo: false,
    password: '',
    current_password: '',
    password_confirmation: '',
};



export default {
    components: {
        AppLayout,
        JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors
    },
    props:["usuariosAll", "query"],
    data() {
        return {
            create: false,
            usuarios: null,
            usuarioDialog: false,
            dadosUsuarioDialog: false,
            deleteUsuarioDialog: false,
            deleteUsuariosDialog: false,
            usuario: {
                "id": null,
                "name": null,
                "email": null,
                "email_verified_at": null,
                "current_team_id": null,
                "empresa_id": null,
                "profile_photo_path": null,
                "created_at": null,
                "updated_at": null,
                "profile_photo_url": null,
                "inativo": false,
            },
            usuarioCreate: {
                 _method: 'PUT',
                "name": null,
                "email": null,
                "password": null,
                "password_confirm": null,
                "current_team_id": null,
                "empresa_id": null,
                "current_team": null,
            },
            usuarioEdit: {
                created_at: null,
                current_team_id: null,
                email: null,
                email_verified_at: null,
                empresa_id: null,
                id: null,
                inativo: null,
                name: null,
                password:null,
                profile_photo_path: null,
                remember_token: null,
                two_factor_recovery_codes: null,
                two_factor_secret: null,
                updated_at: null,
            },
            usuarioEditInput: {
                email: '',
                name: '',
                inativo: false,
                password: '',
                current_password: '',
                password_confirmation: '',
            },
            selectedUsuarios: null,
            filters: {},
            submitted: false,
        }
    },
    created() {
        console.log("props", this.$inertia.page.props);
        this.usuarios = this.$inertia.page.props.usuariosAll;
        this.initFilters();
    },
    mounted() {
        console.log(this.$inertia.page.props.user);
    },
    methods: {
        initFilters() {
            this.filters = {
                'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
            }
        },
        formatCurrency(value) {
            return value.toLocaleString('en-US', {style: 'currency', currency: 'USD'});
        },
        openNew() {
            this.create = true;
            this.submitted = false;
            this.usuarioDialog = true;
        },
        openEditUser() {
            this.edit = true;
            this.submitted = false;
            this.dadosUsuarioDialog = true;
        },
        showMessage(summary, detail,type = true) {
            this.$toast.add({severity: type ? 'success' : 'error', summary, detail, life: 3000});
        },
        hideDialog() {
            this.resetUsuarioCreate();
            this.usuarioDialog = false;
            this.submitted = false;
        },
        hideEditUserDialog() {
            this.resetUsuarioCreate();
            this.dadosUsuarioDialog = false;
            this.submitted = false;
        },
        saveUsuario(user_id) {
            this.submitted = true;
            const body = {...this.usuarioCreate};
            //this.usuario.user_id = user_id;

            if(body.name === '' || body.name === null){
                this.showMessage('Erro', 'O campo nome do usuário não pode está vazio', false);
                return;
            }

            if(body.email === '' || body.email === null){
                this.showMessage('Erro', 'O campo email do usuário não pode está vazio', false);
                return;
            }

            if(body.password === ''  || body.password === null){
                this.showMessage('Erro', 'O campo nova senha do usuário não pode está vazio', false);
                return;
            }
            if(body.password_confirm === '' || body.password_confirm === null){
                this.showMessage('Erro', 'O confirme a senha campo senha do usuário não pode está vazio', false);
                return;
            }
            if(body.password_confirm !== body.password){
                this.showMessage('Erro', 'As senhas são diferentes', false);
                return;
            }

            const userExists = this.usuarios.filter(usuario => {
                return usuario.email === body.email;
            });

            console.log(userExists);

            if(userExists.length > 0){
                this.showMessage('Erro', 'E-mail já cadastrado', false);
                return;
            }


            if (this.create) {
                    body.empresa_id = this.$inertia.page.props.user.empresa_id;
                    body.current_team = this.$inertia.page.props.user.current_team;
                    body.current_team_id = this.$inertia.page.props.user.current_team_id;
                    body.current_team.personal_team = false;
                    const query = this.$inertia.page.props.query;

                    Inertia.post(this.route('usuario.create', query), {
                        query: query,
                        user: body,
                    },{
                        onSuccess: (page) => {
                            this.usuarios = page.props.usuariosAll;
                            this.showMessage('Concluído', 'Usuario Cadastrado');
                        },
                    });
                }
            this.usuarioDialog = false;
            this.resetUsuario();
        },
        editUsuario(usuario) {
            console.log(usuario);
            this.create = false;
            this.usuarioEdit = usuario;
            this.usuarioEditInput.email = usuario.email;
            this.usuarioEditInput.name = usuario.name;
            this.usuarioEditInput.inativo = usuario.inativo ? true : false;
            this.usuarioEditInput.password = null;
            this.usuarioEditInput.password_confirmation = null;
            this.usuarioEditInput.current_password = null;
            this.dadosUsuarioDialog = true;
        },
        findIndexById(id) {
            let index = -1;
            for (let i = 0; i < this.usuarios.length; i++) {
                if (this.usuarios[i].id === id) {
                    index = i;
                    break;
                }
            }
            return index;
        },
        resetUsuario()
        {
            console.log('Reset Values')
            this.usuario = {...usuarioValueDefault};
        },
        resetUsuarioCreate()
        {
            console.log('Reset Values')
            this.usuarioCreate = {...usuarioCreateValueDefault};
        },
        exportCSV() {
            this.$refs.dt.exportCSV();
        },
        updateUser() {
            const registro = {...this.$data.usuarioEditInput};
            console.log(registro);

            if(this.usuarioEditInput.password) {
                if(this.usuarioEditInput.password !== this.usuarioEditInput.password_confirmation | !this.usuarioEdit.password ){
                    this.showMessage('Erro', 'Senhas diferentes', false);
                    return;
                }
            }

            if(registro.name === '' || registro.name === null){
                this.showMessage('Erro', 'O campo nome do usuário não pode está vazio', false);
                return;
            }


            Inertia.put(route('usuario.update', this.$props.query), {
                query: {
                    empresa_id: this.$inertia.page.props.user.empresa_id,
                    user_id: this.$inertia.page.props.user.id,
                },
                input: this.usuarioEditInput,
                user: this.usuarioEdit,
            },
            {
                errorBag: 'updateProfileInformation',
                preserveScroll: true,
                onSuccess: (page) => {
                    console.log(page.props)
                    this.edit = false;
                    if(page.props.error){
                        this.showMessage('Erro', 'Senha Atual do usuário invalida', false);
                    }
                    if(!page.props.error){
                        this.showMessage('Sucesso', 'Senha do usuário atualizada');
                    }
                    this.dadosUsuarioDialog = false;
                    this.usuarioEdit = usuarioEditValueDefault;
                    this.usuarios = page.props.usuariosAll;
                },
            });

        },
    }
};
</script>

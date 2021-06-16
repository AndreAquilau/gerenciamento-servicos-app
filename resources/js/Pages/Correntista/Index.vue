<template>
    <app-layout>
        <DataTable ref="dt" :value="correntistas" v-model:selection="selectedCorrentistas" dataKey="id"
            :paginator="true" :rows="10" :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" :rowsPerPageOptions="[5,10,25]"
            currentPageReportTemplate="Mostrando {first} para {last} de {totalRecords} correntistas">
            <Toast position="top-right" />
            <Toolbar class="p-mb-4">
                <template #left>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Correntista
                    </h2>
                </template>

                <template #right>
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Pesquise..." />
                    </span>
                    <div>
                        <Button label="Novo" icon="pi pi-plus" class="p-button-success p-mr-2" @click="openNew" />
                    </div>
                </template>
            </Toolbar>

            <Column field="nome" header="Nome/Fantasia" sortable></Column>
            <Column field="cpf" header="CPF" sortable></Column>
            <Column field="cnpj" header="CNPJ" sortable></Column>
            <Column field="telefone" header="Telefone" sortable></Column>
            <Column>
                <template #body="slotProps">
                        <Button v-show="slotProps.data.inativo" label="Inativo" class="p-button-danger" />
                </template>
            </Column>
            <Column>
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="editCorrentista(slotProps.data)" />
                </template>
            </Column>
        </DataTable>

        <Dialog
        v-model:visible="correntistaDialog"
        :style="{width: '85%'}"
        header="Correntista"
        :modal="true" class="p-fluid">
            <div class="grid grid-cols-3 gap-4">
                <span class="p-field">
                    <label for="name">Nome/Fantasia</label>
                    <InputText id="name" v-model.trim="correntista.nome" required="true" autofocus :class="{'p-invalid': submitted && !correntista.nome}" />
                    <small class="p-invalid" v-if="submitted && !correntista.nome">Nome é obrigatório.</small>
                </span>
                <div class="p-field">
                    <label for="cpf_cnpj">CPF</label>
                    <InputText id="cpf_cnpj" v-model.trim="correntista.cpf" autofocus />
                </div>
                <div class="p-field">
                    <label for="cpf_cnpj">CNPJ</label>
                    <InputText id="cpf_cnpj" v-model.trim="correntista.cnpj" autofocus />
                </div>
                <div class="p-field">
                    <label for="telefone">Telefone</label>
                    <InputText id="telefone" v-model.trim="correntista.telefone" autofocus />
                </div>
                <div class="p-field">
                    <label for="rua">Rua</label>
                    <InputText id="rua" v-model.trim="correntista.rua" autofocus />
                </div>
                <div class="p-field">
                    <label for="numero">Número</label>
                    <InputText id="numero" v-model.trim="correntista.numero" autofocus />
                </div>
                <div class="p-field">
                    <label for="bairro">Bairro</label>
                    <InputText id="bairro" v-model.trim="correntista.bairro" autofocus />
                </div>
                <div class="p-field">
                    <label for="cidade">Cidade</label>
                    <InputText id="cidade" v-model.trim="correntista.cidade" autofocus />
                </div>
                <div class="p-field">
                    <label for="estado">Estado</label>
                    <InputText id="estado" v-model.trim="correntista.estado" autofocus />
                </div>
                <div class="p-field col-span-1" v-if="!create" >
                    <Checkbox v-model="correntista.inativo"  :binary="true" /> Inativo
                </div>
            </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="hideDialog"/>
                <Button label="Salvar" icon="pi pi-check" class="p-button-text" @click="saveCorrentista($page.props.user.id)" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteCorrentistaDialog" :style="{width: '450px'}" header="Confirme" :modal="true">
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle p-mr-3" style="font-size: 2rem" />
                <span v-if="correntista">Tem certeza de que deseja excluir<b>{{correntista.nome}}</b>?</span>
            </div>
            <template #footer>
                <Button label="Não" icon="pi pi-times" class="p-button-text" @click="hideDeleteDialog()"/>
                <Button label="Sim" icon="pi pi-check" class="p-button-text" @click="deleteCorrentista($page.props.user.id)" />
            </template>
        </Dialog>

    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import RadioButton from 'primevue/radiobutton';
import Textarea from 'primevue/textarea';
import InputText from 'primevue/inputtext';
import Column from 'primevue/column';
import Rating from 'primevue/rating';
import DataTable from 'primevue/datatable';
import Toolbar from 'primevue/toolbar';
import FileUpload from 'primevue/fileupload';
import Toast from 'primevue/toast';
import ToggleButton from 'primevue/togglebutton';
import Checkbox from 'primevue/checkbox';
import { Inertia } from '@inertiajs/inertia';
import { validateUserAuth } from '../Auth';
import { FilterMatchMode } from 'primevue/api';

const correntistaValueDefault = {
    "id": 0,
    "nome": "",
    "cpf": "",
    "cnpj": "",
    "rua": "",
    "telefone": "",
    "bairro": "",
    "cidade": "",
    "numero": "",
    "estado": "",
    "user_id": 0,
    "inativo": false,
    "updated_at": null,
    "created_at": null,
};


export default {
    props: ["correntistasAllByEmpresa", "query"],
    components: {
        AppLayout,
        Dialog,
        Button,
        InputNumber,
        RadioButton,
        Textarea,
        InputText,
        Column,
        Rating,
        DataTable,
        Toolbar,
        FileUpload,
        Toast,
        ToggleButton,
        Checkbox
    },
    data() {
        return {
            create: false,
            correntistas: null,
            correntistaDialog: false,
            deleteCorrentistaDialog: false,
            deleteCorrentistasDialog: false,
            correntista: {
                "id": 0,
                "nome": "",
                "cpf": "",
                "cnpj": "",
                "rua": "",
                "telefone": "",
                "bairro": "",
                "cidade": "",
                "numero": "",
                "estado": "",
                "user_id": 0,
                "inativo": false,
                "updated_at": null,
                "created_at": null,

            },
            selectedCorrentistas: null,
            filters: {},
            submitted: false,
        }
    },
    created() {
        validateUserAuth(this.$inertia, this.$props.query, () => {
            this.correntistas = this.$props.correntistasAllByEmpresa;
            console.log(this.$inertia.page.props);
        });
        this.initFilters();
    },
    beforeCreate() {
        console.log('beforeCreate');
        validateUserAuth(Inertia, this.$props.query);
    },
    mounted() {
        validateUserAuth(Inertia, this.$props.query);
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
            this.correntistaDialog = true;
        },
        showMessage(summary, detail,type = true) {
            this.$toast.add({severity: type ? 'success' : 'error', summary, detail, life: 3000});
        },
        hideDialog() {
            this.resetCorrentista();
            this.correntistaDialog = false;
            this.submitted = false;
        },
        hideDeleteDialog() {
            this.deleteCorrentistaDialog = false;
             this.resetCorrentista();
        },
        saveCorrentista(user_id) {
            this.submitted = true;
            this.correntista.user_id = user_id;

            validateUserAuth(this.$inertia, this.$props.query, async () => {
                if (this.correntista.nome.trim() && this.correntista.user_id)
                {
                    if (this.create) {

                        const body = { query: this.$props.query, ...this.correntista};

                        await Inertia.post(this.route('correntista.create'), body, {
                           preserveState: true,
                           onSuccess: (page) => {
                                console.log(page);
                                this.correntistas = page.props.correntistasAllByEmpresa;
                                this.showMessage('Concluído', 'Correntista Cadastrado');
                           },
                           onError: (errors) => {
                               this.showMessage('Erro', 'Erro ao Correntista', false);
                               console.log(errors)
                               this.resetCorrentista();
                           },
                        });
                    }
                    else {
                        const {created_at,...correntista} = {...this.correntista};

                        const body = { query: this.$props.query, correntista};

                        body.updated_at = Date().now;


                        await Inertia.put(
                            this.route('correntista.update'),
                            body,
                            {
                                onSuccess: (page) => {
                                    console.log('delete', page);
                                    this.correntistas = page.props.correntistasAllByEmpresa;
                                    this.hideDeleteDialog();
                                    this.resetCorrentista();
                                    this.showMessage('Concluído', 'Correntista Atualizado');
                                },
                                onError: (errors) => {
                                    this.showMessage('Erro', 'Erro ao Atualizar', false);
                                    console.log(errors);
                                    this.resetCorrentista();
                                }
                            }
                        );
                    }
                    this.correntistaDialog = false;
                    this.resetCorrentista();
                }
                this.resetCorrentista();
            });
        },
        editCorrentista(correntista) {

            this.create = false;
            this.correntista = {...correntista};
            this.correntistaDialog = true;
        },
        confirmDeleteCorrentista(correntista) {
            this.correntista = correntista;
            this.deleteCorrentistaDialog = true;
        },
        deleteCorrentista(user_id) {

            const body = {...this.correntista};

            validateUserAuth(Inertia, this.$props.query, async () => {
                await Inertia.delete(this.route(`correntista.delete`,{ correntista_id: body.id, user_id: user_id }), {
                        preserveState: true,
                        onSuccess: (page) => {
                            console.log('delete', page);
                            this.correntistas = page.props.correntistasAllByEmpresa;
                            this.showMessage('Concluído', 'Usuário Correntista');
                            this.deleteCorrentistaDialog = false;
                        },
                        onError: (errors) => {
                            this.deleteCorrentistaDialog = false
                            this.showMessage('Erro', 'Erro ao Deletar', false);
                            this.resetCorrentista();
                            console.log(errors)
                        },
                },
                );
            });

        },
        findIndexById(id) {
            let index = -1;
            for (let i = 0; i < this.correntistas.length; i++) {
                if (this.correntistas[i].id === id) {
                    index = i;
                    break;
                }
            }
            return index;
        },
        resetCorrentista()
        {
            console.log('Reset Values')
            this.correntista = {...correntistaValueDefault};
        },
        exportCSV() {
            this.$refs.dt.exportCSV();
        },
    }
}
</script>

<style lang="scss" scoped>
.table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    @media screen and (max-width: 960px) {
        align-items: start;
	}
}

.confirmation-content {
    display: flex;
    align-items: center;
    justify-content: center;
}
@media screen and (max-width: 960px) {
	::v-deep(.p-toolbar) {
		flex-wrap: wrap;

		.p-button {
            margin-bottom: 0.25rem;
        }
	}
}
</style>

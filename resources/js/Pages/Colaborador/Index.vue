<template>
    <app-layout>
        <DataTable ref="dt" :value="colaboradores" v-model:selection="selectedColaboradores" dataKey="id"
            :paginator="true" :rows="10" :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" :rowsPerPageOptions="[5,10,25]"
            currentPageReportTemplate="Mostrando {first} para {last} de {totalRecords} colaboradores">
            <Toast position="top-right" />
            <Toolbar class="p-mb-4">
                <template #left>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Colaborador
                    </h2>
                </template>
                <template #right >
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Pesquise..." />
                    </span>
                    <div>
                        <Button label="Novo" icon="pi pi-plus" class="p-button-success p-mr-2" @click="openNew" />
                    </div>
                </template>
            </Toolbar>

            <Column field="nome_completo" header="Nome" sortable></Column>
            <Column field="cpf_cnpj" header="CPF/CNPJ" sortable></Column>
            <Column field="rg" header="RG" sortable></Column>
            <Column field="registro" header="Registro" sortable></Column>
            <Column>
                <template #body="slotProps">
                        <Button v-show="slotProps.data.inativo" label="Inativo" class="p-button-danger" />
                </template>
            </Column>
            <Column>
                <template #body="slotProps">
                    <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="editColaborador(slotProps.data)" />
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="colaboradorDialog" @hide="resetColaborador()" :style="{width: '85%'}" header="Colaborador" :modal="true" class="p-fluid">
            <div class="grid grid-cols-3 gap-4">
                <div class="p-field">
                    <label for="name">Nome Completo</label>
                    <InputText id="name" v-model.trim="colaborador.nome_completo" required="true" autofocus :class="{'p-invalid': submitted && !colaborador.nome_completo}" />
                    <small class="p-invalid" v-if="submitted && !colaborador.nome_completo">Nome é obrigatório.</small>
                </div>
                <div class="p-field">
                    <label for="cpf_cnpj">CPF/CNPJ</label>
                    <InputText id="cpf_cnpj" v-model.trim="colaborador.cpf_cnpj" autofocus />
                </div>
                <div class="p-field">
                    <label for="registro">Registro</label>
                    <InputText id="registro" v-model.trim="colaborador.registro" autofocus />
                </div>
                <div class="p-field">
                    <label for="telefone">Telefone</label>
                    <InputText id="telefone" v-model.trim="colaborador.telefone" autofocus />
                </div>
                <div class="p-field">
                    <label for="bairro">Bairro</label>
                    <InputText id="bairro" v-model.trim="colaborador.bairro" autofocus />
                </div>
                <div class="p-field">
                    <label for="rua">Rua</label>
                    <InputText id="rua" v-model.trim="colaborador.rua" autofocus />
                </div>
                <div class="p-field">
                    <label for="numero">Número</label>
                    <InputText id="numero" v-model.trim="colaborador.numero" autofocus />
                </div>
                <div class="p-field">
                    <label for="cidade">Cidade</label>
                    <InputText id="cidade" v-model.trim="colaborador.cidade" autofocus />
                </div>
                <div class="p-field">
                    <label for="estado">Estado</label>
                    <InputText id="estado" v-model.trim="colaborador.estado" autofocus />
                </div>
                <div class="p-formgrid p-grid">
                    <div class="p-field p-col">
                        <label for="comissao">Comissao</label>
                        <InputNumber  id="comissao" v-model="colaborador.comissao" mode="decimal" suffix="%" :minFractionDigits="2" :maxFractionDigits="2"/>
                    </div>
                </div>
                <div class="p-field col-span-3">
                    <label for="experiencia">Experiência</label>
                    <Textarea id="experiencia" v-model="colaborador.experiencia" rows="3" cols="20" />
                </div>
                <div class="p-field col-span-1" v-if="!create" >
                    <Checkbox v-model="colaborador.inativo"  :binary="true" /> Inativo
                </div>
            </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="hideDialog"/>
                <Button label="Salvar" icon="pi pi-check" class="p-button-text" @click="saveColaborador($page.props.user.id)" />
            </template>
        </Dialog>

        <Dialog v-model:visible="deleteColaboradorDialog" @hide="resetColaborador()" :style="{width: '450px'}" header="Confirme" :modal="true">
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle p-mr-3" style="font-size: 2rem" />
                <span v-if="colaborador">Tem certeza de que deseja excluir<b>{{colaborador.nome_completo}}</b>?</span>
            </div>
            <template #footer>
                <Button label="Não" icon="pi pi-times" class="p-button-text" @click="hideDeleteDialog()"/>
                <Button label="Sim" icon="pi pi-check" class="p-button-text" @click="deleteColaborador($page.props.user.id)" />
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
import JetApplicationMark from '@/Jetstream/ApplicationMark';
import JetBanner from '@/Jetstream/Banner';
import JetDropdown from '@/Jetstream/Dropdown';
import JetDropdownLink from '@/Jetstream/DropdownLink';
import JetNavLink from '@/Jetstream/NavLink';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink';
import { Inertia } from '@inertiajs/inertia';
import { validateUserAuth } from '../Auth';
import { FilterMatchMode } from 'primevue/api';

const colaboradorValueDefault = {
    "id": 0,
    "nome_completo": "",
    "cpf_cnpj": "",
    "rg": "",
    "registro": "",
    "rua": "",
    "comissao": 0.00,
    "telefone": "",
    "bairro": "",
    "cidade": "",
    "numero": "",
    "estado": "",
    "experiencia": "",
    "user_id": 0,
    "inativo": false,
    "updated_at": null,
    "created_at": null,
}

export default {
    props: ["colaboradoresAllByEmpresa", "query"],
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
        Checkbox,
        JetApplicationMark,
        JetBanner,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        JetResponsiveNavLink,
    },
    data() {
        return {
            create: false,
            colaboradores: null,
            colaboradorDialog: false,
            deleteColaboradorDialog: false,
            deleteColaboradoresDialog: false,
            colaborador: {
                "id": 0,
                "nome_completo": "",
                "cpf_cnpj": "",
                "rg": "",
                "registro": "",
                "rua": "",
                "comissao": 0.00,
                "telefone": "",
                "bairro": "",
                "cidade": "",
                "numero": "",
                "estado": "",
                "experiencia": "",
                "user_id": 0,
                "inativo": false,
                "updated_at": null,
                "created_at": null,

            },
            selectedColaboradores: null,
            filters: {},
            submitted: false,
        }
    },
    created() {
        validateUserAuth(Inertia, this.$props.query, () => {
            this.colaboradores = this.$props.colaboradoresAllByEmpresa;
        });
        this.initFilters();
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
            this.colaboradorDialog = true;
            console.log(colaboradorValueDefault)
        },
        showMessage(summary, detail,type = true) {
            this.$toast.add({severity: type ? 'success' : 'error', summary, detail, life: 3000});
        },
        hideDialog() {
            this.colaboradorDialog = false;
            this.submitted = false;
        },
        hideDeleteDialog() {
            this.deleteColaboradorDialog = false;
        },
        saveColaborador(user_id) {
            this.submitted = true;
            this.colaborador.user_id = user_id;

            validateUserAuth(Inertia, this.$props.query, async () => {

                if (this.colaborador.nome_completo.trim() && this.colaborador.user_id)
                {
                    if (this.create) {
                        const body = { query: this.$props.query, ...this.colaborador};

                       await Inertia.post(this.route('colaborador.create'), body, {
                           preserveState: true,
                           onSuccess: (page) => {
                                this.colaboradores = page.props.colaboradoresAllByEmpresa;
                                this.showMessage('Concluído', 'Colaborador Cadastrado');
                            console.log(page);
                           },
                           onError: (errors) => {
                               this.showMessage('Erro', 'Erro ao Cadastrar', false);
                               console.log(errors)
                           },
                        });
                    }
                    else {
                        const {created_at,...colaborador} = {...this.colaborador};
                        colaborador.updated_at = Date().now;
                        console.log(body);
                        const body = {query: this.$props.query, colaborador};

                        await Inertia.put(this.route('colaborador.update'), body, {
                           preserveState: true,
                           onSuccess: (page) => {
                                console.log(page);
                                this.colaboradores = page.props.colaboradoresAllByEmpresa;
                                this.hideDeleteDialog();
                                this.showMessage('Concluído', 'Colaborador Atualizado');
                           },
                           onError: (errors) => {
                                this.showMessage('Erro', 'Erro ao Atualizar', false);
                                console.log(errors)
                           },
                        });
                    }
                    this.colaboradorDialog = false;
                    this.colaborador = {};
                }
            })
        },
        editColaborador(colaborador) {
            console.log(colaborador);
            this.create = false;
            this.colaborador = {...colaborador};
            this.colaboradorDialog = true;
        },
        confirmDeleteColaborador(colaborador) {
            this.colaborador = colaborador;
            this.deleteColaboradorDialog = true;
        },
        deleteColaborador(user_id) {

            const body = {...this.colaborador};

            console.log(user_id);

            validateUserAuth(Inertia, this.$props.query, async () => {
                await Inertia.delete(this.route(`colaborador.delete`,{ colaborador_id: body.id, user_id: user_id }), {
                        preserveState: true,
                        onSuccess: (page) => {
                            console.log('delete', page);
                            this.colaboradores = page.props.colaboradoresAllByEmpresa;
                            this.showMessage('Concluído', 'Usuário Deletado');
                            this.deleteColaboradorDialog = false;
                        },
                        onError: (errors) => {
                            this.deleteColaboradorDialog = false
                            this.showMessage('Erro', 'Erro ao Deletar', false);
                            console.log(errors)
                        },
                },
                );
            });
        },
        findIndexById(id) {
            let index = -1;
            for (let i = 0; i < this.colaboradores.length; i++) {
                if (this.colaboradores[i].id === id) {
                    index = i;
                    break;
                }
            }
            return index;
        },
        resetColaborador()
        {
            console.log('Reset Values')
            this.colaborador = {...colaboradorValueDefault};
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

.product-image {
    width: 50px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}

.p-dialog .product-image {
    width: 50px;
    margin: 0 auto 2rem auto;
    display: block;
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

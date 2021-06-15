<template>
    <app-layout>
        <DataTable ref="dt" :value="contratos" v-model:selection="selectedContratos" dataKey="id"
            :paginator="true" :rows="10" :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" :rowsPerPageOptions="[5,10,25]"
            currentPageReportTemplate="Mostrando {first} para {last} de {totalRecords} contratos">
            <Toast position="top-right" />
            <Toolbar class="p-mb-4">
                <template #left>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Serviços
                    </h2>
                </template>
                <template #right >
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Pesquise..." />
                    </span>
                    <div>
                        <Button label="Novo" icon="pi pi-plus" class="p-button-success p-mr-2" @click="novoContrato()" />
                    </div>
                </template>
            </Toolbar>
            <Column class="text-center" field="contrato_id" header="Contrato" sortable>
                <template #body="slotProps">
                    {{`${("00000000" + slotProps.data.contrato_id).slice(-8)}`}}
                </template>
            </Column>
            <Column class="text-center" field="correntista_nome" header="Correntista" sortable></Column>
            <Column class="text-center" field="colaborador_nome_completo" header="Colaborador" sortable></Column>
            <Column class="text-center" field="contrato_data_de_emissao" header="Emissao" sortable>
                <template #body="slotProps">
                    {{formatDateEmissao(slotProps)}}
                </template></Column>
            <Column class="text-center" field="contrato_data_de_fechamento" header="Fechamento" sortable>
                <template #body="slotProps">
                    {{formatDateFechamento(slotProps)}}
                </template>
            </Column>
            <Column class="text-center" field="contrato_valor" header="Valor" sortable>
                <template #body="slotProps">
                    {{formatCurrency(slotProps)}}
                </template>
            </Column>
            <Column field="contrato_status" header="Status" sortable>
                <template #body="slotProps">
                     <div v-show="!slotProps.data.contrato_status">
                        <Button  label="Pendente"  class="p-button-warning"/>
                    </div>
                    <div v-show="slotProps.data.contrato_status">
                        <Button  label="Aprovado" class="p-button-success"/>
                    </div>
                </template>
            </Column>
            <Column>
                <template #body="slotProps">
                    <div class="flex">
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-success p-mr-2" @click="editContrato(slotProps.data.contrato_id)"/>
                        <Button v-show="!slotProps.data.contrato_status" icon="pi pi-trash" class="p-button-rounded p-button-warning"  @click="confirmDeleteContrato(slotProps.data)"  />
                    </div>
                </template>
            </Column>
            </DataTable>
            <Dialog v-model:visible="deleteContratoDialog" :style="{width: '450px'}" header="Confirme" :modal="true">
                <div class="confirmation-content">
                    <i class="pi pi-exclamation-triangle p-mr-3" style="font-size: 2rem" />
                    <span v-if="contrato">Tem certeza de que deseja excluir o contrato?</span>
                </div>
                <template #footer>
                    <Button label="Não" icon="pi pi-times" class="p-button-text" @click="hideDeleteDialog()"/>
                    <Button label="Sim" icon="pi pi-check" class="p-button-text" @click="deleteContrato($page.props.user.id)" />
                </template>
            </Dialog>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetApplicationMark from '@/Jetstream/ApplicationMark'
import JetBanner from '@/Jetstream/Banner'
import JetDropdown from '@/Jetstream/Dropdown'
import JetDropdownLink from '@/Jetstream/DropdownLink'
import JetNavLink from '@/Jetstream/NavLink'
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
import moment from 'moment';
import { Inertia } from '@inertiajs/inertia'
import { validateUserAuth } from '../Auth';
import { FilterMatchMode } from 'primevue/api';

export default {
    props: ["contratosAll", "query"],
    components: {
        AppLayout,
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
            contratos: null,
            contratoDialog: false,
            deleteContratoDialog: false,
            contrato: {
                'contrato_id': null,
                'contrato_data_de_fechamento': null,
                'contrato_data_de_emissao': null,
                'contrato_status': null,
                'contrato_descricao_do_servico': null,
                'contrato_valor': null,
                'contrato_acerto_pago': null,
                'contrato_percentual_comissao_colaborador': null,
                'contrato_user_id': null,
                'contrato_acerto_id': null,
                'contrato_colaborador_id': null,
                'contrato_correntista_id': null,
                'colaborador_id': null,
                'colaborador_nome_completo': null,
                'colaborador_cpf_cnpj': null,
                'colaborador_registro': null,
                'colaborador_rg': null,
                'colaborador_comissao': null,
                'colaborador_telefone': null,
                'colaborador_bairro': null,
                'colaborador_rua': null,
                'colaborador_cidade': null,
                'colaborador_numero': null,
                'colaborador_estado': null,
                'colaborador_experiencia': null,
                'colaborador_inativo': null,
                'colaborador_user_id': null,
                'correntista_id': null,
                'correntista_cpf': null,
                'correntista_cnpj': null,
                'correntista_telefone': null,
                'correntista_bairro': null,
                'correntista_rua': null,
                'correntista_cidade': null,
                'correntista_numero': null,
                'correntista_estado': null,
                'correntista_inativo': null,
                'correntista_user_id': null,
            },
            selectedContratos: null,
            filters: {},
            submitted: false,
        }
    },
    beforeCreate(){
    },
    created() {
        this.initFilters();
    },
    mounted() {
        this.contratos = this.$inertia.page.props.contratosAll;
    },
    methods: {
        initFilters() {
            this.filters = {
                'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
            }
        },
        formatCurrency(value) {
            return Number(value.data.contrato_valor).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
        },
        formatDateEmissao(value) {
            return moment(value.data.contrato_data_de_emissao).format("DD/MM/YYYY h:mm:ss");
        },
        formatDateFechamento(value) {
            if(value.data.contrato_data_de_fechamento) {
                return moment(value.data.contrato_data_de_fechamento).format('DD/MM/YYYY h:mm:ss');
            } else {
                return null;
            }
        },
        showMessage(summary, detail,type = true) {
            this.$toast.add({severity: type ? 'success' : 'error', summary, detail, life: 3000});
        },
        hideDialog() {
            this.contratoDialog = false;
            this.submitted = false;
        },
        hideDeleteDialog() {
            this.deleteContratoDialog = false;
        },
        confirmDeleteContrato(contrato) {
            this.contrato = contrato;
            this.deleteContratoDialog = true;
        },
        deleteContrato(user_id) {
            const body = {...this.contrato};

            console.log(body);

            validateUserAuth(Inertia, this.$props.query, async () => {
                await Inertia.delete(this.route(`contrato.delete`,{ contrato_id: body.contrato_id, user_id: user_id }), {
                        preserveState: true,
                        onSuccess: (page) => {
                            console.log('delete contrato', page);
                            this.contratos = page.props.contratosAll;
                            this.showMessage('Concluído', 'Deletado');
                            this.deleteContratoDialog = false;
                        },
                        onError: (errors) => {
                            this.deleteContratoDialog = false
                            this.showMessage('Erro', 'Erro ao Deletar', false);
                            console.log(errors);
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
        exportCSV() {
            this.$refs.dt.exportCSV();
        },
        novoContrato() {
            this.$inertia.get(route('contrato.emissao'), {
                empresa_id: this.$inertia.page.props.user.empresa_id,
                user_id: this.$inertia.page.props.user.id,
            });
        },
        editContrato(contrato_id) {
            Inertia.get(route('contrato.edit'), {
                empresa_id: this.$inertia.page.props.user.empresa_id,
                user_id: this.$inertia.page.props.user.id,
                contrato_id,
            });
        },
        dataBase() {
            let data = new Date();
            let data2 = new Date(data.valueOf() - data.getTimezoneOffset() * 60000);
            const  dataBase = data2.toISOString().replace(/\.\d{3}Z$/, '');

            return dataBase;
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

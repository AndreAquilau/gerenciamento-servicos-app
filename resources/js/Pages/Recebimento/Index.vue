<template>
    <app-layout>

        <DataTable
            ref="dt" :value="recebimentos" v-model:selection="selectedRecebimentos" dataKey="id"
            :paginator="true" :rows="10" :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" :rowsPerPageOptions="[5,10,25]"
            currentPageReportTemplate="Mostrando {first} para {last} de {totalRecords} Recebimentos">
            <Toast position="top-right" />
            <Toolbar class="p-mb-4">
                <template #left>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Recebimentos
                    </h2>
                </template>

                <template #right>
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Pesquise..." />
                    </span>
                </template>
            </Toolbar>
            <Column class="text-center" field="recebe_contrato_id" header="Documento" sortable>
                <template #body="slotProps">
                    {{`${("00000000" + slotProps.data.contrato_id).slice(-8)} - ${slotProps.data.recebe_ordem_documento}/${slotProps.data.recebe_ordem_documento_final}`}}
                </template>
            </Column>
            <Column class="text-center" field="colaborador_nome_completo" header="Colaborador" sortable></Column>
            <Column class="text-center" field="recebe_data_de_emissao" header="Emissao" sortable>
                <template #body="slotProps">
                    {{formatDateEmissao(slotProps)}}
                </template></Column>
            <Column class="text-center" field="recebe_data_de_pagamento" header="Pagamento" sortable>
                <template #body="slotProps">
                    {{formatDateFechamento(slotProps)}}
                </template>
            </Column>
            <Column class="text-center" field="contrato_valor" header="Valor" sortable>
                <template #body="slotProps">
                    {{formatCurrency({data: slotProps.data.recebe_total})}}
                </template>
            </Column>
            <Column class="text-center" field="recebe_pago" header="Valor Pago" sortable>
                <template #body="slotProps">
                    {{formatCurrency({data: slotProps.data.recebe_pago})}}
                </template>
            </Column>
            <Column class="text-center" field="recebe_restante" header="Restante" sortable>
                <template #body="slotProps">
                    {{formatCurrency({data: slotProps.data.recebe_restante})}}
                </template>
            </Column>
            <Column field="acerto_status" header="Status" sortable>
                <template #body="slotProps">
                     <div v-show="!slotProps.data.recebe_status">
                        <Button  label="À Receber"  class="p-button-warning"/>
                    </div>
                    <div v-show="slotProps.data.recebe_status && Number(slotProps.data.recebe_restante) > 0.00">
                        <Button  label="Recebido" class="p-button-success"/>
                    </div>
                    <div v-show="Number(slotProps.data.recebe_restante) === 0.00 ">
                        <Button  label="Quitado" class="p-button-secondary"/>
                    </div>
                </template>
            </Column>
            <Column>
                <template #body="slotProps">
                    <Button icon="pi pi-dollar" class="p-button-rounded p-button-success p-mr-2" @click="openEdit(slotProps.data)" />
                </template>
            </Column>
        </DataTable>

        <Dialog
        v-model:visible="recebimentoDialog"
        :style="{width: '75%'}"
        header="Recebimento"
        :hide="resetRecebimento()"
        :modal="true" class="p-fluid">
            <div class="card mb-8">
                <div class="flex">
                    <div class="p-field p-col-12 p-md-4 mr-2">
                        <span class="p-float-label">
                            <InputText :disabled="true"  id="data_de_emissao" v-model="recebimento.recebe_data_de_emissao" />
                            <label for="Data De Emissao">Data De Emissão</label>
                        </span>
                    </div>
                    <div class="p-field p-col-12 p-md-4 ">
                        <span class="p-float-label">
                            <InputText :disabled="true" id="data_de_pagamento" v-model="recebimento.recebe_data_de_pagamento" />
                            <label for="Data De Fechamento">Data De Pagamento</label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card">
                <DataTable :value="[recebimento]" editMode="cell" class="editable-cells-table" responsiveLayout="scroll">
                    <Column field="recebe_contrato_id" header="Contrato" style="width:15%"></Column>
                    <Column field="correntista_nome" header="Correntista" style="width:20%"></Column>
                    <Column field="recebe_total" header="Total" style="width:15%">
                         <InputNumber v-model="slotProps.data[slotProps.column.props.field]" prefix="R$ "  mode="decimal" :minFractionDigits="2" :maxFractionDigits="2"/>
                    </Column>
                </DataTable>
            </div>
            <div class="card mb-8">
                <Editor v-model="recebimento.contrato_descricao_do_servico" readonly editorStyle="height: 50px">
                    <template #toolbar>
                        <span class="ql-formats">
                            <span>Serviço Prestado: {{recebimento.correntista_nome}}</span>
                        </span>
                    </template>
                </Editor>
            </div>
            <div class="mt-2">

                <div class="p-field p-col-12 p-md-4 mt-8">
                    <span class="p-float-label">
                        <InputNumber id="valor" v-model="recebimento.recebe_pago" prefix="R$ "  mode="decimal" :minFractionDigits="2" :maxFractionDigits="2"/>
                        <label for="valor">Pagar</label>
                    </span>
                </div>
            </div>
            <template #footer>
                <Button label="Voltar" icon="pi pi-times" class="p-button-text" @click="hideDialog()"/>
                <Button v-show="!recebimento.recebe_status" label="Pagar" icon="pi pi-check" class="p-button-text" @click="saveRecebimento()" />
            </template>
        </Dialog>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import { Inertia } from '@inertiajs/inertia';
import { FilterMatchMode } from 'primevue/api';
import moment from 'moment';

const recebimentoValueDefault = {
    'contrato_id': null,
    'contrato_data_de_fechamento': null,
    'contrato_data_de_emissao': null,
    'contratos.status AS contrato_status': null,
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
    'correntista_nome': null,
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
    'acerto_id': null,
    'acerto_valor_colaborador': null,
    'acerto_valor_empresa': null,
    'acerto_pago': null,
    'acerto_restante': null,
    'acerto_total': null,
    'acerto_desconto': null,
    'acerto_acrescimo': null,
    'acerto_status': null,
    'acerto_data_de_pagamento': null,
    'acerto_data_de_emissao': null,
    'acerto_created_at': null,
    'acerto_updated_at': null,
    'acerto_user_id': null,
    'acerto_recebe_id': null,
    'acerto_contrato_id': null,
    'recebe_id': null ,
    'recebe_documento': null ,
    'recebe_ordem_documento': null,
    'recebe_ordem_documento_final': null,
    'recebe_desconto': null,
    'recebe_acrescimo': null,
    'recebe_pago ': null,
    'recebe_restante': null,
    'recebe_total': null,
    'recebe_status': null,
    'recebe_data_de_emissao': null,
    'recebe_data_de_vencimento': null,
    'recebe_data_de_pagamento': null,
    'recebe_created_at': null,
    'recebe_updated_at': null,
    'recebe_user_id': null,
    'recebe_acerto_id': null,
    'recebe_contrato_id': null
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
    props:["recebimentosAll", "query"],
    data() {
        return {
            recebimentos: null,
            recebimentoDialog: false,
            recebimento: {
                'contrato_id': null,
                'contrato_data_de_fechamento': null,
                'contrato_data_de_emissao': null,
                'contratos.status AS contrato_status': null,
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
                'correntista_nome': null,
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
                'recebe_id': null ,
                'recebe_documento': null ,
                'recebe_ordem_documento': null,
                'recebe_ordem_documento_final': null,
                'recebe_desconto': null,
                'recebe_acrescimo': null,
                'recebe_pago ': null,
                'recebe_restante': null,
                'recebe_total': null,
                'recebe_status': null,
                'recebe_data_de_emissao': null,
                'recebe_data_de_vencimento': null,
                'recebe_data_de_pagamento': null,
                'recebe_created_at': null,
                'recebe_updated_at': null,
                'recebe_user_id': null,
                'recebe_acerto_id': null,
                'recebe_contrato_id': null
            },
            acerto: {
                valor_colaborador: 0.00,
                valor_empresa: 0.00,
                pago: 0.00,
                restante: 0.00,
                total: 0.00,
                desconto: 0.00,
                acrescimo: 0.00,
                status: false,
                data_de_emissao: null,
                data_de_pagamento: null,
                updated_at: null,
                created_at: null,
                contrato_id: null,
                recebe_id: null,
                user_id: null,
            },
            selectedRecebimentos: null,
            filters: {},
            submitted: false,
        }
    },
    created() {
        console.log("props", this.$inertia.page.props);
        this.recebimentos = this.$inertia.page.props.recebimentosAll;
        console.log(this.recebimentos);
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
        dataBase() {
            let data = new Date();
            let data2 = new Date(data.valueOf() - data.getTimezoneOffset() * 60000);
            const  dataBase = data2.toISOString().replace(/\.\d{3}Z$/, '');

            return dataBase;
        },
        formatCurrency(value) {
            return Number(value.data).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
        },
        formatDateEmissao(value) {
            return moment(value.data.recebe_data_de_emissao).format("DD/MM/YYYY h:mm:ss");
        },
        formatDateFechamento(value) {
            if(value.data.recebe_data_de_pagamento) {
                return moment(value.data.recebe_data_de_pagamento).format('DD/MM/YYYY h:mm:ss');
            } else {
                return null;
            }
        },
        openEdit(recebimento) {
            this.submitted = false;
            this.recebimentoDialog = true;
            this.recebimento = {...recebimento};

            console.log("Recebimento", this.recebimento);

            if(!recebimento.recebimento_status){
                this.recebimento.recebe_data_de_emissao =  moment(recebimento.recebe_data_de_emissao).format("DD/MM/YYYY h:mm:ss");
                this.recebimento.recebe_data_de_pagamento = recebimento.recebe_data_de_pagamento && moment(recebimento.recebe_data_de_pagamento).format("DD/MM/YYYY h:mm:ss");
                this.recebimento.recebe_pago = recebimento.recebe_total;
            } else {
                this.recebimento.recebe_data_de_emissao =  moment(recebimento.recebe_data_de_emissao).format("DD/MM/YYYY h:mm:ss");
                this.recebimento.recebe_data_de_pagamento = recebimento.recebe_data_de_pagamento && moment(recebimento.recebe_data_de_pagamento).format("DD/MM/YYYY h:mm:ss");
            }
        },
        showMessage(summary, detail,type = true) {
            this.$toast.add({severity: type ? 'success' : 'error', summary, detail, life: 3000});
        },
        hideDialog() {
            this.recebimentoDialog = false;
            this.submitted = false;
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
        resetRecebimento()
        {
            console.log('Reset Values')
        },
        exportCSV() {
            this.$refs.dt.exportCSV();
        },
        saveRecebimento() {
            const recebimento = {...this.recebimento};
            const acerto = {...this.acerto};

            //pre-dados recebimento
            recebimento.recebe_restante = recebimento.recebe_total - recebimento.recebe_pago;
            recebimento.recebe_updated_at = this.dataBase();
            recebimento.recebe_data_de_pagamento = this.dataBase();
            recebimento.recebe_status = true;

            // pre-dados acerto
            acerto.valor_colaborador = Number(recebimento.recebe_total) * (recebimento.contrato_percentual_comissao_colaborador/100);
            acerto.valor_empresa = (recebimento.recebe_total * ((100.00 - recebimento.contrato_percentual_comissao_colaborador)/100));
            acerto.restante = acerto.valor_colaborador
            acerto.total = recebimento.recebe_total;
            acerto.data_de_emissao = this.dataBase();
            acerto.created_at = this.dataBase();
            acerto.updated_at = this.dataBase();
            acerto.contrato_id = recebimento.contrato_id;
            acerto.recebe_id = recebimento.recebe_id
            acerto.user_id = recebimento.contrato_user_id;

            console.log("update recebimento ",recebimento )

            Inertia.put(route('recebimento.update', this.$props.query), {
                query: {
                    empresa_id: this.$inertia.page.props.user.empresa_id,
                    user_id: this.$inertia.page.props.user.id,

                },
                recebimento,
                acerto,
            },
            {
                errorBag: 'updateProfileInformation',
                preserveScroll: true,
                onSuccess: (page) => {
                    console.log("props", page.props);
                    this.recebimentos = page.props.recebimentosAll;
                    console.log(this.acertos);
                    this.showMessage("Sucesso", "Recebimento  Pago");
                    this.recebimentoDialog = false;
                    this.resetRecebimento();
                },
            });

        },
    }
}

</script>

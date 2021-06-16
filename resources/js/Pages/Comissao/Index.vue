<template>
    <app-layout>

        <DataTable
            ref="dt" :value="acertos" v-model:selection="selectedAcertos" dataKey="id"
            :paginator="true" :rows="10" :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown" :rowsPerPageOptions="[5,10,25]"
            currentPageReportTemplate="Mostrando {first} para {last} de {totalRecords} Comissões">
            <Toast position="top-right" />
            <Toolbar class="p-mb-4">
                <template #left>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Comissões
                    </h2>
                </template>

                <template #right>
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText v-model="filters['global'].value" placeholder="Pesquise..." />
                    </span>
                </template>
            </Toolbar>
            <Column class="text-center" field="acerto_contrato_id" header="Documento" sortable>
                <template #body="slotProps">
                    {{`${("00000000" + slotProps.data.contrato_id).slice(-8)} - ${slotProps.data.recebe_ordem_documento}/${slotProps.data.recebe_ordem_documento_final}`}}
                </template>
            </Column>
            <Column class="text-center" field="colaborador_nome_completo" header="Colaborador" sortable></Column>
            <Column class="text-center" field="acerto_created_at" header="Emissao" sortable>
                <template #body="slotProps">
                    {{formatDateEmissao(slotProps)}}
                </template></Column>
            <Column class="text-center" field="acerto_data_de_pagamento" header="Pagamento" sortable>
                <template #body="slotProps">
                    {{formatDateFechamento(slotProps)}}
                </template>
            </Column>
            <Column class="text-center" field="acerto_valor_colaborador" header="Valor" sortable>
                <template #body="slotProps">
                    {{formatCurrency({data: slotProps.data.acerto_valor_colaborador})}}
                </template>
            </Column>
            <Column class="text-center" field="acerto_pago" header="Valor Pago" sortable>
                <template #body="slotProps">
                    {{formatCurrency({data: slotProps.data.acerto_pago})}}
                </template>
            </Column>
            <Column class="text-center" field="acerto_restante" header="Restante" sortable>
                <template #body="slotProps">
                    {{formatCurrency({data: slotProps.data.acerto_restante})}}
                </template>
            </Column>
            <Column field="acerto_status" header="Status" sortable>
                <template #body="slotProps">
                     <div v-show="!slotProps.data.acerto_status">
                        <Button  label="A Pagar"  class="p-button-warning"/>
                    </div>
                    <div v-show="slotProps.data.acerto_status">
                        <Button  label="Pago" class="p-button-success"/>
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
        v-model:visible="acertoDialog"
        :style="{width: '85%'}"
        header="Pagamento De Comissão"
        :hide="resetAcerto()"
        :modal="true" class="p-fluid">
            <div class="card mb-8">
                <div class="flex">
                    <div class="p-field p-col-12 p-md-4 mr-2">
                        <span class="p-float-label">
                            <InputText :disabled="true"  id="data_de_emissao" v-model="acerto.acerto_created_at" />
                            <label for="Data De Emissao">Data De Emissão</label>
                        </span>
                    </div>
                    <div class="p-field p-col-12 p-md-4 ">
                        <span class="p-float-label">
                            <InputText :disabled="true" id="data_de_pagamento" v-model="acerto.acerto_data_de_pagamento" />
                            <label for="Data De Fechamento">Data De Pagamento</label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card">
                <DataTable :value="[acerto]" editMode="cell" class="editable-cells-table" responsiveLayout="scroll">
                    <Column field="acerto_contrato_id" header="Contrato" style="width:15%">
                        <template #body="slotProps">
                            {{`${("00000000" + slotProps.data.contrato_id).slice(-8)}`}}
                        </template>
                    </Column>
                    <Column field="recebe_ordem_documento" header="Documento" style="width:15%">
                        <template #body="slotProps">
                            {{`${("00000000" + slotProps.data.contrato_id).slice(-8)} - ${slotProps.data.recebe_ordem_documento}/${slotProps.data.recebe_ordem_documento_final}`}}
                        </template>
                    </Column>
                    <Column field="colaborador_nome_completo" header="Colaborador" style="width:20%"></Column>
                    <Column field="acerto_total" header="Valor Recebido" style="width:15%">
                         <InputNumber v-model="slotProps.data[slotProps.column.props.field]" prefix="R$ "  mode="decimal" :minFractionDigits="2" :maxFractionDigits="2"/>
                    </Column>
                    <Column field="acerto_valor_empresa" header="Comissão Empresa" style="width:25%">
                        <template #editor="slotProps">
                         <InputNumber v-model="slotProps.data[slotProps.column.props.field]" prefix="R$ "  mode="decimal" :minFractionDigits="2" :maxFractionDigits="2"/>
                        </template>
                    </Column>
                    <Column field="acerto_valor_colaborador" header="Comissão Colaborador" style="width:30%" >
                        <template #editor="slotProps">
                         <InputNumber v-model="slotProps.data[slotProps.column.props.field]" prefix="R$ "  mode="decimal" :minFractionDigits="2" :maxFractionDigits="2"/>
                        </template>
                    </Column>
                </DataTable>
            </div>
            <div class="card mb-8">
                <Editor v-model="acerto.contrato_descricao_do_servico" readonly editorStyle="height: 50px">
                    <template #toolbar>
                        <span class="ql-formats">
                            <span>Serviço Prestado: {{acerto.correntista_nome}}</span>
                        </span>
                    </template>
                </Editor>
            </div>
            <div class="mt-2">
                <div>
                    <h1 class="font-medium">Fatura Da Comissão</h1>
                </div>

                <div class="p-field p-col-12 p-md-4 mt-8">
                    <span class="p-float-label">
                        <InputNumber id="valor" v-model="acerto.acerto_pago" prefix="R$ "  mode="decimal" :minFractionDigits="2" :maxFractionDigits="2"/>
                        <label for="valor">Pagar</label>
                    </span>
                </div>
            </div>
            <template #footer>
                <Button label="Voltar" icon="pi pi-times" class="p-button-text" @click="hideDialog"/>
                <Button icon="pi pi-check" label="Cancelar" class="p-button-danger" @click="cancelarPagamento()"  v-show="acerto.acerto_status"/>
                <Button v-show="!acerto.acerto_status" label="Pagar" icon="pi pi-check" class="p-button-text" @click="saveAcerto()" />
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

const acertoValueDefault = {
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
    'recebe_pago': null,
    'recebe_restante': null,
    'recebe_total': null,
    'recebe_status': null,
    'recebe_data_de_emissao': null,
    'recebe_data_de_vencimento': null,
    'recebe_data_de_pagamento': null,
    'recebe_created_at': null,
    'recebe_updated_at': null,
    'recebe_user_id': null,
    'recebe.acerto_id': null,
    'recebe.contrato_id': null
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
    props:["acertosAll", "query"],
    data() {
        return {
            acertos: null,
            acertoDialog: false,
            acerto: {
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
                'recebe_pago': null,
                'recebe_restante': null,
                'recebe_total': null,
                'recebe_status': null,
                'recebe_data_de_emissao': null,
                'recebe_data_de_vencimento': null,
                'recebe_data_de_pagamento': null,
                'recebe_created_at': null,
                'recebe_updated_at': null,
                'recebe_user_id': null,
                'recebe.acerto_id': null,
                'recebe.contrato_id': null
            },
            selectedAcertos: null,
            filters: {},
            submitted: false,
        }
    },
    created() {
        console.log("props", this.$inertia.page.props);
        this.acertos = this.$inertia.page.props.acertosAll;
        console.log(this.acertos);
        this.initFilters();
    },
    mounted() {
        console.log(this.$inertia.page.props.user);
    },
    methods: {
        cancelarPagamento(){

            const acerto = {};

            acerto.valor_colaborador = Number(this.acerto.acerto_total) * (this.acerto.contrato_percentual_comissao_colaborador/100);
            acerto.valor_empresa = (this.acerto.acerto_total * ((100.00 - this.acerto.contrato_percentual_comissao_colaborador)/100));
            acerto.pago = 0.00;
            acerto.restante = acerto.valor_colaborador;
            acerto.contrato_id = this.acerto.acerto_contrato_id;
            acerto.id = this.acerto.acerto_id;
            acerto.data_de_pagamento = null;
            acerto.status = false;
            acerto.updated_at = this.dataBase();

            console.log("update acerto",acerto)

            Inertia.put(route('comissao.cancelar', this.$props.query), {
                query: {
                    empresa_id: this.$inertia.page.props.user.empresa_id,
                    user_id: this.$inertia.page.props.user.id,
                },
                acerto: acerto,
            },
            {
                errorBag: 'updateProfileInformation',
                preserveScroll: true,
                onSuccess: (page) => {
                    console.log("props", page.props);
                    this.acertos = page.props.acertosAll;
                    console.log(this.acertos);
                    this.showMessage("Sucesso", "Acerto Pago");
                    this.acertoDialog = false;
                    this.resetAcerto();
                },
            });
        },
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
            return moment(value.data.acerto_created_at).format("DD/MM/YYYY h:mm:ss");
        },
        formatDateFechamento(value) {
            if(value.data.acerto_data_de_pagamento) {
                return moment(value.data.acerto_data_de_pagamento).format('DD/MM/YYYY h:mm:ss');
            } else {
                return null;
            }
        },
        openEdit(acerto) {
            console.log(acerto);
            this.submitted = false;
            this.acertoDialog = true;
            this.acerto = {...acerto};

            if(!acerto.acerto_status){
                console.log(acerto);
                this.acerto.acerto_created_at =  moment(acerto.acerto_created_at).format("DD/MM/YYYY h:mm:ss");
                this.acerto.acerto_data_de_pagamento = acerto.acerto_data_de_pagamento && moment(acerto.acerto_data_de_pagamento).format("DD/MM/YYYY h:mm:ss");
                this.acerto.acerto_pago = acerto.acerto_valor_colaborador;
            } else {
                console.log(acerto);
                this.acerto.acerto_created_at =  moment(acerto.acerto_created_at).format("DD/MM/YYYY h:mm:ss");
                this.acerto.acerto_data_de_pagamento = acerto.acerto_data_de_pagamento && moment(acerto.acerto_data_de_pagamento).format("DD/MM/YYYY h:mm:ss");
            }
        },
        showMessage(summary, detail,type = true) {
            this.$toast.add({severity: type ? 'success' : 'error', summary, detail, life: 3000});
        },
        hideDialog() {
            this.resetAcerto();
            this.acertoDialog = false;
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
        resetAcerto()
        {
            console.log('Reset Values')
            //this.acerto = {...acertoValueDefault};
        },
        exportCSV() {
            this.$refs.dt.exportCSV();
        },
        saveAcerto() {
            const acerto = {...this.acerto};
            acerto.acerto_updated_at = this.dataBase();
            acerto.acerto_data_de_pagamento = this.dataBase();

            console.log("update acerto",acerto)

            Inertia.put(route('comissao.update', this.$props.query), {
                query: {
                    empresa_id: this.$inertia.page.props.user.empresa_id,
                    user_id: this.$inertia.page.props.user.id,
                },
                acerto: acerto,
            },
            {
                errorBag: 'updateProfileInformation',
                preserveScroll: true,
                onSuccess: (page) => {
                    console.log("props", page.props);
                    this.acertos = page.props.acertosAll;
                    console.log(this.acertos);
                    this.showMessage("Sucesso", "Acerto Pago");
                    this.acertoDialog = false;
                    this.resetAcerto();
                },
            });

        },
    }
}

</script>

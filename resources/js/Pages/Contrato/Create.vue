<template>
    <app-layout>
        <div class="p-5 px-16">
            <Toast position="top-right" />
            <Card class="mt-2">
                <template  #header>
                    <div class=" p-4">
                        <div class="flex justify-end" v-show="contrato.status === null && contrato.status !== false">
                            <Button  label="Aguardando" />
                        </div>
                        <div class="flex justify-end" v-show="contrato.status === false && contrato.status !== null">
                            <Button  label="Pendente"  class="p-button-warning"/>
                        </div>
                        <div class="flex justify-end" v-show="contrato.status">
                            <Button  label="Aprovado" class="p-button-success"/>
                        </div>
                        <div class="text-center w-full font-bold">
                            <h1 class="text-2xl">Ordem De Serviço</h1>
                        </div>
                        <div class="mt-10 px-6">
                            <div class="flex justify-between">
                                <div class="flex">
                                    <div class="p-field p-col-12 p-md-4 mr-2">
                                        <span class="p-float-label">
                                            <InputText :disabled="contrato.data_de_emissao ? contrato.data_de_emissao : true"  id="data_de_emissao" v-model="contrato.data_de_emissao" />
                                            <label for="Data De Emissao">Data De Emissão</label>
                                        </span>
                                    </div>
                                    <div class="p-field p-col-12 p-md-4 ">
                                        <span class="p-float-label">
                                            <InputText :disabled="contrato.data_de_fechamento ? contrato.data_de_fechamento : true" id="data_de_fechamento" v-model="contrato.data_de_fechamento" />
                                            <label for="Data De Fechamento">Data De Fechamento</label>
                                        </span>
                                    </div>
                                </div>
                                <div div class="flex">
                                    <div class="p-field p-col-12 p-md-4 mr-2">
                                        <span class="p-float-label">
                                            <InputNumber id="comissao" :disabled="contrato.id" v-model="contrato.percentual_comissao_colaborador" mode="decimal" suffix="%" :minFractionDigits="2" :maxFractionDigits="2" />
                                            <label for="comissao">Comissao</label>
                                        </span>
                                    </div>
                                    <div class="p-field">
                                        <span class="p-float-label">
                                            <AutoComplete
                                            class="autocomplete-colaborador"
                                            :disabled="contrato.id"
                                            v-model="colaborador.nome_completo"
                                            :suggestions="filteredColaboradores"
                                            @item-select="setColaboradorSelected($event)"
                                            @complete="searchColaboradores($event)"
                                            field="nome_completo"
                                            />
                                            <label for="autocomplete">
                                                <i class="pi pi-search" />
                                                Colaborador
                                            </label>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr class="border-2">
                </template>
                <template #content >
                    <div>
                        <div>
                            <h1 class="font-medium">Cliente</h1>
                        </div>
                        <div class="flex justify-between m-6">
                            <div class="p-field">
                                <span class="p-float-label">
                                    <AutoComplete
                                    class="autocomplete-correntista"
                                    v-model="correntista.nome"
                                    :disabled="contrato.id"
                                    :suggestions="filteredCorrentistas"
                                    @item-select="setCorrentistaSelected($event)"
                                    @complete="searchCorrentistas($event)"
                                    field="nome"
                                    />
                                    <label for="autocomplete">
                                        <i class="pi pi-search" />
                                        Cliente
                                    </label>
                                </span>
                            </div>
                            <div class="p-field"  v-if="correntista.cpf !== null && correntista.cpf !== ''">
                                <span class="p-float-label">
                                    <InputText id="cpf" type="text" :disabled="contrato.id" v-model="correntista.cpf"/>
                                    <label for="cpf">CPF</label>
                                </span>
                            </div>
                            <div class="p-field"  v-if="correntista.cnpj !== ''">
                                <span class="p-float-label">
                                    <InputText id="cnpj" type="text" :disabled="contrato.id" v-model="correntista.cnpj"/>
                                    <label for="cnpj">CNPJ</label>
                                </span>
                            </div>
                            <div class="p-field">
                                <span class="p-float-label">
                                    <InputText id="endereco" type="text" :disabled="contrato.id" v-model="correntista.rua" />
                                    <label for="endereco">Endereço</label>
                                </span>
                            </div>
                            <div class="p-field">
                                <span class="p-float-label">
                                    <InputText id="numero" type="text" :disabled="contrato.id" v-model="correntista.numero" />
                                    <label for="numero">Número</label>
                                </span>
                            </div>
                            <div class="p-field">
                                <span class="p-float-label">
                                    <InputText id="bairro" type="text" :disabled="contrato.id" v-model="correntista.bairro" />
                                    <label for="bairro">Bairro</label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr class="border-2">
                    <div class="mt-2">
                        <div>
                            <h1 class="font-medium">Descrição Do Serviço</h1>
                        </div>
                        <div class="m-6">
                            <Editor v-model="contrato.descricao_do_servico" v-if="contrato.id" readonly editorStyle="height: 200px">
                                <template #toolbar>
                                    <span class="ql-formats">
                                        <button class="ql-bold"></button>
                                        <button class="ql-italic"></button>
                                        <button class="ql-underline"></button>
                                    </span>
                                </template>
                            </Editor>
                            <Editor v-model="contrato.descricao_do_servico" v-else-if="!contrato.id" editorStyle="height: 200px">
                                <template #toolbar>
                                    <span class="ql-formats">
                                        <button class="ql-bold"></button>
                                        <button class="ql-italic"></button>
                                        <button class="ql-underline"></button>
                                    </span>
                                </template>
                            </Editor>
                        </div>
                    </div>
                    <hr class="border-2">
                    <div class="mt-2">
                        <div class="flex">
                            <div class="m-6 mt-10">
                                <div class="p-field p-col-12 p-md-4">
                                    <span class="p-float-label">
                                        <InputNumber
                                        id="total"
                                        v-model="contrato.valor"
                                        :disabled="contrato.id"
                                        prefix="R$ "
                                        mode="decimal"
                                        :minFractionDigits="2"
                                        :maxFractionDigits="2"
                                        />
                                        <label for="valor">Total</label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template #footer>
                    <div class="flex justify-end">
                        <div class="ml-2">
                            <Button @click="voltar()" icon="pi pi-times" label="Voltar" class="p-button-secondary" style="margin-left: .5em" />
                        </div>
                        <div class="ml-2" v-show="!contrato.id">
                            <Button @click="salvar()" icon="pi pi-check" label="Salvar" />
                        </div>
                        <div class="ml-2" v-show="contrato.id">
                            <Button  @click="editContrato(contrato.id)" icon="pi pi-check" label="Editar" />
                        </div>
                        <div class="ml-2"  @click="() => gerarComissaoDialog = true" v-show="contrato.id && !contrato.status">
                            <Button icon="pi pi-check" label="Aprovar" class="p-button-success" />
                        </div>
                    </div>
                </template>
            </Card>
        </div>
        <Dialog v-model:visible="gerarComissaoDialog" :style="{width: '450px'}" header="Confirme" :modal="true">
            <div class="confirmation-content">
                <i class="pi pi-exclamation-triangle p-mr-3" style="font-size: 2rem" /><br>
                <span >Deseja gerar o recebimento do contrato?</span>
            </div>
            <template #footer>
                <Button label="Não" icon="pi pi-times" class="p-button-text" @click="hideGerarComissaoDialog()"/>
                <Button label="Sim" icon="pi pi-check" class="p-button-text" @click="aprovar()" />
            </template>
        </Dialog>
    </app-layout>
</template>

<script>

import AppLayout from '@/Layouts/AppLayout';
import JetApplicationMark from '@/Jetstream/ApplicationMark';
import JetBanner from '@/Jetstream/Banner';
import JetDropdown from '@/Jetstream/Dropdown';
import JetDropdownLink from '@/Jetstream/DropdownLink';
import JetNavLink from '@/Jetstream/NavLink';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink';
import moment from 'moment';
import { Inertia } from '@inertiajs/inertia';
import { validateUserAuth } from '../Auth';

export default {
    props: ['colaboradorEdit', 'correntistaEdit', 'contratoEdit', 'colaboradoresAll', 'correntistasAll', 'query', "contratoCreated"],
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
            filteredCorrentistas: null,
            filteredColaboradores: null,
            columns: [],
            faturamentoShow: false,
            gerarComissaoDialog: false,
            contrato: {
                id: null,
                data_de_fechamento: null,
                data_de_emissao: null,
                data_de_vencimento: null,
                status: null,
                descricao_do_servico: null,
                valor: 0.00,
                valor_avista: 0.00,
                valor_parcelado: 0.00,
                quantidade_parcela: 0,
                desconto: 0.00,
                acrescimo: 0.00,
                percentual_comissao_colaborador: 0.00,
                created_at: null,
                updated_at: null,
                user_id: null,
                colaborador_id: null,
                correntista_id: null,
            },
            empresa: {
                "id": null,
                "fantasia": "",
                "razao_social": "",
                "inscricao_estadual": "",
                "cpf": "",
                "cnpj": "",
                "telefone": "",
                "celular": "",
                "bairro": "",
                "cep": "",
                "rua": "",
                "cidade": "",
                "numero": "",
                "estado": "",
                "email": "",
                "created_at": null,
                "updated_at": null
            },
            correntista: {
                "id": null,
                "nome": null,
                "cpf": null,
                "cnpj": null,
                "rua": null,
                "telefone": null,
                "bairro": null,
                "cidade": null,
                "numero": null,
                "estado": null,
                "user_id": null,
                "inativo": null,
                "updated_at": null,
                "created_at": null,

            },
            correntistas: null,
            colaborador: {
                "id": null,
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
            receber:{
                documento: 1,
                ordem_documento: 1,
                ordem_documento_final: 1,
                desconto: 0.00,
                acrescimo: 0.00,
                pago: 0.00,
                restante: 0.00,
                total: 0.00,
                status: false,
                data_de_vencimento: null,
                data_de_pagamento: null,
                data_de_emissao: null,
                updated_at: null,
                created_at: null,
                user_id: null,
                contrato_id: null,
                acerto_id: null,
            },
            colaboradores: null,
        }
    },
    beforeCreate() {
        this.correntistas = this.$inertia.page.props.correntistasAll;
        this.colaboradores = this.$inertia.page.props.colaboradoresAll;
    },
    created() {
        this.contrato.user_id = this.$inertia.page.props.user;
        this.contrato.data_de_emissao = moment(this.dataBase()).format("DD/MM/YYYY h:mm:ss");
        this.contrato.data_de_vencimento = moment(this.dataBase()).format("DD/MM/YYYY");
        console.log(this.$inertia);
    },
    mounted() {
        this.correntistas = this.$inertia.page.props.correntistasAll;
        this.colaboradores = this.$inertia.page.props.colaboradoresAll;
        this.contrato.user_id = this.$inertia.page.props.user.id;
    },
    methods: {
        hideGerarComissaoDialog() {
            this.gerarComissaoDialog = false;
        },
        setCorrentistaSelected(event){
            console.log('setCorrentistaSelected');
            this.contrato.correntista_id = event.value.id;
            this.correntista = event.value;
        },
        searchCorrentistas(event) {
            console.log('searchCorrentistas', this.correntistas);
            console.log(!event.query.trim().length);
                setTimeout(() => {
                    if (!event.query.trim().length) {
                        this.filteredCorrentistas = [...this.correntistas];
                    }
                    else {
                        this.filteredCorrentistas = this.correntistas.filter((correntista) => {
                        return correntista.nome.toLowerCase().startsWith(event.query.toLowerCase());
                    });
                }
                }, 250);
        },
        setColaboradorSelected(event){
            console.log('setColaboradorSelected');
            // contrato <= comissao && id
            this.contrato.percentual_comissao_colaborador = event.value.comissao;
            this.contrato.colaborador_id = event.value.id;

            this.colaborador = event.value;
        },
        searchColaboradores(event) {
            console.log('searchColaboradores', this.colaboradores);
            console.log(!event.query.trim().length);
                setTimeout(() => {
                    if (!event.query.trim().length) {
                        this.filteredColaboradores = [...this.colaboradores];
                    }
                    else {
                        this.filteredColaboradores = this.colaboradores.filter((colaborador) => {
                        return colaborador.nome_completo.toLowerCase().startsWith(event.query.toLowerCase());
                    });
                }
                }, 250);
        },
        showMessage(summary, detail,type = true) {
            this.$toast.add({severity: type ? 'success' : 'error', summary, detail, life: 3000});
        },
        dataBase() {
            let data = new Date();
            let data2 = new Date(data.valueOf() - data.getTimezoneOffset() * 60000);
            const  dataBase = data2.toISOString().replace(/\.\d{3}Z$/, '');

            return dataBase;
        },
        voltar() {
            Inertia.get(route('contratos'), {
                empresa_id: this.$inertia.page.props.user.empresa_id,
                user_id: this.$inertia.page.props.user.id,
            });
        },
        formatCurrency(value) {
            return Number(value).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
        },
        updateValorAVista(){
            console.log("update valores")
            this.faturamentoShow = true;
            this.contrato.valor_avista = this.contrato.valor_avista - this.contrato.desconto;
            this.contrato.valor_parcelado = this.contrato.valor - this.contrato.valor_avista
            this.contrato.quantidade_parcela = 1;
        },
        preseveDadosContratoCriar(){
            this.contrato.status = null;
            this.contrato.data_de_emissao = moment(this.dataBase()).format("DD/MM/YYYY h:mm:ss");
            this.contrato.data_de_vencimento = moment(this.dataBase()).format("DD/MM/YYYY");
        },
        salvar() {
            validateUserAuth(this.$inertia, this.$props.query,
            async () => {
                if(!this.contrato.id) {
                    console.log('create',this.contrato);


                    //config data
                    if(this.contrato.data_de_vencimento) {
                        const dataDeVencimento  = this.contrato.data_de_vencimento.split('/');
                        this.contrato.data_de_vencimento = `${dataDeVencimento[2]}${dataDeVencimento[1]}${dataDeVencimento[0]}`;
                    }

                    //pre-dados do contrato

                    this.contrato.status = false;

                    this.contrato.data_de_fechamento = null;

                    this.contrato.data_de_emissao = this.dataBase();

                    this.contrato.data_de_vencimento = this.contrato.data_de_vencimento ? this.contrato.data_de_vencimento : this.dataBase();

                    this.contrato.created_at = this.dataBase();

                    this.contrato. updated_at = this.dataBase();

                    this.contrato.valor = this.contrato.valor > 0.00 ? this.contrato.valor : 0.00;

                    this.contrato.valor_avista = !this.contrato.valor_avista && this.contrato.valor;

                    this.contrato.valor_parcelado = this.contrato.valor_parcelado > 0.00 ? this.contrato.valor_parcelado : 0.00;

                    this.contrato.quantidade_parcela = this.contrato.valor_parcelado > 0 ? this.contrato.quantidade_parcela : 0;

                    this.contrato.desconto = this.contrato.desconto > 0.00 ? this.contrato.desconto : 0.00;

                    this.contrato.descricao_do_servico = this.contrato.descricao_do_servico ? this.contrato.descricao_do_servico : "<p>Serviço Não Informado!<p/>";

                    this.contrato.acrescimo = this.contrato.acrescimo > 0.00 ? this.contrato.acrescimo : 0.00;

                    this.contrato.percentual_comissao_colaborador = this.contrato.percentual_comissao_colaborador > 0.00 ? this.contrato.percentual_comissao_colaborador : 0.00;

                    this.contrato.user_id = this.$inertia.page.props.user.id;

                    this.contrato.colaborador_id = this.colaborador.id;

                    this.contrato.correntista_id = this.correntista.id;

                    if(!this.contrato.colaborador_id){
                        this.preseveDadosContratoCriar();
                        this.showMessage('Alerta', 'Informe o colaborador!', false);
                        return;
                    }
                    if(!this.contrato.correntista_id){
                        this.preseveDadosContratoCriar()
                        this.showMessage('Alerta', 'Informe o correntista!', false);
                        return;
                    }

                    //desconto no total
                    this.contrato.valor = (this.contrato.valor + this.contrato.acrescimo) - this.contrato.desconto;

                    //update dados
                    this.contrato.valor = this.contrato.valor > 0.00 ? this.contrato.valor : 0.00;

                    this.contrato.valor_avista = !this.contrato.valor_avista && this.contrato.valor;

                    this.contrato.quantidade_parcela = this.contrato.valor_parcelado > 0 ? this.contrato.quantidade_parcela : 0;

                    this.contrato.valor_parcelado = this.contrato.valor_parcelado > 0.00 ? this.contrato.valor_parcelado : 0.00;

                    const query = {
                        empresa_id: this.$props.query.empresa_id,
                        user_id: this.$props.query.user_id,
                    };


                    await Inertia.post(this.route('contrato.create', query),
                    {
                        query,
                        contrato: this.contrato
                    },
                    {
                        preserveState: true,
                        onSuccess: (page) => {
                            console.log('Create Contrato Salvo', page);
                            this.contrato = page.props.contratoCreated;
                            this.contrato.data_de_emissao =  moment(page.props.contratoCreated.data_de_emissao).format('DD/MM/YYYY h:mm:ss');
                            this.contrato.data_de_vencimento =  moment(page.props.contratoCreated.data_de_vencimento).format('DD/MM/YYYY');
                            this.contrato.data_de_fechamento = this.contrato.data_de_fechamento && moment(page.props.contratoCreated.data_de_fechamento).format('DD/MM/YYYY h:mm:ss');

                            console.log('Update Date Contrato', page.props);
                            this.showMessage('Sucesso', 'Contrato Salvo');
                        },
                        onError: (errors) => {
                            this.showMessage('Erro', 'Contrato Não Foi Salvo', false);
                            console.log(errors);
                        },
                    });

                }

            })
        },
        setCreatedContrato(contrato) {
            this.contrato = contrato;
        },
        editContrato(contrato_id) {
            Inertia.get(route('contrato.edit'), {
                empresa_id: this.$inertia.page.props.user.empresa_id,
                user_id: this.$inertia.page.props.user.id,
                contrato_id,
            });
        },
        aprovar() {
                    //config data
                    if(this.contrato.data_de_vencimento) {
                        const dataDeVencimento  = this.contrato.data_de_vencimento.split('/');
                        this.contrato.data_de_vencimento = `${dataDeVencimento[2]}${dataDeVencimento[1]}${dataDeVencimento[0]}`;
                    }

                    // pre-dados contrato
                    this.contrato.updated_at = this.dataBase();
                    this.contrato.data_de_emissao = this.$props.contratoCreated.data_de_emissao;
                    this.contrato.created_at = this.$inertia.page.props.contratoCreated.created_at.split('.')[0];
                    this.contrato.status = true;
                    this.contrato.data_de_fechamento = this.dataBase();

                    // pre-dados acerto
                    this.acerto.valor_empresa = (this.contrato.valor * ((100.00 - this.contrato.percentual_comissao_colaborador)/100));
                    this.acerto.valor_colaborador = this.contrato.valor * (this.contrato.percentual_comissao_colaborador/100);
                    this.acerto.restante = this.acerto.valor_colaborador;
                    this.acerto.total = this.contrato.valor;
                    this.acerto.data_de_emissao = this.dataBase();
                    this.acerto.created_at = this.dataBase();
                    this.acerto.updated_at = this.dataBase();
                    this.acerto.contrato_id = this.contrato.id;
                    this.acerto.user_id = this.contrato.user_id;

                    //pre-datos recebimento
                    this.receber.restante = this.contrato.valor;
                    this.receber.total = this.contrato.valor;
                    this.receber.data_de_vencimento = this.contrato.data_de_vencimento;
                    this.receber.data_de_emissao = this.dataBase();
                    this.receber.created_at = this.dataBase();
                    this.receber.updated_at = this.dataBase();
                    this.receber.contrato_id = this.contrato.id;
                    this.receber.user_id = this.contrato.user_id;

                    const query = {
                        empresa_id: this.$props.query.empresa_id,
                        user_id: this.contrato.user_id,
                        contrato_id: this.contrato.id,
                    };

                    Inertia.put(this.route('contrato.aprovar', query),
                    {
                        query,
                        contrato: this.contrato,
                        acerto: this.acerto,
                        receber: this.receber,
                    },
                    {
                        preserveState: true,
                        onSuccess: (page) => {
                            console.log('update contrato aprovar', page.props);
                            this.contrato = page.props.contratoEdit;
                            this.contrato.data_de_emissao =  moment(page.props.contratoEdit.data_de_emissao).format('DD/MM/YYYY h:mm:ss');
                            this.contrato.data_de_fechamento = page.props.contratoEdit.data_de_fechamento && moment(page.props.contratoEdit.data_de_fechamento).format('DD/MM/YYYY h:mm:ss');
                            this.correntista =  page.props.correntistaEdit;
                            this.colaborador = page.props.colaboradorEdit;
                            this.colaboradores = page.props.colaboradoresAll;
                            this.correntistas = page.props.correntistasAll;
                            console.log('Update Date Contrato', page.props);
                            this.showMessage('Sucesso', 'Contrato Salvo');
                        },
                        onError: (errors) => {
                            this.showMessage('Erro', 'Contrato Não Foi Salvo', false);
                            console.log(errors);
                        },
                    });
        },
    },

}
</script>
<style>
    .autocomplete-correntista {
        border: none;
    }
</style>


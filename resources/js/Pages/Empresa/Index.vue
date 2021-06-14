<template>
    <app-layout>
         <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Parâmetro/Empresa
            </h2>
        </template>
        <template>

        </template>
        <Toast position="top-right" />
        <div class="p-fluid p-formgrid p-grid grid grid-cols-3 gap-10 p-10">
            <span class="p-float-label">
                <InputText id="fantasia" type="text" v-model="empresa.fantasia" />
                <label for="fantasia">Fantasia</label>
            </span>
            <span class="p-float-label">
                <InputText id="razao_social" type="text" v-model="empresa.razao_social" />
                <label for="razao social">Razão Social</label>
            </span>
            <span class="p-float-label">
                <InputText id="IE" type="text" v-model="empresa.inscricao_estadual" />
                <label for="IE">Inscrição Estadual</label>
            </span>
            <span class="p-float-label">
                <InputText id="cpf" type="text" v-model="empresa.cpf" />
                <label for="cpf">CPF</label>
            </span>
            <span class="p-float-label">
                <InputText id="cnpj" type="text" v-model="empresa.cnpj" />
                <label for="cnpj">CNPJ</label>
            </span>
            <span class="p-float-label">
                <InputText id="telefone" type="text" v-model="empresa.telefone" />
                <label for="telefone">Telefone</label>
            </span>
            <span class="p-float-label">
                <InputText id="celular" type="text" v-model="empresa.celular" />
                <label for="celular">Celular</label>
            </span>
            <span class="p-float-label">
                <InputText id="bairro" type="text" v-model="empresa.bairro" />
                <label for="bairro">Bairro</label>
            </span>
            <span class="p-float-label">
                <InputText id="cidade" type="text" v-model="empresa.cidade" />
                <label for="cidade">Cidade</label>
            </span>
            <span class="p-float-label">
                <InputText id="cep" type="text" v-model="empresa.cep" />
                <label for="cep">CEP</label>
            </span>
            <span class="p-float-label">
                <InputText id="numero" type="text" v-model="empresa.numero" />
                <label for="numero">Número</label>
            </span>
            <span class="p-float-label">
                <InputText id="estado" type="text" v-model="empresa.estado" />
                <label for="estado">Estado</label>
            </span>
            <span class="p-float-label">
                <InputText id="email" type="text" v-model="empresa.email" />
                <label for="email">E-mail</label>
            </span>
            <div>
                <Button label="Cancelar" icon="pi pi-times" class="p-button-text" @click="cancelUpdate"/>
            </div>
            <div>
                <Button label="Salvar" icon="pi pi-check" class="p-button-text" @click="saveEmpresa" />
            </div>
        </div>
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
import DataView from 'primevue/dataview';

import api from '../../service/api.service';
import { Inertia } from '@inertiajs/inertia';


export default {
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
        DataView
    },
    props: ['query', 'empresaShow'],
    data()
    {
        return {
            empresaDialog: true,
            empresa: {
                "id": 0,
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
            empresaDefault: {
                "id": 0,
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
            submitted: false,
        }
    },
    created()
    {
        this.empresa = this.$inertia.page.props.empresaShow;
        this.empresaDefault = {...this.$inertia.page.props.empresaShow};
    },
    methods:
    {
        saveEmpresa() {
            this.submitted = true;
            this.empresa.created_at = this.empresa.created_at.split('.')[0];
            this.empresa.updated_at = this.empresa.updated_at.split('.')[0];

            const query = {
                empresa_id: this.$props.query.empresa_id,
                user_id: this.$inertia.page.props.user.id,
            };

            console.log("update", this.empresa);

            Inertia.put(route('empresa.update', query),{
                query,
                empresa: this.empresa,
            },
            {
                onSuccess: (page) => {
                        this.empresa = page.props.empresaShow;
                        this.empresaDefault = page.props.empresaShow;
                        console.log("Atualizado", page.props)
                        this.showMessage('Concluído', 'Dados da Empresa Atualizados');
                },
                 onError: () => {
                        this.showMessage('Erro', 'Erro ao Atualizar os Dados', false);
                        //this.empresa = this.empresaDefault;
                },
            }
            );

        },
        showMessage(summary, detail,type = true) {
            this.$toast.add({severity: type ? 'success' : 'error', summary, detail, life: 3000});
        },
        cancelUpdate()
        {

            this.empresa =  this.empresaDefault;
        }
    }
}
</script>

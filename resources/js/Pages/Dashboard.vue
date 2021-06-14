<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="flex justify-center items-center mt-8">
                <div class="card w-2/5 .shadow-2xl">
                    <Chart class=".shadow-2xl" type="pie" :data="contratoData" />
                </div>
                <div class="card w-2/5 .shadow-2xl">
                    <Chart type="pie" :data="acertoData" />
                </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Welcome from "@/Jetstream/Welcome";
import { validateUserAuth } from './Auth';
import { Inertia } from '@inertiajs/inertia';

export default {
    components: {
        AppLayout,
        Welcome,
    },
    props: ['query', "contratosAprovadosAll", "contratosPendentesAll", "comissoesPagasAll", "comissoesAPagarAll"],
    data(){
        return {
            dados: null,
            contratoData: {
                labels: ['Contratos Aprovados','Contratos Pendentes'],
                datasets: [
                    {
                        data: [50, 50],
                        backgroundColor: ["#66BB6A","#FFA726"],
                        hoverBackgroundColor: ["#81C784","#FFB74D"]
                    }
                ]
            },
            acertoData: {
                labels: ['Comissões Pagas','Comissões Pendentes'],
                datasets: [
                    {
                        data: [50, 50],
                        backgroundColor: ["#66BB6A","#FFA726"],
                        hoverBackgroundColor: ["#81C784","#FFB74D"]
                    }
                ]
            },
            comissoesPagas: null,
            comissoesPendentes: null,
            contratosAprovados: null,
            contratosPendentes: null,
        }
    },
    beforeCreate() {

        if(this.$inertia.page.props.query.length === 0){
            this.$inertia.page.props.query = {
                user_id: this.$inertia.page.props.user.id,
                empresa_id: this.$inertia.page.props.user.empresa_id
            }
            Inertia.get(route('dashboard'),{
                empresa_id: this.$inertia.page.props.user.empresa_id,
                user_id: this.$inertia.page.props.user.id,
            },
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (page) => {
                    console.log("beforeCreate", page);
                    this.contratoData.datasets[0].data[0] = page.props.contratosAprovados.length;
                    this.contratoData.datasets[0].data[1] = page.props.contratosPendentes.length;
                    this.acertoData.datasets[0].data[0] = page.props.comissoesPagas.length;
                    this.acertoData.datasets[0].data[1] = page.props.comissoesAPagar.length;
                    Inertia.visit('dashboard', {data: {
                        empresa_id: this.$inertia.page.props.user.empresa_id,
                        user_id: this.$inertia.page.props.user.id,
                    }})
                },
            });
            validateUserAuth(Inertia, this.$inertia.page.props.query);
        }
    },
    updated() {
    },
    created() {
        console.log(this.$inertia.page.props)
        this.contratoData.datasets[0].data[0] = this.$inertia.page.props.contratosAprovados.length;
        this.contratoData.datasets[0].data[1] = this.$inertia.page.props.contratosPendentes.length;
        this.acertoData.datasets[0].data[0] = this.$inertia.page.props.comissoesPagas.length;
        this.acertoData.datasets[0].data[1] = this.$inertia.page.props.comissoesAPagar.length;
    },
    methods: {
    }

};
</script>



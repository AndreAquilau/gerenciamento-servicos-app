<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <template>
            <div>
                <Chart type="pie" :data="chartData" />
            </div>
        </template>
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
    props: ['query', `teste`],
    data(){
        return {
            dados: null,
            chartData: {
                labels: ['A','B'],
                datasets: [
                    {
                        data: [300, 50],
                        backgroundColor: ["#66BB6A","#FFA726"],
                        hoverBackgroundColor: ["#81C784","#FFB74D"]
                    }
                ]
            }
        }
    },
    beforeCreate() {
        this.$inertia.page.props.query = {
            user_id: this.$inertia.page.props.user.id,
            empresa_id: this.$inertia.page.props.user.empresa_id
        }
        validateUserAuth(Inertia, this.$inertia.page.props.query);
    },
    created() {
        Inertia.get(this.route('dashboard'),
        {
            user_id: this.$inertia.page.props.user.id,
            empresa_id: this.$inertia.page.props.user.empresa_id},
        {
            preserveState: true,
            onSuccess: (page) => {
                console.log("DashBoard", page);
            },
        }
        );
    },
    methods: {
        getData() {

        }
    }

};
</script>



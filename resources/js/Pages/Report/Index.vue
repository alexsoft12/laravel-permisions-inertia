<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reports
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="m-4">
                        <dt class="text-lg leading-6 font-medium text-gray-900 bg-blue-200 rounded">
                            Product name  - Stock - Unit - Total amount
                        </dt>
                    </div>
                    <div class="m-4" v-for="report in reports" >
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            {{ report.name_product}}   -  {{ report.stock}} - {{ report.unit }} - $ {{ report.total_amount}}
                        </dt>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'

export default {
    props: ['reports'],
    components: {
        AppLayout,
        Welcome,
    },
}
</script>

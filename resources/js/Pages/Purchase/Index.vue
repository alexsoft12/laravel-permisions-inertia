<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Purchases
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="m-4">
                        <dt class="text-lg leading-6 font-medium text-gray-900 bg-blue-200 rounded">
                            Product id  - Quantity
                        </dt>
                    </div>
                    <div class="m-4" v-for="purchase in purchases" :key="purchase.id">
                        <dt class="text-lg leading-6 font-medium text-gray-900">
                            {{ purchase.product_id}}   - $  {{ purchase.quantity}}
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
    props: ['purchases'],
    components: {
        AppLayout,
        Welcome,
    },
}
</script>

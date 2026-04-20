<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { EmployeeData } from '@/types/data';
import __ from '@/Utils/lang';
import Service from '@/Components/Service.vue'

defineProps<{
    employee: EmployeeData;
}>();
</script>

<template>
    <div>
        <Link :href="route('home')" class='text-md text-blue-500'>&larr; {{ __("go back") }}</Link>
        <h2 class="text-xl font-medium mt-5">{{ __("now choose a service", { name: employee.name }) }}</h2>
        <div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mt-6">
                <Service v-for="service in employee.services" :key="service.id" :service="service" :href="route('checkout', { service: service.slug, employee: employee.slug })" />
            </div>
        </div>
    </div>
</template>
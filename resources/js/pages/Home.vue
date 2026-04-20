<script setup lang="ts">

import { Link } from '@inertiajs/vue3';
import Service from '@/Components/Service.vue'
import { EmployeeData, ServiceData } from '@/types/data';
import __ from '@/Utils/lang';

defineProps<{
    employees: EmployeeData[];
    services: ServiceData[];
}>();

</script>

<template>
    <div class="space-y-10">
        <div>
            <h1 class="text-xl font-medium">{{ __("choose a professional") }}</h1>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mt-6">
                <Link :href="route('employee.services', employee.slug)" v-for="employee in employees" :key="employee.id"
                    class="py-8 px-4 border border-slate-200 rounded-lg shadow-sm flex flex-col items-center justify-center text-center hover:bg-gray-50/75">
                    <img :src="employee.profile_photo_url" alt="" class="size-14 rounded-full mb-4">
                    <div class="text-sm font-medium mt-3 text-slate-600">{{ employee.name }}</div>
                </Link>
            </div>
        </div>
        <div>
            <h1 class="text-xl font-medium">{{ __("choose a service first") }}</h1>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mt-6">
                <Service v-for="service in services" :key="service.id" :service="service" />
            </div>
        </div>
    </div>
</template>
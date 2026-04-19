<script setup lang="ts">

import BaseLayout from '@/layouts/BaseLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    employees: Array,
    services: Array,
});

defineOptions({ layout: BaseLayout });
</script>

<template>
    <div class="space-y-10">
        <div>
            <h1 class="text-xl font-medium">Choose a professional</h1>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mt-6">
                <Link href="" v-for="employee in employees" :key="employee.id"
                    class="py-8 px-4 border border-slate-200 rounded-lg shadow-sm flex flex-col items-center justify-center text-center hover:bg-gray-50/75">
                    <img :src="employee.profile_photo_url" alt="" class="size-14 rounded-full mb-4">
                    <div class="text-sm font-medium mt-3 text-slate-600">{{ employee.name }}</div>
                </Link>
            </div>
        </div>
        <div>
            <h1 class="text-xl font-medium">Or, Choose a service first</h1>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mt-6">
                <Link href="" v-for="service in services" :key="service.id"
                    class="py-8 px-4 border border-slate-200 rounded-lg shadow-sm flex flex-col items-center justify-center text-center hover:bg-gray-50/75">
                    <div class="text-sm font-medium mt-3 text-slate-600">{{ service.title }}</div>

                    <div class="text-sm font-medium mt-1 text-slate-400">{{ service.duration }} minutes</div>
                    <div class="text-sm font-medium mt-3 text-slate-600 bg-slate-200 rounded-lg py-0.5 px-1.5">{{ service.price }}</div>
                </Link>
            </div>
        </div>
    </div>
</template>
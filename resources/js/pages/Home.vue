<script setup lang="ts">
import BaseLayout from '@/layouts/BaseLayout.vue';
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
                <div v-for="employee in employees" :key="employee.id">
                    {{ employee.name }}
                </div>
            </div>
        </div>
        <div>
            services
        </div>
    </div>
</template>
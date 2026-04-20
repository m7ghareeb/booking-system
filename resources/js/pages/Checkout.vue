<script setup lang="ts">
import { EmployeeData, ServiceData } from '@/types/data';
import __ from '@/Utils/lang';

defineProps<{
    employee: EmployeeData;
    service: ServiceData;
}>();
</script>

<template>
    <form class="space-y-10">
        <h2 class="text-xl font-medium">{{ __("here's what you're booking") }}</h2>
        <div class="mt-6 flex space-x-3 bg-slate-100 rounded-lg p-4">
            <img v-if="employee" :src="employee.profile_photo_url" class="rounded-lg size-14" />
            <div v-else class="rounded-lg size-14 bg-slate-200"></div>
            <div class="w-full flex justify-between">
                <div>
                    <div class="font-semibold">{{ service.title }} ({{ service.duration }} {{ __('minutes') }})</div>
                    <div>{{ employee?.name ?? __('any employee') }}</div>
                </div>
                <div>
                    {{ service.price }}
                </div>
            </div>
        </div>
        <div>
            <h2 class="text-xl font-medium">{{ __("1. when for") }}</h2>
            <div class="mt-6">
                Picker
            </div>
        </div>
        <div>
            <h2 class="text-xl font-medium">{{ __("2. choose a slot") }}</h2>
            <div class="mt-6">
                Slots
            </div>
        </div>
        <div>
            <h2 class="text-xl font-medium">{{ __("3. your details and book") }}</h2>
            <div class="mt-6">
                Form
            </div>
        </div>
    </form>
</template>
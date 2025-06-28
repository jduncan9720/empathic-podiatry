<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue'
import DataTable from '@/components/data_table/DataTable.vue'
import { patient_columns, Patient } from '@/components/data_table/columns';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Patients',
        href: '/patients',
    },
];

const data = ref<Patient[]>([])

async function getData(): Promise<Patient[]> {
    // Fetch data from your API here.
    return [
        {
            id: '728ed52f',
            name: 'John Doe',
            date_of_birth: '1978-10-05',
            room_number: '307',
            type_of_consent: 'family',
            primary_insurance: 'Blue Cross Blue Shield',
            date_last_seen: '2023-10-01',
            status: 'active',
        },
        {
            id: '828f',
            name: 'Jane Doe',
            date_of_birth: '1978-09-05',
            room_number: '503',
            type_of_consent: 'parent',
            primary_insurance: 'Aetna',
            date_last_seen: '2023-11-04',
            status: 'active',
        }
        // ...
    ]
}

onMounted(async () => {
    data.value = await getData()
})

</script>

<template>
    <Head title="Patients" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="container py-10 mx-auto">
                    <DataTable :columns="patient_columns" :data="data" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

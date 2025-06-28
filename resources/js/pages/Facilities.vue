<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue'
import DataTable from '@/components/data_table/DataTable.vue'
import { facility_columns, Facility } from '@/components/data_table/columns';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Facilities',
        href: '/facilities',
    },
];

const data = ref<Facility[]>([])

async function getData(): Promise<Facility[]> {
    // Fetch data from your API here.
    return [
        {
            id: '728ed52f',
            name: 'Old Folks Family Living Center',
            address_one: '350 Main St',
            address_two: 'Suite 200',
            city: 'Heber City',
            state: 'Utah',
            zip: '84032',
            phone_one: '435-555-5555',
            phone_two: '435-655-9988',
            email: 'facility@facility.com',
            contact: 'John Smith',
        },
        {
            id: '728ed52f',
            name: 'Really Old Folks House of Fun',
            address_one: '800 S. 500 W.',
            address_two: '',
            city: 'Provo',
            state: 'Utah',
            zip: '84099',
            phone_one: '435-345-7787',
            phone_two: '',
            email: 'facility2@facility.com',
            contact: 'Jane Doe',
        },
        // ...
    ]
}

onMounted(async () => {
    data.value = await getData()
})

</script>

<template>
    <Head title="Facilities" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="container py-10 mx-auto">
                    <DataTable :columns="facility_columns" :data="data" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

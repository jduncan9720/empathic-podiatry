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

const facilityData = ref<Facility[]>([])

async function getFacilityData(): Promise<Facility[]> {
    const response = await fetch('/api/facilities');
    if (!response.ok) throw new Error('Failed to fetch facilities');
    return await response.json();
}

async function refreshFacilityData() {
    facilityData.value = await getFacilityData();
}

function openEditFacilityDialog(facility: Facility) {
    console.log('Open edit form dialog for facility:', facility);
}

onMounted(async () => {
    facilityData.value = await getFacilityData()
})

</script>

<template>
    <Head title="Facilities" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="container py-10 mx-auto">
                    <DataTable
                        :columns="facility_columns"
                        :data="facilityData"
                        @facility-deleted="refreshFacilityData()"
                        @row-clicked="openEditFacilityDialog($event)"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

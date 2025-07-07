<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue'
import DataTable from '@/components/data_table/DataTable.vue'
import { patient_columns, Patient, Facility } from '@/components/data_table/columns';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Patients', href: '/patients' },
];

const patientData = ref<Patient[]>([])
const facilityData = ref<Facility[]>([])

async function getPatientData(): Promise<Patient[]> {
    const response = await fetch('/api/patients');
    if (!response.ok) throw new Error('Failed to fetch patients');
    return await response.json();
}

async function getFacilityData(): Promise<Facility[]> {
    const response = await fetch('/api/facilities');
    if (!response.ok) throw new Error('Failed to fetch facilities');
    return await response.json();
}

async function refreshPatientData() {
    patientData.value = await getPatientData();
}

function openEditFormDialog(patient: Patient) {
    console.log('Open edit form dialog for patient:', patient);
}

onMounted(async () => {
    [patientData.value, facilityData.value] = await Promise.all([
        getPatientData(),
        getFacilityData()
    ]);
})
</script>

<template>
    <Head title="Patients" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="container py-10 mx-auto">
                    <DataTable
                        :columns="patient_columns(facilityData)"
                        :data="patientData"
                        @patient-deleted="refreshPatientData()"
                        @row-clicked="openEditFormDialog($event)"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

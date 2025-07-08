<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue'
import DataTable from '@/components/data_table/DataTable.vue'
import { patient_columns, Patient, Facility } from '@/components/data_table/columns';
import PatientForm from '@/components/specific/PatientForm.vue';
import { Dialog, DialogContent, DialogHeader } from '@/components/ui/dialog';
import { usePatientForm } from '@/composables/usePatientForm';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Patients', href: '/patients' },
];

const { updatePatient, error } = usePatientForm();
const patientData = ref<Patient[]>([])
const facilityData = ref<Facility[]>([])
const showEditDialog = ref(false);
const showSavedDialog = ref(false);
const selectedPatient = ref<Patient | null>(null);

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

function openEditPatientDialog(patient: Patient) {
    console.log('Open edit form dialog for patient:', patient);
    selectedPatient.value = patient;
    showEditDialog.value = true;
}

function closeEditDialog() {
    showEditDialog.value = false;
    selectedPatient.value = null;
}

function closeSavedDialog() {
    showSavedDialog.value = false;
}

async function submitPatientEditForm(form: Record<string, unknown>) {
    if (!selectedPatient.value) return;
    try {
        await updatePatient(Number(selectedPatient.value.id), form);
        closeEditDialog();
        showSavedDialog.value = true;
        await refreshPatientData()
    } catch (e) {
        console.error('Error submitting patient form:', e);
    }
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
                        @row-clicked="openEditPatientDialog($event)"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
    <Dialog v-model:open="showEditDialog">
        <DialogContent>
            <DialogHeader>
                <h2>Edit Patient</h2>
            </DialogHeader>
            <PatientForm
                :patient="selectedPatient"
                @submit="submitPatientEditForm"
                @close="closeEditDialog"
            />
        </DialogContent>
    </Dialog>
    <Dialog v-model:open="showSavedDialog">
        <DialogContent>
            <DialogHeader>
                <h2>Patient saved</h2>
            </DialogHeader>
            <div class="flex justify-end">
                <Button @click="closeSavedDialog">OK</Button>
            </div>
        </DialogContent>
    </Dialog>
</template>

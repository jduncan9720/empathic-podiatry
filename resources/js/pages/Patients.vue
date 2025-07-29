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
import { Users, Plus, UserCheck } from 'lucide-vue-next';

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
    // Fetch all patients including soft deleted ones
    const response = await fetch('/api/patients?include_trashed=true');
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
    // Don't allow editing soft deleted patients
    if (patient.deleted_at) {
        alert('Cannot edit a deleted patient. Please restore the patient first.');
        return;
    }
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
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Patients</h1>
                    <p class="text-slate-600 dark:text-slate-400">Manage your patient records and information.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 rounded-lg bg-green-50 px-3 py-2 dark:bg-green-900/20">
                        <UserCheck class="h-4 w-4 text-green-600 dark:text-green-400" />
                        <span class="text-sm font-medium text-green-600 dark:text-green-400">{{ patientData.length }} Patients</span>
                    </div>
                    <Button class="bg-green-600 hover:bg-green-700">
                        <Plus class="mr-2 h-4 w-4" />
                        Add Patient
                    </Button>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="flex-1">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-700">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Patient Directory</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Click on any patient to edit their information. Deleted patients are shown in red.</p>
                    </div>
                    <div class="p-6">
                        <DataTable
                            :columns="patient_columns(facilityData)"
                            :data="patientData"
                            @patient-deleted="refreshPatientData()"
                            @patient-restored="refreshPatientData()"
                            @row-clicked="openEditPatientDialog($event)"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <!-- Edit Patient Dialog -->
    <Dialog v-model:open="showEditDialog">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Edit Patient</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">Update patient information and details.</p>
            </DialogHeader>
            <PatientForm
                :patient="selectedPatient"
                @submit="submitPatientEditForm"
                @close="closeEditDialog"
            />
        </DialogContent>
    </Dialog>

    <!-- Success Dialog -->
    <Dialog v-model:open="showSavedDialog">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Patient Updated</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">The patient has been successfully updated.</p>
            </DialogHeader>
            <div class="flex justify-end">
                <Button @click="closeSavedDialog" class="bg-green-600 hover:bg-green-700">OK</Button>
            </div>
        </DialogContent>
    </Dialog>
</template>

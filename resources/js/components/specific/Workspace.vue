<script setup lang="ts">
import { watch, onMounted, ref } from 'vue';
import { Facility, Patient, working_columns } from '@/components/data_table/columns';
import DataTable from '@/components/data_table/DataTable.vue';
import PatientForm from '@/components/specific/PatientForm.vue';
import { Dialog, DialogContent, DialogHeader } from '@/components/ui/dialog';
import { usePatientForm } from '@/composables/usePatientForm';
import { Button } from '@/components/ui/button';

const patientData = ref<Patient[]>([])
const facilityData = ref<Facility[]>([])
const selectedFacility = ref<string>('');
const showEditDialog = ref(false);
const selectedPatient = ref<Patient | null>(null);
const showSavedDialog = ref(false);

const { updatePatient, error } = usePatientForm();

async function getPatientData(facilityId: string): Promise<Patient[]> {
    if (!facilityId) return [];
    const response = await fetch(`/api/facilities/${facilityId}/patients`);
    if (!response.ok) throw new Error('Failed to fetch patients');
    return await response.json();
}

async function getFacilityData(): Promise<Facility[]> {
    const response = await fetch('/api/facilities');
    if (!response.ok) throw new Error('Failed to fetch facilities');
    return await response.json();
}

function refreshPatientData() {
    if (selectedFacility.value) {
        getPatientData(selectedFacility.value).then(data => {
            patientData.value = data;
        });
    }
}

function openEditPatientDialog(patient: Patient) {
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
        refreshPatientData();
    } catch (e) {
        console.error('Error submitting patient form:', e);
    }
}
async function updateLastSeen(patient: Patient) {
    if (!patient.id) return;
    try {
        const today = new Date().toISOString().split('T')[0];
        await updatePatient(Number(patient.id), { ...patient, date_last_seen: today });
        refreshPatientData();
    } catch (e) {
        console.error('Error updating last seen:', e);
    }
}

onMounted(async () => {
    [facilityData.value] = await Promise.all([
        getFacilityData()
    ]);
})

watch(selectedFacility, async (facilityId) => {
    patientData.value = await getPatientData(facilityId);
});

defineExpose({ refreshPatientData });
</script>

<template>
    <div class="container pt-10 pl-10">
        <h2 class="text-2xl font-semibold mb-4">Select Facility</h2>
        <select v-model="selectedFacility" class="border rounded px-3 py-2 mb-4">
            <option value="" disabled>Select a facility</option>
            <option v-for="facility in facilityData" :key="facility.id" :value="facility.id">
                {{ facility.name }}
            </option>
        </select>
    </div>
    <div v-if="selectedFacility" class="container pl-10">
        <DataTable
            :columns="working_columns(facilityData)"
            :data="patientData"
            @patient-deleted="refreshPatientData()"
            @done-clicked="updateLastSeen($event)"
            @row-clicked="openEditPatientDialog($event)"
        />
    </div>
    <Dialog v-model:open="showEditDialog">
        <DialogContent>
            <DialogHeader>
                <h2>Edit Patient</h2>
            </DialogHeader>
            <PatientForm
                :patient="selectedPatient"
                :facilityReadonly="true"
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

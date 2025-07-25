<script setup lang="ts">
import { computed, watch, onMounted, ref } from 'vue';
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
const showNeedsSeen = ref(false);
const showPhysicianRequests = ref(false);
const showAll = ref(true);

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
async function updateNeedsSeen(patient: Patient) {
    if (!patient.id) return;
    try {
        const today = new Date().toISOString().split('T')[0];
        await updatePatient(Number(patient.id), { ...patient, date_last_seen: today });
        refreshPatientData();
    } catch (e) {
        console.error('Error updating last seen:', e);
    }
}

function toggleAll() {
    showAll.value = true;
    showNeedsSeen.value = false;
    showPhysicianRequests.value = false;
}
function toggleNeedsSeen() {
    showNeedsSeen.value = true;
    showPhysicianRequests.value = false;
    showAll.value = false;
}
function togglePhysicianRequests() {
    showPhysicianRequests.value = true;
    showNeedsSeen.value = false;
    showAll.value = false;
}

const sortedPatientData = computed(() => {
    const data = [...patientData.value];
    if (showPhysicianRequests.value) {
        data.sort((a, b) => {
            const aIsPhysician = a.type_of_consent === 'Physician Request' ? -1 : 1;
            const bIsPhysician = b.type_of_consent === 'Physician Request' ? -1 : 1;
            return aIsPhysician - bIsPhysician;
        });
    } else if (showNeedsSeen.value) {
        data.sort((a, b) => {
            if (!a.date_last_seen) return 1;
            if (!b.date_last_seen) return -1;
            return a.date_last_seen.localeCompare(b.date_last_seen);
        });
    } else if (showAll.value) {
        data.sort((a, b) => Number(a.id) - Number(b.id));
    }
    return data;
});

function exportPhysicianConsentPatients() {
    const rows = patientData.value.filter(
        p => p.type_of_consent === 'Physician Request'
    );
    if (!rows.length) return;

    // Convert to CSV
    const headers = Object.keys(rows[0]);
    const csv = [
        headers.join(','),
        ...rows.map(row => headers.map(h => `"${(row as any)[h] ?? ''}"`).join(','))
    ].join('\n');

    // Download as file
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'physician_consent_patients.csv';
    a.click();
    URL.revokeObjectURL(url);
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
        <div class="flex items-center mb-4">
            <select v-model="selectedFacility" class="border rounded px-3 py-2">
                <option value="" disabled>Select a facility</option>
                <option v-for="facility in facilityData" :key="facility.id" :value="facility.id">
                    {{ facility.name }}
                </option>
            </select>
            <div v-if="selectedFacility" class="flex items-center ml-4 w-full">
                <Button @click="toggleAll" :variant="showAll ? 'default' : 'outline'">
                    All
                </Button>
                <Button @click="toggleNeedsSeen" class="ml-4" :variant="showNeedsSeen ? 'default' : 'outline'">
                    Needs Seen
                </Button>
                <Button @click="togglePhysicianRequests" class="ml-4" :variant="showPhysicianRequests ? 'default' : 'outline'">
                    Consent
                </Button>
                <Button @click="exportPhysicianConsentPatients" class="ml-4 ml-auto">
                    Export Physician Consent
                </Button>
            </div>
        </div>
    </div>
    <div v-if="selectedFacility" class="container pl-10">
        <DataTable
            :columns="working_columns(facilityData)"
            :data="sortedPatientData"
            @patient-deleted="refreshPatientData()"
            @done-clicked="updateNeedsSeen($event)"
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

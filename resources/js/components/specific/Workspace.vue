<script setup lang="ts">
import { watch, onMounted, ref } from 'vue';
import { Facility, Patient, working_columns } from '@/components/data_table/columns';
import DataTable from '@/components/data_table/DataTable.vue';

const patientData = ref<Patient[]>([])
const facilityData = ref<Facility[]>([])
const selectedFacility = ref<string>('');

// Add this to accept the event from parent
defineProps({});

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
        />
    </div>
</template>

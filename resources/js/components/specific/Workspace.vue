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

async function updatePatientStatus(patient: Patient, newStatus: string) {
    if (!patient.id) {
        console.error('No patient ID found');
        return;
    }
    
    console.log('Updating patient status:', { 
        patientId: patient.id, 
        oldStatus: patient.status, 
        newStatus: newStatus 
    });
    
    try {
        // Create the update payload with only the status field
        const updatePayload = {
            status: newStatus
        };
        
        console.log('Sending update payload:', updatePayload);
        console.log('Making API call to:', `/api/patients/${patient.id}`);
        
        const result = await updatePatient(Number(patient.id), updatePayload);
        
        console.log('API response:', result);
        console.log('Status updated successfully');
        
        // Refresh the patient data to show the updated status
        await refreshPatientData();
        
    } catch (e) {
        console.error('Error updating patient status:', e);
        // You might want to show a user-friendly error message here
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

async function generatePhysicianOrderPDF() {
    const physicianPatients = patientData.value.filter(
        p => p.type_of_consent === 'Physician Request'
    );
    
    if (!physicianPatients.length) {
        alert('No physician consent patients found');
        return;
    }
    
    try {
        const facility = facilityData.value.find(f => f.id === selectedFacility.value);
        const facilityName = facility ? facility.name : 'Spring Creek';
        
        const response = await fetch('/api/pdf/download-physician-order', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/pdf'
            },
            body: JSON.stringify({
                patients: physicianPatients,
                facilityName: facilityName
            })
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `physician-order-${new Date().toISOString().split('T')[0]}.pdf`;
            a.click();
            URL.revokeObjectURL(url);
        } else {
            console.error('Failed to generate PDF');
            alert('Failed to generate PDF. Please try again.');
        }
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
    }
}

async function generatePodiatryVisitPDF() {
    // Use the same logic as the "Needs Seen" filter
    const patientsNeedingSeen = patientData.value.filter(patient => {
        if (!patient.date_last_seen) return true;
        
        const lastSeen = new Date(patient.date_last_seen + 'T00:00:00');
        if (isNaN(lastSeen.getTime())) return true;
        
        const now = new Date();
        const diffDays = (now.getTime() - lastSeen.getTime()) / (1000 * 60 * 60 * 24);
        return diffDays > 30;
    });
    
    if (!patientsNeedingSeen.length) {
        alert('No patients found that need to be seen');
        return;
    }
    
    try {
        const facility = facilityData.value.find(f => f.id === selectedFacility.value);
        const facilityName = facility ? facility.name : 'Spring Creek';
        const facilityContact = facility ? facility.contact_name : '';
        
        const response = await fetch('/api/pdf/download-podiatry-visit', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/pdf'
            },
            body: JSON.stringify({
                patients: patientsNeedingSeen,
                facilityName: facilityName,
                facilityContact: facilityContact
            })
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `podiatry-visit-${new Date().toISOString().split('T')[0]}.pdf`;
            a.click();
            URL.revokeObjectURL(url);
        } else {
            console.error('Failed to generate PDF');
            alert('Failed to generate PDF. Please try again.');
        }
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
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
        <div class="flex items-center mb-4">
            <select v-model="selectedFacility" class="border rounded px-3 py-2">
                <option value="" disabled>Select a facility</option>
                <option v-for="facility in facilityData" :key="facility.id" :value="facility.id">
                    {{ facility.name }}
                </option>
            </select>
            <div v-if="selectedFacility" class="flex items-center ml-4 w-full">
                <div class="flex gap-4">
                    <Button @click="toggleAll" :variant="showAll ? 'default' : 'outline'">
                        All
                    </Button>
                    <Button @click="toggleNeedsSeen" :variant="showNeedsSeen ? 'default' : 'outline'">
                        Needs Seen
                    </Button>
                    <Button @click="togglePhysicianRequests" :variant="showPhysicianRequests ? 'default' : 'outline'">
                        Consent
                    </Button>
                </div>
                <div class="flex justify-end gap-4 ml-auto">
                    <Button @click="generatePhysicianOrderPDF">
                        Generate Physician Order PDF
                    </Button>
                    <Button @click="generatePodiatryVisitPDF">
                        Generate Podiatry Visit PDF
                    </Button>
                </div>
            </div>
        </div>
    </div>
    <div v-if="selectedFacility" class="container pl-10">
        <DataTable
            :columns="working_columns(facilityData)"
            :data="sortedPatientData"
            @patient-deleted="refreshPatientData()"
            @done-clicked="updateNeedsSeen($event)"
            @status-changed="(patient, newStatus) => updatePatientStatus(patient, newStatus)"
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

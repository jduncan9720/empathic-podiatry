<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { HousePlus, UserPlus, Activity, Users, Building2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
import { Dialog, DialogContent, DialogHeader } from '@/components/ui/dialog';
import PatientForm from '@/components/specific/PatientForm.vue';
import FacilityForm from '@/components/specific/FacilityForm.vue';
import Workspace from '@/components/specific/Workspace.vue';
import { useFacilityForm } from '@/composables/useFacilityForm';
import { usePatientForm } from '@/composables/usePatientForm';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
];

const { submitFacility } = useFacilityForm();
const { submitPatient } = usePatientForm();

const dialogs = ref({
    patient: false,
    facility: false,
    patientSaved: false,
    facilitySaved: false,
});

const workspaceRef = ref()

function openDialog(type: keyof typeof dialogs.value) {
    dialogs.value[type] = true;
}
function closeDialog(type: keyof typeof dialogs.value) {
    dialogs.value[type] = false;
}

async function submitPatientForm(form: Record<string, unknown>) {
    await submitPatient(form);
    closeDialog('patient');
    openDialog('patientSaved');
    workspaceRef.value?.refreshPatientData();
}

async function submitFacilityForm(form: Record<string, unknown>) {
    await submitFacility(form);
    closeDialog('facility');
    openDialog('facilitySaved');
}
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="space-y-2">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Dashboard</h1>
                <p class="text-slate-600 dark:text-slate-400">Welcome back! Manage your practice efficiently.</p>
            </div>

            <!-- Quick Actions -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Add Facility Card -->
                <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="flex items-center space-x-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                            <Building2 class="h-6 w-6" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-900 dark:text-white">Add Facility</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Register a new healthcare facility</p>
                        </div>
                    </div>
                    <Button 
                        @click="openDialog('facility')" 
                        variant="outline" 
                        class="mt-4 w-full transition-all hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-400"
                    >
                        <HousePlus class="mr-2 h-4 w-4" />
                        Add Facility
                    </Button>
                </div>

                <!-- Add Patient Card -->
                <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                    <div class="flex items-center space-x-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                            <Users class="h-6 w-6" />
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-900 dark:text-white">Add Patient</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Register a new patient</p>
                        </div>
                    </div>
                    <Button 
                        @click="openDialog('patient')" 
                        variant="outline" 
                        class="mt-4 w-full transition-all hover:bg-green-50 hover:text-green-700 dark:hover:bg-green-900/20 dark:hover:text-green-400"
                    >
                        <UserPlus class="mr-2 h-4 w-4" />
                        Add Patient
                    </Button>
                </div>
            </div>

            <!-- Workspace Section -->
            <div class="flex-1">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-700">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Practice Overview</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Monitor your practice activity and recent updates</p>
                    </div>
                    <div class="p-6">
                        <Workspace ref="workspaceRef"/>
                    </div>
                </div>
            </div>

            <!-- Dialogs -->
            <Dialog v-model:open="dialogs.patient">
                <DialogContent class="sm:max-w-md">
                    <DialogHeader>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Add Patient</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Enter patient information to register them in the system.</p>
                    </DialogHeader>
                    <PatientForm @submit="submitPatientForm" @close="() => closeDialog('patient')" />
                </DialogContent>
            </Dialog>

            <Dialog v-model:open="dialogs.facility">
                <DialogContent class="sm:max-w-md">
                    <DialogHeader>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Add Facility</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Enter facility information to register it in the system.</p>
                    </DialogHeader>
                    <FacilityForm @submit="submitFacilityForm" @close="() => closeDialog('facility')" />
                </DialogContent>
            </Dialog>

            <Dialog v-model:open="dialogs.patientSaved">
                <DialogContent class="sm:max-w-md">
                    <DialogHeader>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Patient Saved</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">The patient has been successfully registered.</p>
                    </DialogHeader>
                    <div class="flex justify-end">
                        <Button @click="closeDialog('patientSaved')" class="bg-green-600 hover:bg-green-700">OK</Button>
                    </div>
                </DialogContent>
            </Dialog>

            <Dialog v-model:open="dialogs.facilitySaved">
                <DialogContent class="sm:max-w-md">
                    <DialogHeader>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Facility Saved</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">The facility has been successfully registered.</p>
                    </DialogHeader>
                    <div class="flex justify-end">
                        <Button @click="closeDialog('facilitySaved')" class="bg-green-600 hover:bg-green-700">OK</Button>
                    </div>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>

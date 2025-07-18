<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { HousePlus, UserPlus } from 'lucide-vue-next';
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
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="button-container">
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="flex p-8 gap-8 h-full">
                        <Button @click="openDialog('facility')" variant="outline" class="w-24 h-24 flex flex-col items-center justify-center gap-2">
                            <HousePlus fill="white" class="!w-[3rem] !h-[3rem]" />
                            <span>Add a Facility</span>
                        </Button>
                        <Button @click="openDialog('patient')" variant="outline" class="w-24 h-24 flex flex-col items-center justify-center gap-2">
                            <UserPlus fill="white" class="!w-[3rem] !h-[3rem]"/>
                            Add a Patient
                        </Button>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <Workspace ref="workspaceRef"/>
            </div>
            <Dialog v-model:open="dialogs.patient">
                <DialogContent>
                    <DialogHeader>
                        <h2>Add Patient</h2>
                    </DialogHeader>
                    <PatientForm @submit="submitPatientForm" @close="() => closeDialog('patient')" />
                </DialogContent>
            </Dialog>
            <Dialog v-model:open="dialogs.facility">
                <DialogContent>
                    <DialogHeader>
                        <h2>Add Facility</h2>
                    </DialogHeader>
                    <FacilityForm @submit="submitFacilityForm" @close="() => closeDialog('facility')" />
                </DialogContent>
            </Dialog>
            <Dialog v-model:open="dialogs.patientSaved">
                <DialogContent>
                    <DialogHeader>
                        <h2>Patient saved</h2>
                    </DialogHeader>
                    <div class="flex justify-end">
                        <Button @click="closeDialog('patientSaved')">OK</Button>
                    </div>
                </DialogContent>
            </Dialog>
            <Dialog v-model:open="dialogs.facilitySaved">
                <DialogContent>
                    <DialogHeader>
                        <h2>Facility saved</h2>
                    </DialogHeader>
                    <div class="flex justify-end">
                        <Button @click="closeDialog('facilitySaved')">OK</Button>
                    </div>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>

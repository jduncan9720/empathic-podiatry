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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const isPatientDialogOpen = ref(false);
const isFacilityDialogOpen = ref(false);
const isPatientSavedDialogOpen = ref(false);

function openAddPatientDialog() {
    isPatientDialogOpen.value = true;
}

function openAddFacilityDialog() {
    isFacilityDialogOpen.value = true;
}
function closePatientDialog() {
    isFacilityDialogOpen.value = false;
}

function closeFacilityDialog() {
    isPatientDialogOpen.value = false;
}
async function submitPatientForm(form: {
    name: string;
    date_of_birth: string;
    room_number: string;
    type_of_consent: string;
    primary_insurance: string;
    date_last_seen: string;
    status: string;
}) {
    console.log('Submitted Patient:', form);
    const response = await fetch('/api/patients', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(form),
    });

    if (!response.ok) {
        throw new Error('Failed to submit patient');
    }
    closePatientDialog();
    isPatientSavedDialogOpen.value = true;
}

function submitFacilityForm(form: { name: string; date_of_birth: string }) {
    console.log('Submitted Facility:', form);
    closePatientDialog();
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="button-container">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="flex p-8 gap-8 h-full">
                        <Button @click="openAddFacilityDialog" variant="outline" class="w-48 h-48 flex flex-col items-center justify-center gap-2">
                            <HousePlus fill="white" class="!w-[6rem] !h-[6rem]" />
                            <span>Add a Facility</span>
                        </Button>
                        <Button @click="openAddPatientDialog" variant="outline" class="w-48 h-48 flex flex-col items-center justify-center gap-2">
                            <UserPlus fill="white" class="!w-[6rem] !h-[6rem]"/>
                            Add a Patient
                        </Button>
                    </div>
                </div>
            </div>
            <Dialog v-model:open="isPatientDialogOpen">
                <DialogContent>
                    <DialogHeader>
                        <h2>Add Patient</h2>
                    </DialogHeader>
                    <PatientForm @submit="submitPatientForm" @close="closePatientDialog" />
                </DialogContent>
            </Dialog>
            <Dialog v-model:open="isFacilityDialogOpen">
                <DialogContent>
                    <DialogHeader>
                        <h2>Add Facility</h2>
                    </DialogHeader>
                    <FacilityForm @submit="submitFacilityForm" @close="closeFacilityDialog" />
                </DialogContent>
            </Dialog>
            <Dialog v-model:open="isPatientSavedDialogOpen">
                <DialogContent>
                    <DialogHeader>
                        <h2>Patient saved</h2>
                    </DialogHeader>
                    <div class="flex justify-end">
                        <Button @click="isPatientSavedDialogOpen = false">OK</Button>
                    </div>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>

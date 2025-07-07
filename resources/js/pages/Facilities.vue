<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue'
import DataTable from '@/components/data_table/DataTable.vue'
import { facility_columns, Facility } from '@/components/data_table/columns';
import FacilityForm from '@/components/specific/FacilityForm.vue';
import { Dialog, DialogContent, DialogHeader } from '@/components/ui/dialog';
import { useFacilityForm } from '@/composables/useFacilityForm';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Facilities',
        href: '/facilities',
    },
];

const { updateFacility, error } = useFacilityForm();
const facilityData = ref<Facility[]>([])
const showEditDialog = ref(false);
const showSavedDialog = ref(false);
const selectedFacility = ref<Facility | null>(null);

async function getFacilityData(): Promise<Facility[]> {
    const response = await fetch('/api/facilities');
    if (!response.ok) throw new Error('Failed to fetch facilities');
    return await response.json();
}

async function refreshFacilityData() {
    facilityData.value = await getFacilityData();
}

function openEditFacilityDialog(facility: Facility) {
    console.log('Open edit form dialog for facility:', facility);
    selectedFacility.value = facility;
    showEditDialog.value = true;
}

function closeEditDialog() {
    showEditDialog.value = false;
    selectedFacility.value = null;
}

function closeSavedDialog() {
        showSavedDialog.value = false;
}

async function submitFacilityEditForm(form: Record<string, unknown>) {
    if (!selectedFacility.value) return;
    try {
        await updateFacility(Number(selectedFacility.value.id), form);
        closeEditDialog();
        showSavedDialog.value = true;
        refreshFacilityData()
    } catch (e) {
        console.error('Error submitting facility form:', e);
    }
}

onMounted(async () => {
    facilityData.value = await getFacilityData()
})

</script>

<template>
    <Head title="Facilities" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="container py-10 mx-auto">
                    <DataTable
                        :columns="facility_columns"
                        :data="facilityData"
                        @facility-deleted="refreshFacilityData()"
                        @row-clicked="openEditFacilityDialog($event)"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
    <Dialog v-model:open="showEditDialog">
        <DialogContent>
            <DialogHeader>
                <h2>Edit Facility</h2>
            </DialogHeader>
            <FacilityForm
                :facility="selectedFacility"
                @submit="submitFacilityEditForm"
                @close="closeEditDialog"
            />
        </DialogContent>
    </Dialog>
    <Dialog v-model:open="showSavedDialog">
        <DialogContent>
            <DialogHeader>
                <h2>Facility saved</h2>
            </DialogHeader>
            <div class="flex justify-end">
                <Button @click="closeSavedDialog">OK</Button>
            </div>
        </DialogContent>
    </Dialog>
</template>

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
import { Building2, Plus } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Facilities', href: '/facilities' },
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
        await refreshFacilityData()
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
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">Facilities</h1>
                    <p class="text-slate-600 dark:text-slate-400">Manage your healthcare facilities and their information.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 rounded-lg bg-blue-50 px-3 py-2 dark:bg-blue-900/20">
                        <Building2 class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ facilityData.length }} Facilities</span>
                    </div>
                    <Button class="bg-blue-600 hover:bg-blue-700">
                        <Plus class="mr-2 h-4 w-4" />
                        Add Facility
                    </Button>
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="flex-1">
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-700">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Facility Directory</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Click on any facility to edit its information</p>
                    </div>
                    <div class="p-6">
                        <DataTable
                            :columns="facility_columns"
                            :data="facilityData"
                            @facility-deleted="refreshFacilityData()"
                            @row-clicked="openEditFacilityDialog($event)"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <!-- Edit Facility Dialog -->
    <Dialog v-model:open="showEditDialog">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Edit Facility</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">Update facility information and details.</p>
            </DialogHeader>
            <FacilityForm
                :facility="selectedFacility"
                @submit="submitFacilityEditForm"
                @close="closeEditDialog"
            />
        </DialogContent>
    </Dialog>

    <!-- Success Dialog -->
    <Dialog v-model:open="showSavedDialog">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Facility Updated</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400">The facility has been successfully updated.</p>
            </DialogHeader>
            <div class="flex justify-end">
                <Button @click="closeSavedDialog" class="bg-green-600 hover:bg-green-700">OK</Button>
            </div>
        </DialogContent>
    </Dialog>
</template>

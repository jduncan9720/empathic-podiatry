<script setup lang="ts">
import { MoreHorizontal, RotateCcw } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'

defineProps<{
    patient: {
        id: string
        deleted_at?: string | null
    }
}>()

import { defineEmits } from 'vue'
const emit = defineEmits(['patient-deleted', 'patient-restored'])

async function deletePatient(id: string) {
    if (!confirm('Are you sure you want to delete this patient?')) return;
    const response = await fetch(`/api/patients/${id}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json' },
    });
    if (response.ok) {
        emit('patient-deleted')
    } else {
        alert('Failed to delete patient');
    }
}

async function restorePatient(id: string) {
    if (!confirm('Are you sure you want to restore this patient?')) return;
    const response = await fetch(`/api/patients/${id}/restore`, {
        method: 'PATCH',
        headers: { 'Accept': 'application/json' },
    });
    if (response.ok) {
        emit('patient-restored')
    } else {
        alert('Failed to restore patient');
    }
}

</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="w-8 h-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="w-4 h-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuLabel>Actions</DropdownMenuLabel>
            <DropdownMenuItem 
                v-if="!patient.deleted_at" 
                @click="deletePatient(patient.id)"
            >
                Delete Patient
            </DropdownMenuItem>
            <DropdownMenuItem 
                v-if="patient.deleted_at" 
                @click="restorePatient(patient.id)"
            >
                <RotateCcw class="mr-2 h-4 w-4" />
                Restore Patient
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>

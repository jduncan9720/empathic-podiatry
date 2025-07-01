<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'

defineProps<{
    patient: {
        id: string
    }
}>()

import { defineEmits } from 'vue'
const emit = defineEmits(['patient-deleted'])

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

function editPatient(id: string) {
    // Logic to delete the patient
    console.log(`Editing patient with ID: ${id}`);
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
            <DropdownMenuItem @click="editPatient(patient.id)">Edit Patient</DropdownMenuItem>
            <DropdownMenuItem @click="deletePatient(patient.id)">Delete Patient</DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>

<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'

defineProps<{
    patient: {
        id: string
    }
}>()

function deletePatient(id: string) {
    // Logic to delete the patient
    console.log(`Deleting patient with ID: ${id}`);
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

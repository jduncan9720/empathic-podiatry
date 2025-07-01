<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'

defineProps<{
    facility: {
        id: string
    }
}>()

import { defineEmits } from 'vue'
const emit = defineEmits(['facility-deleted'])

async function deleteFacility(id: string) {
    if (!confirm('Are you sure you want to delete this facility?')) return;
    const response = await fetch(`/api/facilities/${id}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json' },
    });
    if (response.ok) {
        emit('facility-deleted')
    } else {
        alert('Failed to delete facility');
    }
}

function editFacility(id: string) {
    // Logic to delete the facility
    console.log(`Editing facility with ID: ${id}`);
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
            <DropdownMenuItem @click="editFacility(facility.id)">Edit Facility</DropdownMenuItem>
            <DropdownMenuItem @click="deleteFacility(facility.id)">Delete Facility</DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>

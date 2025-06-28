<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'

defineProps<{
    facility: {
        id: string
    }
}>()

function deleteFacility(id: string) {
    // Logic to delete the facility
    console.log(`Deleting facility with ID: ${id}`);
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

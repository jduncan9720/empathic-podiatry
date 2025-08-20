<script setup lang="ts" generic="TData, TValue">
import { ref } from 'vue'
import type { ColumnDef, SortingState, ColumnFiltersState, PaginationState } from '@tanstack/vue-table'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { valueUpdater } from '@/lib/utils'
import { defineEmits } from 'vue'
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import {
    FlexRender,
    getCoreRowModel,
    getPaginationRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    useVueTable,
} from '@tanstack/vue-table'

const props = defineProps<{
    columns: ColumnDef<TData, TValue>[]
    data: TData[]
}>()
const emit = defineEmits(['patient-deleted', 'patient-restored', 'patient-updated', 'facility-deleted', 'facility-updated', 'row-clicked', 'status-changed', 'done-clicked']);
const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>([])
const pagination = ref<PaginationState>({
    pageIndex: 0,
    pageSize: 50, // Default to 50 rows
})

const table = useVueTable({
    get data() { return props.data },
    get columns() { return props.columns },
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    onSortingChange: updaterOrValue => valueUpdater(updaterOrValue, sorting),
    onColumnFiltersChange: updaterOrValue => valueUpdater(updaterOrValue, columnFilters),
    onPaginationChange: updaterOrValue => valueUpdater(updaterOrValue, pagination),
    getFilteredRowModel: getFilteredRowModel(),
    state: {
        get sorting() { return sorting.value },
        get columnFilters() { return columnFilters.value },
        get pagination() { return pagination.value },
    },
    meta: {
        emit: emit as (event: string, ...args: any[]) => void
    }
})

function onRowClick(rowData: TData) {
    emit('row-clicked', rowData);
}

</script>

<template>
    <div>
        <div class="flex items-center py-4">
            <Input class="max-w-sm" placeholder="Search name..."
                   :model-value="table.getColumn('name')?.getFilterValue() as string"
                   @update:model-value=" table.getColumn('name')?.setFilterValue($event)" />
        </div>
        <div class="border rounded-md">
            <Table>
                <TableHeader>
                    <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <TableHead v-for="header in headerGroup.headers" :key="header.id">
                            <FlexRender
                                v-if="!header.isPlaceholder" :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <TableRow
                            v-for="(row, index) in table.getRowModel().rows" 
                            :key="row.id"
                            :data-state="row.getIsSelected() ? 'selected' : undefined"
                            :class="index % 2 === 0 ? 'bg-white dark:bg-slate-800' : 'bg-slate-50 dark:bg-slate-700'"
                            @click="onRowClick(row.original)"
                        >
                            <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                            </TableCell>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell :colspan="columns.length" class="h-24 text-center">
                                No results.
                            </TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </div>
        <div class="flex items-center justify-between py-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-muted-foreground">Rows per page</span>
                <select
                    :value="table.getState().pagination.pageSize"
                    @change="(event) => table.setPageSize(Number((event.target as HTMLSelectElement).value))"
                    class="h-8 w-16 rounded border border-input bg-background px-2 py-1 text-sm"
                >
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="!table.getCanPreviousPage()"
                    @click="table.previousPage()"
                >
                    Previous
                </Button>
                <div class="flex items-center space-x-1 text-sm">
                    <span>Page</span>
                    <span class="font-medium">{{ table.getState().pagination.pageIndex + 1 }}</span>
                    <span>of</span>
                    <span class="font-medium">{{ table.getPageCount() }}</span>
                </div>
                <Button
                    variant="outline"
                    size="sm"
                    :disabled="!table.getCanNextPage()"
                    @click="table.nextPage()"
                >
                    Next
                </Button>
            </div>
        </div>
    </div>
</template>

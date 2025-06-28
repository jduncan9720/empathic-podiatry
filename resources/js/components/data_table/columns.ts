import { h } from 'vue'
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import PatientsDropdownAction from '@/components/data_table/PatientsTableDropdown.vue'
import FacilitiesDropdownAction from '@/components/data_table/FacilitiesTableDropdown.vue'

export interface Patient {
    id: string
    name: string
    date_of_birth: string
    room_number: string
    type_of_consent: string
    primary_insurance: string
    date_last_seen: string
    status: string
}
export const patient_columns: ColumnDef<Patient>[] = [
    {
        accessorKey: 'id',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Id', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => h('div', { class: 'font-medium' }, row.getValue('id')),
    },
    {
        accessorKey: 'name',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => h('div', { class: 'font-medium' }, row.getValue('name')),
    },
    {
        accessorKey: 'date_of_birth',
        header: () => h('div', {}, 'Date of Birth'),
        cell: ({ row }) => {
            const dateOfBirth = row.getValue('date_of_birth')
            return h('div', { class: 'font-medium' }, String(dateOfBirth))
        },
    },
    {
        accessorKey: 'room_number',
        header: () => h('div', {}, 'Room Number'),
        cell: ({ row }) => {
            const roomNumber = row.getValue('room_number')
            return h('div', { class: 'font-medium' }, String(roomNumber))
        },
    },
    {
        accessorKey: 'primary_insurance',
        header: () => h('div', {}, 'Primary Insurance'),
        cell: ({ row }) => {
            const primaryInsurance = row.getValue('primary_insurance')
            return h('div', { class: 'font-medium' }, String(primaryInsurance))
        },
    },
    {
        accessorKey: 'date_last_seen',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Date Last Seen', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => h('div', { class: 'font-medium' }, row.getValue('date_last_seen')),
    },
    {
        accessorKey: 'status',
        header: () => h('div', {}, 'Status'),
        cell: ({ row }) => {
            const status = row.getValue('status')
            return h('div', { class: 'font-medium' }, String(status))
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const patient = row.original

            return h('div', { class: 'relative' }, h(PatientsDropdownAction, {
                patient,
            }))
        },
    }
]

export interface Facility {
    id: string
    name: string
    address_one: string
    address_two: string
    city: string
    state: string
    zip: string
    phone_one: string
    phone_two: string
    email: string
    contact: string
}

export const facility_columns: ColumnDef<Facility>[] = [
    {
        accessorKey: 'id',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Id', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => h('div', { class: 'font-medium' }, row.getValue('id')),
    },
    {
        accessorKey: 'name',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => h('div', { class: 'font-medium' }, row.getValue('name')),
    },
    {
        accessorKey: 'address_one',
        header: () => h('div', {}, 'Address 1'),
        cell: ({ row }) => {
            const addressOne = row.getValue('address_one')
            return h('div', { class: 'font-medium' }, String(addressOne))
        },
    },
    {
        accessorKey: 'address_two',
        header: () => h('div', {}, 'Address 2'),
        cell: ({ row }) => {
            const addressTwo = row.getValue('address_two')
            return h('div', { class: 'font-medium' }, String(addressTwo))
        },
    },
    {
        accessorKey: 'city',
        header: ({ column }) => {
            return h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['City', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
        },
        cell: ({ row }) => h('div', { class: 'font-medium' }, row.getValue('city')),
    },
    {
        accessorKey: 'state',
        header: () => h('div', {}, 'State'),
        cell: ({ row }) => {
            const state = row.getValue('state')
            return h('div', { class: 'font-medium' }, String(state))
        },
    },
    {
        accessorKey: 'zip',
        header: () => h('div', {}, 'ZIP'),
        cell: ({ row }) => {
            const zip = row.getValue('zip')
            return h('div', { class: 'font-medium' }, String(zip))
        },
    },
    {
        accessorKey: 'phone_one',
        header: () => h('div', {}, 'Phone 1'),
        cell: ({ row }) => {
            const phoneOne = row.getValue('phone_one')
            return h('div', { class: 'font-medium' }, String(phoneOne))
        },
    },
    {
        accessorKey: 'phone_two',
        header: () => h('div', {}, 'Phone 2'),
        cell: ({ row }) => {
            const phoneTwo = row.getValue('phone_two')
            return h('div', { class: 'font-medium' }, String(phoneTwo))
        },
    },
    {
        accessorKey: 'email',
        header: () => h('div', {}, 'Email'),
        cell: ({ row }) => {
            const email = row.getValue('email')
            return h('div', { class: 'font-medium' }, String(email))
        },
    },
    {
        accessorKey: 'contact',
        header: () => h('div', {}, 'Contact'),
        cell: ({ row }) => {
            const contact = row.getValue('contact')
            return h('div', { class: 'font-medium' }, String(contact))
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const facility = row.original

            return h('div', { class: 'relative' }, h(FacilitiesDropdownAction, {
                facility,
            }))
        },
    }
]

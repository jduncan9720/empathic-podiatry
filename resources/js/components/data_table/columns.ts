import { h } from 'vue'
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import PatientsDropdownAction from '@/components/data_table/PatientsTableDropdown.vue'
import FacilitiesDropdownAction from '@/components/data_table/FacilitiesTableDropdown.vue'

declare module '@tanstack/vue-table' {
    interface TableMeta<TData = unknown> {
        emit?: (event: string, ...args: any[]) => void;
    }
}
export interface Patient {
    id: string
    name: string
    facility_id: string
    date_of_birth: string
    room_number: string
    type_of_consent: string
    primary_insurance: string
    date_last_seen: string
    status: string
    deleted_at?: string | null
}
export const patient_columns = (facilities: Facility[]): ColumnDef<Patient>[] => [
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
        cell: ({ row }) => {
            const patient = row.original;
            const isDeleted = patient.deleted_at;
            const name = row.getValue('name') as string;
            return h('div', { 
                class: `font-medium ${isDeleted ? 'text-red-500 line-through' : ''}` 
            }, isDeleted ? `${name} (Deleted)` : name);
        },
    },
    {
        accessorKey: 'facility_id',
        header: () => h('div', {}, 'Facility'),
        cell: ({ row }) => {
            const facilityId = row.getValue('facility_id');
            const facility = facilities.find(f => f.id === facilityId);
            return h('div', { class: 'font-medium' }, facility ? facility.name : 'Unknown');
        },
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
        accessorKey: 'type_of_consent',
        header: () => h('div', {}, 'Type of Consent'),
        cell: ({ row }) => {
            const typeOfConsent = row.getValue('type_of_consent')
            return h('div', { class: 'font-medium' }, String(typeOfConsent))
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
        cell: ({ row, table }) => {
            const patient = row.original;
            return h('div', {
                class: 'relative',
                onClick: (event: MouseEvent) => event.stopPropagation(),
            }, h(PatientsDropdownAction, {
                patient,
                onPatientDeleted: () => {
                    if (table.options.meta?.emit) {
                        table.options.meta.emit('patient-deleted');
                    }
                },
                onPatientRestored: () => {
                    if (table.options.meta?.emit) {
                        table.options.meta.emit('patient-restored');
                    }
                }
            }));
        }
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
    contact_name: string
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
        accessorKey: 'contact_name',
        header: () => h('div', {}, 'Contact Name'),
        cell: ({ row }) => {
            const contactName = row.getValue('contact_name')
            return h('div', { class: 'font-medium' }, String(contactName))
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
        id: 'actions',
        enableHiding: false,
        cell: ({ row, table }) => {
            const facility = row.original;
            return h('div', {
                class: 'relative',
                onClick: (event: MouseEvent) => event.stopPropagation(),
            }, h(FacilitiesDropdownAction, {
                facility,
                onFacilityDeleted: () => {
                    if (table.options.meta?.emit) {
                        table.options.meta.emit('facility-deleted');
                    }
                }
            }));
        }
    }
]

export interface Working {
    id: string
    name: string
    date_of_birth: string
    room_number: string
    type_of_consent: string
    primary_insurance: string
    date_last_seen: string
    status: string
}

export const working_columns = (facilities: Facility[]): ColumnDef<Patient>[] => [
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
        accessorKey: 'facility_id',
        header: () => h('div', {}, 'Facility'),
        cell: ({ row }) => {
            const facilityId = row.getValue('facility_id');
            const facility = facilities.find(f => f.id === facilityId);
            return h('div', { class: 'font-medium' }, facility ? facility.name : 'Unknown');
        },
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
        accessorKey: 'type_of_consent',
        header: () => h('div', {}, 'Type of Consent'),
        cell: ({ row }) => {
            const typeOfConsent = row.getValue('type_of_consent')
            return h('div', { class: 'font-medium' }, String(typeOfConsent))
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
        cell: ({ row, table }) => {
            const status = row.getValue('status') as string;
            const statusOptions = ['needs seen', 'deceased', 'discharged', 'refused', 'visit complete'];
            
            return h('select', {
                class: 'w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-slate-800 dark:border-slate-600 dark:text-white',
                value: status || '',
                onChange: (event: Event) => {
                    const target = event.target as HTMLSelectElement;
                    const newStatus = target.value;
                    console.log('Status dropdown changed:', { 
                        patientId: row.original.id, 
                        oldStatus: status, 
                        newStatus: newStatus 
                    });
                    
                    if (table.options.meta?.emit) {
                        table.options.meta.emit('status-changed', row.original, newStatus);
                    } else {
                        console.error('Table meta emit not available');
                    }
                },
                onClick: (event: MouseEvent) => event.stopPropagation(),
            }, statusOptions.map(option => 
                h('option', {
                    value: option,
                    selected: status === option
                }, option.charAt(0).toUpperCase() + option.slice(1))
            ));
        },
    },
    {
        id: 'done',
        header: () => h('div', {}, 'Done'),
        cell: ({ row, table }) => {
            const dateStr = row.getValue('date_last_seen');
            let disabled = false;
            if (typeof dateStr === 'string' && dateStr) {
                const lastSeen = new Date(dateStr + 'T00:00:00');
                if (!isNaN(lastSeen.getTime())) {
                    const now = new Date();
                    const diffDays = (now.getTime() - lastSeen.getTime()) / (1000 * 60 * 60 * 24);
                    disabled = diffDays <= 60;
                }
            }
            return h(Button, {
                class: [
                    disabled
                        ? 'bg-red-500 hover:bg-red-600'
                        : 'bg-green-500 hover:bg-green-600',
                    'text-white font-bold py-2 px-4 rounded'
                ].join(' '),
                variant: 'outline',
                size: 'sm',
                disabled,
                onClick: (event: MouseEvent) => {
                    event.stopPropagation();
                    if (!disabled && table.options.meta?.emit) {
                        table.options.meta.emit('done-clicked', row.original);
                    }
                }
            }, () => 'Done');
        }
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row, table }) => {
            const patient = row.original;
            return h('div', {
                class: 'relative',
                onClick: (event: MouseEvent) => event.stopPropagation(),
            }, h(PatientsDropdownAction, {
                patient,
                onPatientDeleted: () => {
                    if (table.options.meta?.emit) {
                        table.options.meta.emit('patient-deleted');
                    }
                }
            }));
        }
    }
]

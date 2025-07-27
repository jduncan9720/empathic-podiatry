<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { Button } from '@/components/ui/button';
import { Patient } from '@/components/data_table/columns';

const emit = defineEmits(['submit', 'close']);
const facilities = ref<{ id: number; name: string }[]>([]);
const props = defineProps<{ patient?: Patient | null; facilityReadonly?: boolean }>();

const form = ref({
    name: '',
    facility_id: '',
    date_of_birth: '',
    room_number: '',
    type_of_consent: '',
    primary_insurance: '',
    date_last_seen: '',
    status: '',
});

function handleSubmit() {
    emit('submit', form.value);
}

function handleClose() {
    emit('close');
}

watch(
    () => props.patient,
    (patient) => {
        if (patient) {
            form.value = { ...patient };
        } else {
            form.value = {
                name: '',
                facility_id: '',
                date_of_birth: '',
                room_number: '',
                type_of_consent: '',
                primary_insurance: '',
                date_last_seen: '',
                status: '',
            };
        }
    },
    { immediate: true }
);

onMounted(async () => {
    try {
        const response = await fetch('/api/facilities');
        if (!response.ok) throw new Error('Failed to fetch facilities');
        facilities.value = await response.json();
    } catch (error) {
        console.error(error);
        facilities.value = [];
    }
});
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <label for="name" class="font-medium">Patient Name</label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    placeholder="Patient Name"
                    required
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label for="facility_id" class="font-medium">Facility</label>
                <select
                    id="facility_id"
                    v-model="form.facility_id"
                    required
                    :disabled="props.facilityReadonly"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                >
                    <option value="" disabled>Select a facility</option>
                    <option v-for="facility in facilities" :key="facility.id" :value="facility.id">
                        {{ facility.name }}
                    </option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label for="date_of_birth" class="font-medium">Date of Birth</label>
                <input
                    id="date_of_birth"
                    v-model="form.date_of_birth"
                    type="date"
                    placeholder="Date of Birth (YYYY-MM-DD)"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label for="room_number" class="font-medium">Room Number</label>
                <input
                    id="room_number"
                    v-model="form.room_number"
                    type="text"
                    placeholder="Room Number"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label for="type_of_consent" class="font-medium">Type of Consent</label>
                <select
                    id="type_of_consent"
                    v-model="form.type_of_consent"
                    required
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                >
                    <option value="" disabled>Select type of consent</option>
                    <option value="Resident Request">Resident Request</option>
                    <option value="Physician Request">Physician Request</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label for="primary_insurance" class="font-medium">Primary Insurance</label>
                <select
                    id="primary_insurance"
                    v-model="form.primary_insurance"
                    required
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                >
                    <option value="" disabled>Select primary insurance</option>
                    <option value="Medicare">Medicare</option>
                    <option value="Medicaid">Medicaid</option>
                    <option value="Blue Cross Blue Shield">Blue Cross Blue Shield</option>
                    <option value="Humana">Humana</option>
                    <option value="Select Health">Select Health</option>
                    <option value="Cigna">Cigna</option>
                    <option value="United/AARP">United/AARP</option>
                    <option value="U of U Health">U of U Health</option>
                    <option value="Molina">Molina</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label for="date_last_seen" class="font-medium">Date Last Seen</label>
                <input
                    id="date_last_seen"
                    v-model="form.date_last_seen"
                    type="date"
                    placeholder="Date Last Seen (YYYY-MM-DD)"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label for="status" class="font-medium">Status</label>
                <select
                    id="status"
                    v-model="form.status"
                    required
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                >
                    <option value="" disabled>Select status</option>
                    <option value="treated">Treated</option>
                    <option value="deceased">Deceased</option>
                    <option value="discharged">Discharged</option>
                    <option value="refused">Refused</option>
                    <option value="visit complete">Visit Complete</option>
                </select>
            </div>
        </div>
        <br>
        <slot name="footer">
            <div class="flex gap-2 justify-end">
                <Button type="submit" class="btn btn-primary">Submit</Button>
                <Button type="button" class="btn btn-outline" @click="handleClose">Cancel</Button>
            </div>
        </slot>
    </form>
</template>

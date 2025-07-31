<script setup lang="ts">
import { ref, watch, toRefs } from 'vue';
import { Button } from '@/components/ui/button';
import { Facility } from '@/components/data_table/columns';

const props = defineProps<{ facility?: Facility | null }>();

const emit = defineEmits(['submit', 'close']);

const form = ref({
    name: '',
    address_one: '',
    address_two: '',
    city: '',
    state: 'ut',
    zip: '',
    phone_one: '',
    phone_two: '',
    email: '',
    contact_name: '',
});

function handleSubmit() {
    emit('submit', form.value);
}

function handleClose() {
    emit('close');
}

watch(
    () => props.facility,
    (facility) => {
        if (facility) {
            form.value = { ...facility };
        } else {
            form.value = {
                name: '',
                address_one: '',
                address_two: '',
                city: '',
                state: 'ut',
                zip: '',
                phone_one: '',
                phone_two: '',
                email: '',
                contact_name: '',
            };
        }
    },
    { immediate: true }
);
</script>

<template>
    <form @submit.prevent="handleSubmit" class="max-h-[70vh] overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Row 1: Facility Name and Contact Name -->
            <div class="flex flex-col gap-1">
                <label for="name" class="font-medium text-sm">Facility Name</label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    placeholder="Facility Name"
                    required
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label for="contact_name" class="font-medium text-sm">Contact Name</label>
                <input
                    id="contact_name"
                    v-model="form.contact_name"
                    type="text"
                    placeholder="Contact Name"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                />
            </div>

            <!-- Row 2: Phone One and Phone Two -->
            <div class="flex flex-col gap-1">
                <label for="phone_one" class="font-medium text-sm">Phone One</label>
                <input
                    id="phone_one"
                    v-model="form.phone_one"
                    type="text"
                    placeholder="Phone One"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label for="phone_two" class="font-medium text-sm">Phone Two</label>
                <input
                    id="phone_two"
                    v-model="form.phone_two"
                    type="text"
                    placeholder="Phone Two"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                />
            </div>

            <!-- Row 3: Email (full width) -->
            <div class="flex flex-col gap-1 md:col-span-2">
                <label for="email" class="font-medium text-sm">Email</label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    placeholder="Email Address"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                />
            </div>

            <!-- Row 4: Address One and Address Two -->
            <div class="flex flex-col gap-1">
                <label for="address_one" class="font-medium text-sm">Address One</label>
                <input
                    id="address_one"
                    v-model="form.address_one"
                    type="text"
                    placeholder="Address One"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label for="address_two" class="font-medium text-sm">Address Two</label>
                <input
                    id="address_two"
                    v-model="form.address_two"
                    type="text"
                    placeholder="Address Two"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                />
            </div>

            <!-- Row 5: City, State, and Zip -->
            <div class="flex flex-col gap-1">
                <label for="city" class="font-medium text-sm">City</label>
                <input
                    id="city"
                    v-model="form.city"
                    type="text"
                    placeholder="City"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label for="state" class="font-medium text-sm">State</label>
                <select
                    v-model="form.state"
                    id="state"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                        <option value="ut">UT</option>
                        <option value="tx">TX</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label for="zip" class="font-medium text-sm">Zip</label>
                <input
                    id="zip"
                    v-model="form.zip"
                    type="text"
                    placeholder="Zip Code"
                    class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                />
            </div>
        </div>
        
        <div class="mt-6 flex gap-2 justify-end">
            <Button type="submit" class="btn btn-primary">Submit</Button>
            <Button type="button" class="btn btn-outline" @click="handleClose">Cancel</Button>
        </div>
    </form>
</template>

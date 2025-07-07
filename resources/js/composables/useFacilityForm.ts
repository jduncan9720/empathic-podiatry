import { ref } from 'vue';

export function useFacilityForm() {
    const isSubmitting = ref(false);
    const error = ref<string | null>(null);

    async function submitFacility(form: Record<string, unknown>) {
        isSubmitting.value = true;
        error.value = null;
        const response = await fetch('/api/facilities', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(form),
        });
        isSubmitting.value = false;
        if (!response.ok) {
            error.value = 'Failed to submit facility';
            throw new Error(error.value);
        }
        return await response.json();
    }

    async function updateFacility(id: number, form: Record<string, unknown>) {
        isSubmitting.value = true;
        error.value = null;
        const response = await fetch(`/api/facilities/${id}`, {
            method: 'PUT', // or 'PATCH' if your API expects it
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(form),
        });
        isSubmitting.value = false;
        if (!response.ok) {
            error.value = 'Failed to update facility';
            throw new Error(error.value);
        }
        return await response.json();
    }

    return { submitFacility, updateFacility, isSubmitting, error };
}

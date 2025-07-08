import { ref } from 'vue';

export function usePatientForm() {
    const isSubmitting = ref(false);
    const error = ref<string | null>(null);

    async function submitPatient(form: Record<string, unknown>) {
        isSubmitting.value = true;
        error.value = null;
        const response = await fetch('/api/patients', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(form),
        });
        isSubmitting.value = false;
        if (!response.ok) {
            error.value = 'Failed to submit patient';
            throw new Error(error.value);
        }
        return await response.json();
    }

    async function updatePatient(id: number, form: Record<string, unknown>) {
        isSubmitting.value = true;
        error.value = null;
        const response = await fetch(`/api/patients/${id}`, {
            method: 'PUT', // or 'PATCH' if your API expects it
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(form),
        });
        isSubmitting.value = false;
        if (!response.ok) {
            error.value = 'Failed to update patient';
            throw new Error(error.value);
        }
        return await response.json();
    }

    return { submitPatient, updatePatient, isSubmitting, error };
}

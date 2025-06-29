<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { HousePlus, UserPlus } from 'lucide-vue-next';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="button-container">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="flex p-8 gap-8 h-full">
                        <Button variant="outline" class="w-48 h-48 flex flex-col items-center justify-center gap-2">
                            <HousePlus fill="white" class="!w-[6rem] !h-[6rem]" />
                            <span>Add a Facility</span>
                        </Button>
                        <Button variant="outline" class="w-48 h-48 flex flex-col items-center justify-center gap-2">
                            <UserPlus fill="white" class="!w-[6rem] !h-[6rem]"/>
                            Add a Patient
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

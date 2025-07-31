<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { LoaderCircle, ArrowLeft } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register - Empathic Podiatry" />
    
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <!-- Navigation -->
        <header class="relative z-10 px-6 py-6 lg:px-8">
            <nav class="mx-auto flex max-w-7xl items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2">
                        <!-- Logo Image -->
                        <img 
                            src="/images/empathic-podiatry-logo.png" 
                            alt="Empathic Podiatry" 
                            class="h-10 w-auto"
                        />
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('home')"
                        class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Home
                    </Link>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <div class="flex min-h-[calc(100vh-80px)] items-center justify-center px-6 lg:px-8">
            <div class="w-full max-w-md">
                <!-- Logo and Header -->
                <div class="text-center mb-8">
                    <div class="flex justify-center mb-6">
                        <img 
                            src="/images/empathic-podiatry-logo.png" 
                            alt="Empathic Podiatry" 
                            class="h-20 w-auto shadow-lg"
                        />
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
                        Join Empathic Podiatry
                    </h1>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">
                        Create your account to get started
                    </p>
                </div>

                <!-- Register Form -->
                <div class="rounded-xl bg-white p-8 shadow-lg dark:bg-slate-800">
                    <form @submit.prevent="submit" class="flex flex-col gap-6">
                        <div class="grid gap-6">
                            <div class="grid gap-2">
                                <Label for="name" class="text-sm font-medium text-slate-700 dark:text-slate-300">Full Name</Label>
                                <Input 
                                    id="name" 
                                    type="text" 
                                    required 
                                    autofocus 
                                    :tabindex="1" 
                                    autocomplete="name" 
                                    v-model="form.name" 
                                    placeholder="Enter your full name"
                                    class="border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="email" class="text-sm font-medium text-slate-700 dark:text-slate-300">Email address</Label>
                                <Input 
                                    id="email" 
                                    type="email" 
                                    required 
                                    :tabindex="2" 
                                    autocomplete="email" 
                                    v-model="form.email" 
                                    placeholder="Enter your email"
                                    class="border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                                />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password" class="text-sm font-medium text-slate-700 dark:text-slate-300">Password</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    required
                                    :tabindex="3"
                                    autocomplete="new-password"
                                    v-model="form.password"
                                    placeholder="Create a password"
                                    class="border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                                />
                                <InputError :message="form.errors.password" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password_confirmation" class="text-sm font-medium text-slate-700 dark:text-slate-300">Confirm password</Label>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    required
                                    :tabindex="4"
                                    autocomplete="new-password"
                                    v-model="form.password_confirmation"
                                    placeholder="Confirm your password"
                                    class="border-slate-200 focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                                />
                                <InputError :message="form.errors.password_confirmation" />
                            </div>

                            <Button 
                                type="submit" 
                                class="mt-2 w-full bg-blue-600 hover:bg-blue-700 text-white" 
                                tabindex="5" 
                                :disabled="form.processing"
                            >
                                <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                                Create Account
                            </Button>
                        </div>

                        <div class="text-center text-sm text-slate-600 dark:text-slate-400">
                            Already have an account?
                            <TextLink :href="route('login')" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" :tabindex="6">
                                Sign in
                            </TextLink>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Secure, HIPAA-compliant podiatry practice management
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

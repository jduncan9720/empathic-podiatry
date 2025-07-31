<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Heart, Users, Building2, FileText, Shield, ArrowRight } from 'lucide-vue-next';
</script>

<template>
    <Head title="Welcome - Empathic Podiatry">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    
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

                <!-- Navigation Links -->
                <div class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-blue-700 hover:shadow-md"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:text-slate-900 dark:text-slate-300 dark:hover:text-white"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="route('register')"
                            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-blue-700 hover:shadow-md"
                        >
                            Get Started
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="py-20 text-center">
                <div class="mx-auto max-w-4xl">
                    <!-- Main Logo -->
                    <div class="flex justify-center mb-8">
                        <img 
                            src="/images/empathic-podiatry-logo.png" 
                            alt="Empathic Podiatry" 
                            class="h-32 w-auto shadow-lg"
                        />
                    </div>

                    <!-- Hero Text -->
                    <h1 class="text-5xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-6xl">
                        Compassionate
                        <span class="text-blue-600 dark:text-blue-400">Foot Care</span>
                        <br />
                        for Every Patient
                    </h1>
                    <p class="mt-6 text-xl text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">
                        Streamline your podiatry practice with our comprehensive patient management system. 
                        Track patient care, manage facilities, and deliver exceptional foot health services with empathy and precision.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="route('register')"
                            class="inline-flex items-center rounded-lg bg-blue-600 px-8 py-4 text-lg font-semibold text-white shadow-lg transition-all hover:bg-blue-700 hover:shadow-xl"
                        >
                            Start Your Practice
                            <ArrowRight class="ml-2 h-5 w-5" />
                        </Link>
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="route('login')"
                            class="inline-flex items-center rounded-lg border-2 border-blue-600 px-8 py-4 text-lg font-semibold text-blue-600 transition-all hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20"
                        >
                            Sign In
                        </Link>
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="inline-flex items-center rounded-lg bg-blue-600 px-8 py-4 text-lg font-semibold text-white shadow-lg transition-all hover:bg-blue-700 hover:shadow-xl"
                        >
                            Go to Dashboard
                            <ArrowRight class="ml-2 h-5 w-5" />
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="py-20">
                <div class="mx-auto max-w-6xl">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">
                            Everything You Need for Modern Podiatry Care
                        </h2>
                        <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">
                            Comprehensive tools designed specifically for podiatry practices
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

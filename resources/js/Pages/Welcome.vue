<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Welcome to TS4.in" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        <!-- Header -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-blue-600">TS4.in</h1>
                    </div>
                    <div v-if="canLogin" class="flex items-center space-x-4">
                        <Link :href="route('login')" class="text-gray-700 hover:text-gray-900 font-medium">
                            Log in
                        </Link>
                        <Link v-if="canRegister" :href="route('register')" 
                              class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition font-medium">
                            Sign up
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-5xl font-bold text-gray-900 mb-6">
                    Free Forever Link Shortener 🔗
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Shorten URLs, generate QR codes, and create biolink pages.
                    Completely free and open-source alternative to Linktree and Bitly.
                </p>
                <div class="flex justify-center space-x-4">
                    <Link v-if="canRegister" :href="route('register')" 
                          class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-medium text-lg">
                        Get Started Free
                    </Link>
                    <Link v-if="canLogin" :href="route('login')" 
                          class="bg-white text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-lg hover:bg-blue-50 transition font-medium text-lg">
                        Sign In
                    </Link>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-4xl mb-4">🔗</div>
                    <h3 class="text-xl font-semibold mb-2">URL Shortening</h3>
                    <p class="text-gray-600">Create short, memorable links with custom aliases or auto-generated slugs.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold mb-2">Click Analytics</h3>
                    <p class="text-gray-600">Track clicks with detailed analytics including location, device, and referrer data.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-4xl mb-4">📱</div>
                    <h3 class="text-xl font-semibold mb-2">QR Code Generation</h3>
                    <p class="text-gray-600">Generate and download QR codes for your short links instantly.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-4xl mb-4">🌐</div>
                    <h3 class="text-xl font-semibold mb-2">Biolink Pages</h3>
                    <p class="text-gray-600">Create Linktree-style profile pages with customizable themes.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-4xl mb-4">⏰</div>
                    <h3 class="text-xl font-semibold mb-2">Link Scheduling</h3>
                    <p class="text-gray-600">Set expiration dates for temporary campaigns and promotions.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-4xl mb-4">🔒</div>
                    <h3 class="text-xl font-semibold mb-2">100% Free</h3>
                    <p class="text-gray-600">Open-source and free forever. No hidden charges or premium tiers.</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center text-gray-600">
                    <p class="mb-2">Made with ❤️ in India 🇮🇳</p>
                    <p class="text-sm">
                        <a href="https://github.com/slowestwind/url-shortner-ts4" target="_blank" class="text-blue-600 hover:underline">
                            Open Source on GitHub
                        </a>
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

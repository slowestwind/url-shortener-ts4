<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    link: Object,
    analytics: Object,
});

const qrCodeUrl = computed(() => route('links.qr', props.link.id));
const qrDownloadUrl = computed(() => route('links.qr.download', props.link.id));

const copyToClipboard = async (url) => {
    try {
        await navigator.clipboard.writeText(url);
        alert('Link copied to clipboard!');
    } catch (err) {
        console.error('Failed to copy:', err);
    }
};
</script>

<template>
    <Head :title="`Link Analytics - ${link.title || link.slug}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Link Analytics</h2>
                <Link :href="route('links.index')" class="text-gray-600 hover:text-gray-900">
                    ← Back to Links
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Link Info Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                {{ link.title || link.slug }}
                            </h3>
                            <div class="flex items-center space-x-2 mb-4">
                                <span class="font-mono text-lg bg-blue-50 text-blue-700 px-4 py-2 rounded border border-blue-200">
                                    {{ link.public_url }}
                                </span>
                                <button @click="copyToClipboard(link.public_url)" 
                                        class="text-blue-600 hover:text-blue-800 p-2" 
                                        title="Copy link">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="text-gray-600 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                    <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                                </svg>
                                <a :href="link.target_url" target="_blank" class="hover:underline">
                                    {{ link.target_url }}
                                </a>
                            </div>
                            <div v-if="link.description" class="text-gray-600 mt-2">
                                {{ link.description }}
                            </div>
                        </div>
                        <div class="ml-6">
                            <Link :href="route('links.edit', link.id)" 
                                  class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                Edit Link
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Analytics Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-600 text-sm mb-2">Total Clicks</div>
                        <div class="text-3xl font-bold text-blue-600">{{ analytics.total_clicks }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-600 text-sm mb-2">Today</div>
                        <div class="text-3xl font-bold text-green-600">{{ analytics.today_clicks }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-600 text-sm mb-2">This Week</div>
                        <div class="text-3xl font-bold text-purple-600">{{ analytics.week_clicks }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-600 text-sm mb-2">This Month</div>
                        <div class="text-3xl font-bold text-orange-600">{{ analytics.month_clicks }}</div>
                    </div>
                </div>

                <!-- QR Code Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">QR Code</h3>
                    <div class="flex items-center space-x-6">
                        <div class="border-2 border-gray-200 rounded-lg p-4 bg-white">
                            <img :src="qrCodeUrl" alt="QR Code" class="w-48 h-48">
                        </div>
                        <div>
                            <p class="text-gray-600 mb-4">Scan this QR code to access your short link</p>
                            <a :href="qrDownloadUrl" 
                               class="inline-block bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition">
                                📥 Download QR Code
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Clicks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">Recent Clicks</h3>
                    </div>
                    <div class="p-6">
                        <div v-if="analytics.recent_clicks?.length" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Browser</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referrer</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="click in analytics.recent_clicks" :key="click.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ click.clicked_at }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ click.country || 'Unknown' }}
                                            <span v-if="click.city">, {{ click.city }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ click.device || 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ click.browser || 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            {{ click.referrer || 'Direct' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-center text-gray-500 py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p>No clicks yet. Share your link to start tracking!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

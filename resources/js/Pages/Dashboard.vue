<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
    recentLinks: Array,
});

const totalClicks = computed(() => props.stats?.total_clicks || 0);
const totalLinks = computed(() => props.stats?.total_links || 0);
const todayClicks = computed(() => props.stats?.today_clicks || 0);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-600 text-sm mb-2">Total Links</div>
                        <div class="text-3xl font-bold text-gray-900">{{ totalLinks }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-600 text-sm mb-2">Total Clicks</div>
                        <div class="text-3xl font-bold text-blue-600">{{ totalClicks }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-600 text-sm mb-2">Today's Clicks</div>
                        <div class="text-3xl font-bold text-green-600">{{ todayClicks }}</div>
                    </div>
                </div>

                <!-- Recent Links -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold">Recent Links</h3>
                            <Link :href="route('links.create')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                Create New Link
                            </Link>
                        </div>
                    </div>
                    <div class="p-6">
                        <div v-if="recentLinks?.length" class="space-y-4">
                            <div v-for="link in recentLinks" :key="link.id" class="flex items-center justify-between border-b pb-4 last:border-0">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ link.title || link.slug }}</div>
                                    <div class="text-sm text-gray-500 truncate">{{ link.target_url }}</div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        <span class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ link.click_count }} clicks
                                        </span>
                                    </div>
                                </div>
                                <Link :href="route('links.show', link.id)" class="text-blue-600 hover:text-blue-800 font-medium">
                                    View Details →
                                </Link>
                            </div>
                        </div>
                        <div v-else class="text-center text-gray-500 py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <p class="text-lg mb-4">No links created yet</p>
                            <Link :href="route('links.create')" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition">
                                Create Your First Link
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    links: Object,
});

const deleteLink = (linkId) => {
    if (confirm('Are you sure you want to delete this link? This action cannot be undone.')) {
        router.delete(route('links.destroy', linkId));
    }
};

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
    <Head title="My Links" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Links</h2>
                <Link :href="route('links.create')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    + Create New Link
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="links.data.length" class="space-y-4">
                            <div v-for="link in links.data" :key="link.id" 
                                 class="border rounded-lg p-5 hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-lg text-gray-900 mb-2">
                                            {{ link.title || link.slug }}
                                        </h3>
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="font-mono text-sm bg-blue-50 text-blue-700 px-3 py-1 rounded border border-blue-200">
                                                {{ link.slug }}
                                            </span>
                                            <button @click="copyToClipboard(link.public_url)" 
                                                    class="text-gray-500 hover:text-gray-700" 
                                                    title="Copy link">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="text-sm text-gray-600 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                                            </svg>
                                            <span class="truncate">{{ link.target_url }}</span>
                                        </div>
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ link.click_count }} clicks
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ link.created_at }}
                                            </span>
                                            <span v-if="link.expires_at" class="flex items-center text-orange-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                Expires: {{ link.expires_at }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 ml-4">
                                        <Link :href="route('links.show', link.id)" 
                                              class="text-blue-600 hover:text-blue-800 text-sm font-medium whitespace-nowrap">
                                            📊 Analytics
                                        </Link>
                                        <Link :href="route('links.edit', link.id)" 
                                              class="text-green-600 hover:text-green-800 text-sm font-medium whitespace-nowrap">
                                            ✏️ Edit
                                        </Link>
                                        <button @click="deleteLink(link.id)" 
                                                class="text-red-600 hover:text-red-800 text-sm font-medium whitespace-nowrap">
                                            🗑️ Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center text-gray-500 py-16">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <p class="text-xl mb-4 font-medium">No links created yet</p>
                            <p class="text-gray-500 mb-6">Create your first short link to get started!</p>
                            <Link :href="route('links.create')" 
                                  class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition font-medium">
                                + Create Your First Link
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="links.links && links.links.length > 3" class="mt-6">
                    <div class="flex justify-center space-x-1">
                        <component v-for="(link, index) in links.links" :key="index"
                                   :is="link.url ? Link : 'span'"
                                   :href="link.url" 
                                   :class="[
                                       'px-4 py-2 text-sm rounded',
                                       link.active 
                                           ? 'bg-blue-600 text-white font-medium' 
                                           : link.url 
                                               ? 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300' 
                                               : 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                   ]"
                                   v-html="link.label">
                        </component>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

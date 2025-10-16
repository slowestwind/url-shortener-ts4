<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    target_url: '',
    custom_alias: '',
    title: '',
    description: '',
    category: '',
    expires_at: '',
});

const submit = () => {
    form.post(route('links.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Create Link" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New Link</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6 space-y-6">
                        <!-- Target URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Target URL <span class="text-red-500">*</span>
                            </label>
                            <input v-model="form.target_url" 
                                   type="url" 
                                   required
                                   placeholder="https://example.com/your-long-url" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">The URL you want to shorten</p>
                            <div v-if="form.errors.target_url" class="text-red-600 text-sm mt-1">
                                {{ form.errors.target_url }}
                            </div>
                        </div>

                        <!-- Custom Alias -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Custom Alias (optional)
                            </label>
                            <div class="flex items-center">
                                <span class="text-gray-500 bg-gray-50 px-3 py-2 border border-r-0 border-gray-300 rounded-l-md">
                                    ts4.in/
                                </span>
                                <input v-model="form.custom_alias" 
                                       type="text" 
                                       placeholder="my-custom-link"
                                       pattern="[a-zA-Z0-9\-_]+"
                                       class="flex-1 border-gray-300 rounded-r-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Leave empty for auto-generated slug. Only letters, numbers, hyphens, and underscores allowed.</p>
                            <div v-if="form.errors.custom_alias" class="text-red-600 text-sm mt-1">
                                {{ form.errors.custom_alias }}
                            </div>
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Title
                            </label>
                            <input v-model="form.title" 
                                   type="text" 
                                   placeholder="My Awesome Link"
                                   maxlength="255"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">A friendly name to help you identify this link</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea v-model="form.description" 
                                      rows="3"
                                      placeholder="Optional description for your reference..."
                                      maxlength="1000"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </textarea>
                            <p class="text-xs text-gray-500 mt-1">{{ form.description?.length || 0 }}/1000 characters</p>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Category
                            </label>
                            <select v-model="form.category" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Select Category --</option>
                                <option value="personal">Personal</option>
                                <option value="business">Business</option>
                                <option value="social">Social Media</option>
                                <option value="marketing">Marketing</option>
                                <option value="education">Education</option>
                                <option value="other">Other</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Organize your links by category</p>
                        </div>

                        <!-- Expiration Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Expiration Date (optional)
                            </label>
                            <input v-model="form.expires_at" 
                                   type="datetime-local"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Link will stop working after this date (leave empty for permanent link)</p>
                            <div v-if="form.errors.expires_at" class="text-red-600 text-sm mt-1">
                                {{ form.errors.expires_at }}
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                            <Link :href="route('links.index')" 
                                  class="text-gray-600 hover:text-gray-900 px-4 py-2 font-medium">
                                Cancel
                            </Link>
                            <button type="submit" 
                                    :disabled="form.processing"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed font-medium transition">
                                <span v-if="form.processing">Creating...</span>
                                <span v-else>🔗 Create Short Link</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Help Text -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-medium text-blue-900 mb-2">💡 Tips:</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Use custom aliases to create memorable links</li>
                        <li>• Set expiration dates for temporary campaigns</li>
                        <li>• Organize links with categories for easy management</li>
                        <li>• Add descriptions to remember link purposes</li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

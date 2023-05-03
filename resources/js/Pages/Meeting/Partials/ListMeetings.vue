<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DropDown from '@/Components/Dropdown.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    meetings: {
        type: Object,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    citizen: user.id,
    lawyer: null,
    date: null
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Manage meetings</h2>

            <p class="mt-1 text-sm text-gray-600">
                Browse, reschedule or cancel meetings.
            </p>
        </header>
            <div class="py-12">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
                <table class="w-max divide-y divide-gray-200 border">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Citizen</span>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date</span>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</span>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Reschedule</span>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Cancel</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                        <tr v-for="(meeting, index) in meetings" :key="index">
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                {{ `${meeting.citizen.first_name} ${meeting.citizen.last_name}`}}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                {{ meeting.date }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                {{ meeting.status.charAt(0).toUpperCase() + meeting.status.slice(1) }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                <PrimaryButton>Change</PrimaryButton>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                <PrimaryButton>Cancel</PrimaryButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</template>

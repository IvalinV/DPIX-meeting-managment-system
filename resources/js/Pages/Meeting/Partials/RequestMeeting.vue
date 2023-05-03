<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DropDown from '@/Components/Dropdown.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    lawyers: {
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
            <h2 class="text-lg font-medium text-gray-900">Request meeting</h2>

            <p class="mt-1 text-sm text-gray-600">
                Request a meeting with a lawyer.
            </p>
        </header>

        <form @submit.prevent="form.post(route('meeting.store'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="lawyer" value="Select Lawyer" />

                <select class="mt-1 block w-full" v-model="form.lawyer" name="lawyer" id="lawyer">
                    <option selected disabled :value="null">Select Lawyer</option>
                    <option 
                        v-for="(lawyer, index) in lawyers" 
                        :key="index" 
                        :value="lawyer.id"
                    >
                    {{ `${lawyer.first_name} ${lawyer.last_name}`}}
                    </option>
                </select>

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="date" value="Date" />

                <TextInput
                    id="date"
                    type="datetime-local"
                    class="mt-1 block w-full"
                    v-model="form.date"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.date" />
            </div>


            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Requested.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>

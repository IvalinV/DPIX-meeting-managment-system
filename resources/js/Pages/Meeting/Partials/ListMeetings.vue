<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DropDown from '@/Components/Dropdown.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref, reactive, computed } from 'vue';
import { TailwindPagination } from 'laravel-vue-pagination';
import debounce from 'lodash.debounce';
import { initFlowbite } from 'flowbite';

let props = defineProps({
    meetings: {
        type: Object,
    },
});

const sortDirection = computed(() => {
  return state.sortBy === 'asc' ? 'desc' : 'asc';
})

const sortByDate = (param) => {

    axios.post('/api/meeting/sort', {
        param: param,
        direction: state.sortBy === 'asc' ? 'Desc' : 'asc',
        lawyer_id: user.id
    }).then(({data}) => {
        if(state.sortBy === 'asc'){
            state.sortBy = 'desc';
        }
        else{
            state.sortBy = 'asc';
        }
        state.lawyer_meetings = data;
    })
};

const filter = () => {
    axios.post('/api/meeting/filter', {
        lawyer_id: user.id,
        filter_by: state.filterBy
    }).then(({data}) => {
        state.lawyer_meetings = data;
    });
}

const state = reactive({
    lawyer_meetings: null,
    sortBy: 'asc',
    filterBy: null
});

onMounted(() => {
   list();
   initFlowbite();
})

async function list(page=1){
    await axios.get(`/api/meetings?page=${page}`)
    .then(({data})=>{
        state.lawyer_meetings = data;
    })
}

let search_param = null;

const search = debounce(() => {
    axios.get(`/api/meeting/search?search=${search_param}`).then(({data})=>{
        state.lawyer_meetings = data;
    })
}, 500)


const user = usePage().props.auth.user;

const updateMeeting = (id, meeting_tatus) => {
    axios.post(route('meeting.update'), {
        meeting_id: id,
        status: meeting_tatus
    }).then(() =>{
        console.log(usePage().props.flash)
    });
};
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
                <div class="flex items-center justify-between pb-4">

                    <button id="dropdownRadioButton" data-dropdown-toggle="dropdownDefaultRadio" class="text-gray-600 bg-white border border-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Filter<svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>

                    <!-- Dropdown menu -->
                    <div id="dropdownDefaultRadio" class="z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600">
                        <ul class="p-3 space-y-3 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownRadioButton">
                        <li>
                            <div class="flex items-center">
                                <input id="default-radio-1" v-model="state.filterBy" type="radio" @change="filter" value="status" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                <label for="default-radio-1" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Status</label>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <input checked id="default-radio-2" v-model="state.filterBy" type="radio" @change="filter" value="latest" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Latest</label>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <input id="default-radio-3" v-model="state.filterBy" type="radio" @change="filter" value="oldest" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                <label for="default-radio-3" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Oldest</label>
                            </div>
                        </li>
                        </ul>
                    </div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input 
                            type="text" v-model="search_param" 
                            id="table-search" 
                            class="block p-2 pl-10 text-sm text-gray-900 border
                             border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500
                             focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                             dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                            placeholder="Search for items"
                            @keyup="search"
                        >
                    </div>
                </div>
                <table class="w-max divide-y divide-gray-200 border">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Citizen</span>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <div class="flex items-center">
                                    <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date</span>
                                    <a href="#" @click="sortByDate('date')"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                                </div>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left">
                                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</span>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-center">
                                <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody v-if="state.lawyer_meetings && state.lawyer_meetings.data.length > 0" class="bg-white divide-y divide-gray-200 divide-solid">
                        <tr v-for="(meeting, index) in state.lawyer_meetings.data" :key="index">
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                {{ `${meeting?.first_name} ${meeting?.last_name}`}}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                {{ meeting?.date }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                {{ meeting?.status?.charAt(0).toUpperCase() + meeting?.status?.slice(1) }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                <PrimaryButton class="mr-4" @click="updateMeeting(meeting?.id, 'accepted')">Accept</PrimaryButton>
                                <PrimaryButton class="mr-4" @click="updateMeeting(meeting?.id, 'reschedule')">Change</PrimaryButton>
                                <PrimaryButton class="mr-4" @click="updateMeeting(meeting?.id, 'canceled')">Cancel</PrimaryButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <TailwindPagination 
                    align="center" 
                    :data="state.lawyer_meetings" 
                    @pagination-change-page="list"
                    v-if="state.lawyer_meetings && state.lawyer_meetings.data.length > 0"
                />
            </div>
        </div>
    </section>
</template>

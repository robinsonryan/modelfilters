<template>
    <div class="max-w-2xl mx-auto p-4 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-gray-700">User Search</h1>
        <span @click="showHowItWorks" class="text-sm text-blue-500 cursor-pointer">[ Learn More ]</span><span class="text-sm text-black-500"> This form automatically applies filtering queries to the underlying Laravel model.</span>
        <br>
        <form @submit.prevent="submitForm" class="space-y-2 mt-4">
            <div class="flex flex-col">
                <label for="name" class="mb-1 text-gray-600">NAME or NICKNAME</label>
                <input
                    v-model="form.name"
                    type="text"
                    id="name"
                    name="name"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter name"
                />
            </div>

            <!--div class="flex flex-col">
                <label for="nickname" class="mb-1 text-gray-600">NICKNAME</label>
                <input
                    v-model="form.nickname"
                    type="text"
                    id="nickname"
                    name="nickname"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter nickname"
                />
            </div-->

            <div class="flex flex-col">
                <label for="country" class="mb-1 text-gray-600">(not) COUNTRY</label>
                <input
                    v-model="form.country"
                    type="text"
                    id="country"
                    name="country"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter country"
                />
            </div>

            <div class="flex flex-col">
                <label for="pet" class="mb-1 text-gray-600">PET</label>
                <input
                    v-model="form.pet"
                    type="text"
                    id="pet"
                    name="pet"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter pet"
                />
            </div>

            <div class="flex flex-col">
                <label for="car_model" class="mb-1 text-gray-600">CAR MODEL</label>
                <input
                    v-model="form.car_model"
                    type="text"
                    id="car_model"
                    name="car_model"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter car model"
                />
            </div>

            <div class="flex flex-col">
                <label for="favorite_food" class="mb-1 text-gray-600">FAVORITE FOOD</label>
                <input
                    v-model="form.favorite_food"
                    type="text"
                    id="favorite_food"
                    name="favorite_food"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter favorite food"
                />
            </div>

            <div class="flex flex-col">
                <label for="email" class="mb-1 text-gray-600">EMAIL</label>
                <input
                    v-model="form.email"
                    type="email"
                    id="email"
                    name="email"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter email"
                />
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                Search
            </button>
        </form>

        <!-- Modal for How It Works -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full max-h-screen overflow-y-auto">
                <h2 class="text-xl font-bold mb-4">How It Works</h2>

                <p>
                    The purpose of this search form is to demonstrate the underlying automated process of building search queries for models in Laravel applications. It allows you to easily define filters within your <strong>FilterRequest</strong> ( which extendse Laravel's <strong>FormRequest</strong> class ) and automatically apply those filters to Eloquent models in the controller.
                </p>

                <p class="mt-4">
                    Here’s how it works:
                </p>

                <ul class="list-disc list-inside space-y-2 mt-2">
                    <li>
                        <strong>Create a FilterRequest:</strong> In your app, create a new class that extends the <code>FilterRequest</code>. This class will define your validation rules, messages, and, most importantly, the <code>filters()</code> method.
                    </li>
                    <li>
                        <strong>Define the Filters:</strong> In the <code>filters()</code> method, you map the input fields (e.g., 'name', 'email', 'nickname') to the filtering logic. For example, this form uses:
                        <pre class="bg-gray-100 p-3 rounded-lg text-sm mt-2 overflow-auto">
<code class="whitespace-pre-wrap">
public function filters(): array
{
    return [
        'name' => 'string|or:name,nickname, // Apply an OR filter on 'name' and 'nickname'
        'email' => 'string:email',      // Custom column 'email'
        'country' => 'string|not',      // Exclude certain countries
    ];
}
</code>
                </pre>
                        Each filter string follows the format: <code>'input_name(s)' => 'method|modifier(s):property_name(s)'</code>. The method and modifiers define how the query is built.
                    </li>
                    <li>
                        <strong>Apply Filters in the Controller:</strong> In the controller, simply inject the <code>FilterRequest</code> and <code>ModelFilter</code> into the method and call the filter logic:
                        <pre class="bg-gray-100 p-3 rounded-lg text-sm mt-2 overflow-auto">
<code class="whitespace-pre-wrap">
public function search(UsersFilterRequest $request, ModelFilter $filter): Response
{
    $query = User::filter($request, $filter)->get();
}
</code>
                </pre>
                        This automatically applies the filters defined in the <code>FilterRequest</code> class to the model's query, handling the search logic behind the scenes.
                    </li>
                </ul>

                <p class="mt-6">
                    <strong>Note:</strong> The current implementation focuses primarily on string-based filtering with simple modifiers like <strong>and</strong>, <strong>or</strong>, and <strong>not</strong>. While this system provides flexibility for basic searches, there are several plans to extend its functionality.
                </p>

                <!-- Future Plans Section -->
                <h3 class="text-lg font-semibold mt-4">Future Plans:</h3>
                <ul class="list-disc list-inside space-y-2 mt-2">
                    <li><strong>Support for numeric filters:</strong> Add methods to handle integers and numeric ranges (e.g., greater than, less than, between).</li>
                    <li><strong>Date filters:</strong> Support for date-based queries, such as filtering by dates before, after, or between specified ranges.</li>
                    <li><strong>Custom filter methods:</strong> Allow developers to define custom filtering methods, making it easier to handle complex queries and edge cases.</li>
                    <li><strong>Related model filtering:</strong> Enable filtering based on properties in related models (e.g., filtering users based on properties in their associated posts).</li>
                    <li><strong>Additional modifiers:</strong> Introduce new modifiers such as <strong>not_null</strong> for checking non-empty fields, and <strong>exists</strong> to filter records based on the existence of related data.</li>
                </ul>

                <button @click="closeModal" class="mt-6 bg-blue-600 text-white py-1 px-3 rounded-lg">Close</button>
            </div>
        </div>

        <div v-if="users.length" class="mt-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-3" v-if="totalUsers === null">No results to show</h2>
            <h2 class="text-lg font-semibold text-gray-700 mb-3" v-else>Showing the first 5 of ({{ formatNumber(totalUsers) }} total matches)</h2>

            <ul class="space-y-2">
                <li v-for="user in users" :key="user.id" class="bg-gray-100 px-3 py-2 rounded-lg shadow-sm">
                    <p class="text-gray-700 font-medium">{{ user.name }} ({{ user.nickname }})</p>
                    <p class="text-gray-500">{{ user.email }} (from: {{ user.country }}</p>
                </li>
            </ul>
        </div>

        <div v-else class="mt-4">
            <p class="text-gray-500">No results found.</p>
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import {ref} from "vue";

export default {
    props: {
        users: {
            type: Array,
            default: () => [],
        },
        totalUsers: {
            type: Number,
            default: null,
        },
        filters: {
            type: Object,
            default: () => ({
                name: '',
                nickname: '',
                country: '',
                pet: '',
                car_model: '',
                favorite_food: '',
                email: '',
            }),
        },
    },
    setup(props) {
        // Use Inertia's useForm helper
        const form = useForm({
            name: props.filters.name || '',
            nickname: props.filters.nickname || '',
            country: props.filters.country || '',
            pet: props.filters.pet || '',
            car_model: props.filters.car_model || '',
            favorite_food: props.filters.favorite_food || '',
            email: props.filters.email || '',
        });

        const submitForm = () => {
            form.post(route('users.search'), {
                onSuccess: () => {
                    // Reset form values after success or update with new data if needed
                    // not resetting so we don't lose the values
                    //form.reset();
                },
            });
        };

        const showModal = ref(false);

        const showHowItWorks = () => {
            showModal.value = true;
        };

        const closeModal = () => {
            showModal.value = false;
        };

        const formatNumber = (value) => {
            return value !== null
                ? new Intl.NumberFormat().format(value)
                : null;
        };

        return {
            form,
            submitForm,
            showModal,
            showHowItWorks,
            closeModal,
            formatNumber
        };
    },
};
</script>

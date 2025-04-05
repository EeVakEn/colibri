<template>

    <ais-instant-search :search-client="searchClient" index-name="content_index">
        <ais-configure
            :hits-per-page.camel="5"
        >
            <ais-autocomplete class="relative" >
                <template v-slot="{ currentRefinement, indices, refine }">
                    <span class="relative">
                        <Icon name="search" class="absolute left-2 top-1/2 -translate-y-3/4 h-4 w-4 text-gray-400"/>
                        <input
                            type="search"
                            :value="currentRefinement"
                            ref="search"
                            placeholder="Search"
                            class="input-field pl-7"
                            autofocus
                            autocomplete="off"
                            @input="refine($event.currentTarget.value)"
                            @keydown.enter="search"
                        >
                    </span>

                    <div class="absolute z-10 transform mt-3 px-2 w-full sm:px-0" v-if="currentRefinement.length">
                        <div class="rounded-lg shadow-lg overflow-hidden">
                            <div class="bg-white px-1 py-2">
                                <div class="divide-y divide-blue-900" v-for="item in indices" :key="item.objectID">
                                    <Link :href="route('contents.show', hit.id)" class="hover-li flex gap-2" v-for="(hit, index) in item.hits" :key="index">
                                        <div class="flex w-24 aspect-video">
                                            <img
                                                :src="hit.preview_url"
                                                :alt="hit.title"
                                                class="object-cover"
                                            />
                                        </div>
                                        <div class="w-full justify-content-between">
                                            <div>
                                                <div>
                                                    <ais-highlight attribute="title" :hit="hit" class="block text-blue-300 font-medium"></ais-highlight>
                                                </div>
                                                <div class="text-gray-500 text-sm">
                                                    Views: {{hit.views_count}}
                                                </div>
                                            </div>

                                            <div class="text-gray-500 text-sm">
                                                {{moment(hit.date).fromNow()}}
                                            </div>
                                        </div>

                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </ais-autocomplete>
        </ais-configure>
    </ais-instant-search>
</template>


<script setup>
import TypesenseInstantSearchAdapter from "typesense-instantsearch-adapter"
import {Link} from "@inertiajs/vue3";
import Icon from "@/UI/Icon.vue";
import moment from "moment";
import {route} from "ziggy-js";
import {useTemplateRef} from "vue";

const typesenseInstantsearchAdapter = new TypesenseInstantSearchAdapter({
    server: {
        apiKey: "xyz",
        nodes: [
            {
                host: "localhost",
                path: "",
                port: "8108",
                protocol: "http",
            },
        ],
    },
    additionalSearchParameters: {
        query_by: "title,content,transcript",
    },
});

const searchClient = typesenseInstantsearchAdapter.searchClient;
const searchInput = useTemplateRef('search')
function search() {
    console.log(searchInput.value)
    if(searchInput.value && searchInput.value.value){
        const encodedQuery = encodeURIComponent(searchInput.value.value);
        window.location.href = route('search', { q: encodedQuery });
    }
}
</script>



<style>
@import 'instantsearch.css/themes/satellite-min.css';
</style>


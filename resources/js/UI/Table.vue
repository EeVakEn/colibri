<script setup>
import {ref, onBeforeMount, reactive, watch, defineExpose} from "vue";
import {ChevronsUpDown, ChevronDown, ChevronUp, ChevronRight, ChevronLeft, ListFilter} from 'lucide-vue-next';
import axios from 'axios';

// Пропс для передачи метаинформации о таблице
const props = defineProps({
    tableMeta: {
        type: Object,
        required: true,
    },
});

// Модели для фильтров, пагинации и данных
const search = ref('')
const isOpenFilter = ref('')
const filters = reactive({});
const pagination = reactive({
    currentPage: 1,
    totalPages: 1,
});
const sort = reactive({
    sortBy: null,
    sortDesc: false,
});

const data = ref([]);

// Функция для получения данных с API
const query = ref(route(props.tableMeta.apiUrl));

const buildQuery = () => {
    let queryParams = [];

    // Поиск
    if (search.value) {
        queryParams.push(`search=${search.value}`);
    }

    // Фильтры
    const localFilters = {}
    Object.keys(filters).forEach(filter => {
        if (!!filters[filter] || filters[filter] === 0) {
            localFilters[filter] = filters[filter]
        }
    })
    if (Object.keys(localFilters).length) {
        queryParams.push(`filters=${JSON.stringify(localFilters)}`)
    }
    // Пагинация
    queryParams.push(`page=${pagination.currentPage}`);

    // Сортировка
    if (sort.sortBy) {
        queryParams.push(`sort_by=${sort.sortBy}`);
        if (sort.sortDesc) {
            queryParams.push(`sort_desc=true`);
        }
    }

    // Строка запроса
    return queryParams.length ? `?${queryParams.join("&")}` : '';
};

const fetchData = async (url) => {
    try {
        const response = await axios.get(url);
        // Обновляем данные
        data.value = response.data.data.data;

        pagination.currentPage = response.data.data.last_page >= response.data.data.current_page ? response.data.data.current_page: 1;
        pagination.totalPages = response.data.data.last_page;
    } catch (error) {
        console.error("Error fetching data:", error);
    }
};
watch([search, filters, pagination, sort], () => {
    query.value = route(props.tableMeta.apiUrl) + buildQuery();
}, {immediate: true});


watch(query, async (newValue) => {
    await fetchData(newValue);
})
onBeforeMount(() => {
    fetchData(route(props.tableMeta.apiUrl));
    props.tableMeta.filters.forEach(filter => {
        filters[filter.name] = filter.defaultOption
    })
});
const sortBy = (column) => {
    // Проверяем, выбран ли тот же столбец для сортировки
    if (sort.sortBy !== column.field) {
        sort.sortBy = column.field;
        sort.sortDesc = false; // Если выбран новый столбец, сортировка будет по возрастанию
    } else {
        // Если столбец тот же, меняем направление сортировки
        sort.sortDesc = !sort.sortDesc;
    }
};
// Изменение страницы
const changePage = (newPage) => {
    if (newPage > 0 && newPage <= pagination.totalPages) {
        pagination.currentPage = newPage
    }
};

const refresh = async () => {
    await fetchData(query.value);
};
defineExpose({
    refresh,
});
</script>

<template>
    <div class="w-full">
        <!-- Панель фильтров -->
        <div class="flex items-center justify-between gap-4 mb-4">
            <input
                v-model="search"
                type="text"
                placeholder="Search..."
                class="input-field max-w-[300px]"
            />
            <ListFilter class="cursor-pointer" v-if="props.tableMeta.filters.length" @click="isOpenFilter = !isOpenFilter" size="30"/>
        </div>
        <Transition name="fade">
            <div v-if="isOpenFilter" class="rounded-lg  border border-gray-200 px-4 py-2 my-2">
                <h2 class="mb-3 font-bold">Filters</h2>
                <!-- Панель фильтров -->
                <div v-for="(filter, index) in props.tableMeta.filters" :key="index" class="flex items-center">
                    <div class="flex flex-col">
                        <label :for=filter.name class="text-sm font-medium text-gray-700">{{filter.label}}</label>
                        <select
                            v-model="filters[filter.name]"
                            class="select-field min-w-[200px]"
                            :id="filter.name"
                        >
                            <option v-for="value in filter.options" :value="value['value']" :key="value['value']">{{ value['text'] }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </Transition>




        <!-- Таблица -->
        <div class="rounded-lg border border-gray-200">

            <div class="overflow-x-auto rounded-t-lg" style="overflow: visible">
            <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                <thead >
                <tr class="bg-indigo-200">
                    <th
                        v-for="col in tableMeta.columns"
                        :key="col.field"
                        class="whitespace-nowrap text-left px-4 py-2 font-medium text-gray-900"
                        :class="col.sortable? 'cursor-pointer': ''"
                        @click="col.sortable && sortBy(col)"
                    >
                        {{ col.label }}
                        <span v-if="col.sortable">
                            <template v-if="sort.sortBy === col.field">
                                <template v-if="!sort.sortDesc"><ChevronDown :size="17" class="inline"/></template>
                                <template v-else><ChevronUp :size="17" class="inline"/></template>
                            </template>
                            <template v-else><ChevronsUpDown :size="17" class="inline"/></template>
                        </span>
                    </th>
                </tr>
                </thead>
                <tbody>
                <Transition v-for="row in data" name="fade">
                    <tr :key="row.id" class="border-t">
                        <td
                            v-for="col in tableMeta.columns"
                            :key="col.field"
                            class="whitespace-nowrap px-4 py-2 text-gray-700"
                        >
                            <slot :name="`cell(${col.field})`" :item="row[col.field]" :data="row">
                                {{ row[col.field] }}
                            </slot>
                        </td>
                    </tr>
                </Transition>

                <tr v-if="data.length === 0">
                    <td :colspan="tableMeta.columns.length" class="text-center p-4 text-gray-500">
                        No data available.
                    </td>
                </tr>
                </tbody>
            </table>
            </div>
            <div class="rounded-b-lg border-t border-gray-200 px-4 py-2">
                <!-- Пагинация -->
                <div class="flex justify-end gap-2 text-xs font-medium items-center">
                    <button
                        class="btn-primary p-1"
                        :disabled="pagination.currentPage === 1"
                        @click="changePage(pagination.currentPage - 1)"
                    >
                        <ChevronLeft color="white"/>
                    </button>

                    <span class="text-gray-700">
                {{ pagination.currentPage }} /
                {{ pagination.totalPages }}
            </span>

                    <button
                        class="btn-primary p-1"
                        :disabled="pagination.currentPage === pagination.totalPages"
                        @click="changePage(pagination.currentPage + 1)"
                    >
                        <ChevronRight color="white"/>
                    </button>
                </div>
            </div>
        </div>


    </div>
</template>

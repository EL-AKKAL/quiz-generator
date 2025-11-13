<script setup lang="ts">
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import { Paginator } from '@/types';
import { Link } from '@inertiajs/vue3';
import PaginationFirst from '../ui/pagination/PaginationFirst.vue';
import PaginationLast from '../ui/pagination/PaginationLast.vue';

defineProps<{ data: Paginator<any> }>();
</script>
<template>
    <div class="mb-4">
        <Pagination
            v-slot="{ page }"
            :items-per-page="data.per_page"
            :total="data.total"
            :default-page="data.current_page"
            v-if="data.total > data.per_page"
        >
            <PaginationContent v-slot="{ items }">
                <PaginationFirst
                    :class="{
                        'pointer-events-none opacity-50':
                            data.current_page === 1,
                    }"
                    :as="Link"
                    :href="data.first_page_url || undefined"
                />
                <PaginationPrevious
                    :class="{
                        'pointer-events-none opacity-50': !data.prev_page_url,
                    }"
                    :as="Link"
                    :href="data.prev_page_url || undefined"
                />

                <template v-for="(item, index) in items" :key="index">
                    <PaginationItem
                        v-if="item.type === 'page'"
                        :value="item.value"
                        :is-active="item.value === page"
                        :as="Link"
                        :href="`?page=${item.value}`"
                    >
                        {{ item.value }}
                    </PaginationItem>
                </template>

                <PaginationEllipsis v-if="data.last_page > 5" :index="4" />

                <PaginationNext
                    :class="{
                        'pointer-events-none opacity-50': !data.next_page_url,
                    }"
                    :as="Link"
                    :href="data.next_page_url || undefined"
                />
                <PaginationLast
                    :class="{
                        'pointer-events-none opacity-50':
                            data.current_page === data.last_page,
                    }"
                    :as="Link"
                    :href="data.last_page_url || undefined"
                />
            </PaginationContent>
        </Pagination>
    </div>
</template>

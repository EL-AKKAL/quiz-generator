<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import { Form, usePage } from '@inertiajs/vue3';
import { Bell } from 'lucide-vue-next';
import moment from 'moment';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import Button from '../ui/button/Button.vue';
import DropdownMenu from '../ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuContent from '../ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuItem from '../ui/dropdown-menu/DropdownMenuItem.vue';
import DropdownMenuTrigger from '../ui/dropdown-menu/DropdownMenuTrigger.vue';

const page = usePage();

const notifications = ref([...(page.props.auth?.notifications || [])]);

const removeNotification = (id: number) => {
    notifications.value = notifications.value.filter((n) => n.id !== id);
    toast.success('you marked this notification as read');
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button
                size="icon"
                variant="outline"
                class="relative !z-50 h-8 w-8 rounded-full !text-primary"
            >
                <Bell
                    class="h-4 w-4 scale-100 rotate-0 transition-all duration-300"
                />
                <span
                    v-if="notifications.length"
                    class="absolute -top-1 -right-1 rounded-full bg-red-600 px-1.5 text-xs font-black text-white"
                >
                    {{ notifications.length }}
                </span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent class="space-y-3 p-2" align="end">
            <DropdownMenuItem v-if="notifications.length === 0">
                No new notifications
            </DropdownMenuItem>

            <template v-else>
                <DropdownMenuItem
                    class="cursor-pointer border"
                    v-for="notif in notifications"
                    :key="notif.id"
                >
                    <Form
                        v-bind="QuizController.read.form({ id: notif.id })"
                        @success="removeNotification(notif.id)"
                        @error="
                            () =>
                                toast.error(
                                    'Failed to read notification. Please try again.',
                                )
                        "
                    >
                        <Button type="submit" variant="ghost">
                            <div
                                class="text-sm text-gray-800 dark:text-gray-100"
                            >
                                {{
                                    notif.data.quiz_id
                                        ? `Quiz ${notif.data.quiz_id} received a new answer (#${notif.data.answer_id})`
                                        : 'New notification'
                                }}
                            </div>

                            <div class="mt-1 text-xs text-gray-500">
                                {{ moment(notif.created_at).fromNow() }}
                            </div>
                        </Button>
                    </Form>
                </DropdownMenuItem>
            </template>
        </DropdownMenuContent>
    </DropdownMenu>
</template>

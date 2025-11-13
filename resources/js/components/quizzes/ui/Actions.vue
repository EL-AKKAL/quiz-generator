<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import Button from '@/components/ui/button/Button.vue';
import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogClose from '@/components/ui/dialog/DialogClose.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import DialogTrigger from '@/components/ui/dialog/DialogTrigger.vue';
import Tooltip from '@/components/ui/tooltip/Tooltip.vue';
import TooltipContent from '@/components/ui/tooltip/TooltipContent.vue';
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import TooltipTrigger from '@/components/ui/tooltip/TooltipTrigger.vue';
import { Form, Link } from '@inertiajs/vue3';
import {
    Archive,
    BadgeCheck,
    FilePenLine,
    SquareArrowOutUpRight,
    Trash,
} from 'lucide-vue-next';

defineProps<{ id: number; slug: string; status: boolean }>();
</script>
<template>
    <div class="absolute top-2 right-2 flex space-x-2">
        <Button
            as="a"
            class="size-8 rounded-full text-primary"
            variant="outline"
            target="_blank"
            :href="QuizController.view.url({ slug })"
        >
            <SquareArrowOutUpRight class="size-4 scale-100" />
        </Button>
        <Button
            :as="Link"
            size="sm"
            variant="outline"
            class="size-8 rounded-full text-primary"
            :href="QuizController.edit.url({ quiz: id })"
        >
            <FilePenLine class="size-4 scale-100" />
        </Button>

        <Dialog>
            <DialogTrigger as-child>
                <Button
                    variant="outline"
                    class="size-8 rounded-full text-destructive"
                    data-test="delete-quiz-button"
                >
                    <Trash class="size-4 scale-100" />
                </Button>
            </DialogTrigger>
            <DialogContent>
                <Form
                    v-bind="QuizController.destroy.form(id)"
                    reset-on-success
                    :options="{
                        preserveScroll: true,
                    }"
                    class="space-y-6"
                    v-slot="{ processing, reset, clearErrors }"
                >
                    <DialogHeader class="space-y-3">
                        <DialogTitle>
                            Are you sure you want to delete this quiz?
                        </DialogTitle>
                        <DialogDescription class="text-xs">
                            Once your quiz is deleted, all of its resources and
                            data will also be permanently deleted. Please
                            confirm you would like to permanently delete your
                            quiz.
                        </DialogDescription>
                    </DialogHeader>

                    <DialogFooter class="grid grid-cols-2 gap-2">
                        <DialogClose as-child>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="
                                    () => {
                                        clearErrors();
                                        reset();
                                    }
                                "
                            >
                                Cancel
                            </Button>
                        </DialogClose>

                        <Button
                            type="submit"
                            size="sm"
                            variant="destructive"
                            :disabled="processing"
                            data-test="confirm-delete-quiz-button"
                        >
                            Delete quiz
                        </Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <Button
                        class="size-8 rounded-full text-primary"
                        variant="outline"
                    >
                        <Archive
                            class="size-4 scale-100 !text-destructive"
                            v-if="!status"
                        />
                        <BadgeCheck
                            class="size-4 scale-100 text-green-500"
                            v-else
                        />
                    </Button>
                </TooltipTrigger>
                <TooltipContent>
                    <p>Status : {{ status ? 'Active' : 'Draft' }}</p>
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
    </div>
</template>

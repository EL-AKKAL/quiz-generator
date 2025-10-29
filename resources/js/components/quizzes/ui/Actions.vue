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
import { Form, Link } from '@inertiajs/vue3';
import { FilePenLine, Trash } from 'lucide-vue-next';

defineProps<{ id: number }>();
</script>
<template>
    <div class="absolute top-2 right-2 flex space-x-2">
        <Button
            :as="Link"
            size="sm"
            variant="ghost"
            class="rounded-full bg-white p-2 shadow-md transition-colors duration-200 hover:bg-gray-100"
            :href="QuizController.edit.url({ quiz: id })"
        >
            <FilePenLine class="h-4 w-4" />
        </Button>
        <Dialog>
            <DialogTrigger as-child>
                <Button
                    variant="destructive"
                    class="rounded-full bg-white p-2 shadow-md transition-colors duration-200 hover:bg-gray-100"
                    data-test="delete-quiz-button"
                >
                    <Trash class="h-4 w-4" />
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
                        <DialogDescription>
                            Once your quiz is deleted, all of its resources and
                            data will also be permanently deleted. Please
                            confirm you would like to permanently delete your
                            quiz.
                        </DialogDescription>
                    </DialogHeader>

                    <DialogFooter class="gap-2">
                        <DialogClose as-child>
                            <Button
                                variant="secondary"
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
    </div>
</template>

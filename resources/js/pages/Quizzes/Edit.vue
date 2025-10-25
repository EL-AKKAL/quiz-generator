<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import InputError from '@/components/InputError.vue';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import AvatarFallback from '@/components/ui/avatar/AvatarFallback.vue';
import AvatarImage from '@/components/ui/avatar/AvatarImage.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { Quiz, type BreadcrumbItem } from '@/types';
import { Form, Link, usePage } from '@inertiajs/vue3';
import { Loader2, Save, Undo2 } from 'lucide-vue-next';
const page = usePage();

const quiz: Quiz = page.props.quiz as Quiz;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: quiz.id ? `Edit ${quiz.title}` : 'New Quiz',
        href: quiz.id
            ? QuizController.edit.url({ quiz: quiz.id })
            : QuizController.create.url(),
    },
];

const formConfig = quiz.id
    ? QuizController.update.form({ quiz: quiz.id })
    : QuizController.store.form();
</script>
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-5">
            <Form
                :key="quiz.id"
                v-bind="formConfig"
                class="grid w-full max-w-3xl gap-4 p-5 pt-0 md:grid-cols-1 lg:grid-cols-2"
                v-slot="{ errors, processing }"
            >
                <div class="col-span-2 grid w-full grid-cols-2 items-center">
                    <Avatar class="h-20 w-20">
                        <AvatarImage
                            :src="
                                quiz.picture
                                    ? `/storage/${quiz.picture}`
                                    : 'https://github.com/unovue.png'
                            "
                            :alt="quiz.title"
                        />
                        <AvatarFallback>CN</AvatarFallback>
                    </Avatar>
                    <div>
                        <Label for="picture">Quiz Picture</Label>
                        <Input id="picture" name="picture" type="file" />
                    </div>
                </div>
                <div class="grid gap-2">
                    <Label for="title">Quiz title</Label>
                    <Input
                        id="title"
                        class="mt-1 block w-full"
                        name="title"
                        :default-value="quiz.title"
                        required
                        autocomplete="name"
                        placeholder="Enter quiz title"
                    />
                    <InputError class="mt-2" :message="errors.title" />
                </div>
                <div class="grid gap-2">
                    <Label for="slug">Slug</Label>
                    <Input
                        id="slug"
                        class="mt-1 block w-full"
                        name="slug"
                        :default-value="quiz.slug ?? ''"
                        required
                        autocomplete="slug"
                        placeholder="Enter quiz slug"
                    />
                    <InputError class="mt-2" :message="errors.slug" />
                </div>

                <div class="col-span-2 grid gap-2">
                    <Label for="description">Description</Label>
                    <Textarea
                        id="description"
                        class="mt-1 block w-full resize-none"
                        name="description"
                        :default-value="quiz.description"
                        autocomplete="description"
                        placeholder="Enter quiz description"
                        rows="6"
                    />

                    <InputError class="mt-2" :message="errors.description" />
                </div>

                <div class="col-span-2 grid gap-2">
                    <Label for="expire_date">Expiration Date</Label>
                    <Input
                        id="expire_date"
                        class="mt-1 block w-full"
                        name="expire_date"
                        type="date"
                        :default-value="
                            quiz.expire_date
                                ? quiz.expire_date.split(' ')[0]
                                : ''
                        "
                        autocomplete="expire_date"
                        placeholder="Enter expiration date"
                    />
                    <InputError class="mt-2" :message="errors.expire_date" />
                </div>

                <div class="flex items-end justify-start gap-2">
                    <Switch
                        :value="quiz.status ? 'on' : 'off'"
                        id="status"
                        name="status"
                        :model-value="quiz.status"
                    />
                    <Label for="status">Active Status</Label>

                    <InputError class="mt-2" :message="errors.status" />
                </div>
                <div class="mt-5 flex w-full items-center justify-end gap-4">
                    <Button type="submit" :disabled="processing" size="sm">
                        <Loader2
                            v-if="processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        <Save v-else class="h-4 w-4" /> Save Changes
                    </Button>
                    <!-- <Button
                        :disabled="processing"
                        variant="secondary"
                        size="sm"
                        type="button"
                        @click="resetAndClearErrors()"
                    >
                        <RotateCcw class="h-4 w-4" /> Reset Form
                    </Button> -->
                    <Button
                        variant="secondary"
                        size="sm"
                        :as="Link"
                        :href="QuizController.index.url()"
                    >
                        <Undo2 class="h-4 w-4" />
                        Cancel
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>

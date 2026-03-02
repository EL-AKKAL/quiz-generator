<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import Empty from '@/components/Empty.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import QuestionEditor from '@/components/questions/QuestionEditor.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { formatDate } from '@/lib/utils';
import { IQuiz } from '@/types';
import { Form, Link, usePage } from '@inertiajs/vue3';
import { Loader2, Plus, Save, Undo2 } from 'lucide-vue-next';
import { reactive } from 'vue';
import { toast } from 'vue-sonner';
import ReusableAvatar from '../shared/ReusableAvatar.vue';

const page = usePage();
const quiz: IQuiz = reactive({ ...(page.props.quiz as IQuiz) });

const formConfig = quiz.id
    ? QuizController.update.form({ quiz: quiz.id })
    : QuizController.store.form();

const addQuestion = (index?: number) => {
    const newQuestion = {
        quiz_id: quiz.id,
        id: Date.now(),
        question: '',
        type: 'text',
        data: {
            options: [],
        },
        description: '',
        created_at: '',
        updated_at: '',
    };
    if (!quiz.questions) quiz.questions = [];
    if (index !== undefined) quiz.questions.splice(index + 1, 0, newQuestion);
    else quiz.questions.push(newQuestion);
};

const deleteQuestion = (index: number) => quiz.questions?.splice(index, 1);

const updateQuestion = (index: number, updatedQuestion: any) =>
    Object.assign(quiz.questions[index], updatedQuestion);
</script>
<template>
    <Form
        :key="quiz.id"
        v-bind="formConfig"
        class="grid w-full max-w-3xl gap-5 !py-5 md:grid-cols-1 lg:grid-cols-2"
        v-slot="{ errors, processing }"
        @success="() => toast.success('Quiz saved successfully! 🎉')"
        @error="() => toast.error('Failed to save quiz. Please try again.')"
    >
        <div
            class="col-span-2 flex w-full flex-col items-center gap-3 md:flex-row"
        >
            <div class="w-full flex-1 self-center md:self-start">
                <ReusableAvatar name="picture" v-model="quiz.picture" />
            </div>
        </div>

        <div class="col-span-2 grid grid-cols-1 gap-5 md:grid-cols-2">
            <div class="grid gap-2">
                <Label for="title">Quiz title</Label>
                <Input id="title" class="mt-1 block w-full" name="title" :default-value="quiz.title" required
                    placeholder="Enter quiz title" />
                <InputError class="mt-2" :message="errors.title" />
            </div>
            <div class="grid gap-2">
                <Label for="expire_date">Expiration Date</Label>
                <Input id="expire_date" class="mt-1 block w-full" name="expire_date" type="date"
                    :default-value="formatDate(quiz.expire_date)" placeholder="Enter expiration date" />
                <InputError class="mt-2" :message="errors.expire_date" />
            </div>
        </div>
        <div class="col-span-2 grid gap-2">
            <Label for="description">Description</Label>
            <Textarea id="description" class="mt-1 block w-full resize-none" name="description"
                :default-value="quiz.description" placeholder="Enter quiz description" rows="6" />

            <InputError class="mt-2" :message="errors.description" />
        </div>

        <div class="col-span-2 flex items-end justify-start gap-2">
            <Switch
                :model-value="Boolean(quiz.status)"
                id="status"
                name="status"
                @update:model-value="
                    (v) => {
                        quiz.status = v;
                    }
                "
            />
            <input type="hidden" name="status" :value="quiz.status" />
            <Label for="status">Active Status</Label>
            <InputError class="mt-2" :message="errors.status" />
        </div>

        <div class="col-span-2 my-5 flex items-center justify-between gap-2">
            <Heading class="!mb-0" title="Questions" />
            <Button type="button" size="sm" @click="addQuestion()">
                <Plus class="h-4 w-4" />
                Add Question
            </Button>
        </div>
        <Empty v-if="!quiz.questions?.length" unit="questions" />
        <div v-else class="col-span-2 space-y-4">
            <div
                v-for="(question, index) in quiz.questions"
                :key="question.id"
                class="rounded-md border p-4"
            >
                <QuestionEditor
                    :question="question"
                    :index="index"
                    :update-question="
                        (updatedQuestion) =>
                            updateQuestion(index, updatedQuestion)
                    "
                    :add-question="addQuestion"
                    :delete-question="() => deleteQuestion(index)"
                />
            </div>
            <input
                type="hidden"
                name="questions"
                :value="JSON.stringify(quiz.questions)"
            />
        </div>
        <div class="col-span-2 mt-5 flex w-full items-center justify-end gap-4">
            <Button type="submit" :disabled="processing" size="sm">
                <Loader2 v-if="processing" class="mr-2 h-4 w-4 animate-spin" />
                <Save v-else class="h-4 w-4" /> Save Changes
            </Button>
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
</template>

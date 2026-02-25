<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import Empty from '@/components/Empty.vue';
import Heading from '@/components/Heading.vue';
import Button from '@/components/ui/button/Button.vue';
import { IQuiz } from '@/types';
import { Form } from '@inertiajs/vue3';
import { Loader2, RotateCcw, Save } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import Input from '../ui/input/Input.vue';
import Select from '../ui/select/Select.vue';
import SelectContent from '../ui/select/SelectContent.vue';
import SelectItem from '../ui/select/SelectItem.vue';
import SelectTrigger from '../ui/select/SelectTrigger.vue';
import SelectValue from '../ui/select/SelectValue.vue';

defineProps<{ quiz: IQuiz }>();

const finished = ref(false);

const SuccessfulSubmission = () => {
    toast.success('Your answers submitted successfully! 🎉');
    finished.value = true;
};
</script>

<template>
    <Form v-if="!finished" :key="quiz.id" v-bind="QuizController.save_answers.form({ quiz: quiz.slug })"
        v-slot="{ processing, reset }" @success="SuccessfulSubmission" @error="
            () => toast.error('Failed to submit answers. Please try again.')
        ">
        <Heading class="my-5" title="Questions" />
        <Empty v-if="!quiz.questions?.length" unit="questions" />
        <div v-else class="space-y-4">
            <div v-for="(question) in quiz.questions" :key="question.id" class="rounded-md border p-4">
                <h3 class="mb-2 text-lg font-semibold">
                    {{ question.question }} :
                </h3>
                <div class="space-y-2">
                    <div v-if="question.type === 'radio'">
                        <div v-for="option in question.data.options" :key="option.id" class="flex items-center gap-2">
                            <input type="radio" :name="`${question.id}`" :value="option.text" class="h-4 w-4" />
                            <label :for="`question_${question.id}`">{{
                                option.text
                            }}</label>
                        </div>
                    </div>
                    <div v-else-if="question.type === 'select'">
                        <Select :name="`${question.id}`" id="question-type-{{ question.id ?? index }}">
                            <SelectTrigger class="!w-full">
                                <SelectValue placeholder="Select a response" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="option in question.data.options" :key="option.id"
                                    :value="option.text">
                                    {{ option.text }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div v-else-if="question.type === 'checkbox'">
                        <div v-for="option in question.data.options" :key="option.id" class="flex items-center gap-2">
                            <input type="checkbox" :id="`question_${question.id}_${option.id}`"
                                :name="`${question.id}[]`" :value="option.text" class="h-4 w-4" />
                            <label :for="`question_${question.id}_${option.id}`">
                                {{ option.text }}
                            </label>
                        </div>
                    </div>
                    <div v-else-if="question.type === 'text'">
                        <Input type="text" :name="`${question.id}`" />
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5 flex w-full items-center justify-end gap-4">
            <Button type="submit" :disabled="processing" size="sm">
                <Loader2 v-if="processing" class="mr-2 h-4 w-4 animate-spin" />
                <Save v-else class="h-4 w-4" /> Submit answers
            </Button>
            <Button variant="secondary" size="sm" type="reset" @click="reset()">
                <RotateCcw class="h-4 w-4" />
                Reset
            </Button>
        </div>
    </Form>
    <div v-else class="flex flex-col items-center justify-center py-10">
        <Heading title="Thank you for completing the quiz!" />
        <p class="mt-4 text-center text-gray-600">
            Your responses have been recorded successfully.
        </p>
    </div>
</template>

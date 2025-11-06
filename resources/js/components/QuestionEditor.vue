<script setup lang="ts">
import Empty from '@/components/Empty.vue';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Question } from '@/types';
import { Plus, Trash } from 'lucide-vue-next';
import Button from './ui/button/Button.vue';
import Input from './ui/input/Input.vue';
import Label from './ui/label/Label.vue';
import { Separator } from './ui/separator';
import Textarea from './ui/textarea/Textarea.vue';

const props = defineProps<{
    question: Question;
    index: number;
    updateQuestion: (question: Question) => void;
    addQuestion: (index: number) => void;
    deleteQuestion: (index: number) => void;
}>();

const shouldHaveOptions = () =>
    props.question.type === 'select' || props.question.type === 'radio';

const addOption = () => {
    if (!props.question.data) props.question.data = { options: [] };
    if (!props.question.data.options) props.question.data.options = [];
    props.question.data.options.push({
        id: Date.now(),
        text: '',
    });
};

const deleteOption = (optIndex: number) =>
    props.question.data?.options?.splice(optIndex, 1);
</script>
<template>
    <div>
        <div class="flex w-full items-center justify-between">
            <h3 class="font-bold">Question {{ question.question ?? index }}</h3>
            <div class="space-x-2">
                <Button
                    @click="deleteQuestion(index)"
                    size="sm"
                    variant="outline"
                    type="button"
                >
                    <Trash class="h-4 w-4" />
                </Button>
                <Button
                    @click="addQuestion(index)"
                    size="sm"
                    variant="outline"
                    type="button"
                >
                    <Plus class="h-4 w-4" />
                </Button>
            </div>
        </div>
        <Separator orientation="vertical" class="mx-auto my-6 !h-px !w-full" />

        <div class="w-full space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="grid gap-1.5">
                    <Label for="question-{{ question.id ?? index }}">
                        Question text
                    </Label>

                    <Input
                        id="question-{{ question.id ?? index }}"
                        type="text"
                        v-model="question.question"
                        @input="updateQuestion(question)"
                    />
                </div>
                <div class="grid gap-1.5">
                    <Label for="question-type-{{ question.id ?? index }}">
                        Question type
                    </Label>
                    <Select
                        id="question-type-{{ question.id ?? index }}"
                        v-model="question.type"
                        @update:model-value="updateQuestion(question)"
                    >
                        <SelectTrigger class="!w-full">
                            <SelectValue placeholder="Select a Question Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Types</SelectLabel>
                                <SelectItem value="text">
                                    Short answer
                                </SelectItem>
                                <SelectItem value="select">
                                    Multiple choice
                                </SelectItem>
                                <SelectItem value="radio">
                                    One choice
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 grid gap-1.5">
                    <Label
                        for="question-description-{{ question.id ?? index }}"
                    >
                        Description (optional)
                    </Label>
                    <Textarea
                        class="mt-1 block w-full resize-none"
                        name="description"
                        :default-value="question.description ?? ''"
                        autocomplete="description"
                        placeholder="Enter quiz description"
                        id="question-description-{{ question.id ?? index }}"
                        rows="4"
                        @input="updateQuestion(question)"
                    />
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <div v-if="shouldHaveOptions()">
                    <div class="flex w-full justify-between">
                        <Label class="font-semibold">Options</Label>
                        <Button
                            size="sm"
                            variant="outline"
                            type="button"
                            @click="addOption()"
                        >
                            <Plus class="h-4 w-4" />
                        </Button>
                    </div>
                    <Empty
                        v-if="!question.data?.options?.length"
                        unit="options"
                    />
                    <div v-else class="space-y-4">
                        <div
                            v-for="(option, dex) in question.data.options"
                            :key="index"
                            class="space-y-4"
                        >
                            <span>Option {{ dex + 1 }}.</span>
                            <div
                                class="flex w-full items-center space-y-0 space-x-2"
                            >
                                <Input
                                    type="text"
                                    v-model="option.text"
                                    class="w-full"
                                />
                                <Button
                                    size="sm"
                                    variant="outline"
                                    type="button"
                                    @click="deleteOption(dex)"
                                >
                                    <Trash class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

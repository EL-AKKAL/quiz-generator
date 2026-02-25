<script setup lang="ts">
import Empty from '@/components/Empty.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { Question } from '@/types';
import { Plus, Trash } from 'lucide-vue-next';
import { computed, toRaw } from 'vue';

const props = defineProps<{
    question: Question;
    index: number;
    updateQuestion: (question: Question) => void;
    addQuestion: (index: number) => void;
    deleteQuestion: (index: number) => void;
}>();

const questionModel = computed({
    get: () => props.question,
    set: (val: Question) => props.updateQuestion(val),
});

const descriptionModel = computed({
    get: () => questionModel.value.description ?? '',
    set: (val: string) => {
        const updated = structuredClone(toRaw(questionModel.value));
        updated.description = val;
        questionModel.value = updated;
    },
});

const shouldHaveOptions = () =>
    ['select', 'radio', 'checkbox', 'multiple'].includes(
        questionModel.value.type,
    );

const addOption = () => {
    const updated = structuredClone(toRaw(questionModel.value));

    if (!updated.data) updated.data = { options: [] };
    if (!updated.data.options) updated.data.options = [];

    updated.data.options.push({
        id: Date.now(),
        text: '',
    });

    questionModel.value = updated;
};

const deleteOption = (optIndex: number) => {
    const updated = structuredClone(toRaw(questionModel.value));
    updated.data?.options?.splice(optIndex, 1);
    questionModel.value = updated;
};
</script>
<template>
    <div>
        <div class="flex w-full items-center justify-between">
            <h3 class="font-bold">
                Question {{ questionModel.question ?? index }}
            </h3>
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
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="grid gap-1.5">
                    <Label :for="`question-${questionModel.id ?? index}`">
                        Question text
                    </Label>

                    <Input
                        :id="`question-${questionModel.id ?? index}`"
                        type="text"
                        v-model="questionModel.question"
                    />
                </div>
                <div class="grid gap-1.5">
                    <Label :for="`question-type-${questionModel.id ?? index}`">
                        Question type
                    </Label>
                    <Select
                        :id="`question-type-${questionModel.id ?? index}`"
                        v-model="questionModel.type"
                    >
                        <SelectTrigger class="!w-full">
                            <SelectValue placeholder="Select a Question Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Types</SelectLabel>
                                <SelectItem
                                    v-for="optionType in $page.props
                                        .questionTypes"
                                    :value="optionType"
                                    :key="optionType"
                                >
                                    {{ optionType }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 grid gap-1.5">
                    <Label
                        :for="`question-description-${questionModel.id ?? index}`"
                    >
                        Description (optional)
                    </Label>
                    <Textarea
                        class="mt-1 block w-full resize-none"
                        name="description"
                        v-model="descriptionModel"
                        autocomplete="description"
                        placeholder="Enter quiz description"
                        :id="`question-description-${questionModel.id ?? index}`"
                        rows="4"
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
                        v-if="!questionModel.data?.options?.length"
                        unit="options"
                    />
                    <div v-else class="space-y-4">
                        <div
                            v-for="(option, dex) in questionModel.data?.options"
                            :key="option.id"
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

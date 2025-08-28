<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { AdaptiveRule, BreadcrumbItem, MatPel, MateriPelajaran } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    rule: AdaptiveRule;
    matpels: MatPel[];
    materis: MateriPelajaran[];
}>();

type Form = {
    matpel_id: number | null;
    materi_id: number | null;
    operator: string;
    nilai_batas: number;
};

const form = useForm<Form>({
    matpel_id: props.rule.matpel_id,
    materi_id: props.rule.materi_id,
    operator: props.rule.operator,
    nilai_batas: props.rule.nilai_batas,
});

const selectedMatpelLabel = ref(props.rule.mataPelajaran?.nama_mapel || 'Pilih Mapel');
const selectedMateriLabel = ref(props.rule.materi?.judul_materi || 'Pilih Materi');
const selectedOperatorLabel = ref(props.rule.operator || '=');

const operators = ['<', '<=', '=', '>=', '>'];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Adaptive Rules', href: route('guru.adaptive-rules.index') },
    { title: 'Edit Rule', href: route('guru.adaptive-rules.edit', props.rule.id) },
];

const submit = () => {
    form.put(route('guru.adaptive-rules.update', props.rule.id));
};
</script>

<template>
    <Head title="Edit Adaptive Rule" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Adaptive Rule</h1>

            <form class="grid max-w-full grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Mapel -->
                <div class="flex flex-col gap-2">
                    <Label>Mapel</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button type="button" class="flex w-full justify-between rounded border px-4 py-2 text-sm">
                                <span>{{ selectedMatpelLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="m in matpels"
                                :key="m.id"
                                @click="
                                    () => {
                                        form.matpel_id = m.id;
                                        selectedMatpelLabel = m.nama_mapel;
                                    }
                                "
                            >
                                {{ m.nama_mapel }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.matpel_id" />
                </div>

                <!-- Materi -->
                <div class="flex flex-col gap-2">
                    <Label>Materi</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button type="button" class="flex w-full justify-between rounded border px-4 py-2 text-sm">
                                <span>{{ selectedMateriLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="mat in materis"
                                :key="mat.id"
                                @click="
                                    () => {
                                        form.materi_id = mat.id;
                                        selectedMateriLabel = mat.judul_materi;
                                    }
                                "
                            >
                                {{ mat.judul_materi }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.materi_id" />
                </div>

                <!-- Operator -->
                <div class="flex flex-col gap-2">
                    <Label>Operator</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button type="button" class="flex w-full justify-between rounded border px-4 py-2 text-sm">
                                <span>{{ selectedOperatorLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="op in operators"
                                :key="op"
                                @click="
                                    () => {
                                        form.operator = op;
                                        selectedOperatorLabel = op;
                                    }
                                "
                            >
                                {{ op }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.operator" />
                </div>

                <!-- Nilai Batas -->
                <div class="flex flex-col gap-3">
                    <Label>Nilai Batas</Label>
                    <Input v-model="form.nilai_batas" type="number" placeholder="Contoh: 75" />
                    <InputError :message="form.errors.nilai_batas" />
                </div>

                <!-- Submit -->
                <div class="mt-4 flex justify-end gap-2 md:col-span-2">
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-700">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan Perubahan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

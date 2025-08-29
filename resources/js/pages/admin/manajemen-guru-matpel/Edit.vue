<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    guruMapel: { id: number; guru_id: number; matpel_id: number };
    guruList: { id: number; name: string }[];
    matpelList: { id: number; nama_mapel: string }[];
}>();

console.log(props.guruMapel);

type Form = {
    guru_id: number | null;
    matpel_id: number | null;
};

const form = useForm<Form>({
    guru_id: props.guruMapel.guru_id,
    matpel_id: props.guruMapel.matpel_id,
});

const selectedGuruLabel = ref(props.guruList.find((g) => g.id === props.guruMapel.guru_id)?.name || 'Pilih Guru');
const selectedMatpelLabel = ref(props.matpelList.find((m) => m.id === props.guruMapel.matpel_id)?.nama_mapel || 'Pilih Mata Pelajaran');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Manajemen Guru Mata Pelajaran', href: route('admin.manajemen-guru-mapel.index') },
    { title: 'Edit Data', href: route('admin.manajemen-guru-mapel.edit', props.guruMapel.id) },
];

const submit = () => {
    form.put(route('admin.manajemen-guru-mapel.update', props.guruMapel.id));
};
</script>

<template>
    <Head title="Edit Data" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Data</h1>

            <form class="grid max-w-full grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Guru -->
                <div class="flex flex-col gap-3">
                    <div class="flex flex-col gap-2">
                        <Label>Guru</Label>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <button type="button" class="flex w-full justify-between rounded border px-4 py-2">
                                    <span class="text-sm">{{ selectedGuruLabel }}</span>
                                    <ChevronDown class="h-4 w-4 text-gray-500" />
                                </button>
                            </DropdownMenuTrigger>

                            <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                                <DropdownMenuItem
                                    v-for="guru in guruList"
                                    :key="guru.id"
                                    @click="
                                        form.guru_id = guru.id;
                                        selectedGuruLabel = guru.name;
                                    "
                                >
                                    {{ guru.name }}
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                        <InputError :message="form.errors.guru_id" />
                    </div>
                </div>

                <!-- Guru -->
                <div class="flex flex-col gap-3">
                    <div class="flex flex-col gap-2">
                        <Label>Mata Pelajaran</Label>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <button type="button" class="flex w-full justify-between rounded border px-4 py-2">
                                    <span class="text-sm">{{ selectedMatpelLabel }}</span>
                                    <ChevronDown class="h-4 w-4 text-gray-500" />
                                </button>
                            </DropdownMenuTrigger>

                            <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                                <DropdownMenuItem
                                    v-for="matpel in matpelList"
                                    :key="matpel.id"
                                    @click="
                                        form.matpel_id = matpel.id;
                                        selectedMatpelLabel = matpel.nama_mapel;
                                    "
                                >
                                    {{ matpel.nama_mapel }}
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                        <InputError :message="form.errors.matpel_id" />
                    </div>
                </div>

                <!-- Submit -->
                <div class="mt-4 md:col-span-2">
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

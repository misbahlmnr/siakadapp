<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

type Semester = {
    id: number;
    semester: string;
    tahun_ajaran: string;
    status_aktif: boolean;
};

const props = defineProps<{
    dataSemester: Semester;
}>();

// isi form dengan data lama
const form = useForm({
    semester: props.dataSemester.semester,
    tahun_ajaran: props.dataSemester.tahun_ajaran,
    status_aktif: props.dataSemester.status_aktif,
});

const semesterList = ['Ganjil', 'Genap'];
const selectedSemesterLabel = ref(props.dataSemester.semester === 'ganjil' ? 'Ganjil' : 'Genap');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Semester', href: route('admin.semester.index') },
    { title: 'Edit Semester', href: route('admin.semester.edit', props.dataSemester.id) },
];

const submit = () => {
    form.put(route('admin.semester.update', props.dataSemester.id));
};
</script>

<template>
    <Head title="Edit Semester" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Semester</h1>

            <form class="grid max-w-full grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Semester -->
                <div class="flex flex-col gap-3">
                    <Label for="semester">Semester</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button type="button" class="flex w-full justify-between rounded border px-4 py-2">
                                <span class="text-sm">{{ selectedSemesterLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="s in semesterList"
                                :key="s"
                                @click="
                                    form.semester = s.toLowerCase();
                                    selectedSemesterLabel = s;
                                "
                            >
                                {{ s }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.semester" />
                </div>

                <!-- Tahun Ajaran -->
                <div class="flex flex-col gap-3">
                    <Label for="tahun_ajaran">Tahun Ajaran</Label>
                    <Input id="tahun_ajaran" v-model="form.tahun_ajaran" placeholder="Contoh: 2025/2026" />
                    <InputError :message="form.errors.tahun_ajaran" />
                </div>

                <!-- Status Aktif -->
                <div class="flex flex-col gap-3">
                    <Label for="status_aktif">Status Aktif</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button type="button" class="flex w-full justify-between rounded border px-4 py-2">
                                <span class="text-sm">{{ form.status_aktif ? 'Aktif' : 'Nonaktif' }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem @click="form.status_aktif = true">Aktif</DropdownMenuItem>
                            <DropdownMenuItem @click="form.status_aktif = false">Nonaktif</DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.status_aktif" />
                </div>

                <!-- Submit -->
                <div class="mt-4 md:col-span-2">
                    <Button type="button" variant="outline" class="ml-2" @click="router.visit(route('admin.semester.index'))"> Batal </Button>
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

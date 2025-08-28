<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    rekomendasi: {
        id: number;
        siswa_id: number;
        evaluasi_id: number;
        materi_id: number;
        alasan_rekomendasi: string;
        status: 'belum_dibaca' | 'dibaca' | 'selesai';
        siswa?: { name: string };
        evaluasi?: { judul: string };
        materi?: { judul_materi: string };
    };
    siswaOptions: Array<{ id: number; name: string }>;
    evaluasiOptions: Array<{ id: number; judul: string }>;
    materiOptions: Array<{ id: number; judul_materi: string }>;
}>();

type Form = {
    siswa_id: number | null;
    evaluasi_id: number | null;
    materi_id: number | null;
    alasan_rekomendasi: string;
    status: 'belum_dibaca' | 'dibaca' | 'selesai';
};

const form = useForm<Form>({
    siswa_id: null,
    evaluasi_id: null,
    materi_id: null,
    alasan_rekomendasi: '',
    status: 'belum_dibaca',
});

// Dropdown label refs
const selectedSiswaLabel = ref('Pilih Siswa');
const selectedEvaluasiLabel = ref('Pilih Evaluasi');
const selectedMateriLabel = ref('Pilih Materi');

// Status options
const statusOptions = [
    {
        label: 'Belum Dibaca',
        value: 'belum_dibaca',
    },
    {
        label: 'Dibaca',
        value: 'dibaca',
    },
    {
        label: 'Selesai',
        value: 'selesai',
    },
];
const selectedStatusLabel = ref('Belum Dibaca');

// Load existing data
onMounted(() => {
    form.siswa_id = props.rekomendasi.siswa_id;
    form.evaluasi_id = props.rekomendasi.evaluasi_id;
    form.materi_id = props.rekomendasi.materi_id;
    form.alasan_rekomendasi = props.rekomendasi.alasan_rekomendasi;
    form.status = props.rekomendasi.status;

    // Set dropdown labels
    const selectedSiswa = props.siswaOptions.find((s) => s.id === props.rekomendasi.siswa_id);
    const selectedEvaluasi = props.evaluasiOptions.find((e) => e.id === props.rekomendasi.evaluasi_id);
    const selectedMateri = props.materiOptions.find((m) => m.id === props.rekomendasi.materi_id);
    const selectedStatus = statusOptions.find((s) => s.value === props.rekomendasi.status);

    selectedSiswaLabel.value = selectedSiswa?.name || 'Pilih Siswa';
    selectedEvaluasiLabel.value = selectedEvaluasi?.judul || 'Pilih Evaluasi';
    selectedMateriLabel.value = selectedMateri?.judul_materi || 'Pilih Materi';
    selectedStatusLabel.value = selectedStatus?.label || 'Belum Dibaca';
});

const submit = () => {
    form.put(route('guru.rekomendasi-materi.update', props.rekomendasi.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Rekomendasi Materi" />
    <AppLayout
        :breadcrumbs="[
            { title: 'Dashboard', href: route('guru.dashboard') },
            { title: 'Rekomendasi Materi', href: route('guru.rekomendasi-materi.index') },
            { title: 'Edit Rekomendasi', href: route('guru.rekomendasi-materi.edit', props.rekomendasi.id) },
        ]"
    >
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Rekomendasi Materi</h1>

            <form class="grid w-full max-w-full grid-cols-1 gap-4 md:max-w-lg md:grid-cols-2" @submit.prevent="submit">
                <!-- Siswa -->
                <div class="flex flex-col gap-3">
                    <Label for="siswa_id">Siswa</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedSiswaLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="s in props.siswaOptions"
                                :key="s.id"
                                @click="
                                    form.siswa_id = s.id;
                                    selectedSiswaLabel = s.name || 'Siswa';
                                "
                            >
                                {{ s.name || 'Siswa' }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.siswa_id" />
                </div>

                <!-- Evaluasi -->
                <div class="flex flex-col gap-3">
                    <Label for="evaluasi_id">Evaluasi</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="truncate text-sm">{{ selectedEvaluasiLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="e in props.evaluasiOptions"
                                :key="e.id"
                                @click="
                                    form.evaluasi_id = e.id;
                                    selectedEvaluasiLabel = e.judul || 'Evaluasi';
                                "
                            >
                                <span class="block flex-1 truncate">{{ e.judul || 'Evaluasi' }}</span>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.evaluasi_id" />
                </div>

                <!-- Materi -->
                <div class="flex flex-col gap-3">
                    <Label for="materi_id">Materi</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="truncate text-sm">{{ selectedMateriLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="m in props.materiOptions"
                                :key="m.id"
                                @click="
                                    form.materi_id = m.id;
                                    selectedMateriLabel = m.judul_materi || 'Materi';
                                "
                            >
                                <span class="block flex-1 truncate">{{ m.judul_materi || 'Materi' }}</span>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.materi_id" />
                </div>

                <!-- Status -->
                <div class="flex flex-col gap-3">
                    <Label for="status">Status</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedStatusLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="s in statusOptions"
                                :key="s.value"
                                @click="
                                    form.status = s.value as 'belum_dibaca' | 'dibaca' | 'selesai';
                                    selectedStatusLabel = s.label;
                                "
                            >
                                {{ s.label }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.status" />
                </div>

                <!-- Alasan Rekomendasi -->
                <div class="col-span-2 flex flex-col gap-3">
                    <Label for="alasan_rekomendasi">Alasan Rekomendasi</Label>
                    <textarea
                        id="alasan_rekomendasi"
                        v-model="form.alasan_rekomendasi"
                        placeholder="Masukkan alasan rekomendasi materi"
                        class="w-full rounded border p-2 text-sm"
                        rows="4"
                    ></textarea>
                    <InputError :message="form.errors.alasan_rekomendasi" />
                </div>

                <!-- Submit -->
                <div class="mt-4 md:col-span-2">
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Update
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

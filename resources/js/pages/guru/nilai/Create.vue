<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    guru_id: number;
    siswaOptions: any[];
    evaluasiOptions: any[];
}>();

type Form = {
    siswa_id: number | null;
    evaluasi_id: number | null;
    nilai: number | string;
    status: string;
    feedback: string;
    tanggal_dinilai: string;
};

const form = useForm<Form>({
    siswa_id: null,
    evaluasi_id: null,
    nilai: '',
    status: '',
    feedback: '',
    tanggal_dinilai: '',
});

// Status options
const statusOptions = [
    {
        label: 'Lulus',
        value: 'lulus',
    },
    {
        label: 'Kosong',
        value: 'kosong',
    },
    {
        label: 'Remedial',
        value: 'remedial',
    },
];
const selectedStatusLabel = ref('Pilih Status');

// Dropdown label refs
const selectedSiswaLabel = ref('Pilih Siswa');
const selectedEvaluasiLabel = ref('Pilih Evaluasi');

console.log(props.siswaOptions);
console.log(props.evaluasiOptions);

const submit = () => {
    form.post(route('guru.nilai.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Tambah Nilai Siswa" />
    <AppLayout
        :breadcrumbs="[
            { title: 'Dashboard', href: route('guru.dashboard') },
            { title: 'Nilai Siswa', href: route('guru.nilai.index') },
            { title: 'Tambah Nilai', href: route('guru.nilai.create') },
        ]"
    >
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Tambah Nilai Siswa</h1>

            <form class="grid w-full max-w-full grid-cols-1 gap-4 md:max-w-lg md:grid-cols-2" @submit.prevent="submit">
                <!-- Siswa -->
                <div class="flex flex-col gap-3">
                    <Label for="siswa_id">Siswa</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="tex text-sm">{{ selectedSiswaLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="s in props.siswaOptions"
                                :key="s.id"
                                @click="
                                    form.siswa_id = s.id;
                                    selectedSiswaLabel = s.nama;
                                "
                            >
                                {{ s.nama }}
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
                                    selectedEvaluasiLabel = `${e.judul} (${e.jenis})`;
                                "
                            >
                                <span class="block flex-1 truncate"> {{ e.judul }} ({{ e.jenis }}) </span>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.evaluasi_id" />
                </div>

                <!-- Nilai -->
                <div class="flex flex-col gap-3">
                    <Label for="nilai">Nilai</Label>
                    <Input id="nilai" type="number" step="0.01" v-model="form.nilai" placeholder="Masukkan nilai" />
                    <InputError :message="form.errors.nilai" />
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
                                    form.status = s.value;
                                    selectedStatusLabel = s.label;
                                "
                            >
                                {{ s.label }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.status" />
                </div>

                <!-- Feedback -->
                <div class="col-span-2 flex flex-col gap-3">
                    <Label for="feedback">Feedback</Label>
                    <textarea
                        id="feedback"
                        v-model="form.feedback"
                        placeholder="Catatan guru (opsional)"
                        class="w-full rounded border p-2 text-sm"
                        rows="4"
                    ></textarea>
                    <InputError :message="form.errors.feedback" />
                </div>

                <!-- Tanggal Dinilai -->
                <div class="flex flex-col gap-3">
                    <Label for="tanggal_dinilai">Tanggal Dinilai</Label>
                    <Input id="tanggal_dinilai" type="date" v-model="form.tanggal_dinilai" />
                    <InputError :message="form.errors.tanggal_dinilai" />
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

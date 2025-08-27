<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Kelas, MatPel } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
    kelasOptions: Kelas[];
    mapelOptions: MatPel[];
    siswaOptions: { id: number; nama: string }[];
    jadwalOptions: any[];
    semesterDanTahunAjaranList: { id: number; semester: string; tahun_ajaran: string }[];
}>();

// form state
type Form = {
    jadwal_id: number | null;
    siswa_id: number | null;
    semester_ajaran_id: number | null;
    pertemuan_ke: number | string;
    tanggal: string;
    status: string;
};

const form = useForm<Form>({
    jadwal_id: null,
    siswa_id: null,
    semester_ajaran_id: null,
    pertemuan_ke: '',
    tanggal: '',
    status: 'hadir',
});

// dropdown label refs
const selectedKelasLabel = ref('Pilih Kelas');
const selectedMapelLabel = ref('Pilih Mata Pelajaran');
const selectedSiswaLabel = ref('Pilih Siswa');
const selectedStatusLabel = ref('Pilih Status');
const selectedSemesterDanTahunAjaranLabel = ref('Pilih Semester & Tahun Ajaran');
const selectedJadwalLabel = ref('Pilih Jadwal');

// State untuk filter
const selectedKelasId = ref<number | null>(null);
const selectedMapelId = ref<number | null>(null);

// Status options
const statusOptions = [
    { label: 'Hadir', value: 'hadir' },
    { label: 'Sakit', value: 'sakit' },
    { label: 'Ijin', value: 'ijin' },
    { label: 'Alfa', value: 'alfa' },
];

// Filter jadwal berdasarkan kelas dan mapel yang dipilih
const filteredJadwalOptions = computed(() => {
    return props.jadwalOptions.filter((jadwal) => {
        const matchesKelas = !selectedKelasId.value || jadwal.kelas_id === selectedKelasId.value;
        const matchesMapel = !selectedMapelId.value || jadwal.matpel_id === selectedMapelId.value;
        return matchesKelas && matchesMapel;
    });
});

// Reset jadwal_id ketika kelas atau mapel berubah
watch([selectedKelasId, selectedMapelId], () => {
    form.jadwal_id = null;
    selectedJadwalLabel.value = 'Pilih Jadwal';
});

const submit = () => {
    form.post(route('guru.absensi.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Tambah Absensi" />

    <AppLayout
        :breadcrumbs="[
            { title: 'Dashboard', href: route('guru.dashboard') },
            { title: 'Absensi Siswa', href: route('guru.absensi.index') },
            { title: 'Tambah Absensi', href: route('guru.absensi.create') },
        ]"
    >
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Tambah Absensi</h1>

            <form class="grid w-full max-w-full grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Kelas -->
                <div class="flex flex-col gap-3">
                    <Label for="kelas">Kelas</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="truncate text-sm">{{ selectedKelasLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="k in props.kelasOptions"
                                :key="k.id"
                                @click="
                                    selectedKelasId = k.id;
                                    selectedKelasLabel = k.nama_kelas;
                                "
                            >
                                {{ k.nama_kelas }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.jadwal_id" />
                </div>

                <!-- Mata Pelajaran -->
                <div class="flex flex-col gap-3">
                    <Label for="mapel">Mata Pelajaran</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="truncate text-sm">{{ selectedMapelLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="m in props.mapelOptions"
                                :key="m.id"
                                @click="
                                    selectedMapelId = m.id;
                                    selectedMapelLabel = m.nama_mapel;
                                "
                            >
                                {{ m.nama_mapel }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.jadwal_id" />
                </div>

                <!-- Jadwal (akan muncul setelah kelas dan mapel dipilih) -->
                <div v-if="selectedKelasId && selectedMapelId" class="flex flex-col gap-3">
                    <Label for="jadwal_id">Jadwal</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="truncate text-sm">{{ selectedJadwalLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="jadwal in filteredJadwalOptions"
                                :key="jadwal.id"
                                @click="
                                    form.jadwal_id = jadwal.id;
                                    selectedJadwalLabel = `${jadwal.kelas_nama} - ${jadwal.mapel_nama}`;
                                "
                            >
                                {{ jadwal.kelas_nama }} - {{ jadwal.mapel_nama }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.jadwal_id" />
                </div>

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
                                    selectedSiswaLabel = s.nama;
                                "
                            >
                                {{ s.nama }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.siswa_id" />
                </div>

                <!-- Pertemuan -->
                <div class="flex flex-col gap-3">
                    <Label for="pertemuan_ke">Pertemuan Ke</Label>
                    <Input id="pertemuan_ke" type="number" min="1" v-model="form.pertemuan_ke" placeholder="Masukkan pertemuan ke-" />
                    <InputError :message="form.errors.pertemuan_ke" />
                </div>

                <!-- Tanggal -->
                <div class="flex flex-col gap-3">
                    <Label for="tanggal">Tanggal</Label>
                    <Input id="tanggal" type="date" v-model="form.tanggal" />
                    <InputError :message="form.errors.tanggal" />
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

                <div class="flex flex-col gap-3">
                    <Label for="semester_ajaran">Semester & Tahun Ajaran</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedSemesterDanTahunAjaranLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="sa in props.semesterDanTahunAjaranList"
                                :key="sa.id"
                                @click="
                                    form.semester_ajaran_id = sa.id;
                                    selectedSemesterDanTahunAjaranLabel = `${sa.semester} - ${sa.tahun_ajaran}`;
                                "
                            >
                                {{ sa.semester }} - {{ sa.tahun_ajaran }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.semester_ajaran_id" />
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

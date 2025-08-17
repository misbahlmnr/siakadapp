<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, JadwalPelajaran } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    jadwal: JadwalPelajaran;
    kelasList: { id: number; nama_kelas: string }[];
    mapelList: { id: number; nama_mapel: string }[];
    guruList: { id: number; nama: string }[];
}>();

type Form = {
    kelas_id: number | null;
    matpel_id: number | null;
    guru_id: number | null;
    hari: string;
    jam_mulai: string;
    jam_selesai: string;
    semester: string;
    tahun_ajaran: string;
};

const form = useForm<Form>({
    kelas_id: props.jadwal.kelas_id,
    matpel_id: props.jadwal.matpel_id,
    guru_id: props.jadwal.guru_id,
    hari: props.jadwal.hari,
    jam_mulai: props.jadwal.jam_mulai,
    jam_selesai: props.jadwal.jam_selesai,
    semester: props.jadwal.semester,
    tahun_ajaran: props.jadwal.tahun_ajaran,
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Jadwal Pelajaran', href: route('admin.jadwal-pelajaran.index') },
    { title: 'Edit Jadwal', href: route('admin.jadwal-pelajaran.edit', props.jadwal.id) },
];

const hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
const semesterOptions = ['Ganjil', 'Genap'];

// inisialisasi label agar langsung sesuai data jadwal
const selectedKelasLabel = ref(props.kelasList.find((k) => k.id === props.jadwal.kelas_id)?.nama_kelas || 'Pilih Kelas');
const selectedMapelLabel = ref(props.mapelList.find((m) => m.id === props.jadwal.matpel_id)?.nama_mapel || 'Pilih Mata Pelajaran');
const selectedGuruLabel = ref(props.guruList.find((g) => g.id === props.jadwal.guru_id)?.nama || 'Pilih Guru');
const selectedHariLabel = ref(props.jadwal.hari || 'Pilih Hari');
const selectedSemesterLabel = ref(props.jadwal.semester || 'Pilih Semester');

console.log(props.jadwal);

const submit = () => {
    form.put(route('admin.jadwal-pelajaran.update', props.jadwal.id));
};
</script>

<template>
    <Head title="Edit Jadwal Pelajaran" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Jadwal Pelajaran</h1>

            <form class="grid max-w-full grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Kelas -->
                <div class="flex flex-col gap-3">
                    <Label for="kelas_id">Kelas</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedKelasLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="kelas in props.kelasList"
                                :key="kelas.id"
                                @click="
                                    form.kelas_id = kelas.id;
                                    selectedKelasLabel = kelas.nama_kelas;
                                "
                            >
                                {{ kelas.nama_kelas }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.kelas_id" />
                </div>

                <!-- Mata Pelajaran -->
                <div class="flex flex-col gap-3">
                    <Label for="mata_pelajaran_id">Mata Pelajaran</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedMapelLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="mapel in props.mapelList"
                                :key="mapel.id"
                                @click="
                                    form.matpel_id = mapel.id;
                                    selectedMapelLabel = mapel.nama_mapel;
                                "
                            >
                                {{ mapel.nama_mapel }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.matpel_id" />
                </div>

                <!-- Guru -->
                <div class="flex flex-col gap-3">
                    <Label for="guru_id">Guru</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedGuruLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="guru in props.guruList"
                                :key="guru.id"
                                @click="
                                    form.guru_id = guru.id;
                                    selectedGuruLabel = guru.nama;
                                "
                            >
                                {{ guru.nama }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.guru_id" />
                </div>

                <!-- Hari -->
                <div class="flex flex-col gap-3">
                    <Label for="hari">Hari</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedHariLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="h in hariOptions"
                                :key="h"
                                @click="
                                    form.hari = h;
                                    selectedHariLabel = h;
                                "
                            >
                                {{ h }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.hari" />
                </div>

                <!-- Jam Mulai -->
                <div class="flex flex-col gap-3">
                    <Label for="jam_mulai">Jam Mulai</Label>
                    <Input id="jam_mulai" type="time" v-model="form.jam_mulai" />
                    <InputError :message="form.errors.jam_mulai" />
                </div>

                <!-- Jam Selesai -->
                <div class="flex flex-col gap-3">
                    <Label for="jam_selesai">Jam Selesai</Label>
                    <Input id="jam_selesai" type="time" v-model="form.jam_selesai" />
                    <InputError :message="form.errors.jam_selesai" />
                </div>

                <!-- Semester -->
                <div class="flex flex-col gap-3">
                    <Label for="semester">Semester</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedSemesterLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-40 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="s in semesterOptions"
                                :key="s"
                                @click="
                                    form.semester = s;
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
                    <Input id="tahun_ajaran" type="text" placeholder="2024/2025" v-model="form.tahun_ajaran" />
                    <InputError :message="form.errors.tahun_ajaran" />
                </div>

                <!-- Submit -->
                <div class="mt-4 md:col-span-2">
                    <Button variant="outline" type="button" @click="router.visit(route('admin.jadwal-pelajaran.index'))">Batal</Button>
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

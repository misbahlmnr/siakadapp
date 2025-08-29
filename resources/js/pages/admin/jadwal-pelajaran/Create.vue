<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    kelasList: { id: number; nama_kelas: string }[];
    guruMapelList: { id: number; nama: string }[];
    semesterDanTahunAjaranList: { id: number; semester: string; tahun_ajaran: string }[];
}>();

type Form = {
    kelas_id: number | null;
    guru_matpel_id: number | null;
    hari: string;
    jam_mulai: string;
    jam_selesai: string;
    semester_ajaran_id: number | null;
};

const form = useForm<Form>({
    kelas_id: null,
    guru_matpel_id: null,
    hari: '',
    jam_mulai: '',
    jam_selesai: '',
    semester_ajaran_id: null,
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Jadwal Pelajaran', href: route('admin.jadwal-pelajaran.index') },
    { title: 'Tambah Jadwal', href: route('admin.jadwal-pelajaran.create') },
];

const hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

const selectedKelasLabel = ref('Pilih Kelas');
const selectedGuruMatpelLabel = ref('Pilih Guru & Mata Pelajaran');
const selectedHariLabel = ref('Pilih Hari');
const selectedSemesterDanTahunAjaranLabel = ref('Pilih Semester & Tahun Ajaran');

const submit = () => {
    form.post(route('admin.jadwal-pelajaran.store'));
};
</script>

<template>
    <Head title="Tambah Jadwal Pelajaran" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Tambah Jadwal Pelajaran</h1>

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
                    <Label for="mata_pelajaran_id">Guru Mata Pelajaran</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedGuruMatpelLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="mapel in props.guruMapelList"
                                :key="mapel.id"
                                @click="
                                    form.guru_matpel_id = mapel.id;
                                    selectedGuruMatpelLabel = mapel.nama;
                                "
                            >
                                {{ mapel.nama }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.guru_matpel_id" />
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

                <!-- Semester & Tahun Ajaran -->
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
                    <Button :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

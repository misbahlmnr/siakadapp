<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Kelas } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ChevronDown, LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    role: string;
    kelas: Kelas[];
}>();

type Form = {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    kelas_id: number | null;
    nis: string;
    nisn: string;
    jenis_kelamin: 'L' | 'P' | null;
    tempat_lahir: string;
    tanggal_lahir: string;
    no_hp: string;
    angkatan: string;
    status: 'aktif' | 'lulus' | 'pindah';
    nama_ortu: string;
    kontak_ortu: string;
    role: string;
};

// Form
const form = useForm<Form>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    kelas_id: null,
    nis: '',
    nisn: '',
    jenis_kelamin: null,
    tempat_lahir: '',
    tanggal_lahir: '',
    no_hp: '',
    angkatan: '',
    status: 'aktif',
    nama_ortu: '',
    kontak_ortu: '',
    role: 'siswa',
});

// Dropdown Labels
const statusLabel = ref('Aktif');
const selectedKelasLabel = ref('Pilih Kelas');
const selectedJenisKelaminLabel = ref('Pilih Jenis Kelamin');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Manajemen Data Siswa', href: route('admin.users.index', 'siswa') },
    { title: 'Tambah Data Siswa', href: route('admin.users.create', 'siswa') },
];

const submit = () => {
    form.post(route('admin.users.store', props.role));
};
</script>

<template>
    <Head title="Tambah Data Siswa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 px-10 py-6">
            <h1 class="text-2xl font-bold">Tambah Data Siswa</h1>

            <form class="grid grid-cols-1 gap-4 md:max-w-3xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Nama -->
                <div class="flex flex-col gap-2">
                    <Label for="name">Nama Lengkap</Label>
                    <Input id="name" v-model="form.name" placeholder="Masukkan nama lengkap" />
                    <InputError :message="form.errors.name" />
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-2">
                    <Label for="email">Email</Label>
                    <Input id="email" type="email" v-model="form.email" placeholder="Masukkan email" />
                    <InputError :message="form.errors.email" />
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-2">
                    <Label for="password">Password</Label>
                    <Input id="password" type="password" v-model="form.password" placeholder="Minimal 8 karakter" />
                    <InputError :message="form.errors.password" />
                </div>

                <!-- Konfirmasi Password -->
                <div class="flex flex-col gap-2">
                    <Label for="password_confirmation">Konfirmasi Password</Label>
                    <Input id="password_confirmation" type="password" v-model="form.password_confirmation" placeholder="Ulangi password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <!-- NIS -->
                <div class="flex flex-col gap-2">
                    <Label for="nis">NIS</Label>
                    <Input id="nis" type="number" v-model="form.nis" placeholder="Masukkan NIS" />
                    <InputError :message="form.errors.nis" />
                </div>

                <!-- NISN -->
                <div class="flex flex-col gap-2">
                    <Label for="nisn">NISN</Label>
                    <Input id="nisn" type="number" v-model="form.nisn" placeholder="Masukkan NISN" />
                    <InputError :message="form.errors.nisn" />
                </div>

                <!-- Kelas -->
                <div class="flex flex-col gap-2">
                    <Label>Kelas</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedKelasLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="max-h-60 min-w-[200px] overflow-auto">
                            <DropdownMenuItem
                                v-for="kelas in props.kelas"
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

                <!-- Angkatan -->
                <div class="flex flex-col gap-2">
                    <Label>Angkatan</Label>
                    <Input v-model="form.angkatan" type="number" placeholder="Misal: 2022" />
                    <InputError :message="form.errors.angkatan" />
                </div>

                <!-- Jenis Kelamin -->
                <div class="flex flex-col gap-2">
                    <Label>Jenis Kelamin</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ selectedJenisKelaminLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="min-w-[200px]">
                            <DropdownMenuItem
                                @click="
                                    form.jenis_kelamin = 'L';
                                    selectedJenisKelaminLabel = 'Laki-laki';
                                "
                                >Laki-laki</DropdownMenuItem
                            >
                            <DropdownMenuItem
                                @click="
                                    form.jenis_kelamin = 'P';
                                    selectedJenisKelaminLabel = 'Perempuan';
                                "
                                >Perempuan</DropdownMenuItem
                            >
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.jenis_kelamin" />
                </div>

                <!-- Tempat Lahir -->
                <div class="flex flex-col gap-2">
                    <Label>Tempat Lahir</Label>
                    <Input v-model="form.tempat_lahir" placeholder="Masukkan tempat lahir" />
                    <InputError :message="form.errors.tempat_lahir" />
                </div>

                <!-- Tanggal Lahir -->
                <div class="flex flex-col gap-2">
                    <Label>Tanggal Lahir</Label>
                    <Input type="date" v-model="form.tanggal_lahir" />
                    <InputError :message="form.errors.tanggal_lahir" />
                </div>

                <!-- No HP -->
                <div class="flex flex-col gap-2">
                    <Label>No HP</Label>
                    <Input v-model="form.no_hp" type="number" placeholder="Masukkan nomor HP" />
                    <InputError :message="form.errors.no_hp" />
                </div>

                <!-- Nama Orang Tua -->
                <div class="flex flex-col gap-2">
                    <Label>Nama Orang Tua</Label>
                    <Input v-model="form.nama_ortu" placeholder="Masukkan nama orang tua" />
                    <InputError :message="form.errors.nama_ortu" />
                </div>

                <!-- Kontak Orang Tua -->
                <div class="flex flex-col gap-2">
                    <Label>Kontak Orang Tua</Label>
                    <Input type="number" v-model="form.kontak_ortu" placeholder="Masukkan nomor HP orang tua" />
                    <InputError :message="form.errors.kontak_ortu" />
                </div>

                <!-- Status -->
                <div class="flex flex-col gap-2">
                    <Label>Status</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">{{ statusLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="min-w-[200px]">
                            <DropdownMenuItem
                                @click="
                                    form.status = 'aktif';
                                    statusLabel = 'Aktif';
                                "
                                >Aktif</DropdownMenuItem
                            >
                            <DropdownMenuItem
                                @click="
                                    form.status = 'lulus';
                                    statusLabel = 'Lulus';
                                "
                                >Lulus</DropdownMenuItem
                            >
                            <DropdownMenuItem
                                @click="
                                    form.status = 'pindah';
                                    statusLabel = 'Pindah';
                                "
                                >Pindah</DropdownMenuItem
                            >
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.status" />
                </div>

                <!-- Submit -->
                <div class="mt-4 flex justify-start md:col-span-2">
                    <Button :disabled="form.processing" class="bg-blue-600 text-white hover:bg-blue-600/90">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

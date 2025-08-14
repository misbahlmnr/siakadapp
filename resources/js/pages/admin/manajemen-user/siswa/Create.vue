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
    nisn: string;
    kelas_id: number | null;
    tahun_masuk: string;
    alamat: string;
    kontak_ortu: string;
    status: string;
    role: string;
};

// Form
const form = useForm<Form>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    nisn: '',
    kelas_id: null,
    tahun_masuk: '',
    alamat: '',
    kontak_ortu: '',
    status: 'aktif',
    role: 'siswa',
});

// Dropdown Status
const statusLabel = ref('Aktif');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Manajemen Data Siswa', href: route('admin.users.index', 'siswa') },
    { title: 'Tambah Data Siswa', href: route('admin.users.create', 'siswa') },
];

const selectedKelasLabel = ref('Pilih Kelas');

const submit = () => {
    form.post(route('admin.users.store', props.role));
};
</script>

<template>
    <Head title="Tambah Data Siswa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Tambah Data Siswa</h1>

            <form class="grid max-w-full grid-cols-1 gap-4 md:max-w-2xl md:grid-cols-2" @submit.prevent="submit">
                <!-- Nama -->
                <div class="flex flex-col gap-3">
                    <Label for="name">Nama</Label>
                    <Input id="name" v-model="form.name" placeholder="Masukkan nama lengkap" />
                    <InputError :message="form.errors.name" />
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-3">
                    <Label for="email">Email</Label>
                    <Input id="email" type="email" v-model="form.email" placeholder="Masukkan email" />
                    <InputError :message="form.errors.email" />
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-3">
                    <Label for="password">Password</Label>
                    <Input id="password" type="password" v-model="form.password" placeholder="Minimal 8 karakter" />
                    <InputError :message="form.errors.password" />
                </div>

                <!-- Konfirmasi Password -->
                <div class="flex flex-col gap-3">
                    <Label for="password_confirmation">Konfirmasi Password</Label>
                    <Input id="password_confirmation" type="password" v-model="form.password_confirmation" placeholder="Ulangi password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <!-- NISN -->
                <div class="flex flex-col gap-3">
                    <Label for="nisn">NISN</Label>
                    <Input id="nisn" v-model="form.nisn" placeholder="Masukkan NISN" />
                    <InputError :message="form.errors.nisn" />
                </div>

                <!-- Kelas -->
                <div class="flex flex-col gap-3">
                    <Label for="kelas">Kelas</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span class="text-sm">
                                    {{ selectedKelasLabel }}
                                </span>
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

                <!-- Tahun Masuk -->
                <div class="flex flex-col gap-3">
                    <Label for="tahun_masuk">Tahun Masuk</Label>
                    <Input id="tahun_masuk" v-model="form.tahun_masuk" placeholder="Misal: 2022" />
                    <InputError :message="form.errors.tahun_masuk" />
                </div>

                <!-- Kontak Ortu -->
                <div class="flex flex-col gap-3">
                    <Label for="kontak_ortu">Kontak Orang Tua</Label>
                    <Input id="kontak_ortu" v-model="form.kontak_ortu" placeholder="Masukkan No. HP Orang Tua" />
                    <InputError :message="form.errors.kontak_ortu" />
                </div>

                <!-- Alamat -->
                <div class="flex flex-col gap-3 md:col-span-2">
                    <Label for="alamat">Alamat</Label>
                    <textarea
                        id="alamat"
                        v-model="form.alamat"
                        placeholder="Masukkan alamat"
                        class="w-full rounded border p-2 text-sm"
                        rows="3"
                    ></textarea>
                    <InputError :message="form.errors.alamat" />
                </div>

                <!-- Status -->
                <div class="flex flex-col gap-3">
                    <Label for="status">Status</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
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
                            >
                                Aktif
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                @click="
                                    form.status = 'tidak_aktif';
                                    statusLabel = 'Tidak Aktif';
                                "
                            >
                                Tidak Aktif
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.status" />
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

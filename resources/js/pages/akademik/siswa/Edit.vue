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

const props = defineProps({
    siswa: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    nis: props.siswa.nis ?? '',
    nama: props.siswa.nama ?? '',
    jenis_kelamin: props.siswa.jenis_kelamin ?? 'L',
    kelas: props.siswa.kelas ?? '',
    tahun_masuk: props.siswa.tahun_masuk ?? '',
    ttl: props.siswa.ttl ?? '',
    alamat: props.siswa.alamat ?? '',
    kontak_ortu: props.siswa.kontak_ortu ?? '',
    status: props.siswa.status ?? 'Aktif',
});

const jenisKelaminLabel = ref(props.siswa.jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki');
const statusLabel = ref(props.siswa.status === 'tidak_aktif' ? 'Tidak Aktif' : 'Aktif');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Akademik', href: '#' },
    { title: 'Siswa', href: '/akademik/siswa' },
    { title: 'Edit Data Siswa', href: `/akademik/siswa/${props.siswa.id}/edit` },
];

const submit = () => {
    form.put(route('akademik.siswa.update', props.siswa.id));
};
</script>

<template>
    <Head title="Edit Data Siswa" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Data Siswa</h1>

            <form class="grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submit">
                <!-- NIS -->
                <div class="flex flex-col gap-3">
                    <Label for="nis">NIS</Label>
                    <Input id="nis" v-model="form.nis" placeholder="Masukkan NIS" />
                    <InputError :message="form.errors.nis" />
                </div>

                <!-- Nama -->
                <div class="flex flex-col gap-3">
                    <Label for="nama">Nama</Label>
                    <Input id="nama" v-model="form.nama" placeholder="Masukkan nama lengkap" />
                    <InputError :message="form.errors.nama" />
                </div>

                <!-- Jenis Kelamin (Dropdown) -->
                <!-- Jenis Kelamin -->
                <div class="flex flex-col gap-3">
                    <Label>Jenis Kelamin</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span>{{ jenisKelaminLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="min-w-[200px]">
                            <DropdownMenuItem
                                @click="
                                    form.jenis_kelamin = 'L';
                                    jenisKelaminLabel = 'Laki-laki';
                                "
                            >
                                Laki-laki
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                @click="
                                    form.jenis_kelamin = 'P';
                                    jenisKelaminLabel = 'Perempuan';
                                "
                            >
                                Perempuan
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.jenis_kelamin" />
                </div>

                <!-- Kelas -->
                <div class="flex flex-col gap-3">
                    <Label for="kelas">Kelas</Label>
                    <Input id="kelas" v-model="form.kelas" placeholder="Contoh: 8B" />
                    <InputError :message="form.errors.kelas" />
                </div>

                <!-- Tahun Masuk -->
                <div class="flex flex-col gap-3">
                    <Label for="tahun_masuk">Tahun Masuk</Label>
                    <Input id="tahun_masuk" v-model="form.tahun_masuk" placeholder="Masukan tahun masuk" />
                    <InputError :message="form.errors.tahun_masuk" />
                </div>

                <!-- TTL -->
                <div class="flex flex-col gap-3">
                    <Label for="ttl">Tempat, Tanggal Lahir</Label>
                    <Input id="ttl" v-model="form.ttl" placeholder="Masukan tempat, tanggal lahir" />
                    <InputError :message="form.errors.ttl" />
                </div>

                <!-- Alamat -->
                <div class="flex flex-col gap-3 md:col-span-2">
                    <Label for="alamat">Alamat</Label>
                    <textarea id="alamat" v-model="form.alamat" placeholder="Masukan alamat" class="w-full rounded border p-2" rows="3" />
                    <InputError :message="form.errors.alamat" />
                </div>

                <!-- Kontak Orang Tua -->
                <div class="flex flex-col gap-3">
                    <Label for="kontak_ortu">Kontak Orang Tua</Label>
                    <Input id="kontak_ortu" v-model="form.kontak_ortu" placeholder="08xxxxxxx" />
                    <InputError :message="form.errors.kontak_ortu" />
                </div>

                <!-- Status -->
                <div class="flex flex-col gap-3">
                    <Label>Status</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span>{{ statusLabel }}</span>
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
                                    form.status = 'tidak_aktif';
                                    statusLabel = 'Tidak Aktif';
                                "
                                >Tidak Aktif</DropdownMenuItem
                            >
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

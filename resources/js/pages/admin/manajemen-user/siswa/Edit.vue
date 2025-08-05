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
    role: {
        type: String,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
});

// Form init
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    nisn: props.user.siswa_profile?.nisn ?? '',
    kelas: props.user.siswa_profile?.kelas ?? '',
    tahun_masuk: props.user.siswa_profile?.tahun_masuk ?? '',
    alamat: props.user.siswa_profile?.alamat ?? '',
    kontak_ortu: props.user.siswa_profile?.kontak_ortu ?? '',
    status: props.user.siswa_profile?.status ?? 'aktif',
    role: props.role,
});

console.log(props.user);

// Dropdown Status
const statusLabel = ref(form.status === 'aktif' ? 'Aktif' : 'Tidak Aktif');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Manajemen Data Siswa', href: route('admin.users.index', 'siswa') },
    { title: `Edit ${props.user.name}`, href: route('admin.users.edit', { role: props.role, id: props.user.user_id }) },
];

const submit = () => {
    form.put(route('admin.users.update', { role: props.role, id: props.user.user_id }));
};
</script>

<template>
    <Head title="Edit Data Siswa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Data Siswa</h1>

            <form class="grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submit">
                <!-- Nama -->
                <div class="flex flex-col gap-3">
                    <Label for="name">Nama</Label>
                    <Input id="name" v-model="form.name" />
                    <InputError :message="form.errors.name" />
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-3">
                    <Label for="email">Email</Label>
                    <Input id="email" type="email" v-model="form.email" />
                    <InputError :message="form.errors.email" />
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-3">
                    <Label for="password">Password</Label>
                    <Input id="password" type="password" v-model="form.password" placeholder="Biarkan kosong jika tidak ingin diubah" />
                    <InputError :message="form.errors.password" />
                </div>

                <!-- Konfirmasi Password -->
                <div class="flex flex-col gap-3">
                    <Label for="password_confirmation">Konfirmasi Password</Label>
                    <Input id="password_confirmation" type="password" v-model="form.password_confirmation" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <!-- NISN -->
                <div class="flex flex-col gap-3">
                    <Label for="nisn">NISN</Label>
                    <Input id="nisn" v-model="form.nisn" />
                    <InputError :message="form.errors.nisn" />
                </div>

                <!-- Kelas -->
                <div class="flex flex-col gap-3">
                    <Label for="kelas">Kelas</Label>
                    <Input id="kelas" v-model="form.kelas" />
                    <InputError :message="form.errors.kelas" />
                </div>

                <!-- Tahun Masuk -->
                <div class="flex flex-col gap-3">
                    <Label for="tahun_masuk">Tahun Masuk</Label>
                    <Input id="tahun_masuk" v-model="form.tahun_masuk" />
                    <InputError :message="form.errors.tahun_masuk" />
                </div>

                <!-- Kontak Ortu -->
                <div class="flex flex-col gap-3">
                    <Label for="kontak_ortu">Kontak Orang Tua</Label>
                    <Input id="kontak_ortu" v-model="form.kontak_ortu" />
                    <InputError :message="form.errors.kontak_ortu" />
                </div>

                <!-- Alamat -->
                <div class="flex flex-col gap-3 md:col-span-2">
                    <Label for="alamat">Alamat</Label>
                    <textarea id="alamat" v-model="form.alamat" class="w-full rounded border p-2 text-sm" rows="3"></textarea>
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
                        Perbarui
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

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
    user: {
        type: Object,
        required: true,
    },
});

// Prefill form dari props.user
const form = useForm({
    name: props.user.name || '',
    email: props.user.email || '',
    password: '',
    password_confirmation: '',
    nip: props.user.nip || '',
    mapel: props.user.mapel || '',
    golongan: props.user.golongan || '',
    no_telp: props.user.no_telp || '',
    alamat: props.user.alamat || '',
    status_guru: props.user.status_guru || 'pns',
    tanggal_masuk: props.user.tanggal_masuk || '',
    role: 'guru',
});

const statusGuruLabel = ref(props.user.status_guru === 'honorer' ? 'Honorer' : 'PNS');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Manajemen Data Guru', href: route('admin.users.index', 'guru') },
    { title: 'Edit Data Guru', href: route('admin.users.edit', { role: 'guru', id: props.user.id }) },
];

const submit = () => {
    form.put(route('admin.users.update', { role: 'guru', id: props.user.id }));
};
</script>

<template>
    <Head title="Edit Data Guru" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Edit Data Guru</h1>

            <form class="grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submit">
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
                    <Label for="password">Password (Kosongkan jika tidak ingin diubah)</Label>
                    <Input id="password" type="password" v-model="form.password" placeholder="Minimal 8 karakter" />
                    <InputError :message="form.errors.password" />
                </div>

                <!-- Konfirmasi Password -->
                <div class="flex flex-col gap-3">
                    <Label for="password_confirmation">Konfirmasi Password</Label>
                    <Input id="password_confirmation" type="password" v-model="form.password_confirmation" placeholder="Ulangi password" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <!-- NIP -->
                <div class="flex flex-col gap-3">
                    <Label for="nip">NIP</Label>
                    <Input id="nip" v-model="form.nip" placeholder="Masukkan NIP" />
                    <InputError :message="form.errors.nip" />
                </div>

                <!-- Mata Pelajaran -->
                <div class="flex flex-col gap-3">
                    <Label for="mapel">Mata Pelajaran</Label>
                    <Input id="mapel" v-model="form.mapel" placeholder="Contoh: Matematika" />
                    <InputError :message="form.errors.mapel" />
                </div>

                <!-- Golongan -->
                <div class="flex flex-col gap-3">
                    <Label for="golongan">Golongan</Label>
                    <Input id="golongan" v-model="form.golongan" placeholder="Contoh: III/A" />
                    <InputError :message="form.errors.golongan" />
                </div>

                <!-- No Telp -->
                <div class="flex flex-col gap-3">
                    <Label for="no_telp">No Telepon</Label>
                    <Input id="no_telp" v-model="form.no_telp" placeholder="08xxxx" />
                    <InputError :message="form.errors.no_telp" />
                </div>

                <!-- Alamat -->
                <div class="flex flex-col gap-3 md:col-span-2">
                    <Label for="alamat">Alamat</Label>
                    <textarea id="alamat" v-model="form.alamat" placeholder="Masukkan alamat" class="w-full rounded border p-2" rows="3"></textarea>
                    <InputError :message="form.errors.alamat" />
                </div>

                <!-- Status Guru -->
                <div class="flex flex-col gap-3">
                    <Label>Status Guru</Label>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <button class="flex w-full items-center justify-between rounded border px-4 py-2" type="button">
                                <span>{{ statusGuruLabel }}</span>
                                <ChevronDown class="h-4 w-4 text-gray-500" />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="min-w-[200px]">
                            <DropdownMenuItem
                                @click="
                                    form.status_guru = 'pns';
                                    statusGuruLabel = 'PNS';
                                "
                            >
                                PNS
                            </DropdownMenuItem>
                            <DropdownMenuItem
                                @click="
                                    form.status_guru = 'honorer';
                                    statusGuruLabel = 'Honorer';
                                "
                            >
                                Honorer
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <InputError :message="form.errors.status_guru" />
                </div>

                <!-- Tanggal Masuk -->
                <div class="flex flex-col gap-3">
                    <Label for="tanggal_masuk">Tanggal Masuk</Label>
                    <Input id="tanggal_masuk" type="date" v-model="form.tanggal_masuk" />
                    <InputError :message="form.errors.tanggal_masuk" />
                </div>

                <!-- Submit -->
                <div class="mt-4 md:col-span-2">
                    <Button :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Simpan Perubahan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

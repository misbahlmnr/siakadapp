<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'admin',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Beranda', href: route('admin.dashboard') },
    { title: 'Manajemen Data Admin', href: route('admin.users.index', 'admin') },
    { title: 'Tambah Data Admin', href: route('admin.users.create', 'admin') },
];

const submit = () => {
    form.post(route('admin.users.store', 'admin'));
};
</script>

<template>
    <Head title="Tambah Data Admin" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 px-10 py-6">
            <h1 class="text-2xl font-bold">Tambah Data Admin</h1>

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

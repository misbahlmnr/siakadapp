import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    children?: NavItem[];
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Kelas {
    id: number;
    nama_kelas: string;
    tingkat: string;
    tahun_ajaran: string;
    created_at: string;
    updated_at: string;
}

export interface MatPel {
    id: number;
    kode_mapel: string;
    nama_mapel: string;
    guru_id: string | number | null;
    created_at: string;
    updated_at: string;
}

export interface MateriPelajaran {
    id: number;
    jadwal_id: number;
    guru_matpel_id: number;
    semester_ajaran_id: number;
    pertemuan_ke: number;
    judul_materi: string;
    deskripsi: string;
    file_materi: string;
    link_materi: string;
    semester_ajaran: any;
    created_at: string;
    updated_at: string;
}

export interface JadwalPelajaran {
    id: number;
    kelas_id: number;
    guru_matpel_id: number;
    semester_ajaran_id: number;
    hari: string;
    jam_mulai: string;
    jam_selesai: string;
    created_at: string;
    updated_at: string;
}

export interface AdaptiveRule {
    id: number;
    matpel_id: number;
    materi_id: number;
    dibuat_oleh: number;
    operator: string;
    nilai_batas: number;
    mataPelajaran: MatPel;
    materi: MateriPelajaran;
    dibuatOleh: User;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

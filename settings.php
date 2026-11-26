<?php
require __DIR__ . '/partials/route-guard.php';

$semesterOptions = [
    'Ganjil 2026/2027 (Semester 1)', 'Genap 2026/2027 (Semester 2)',
    'Ganjil 2027/2028 (Semester 3)', 'Genap 2027/2028 (Semester 4)',
    'Ganjil 2028/2029 (Semester 5)', 'Genap 2028/2029 (Semester 6)',
    'Ganjil 2029/2030 (Semester 7)', 'Genap 2029/2030 (Semester 8)',
];
$courseColors = ['#148278', '#22a35a', '#718096', '#8b45e8', '#e32636', '#83c719', '#15a7dc', '#ff873b', '#3267ee', '#ed2772'];
?>
<div class="mb-4">
    <h1 class="text-[22px] sm:text-2xl font-bold tracking-tight text-[#1E1B3A]">Pengaturan</h1>
    <p class="text-xs text-[#77758d]">Akun dan data FRS</p>
</div>
<form action="index.php" method="post" enctype="multipart/form-data">
    <?php csrf_field(); ?>
    <input type="hidden" name="action" value="save_settings">
    <div class="grid grid-cols-1 items-start gap-4 xl:grid-cols-2">
        <section class="rounded-xl border border-[#e5e5f0] bg-white p-4">
            <h2 class="mb-2 text-xs font-semibold text-[#39308e]">Akun</h2>
            <div class="mb-3 flex items-center gap-2">
                <?php if (!empty($user['avatar_path'])): ?>
                    <img src="<?= e($user['avatar_path']) ?>" alt="Foto profil" class="h-10 w-10 rounded-full object-cover">
                <?php else: ?>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#39308e] text-sm font-semibold text-white"><?= e(mb_strtoupper(mb_substr($user['name'] ?? '', 0, 1))) ?></div>
                <?php endif; ?>
                <label class="cursor-pointer text-[10px] font-medium text-[#39308e]">Ganti foto profil
                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" class="sr-only">
                </label>
            </div>
            <label for="settings-name" class="mb-1 block text-[9px] font-semibold text-[#302d45]">Nama pengguna</label>
            <input id="settings-name" name="name" type="text" value="<?= e($user['name'] ?? '') ?>" required class="mb-2.5 h-8 w-full rounded-lg border border-[#d4d2ea] px-2.5 text-[10px]">
            <label for="settings-email" class="mb-1 block text-[9px] font-semibold text-[#302d45]">Email</label>
            <input id="settings-email" name="email" type="email" value="<?= e($user['email'] ?? '') ?>" required class="mb-2.5 h-8 w-full rounded-lg border border-[#d4d2ea] px-2.5 text-[10px]">
            <label for="settings-prodi" class="mb-1 block text-[9px] font-semibold text-[#302d45]">Program studi</label>
            <input id="settings-prodi" name="prodi" type="text" value="<?= e($user['prodi'] ?? '') ?>" required class="mb-2.5 h-8 w-full rounded-lg border border-[#d4d2ea] px-2.5 text-[10px]">
            <label for="settings-semester" class="mb-1 block text-[9px] font-semibold text-[#302d45]">Semester aktif</label>
            <select id="settings-semester" name="semester" required class="mb-3 h-8 w-full rounded-lg border border-[#d4d2ea] bg-white px-2.5 text-[10px]">
                <option value="" <?= ($user['semester'] ?? '') === '' ? 'selected' : '' ?>>Pilih semester</option>
                <?php foreach ($semesterOptions as $option): ?>
                    <option value="<?= e($option) ?>" <?= ($user['semester'] ?? '') === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                <?php endforeach; ?>
                <?php if (($user['semester'] ?? '') !== '' && !in_array((string) ($user['semester'] ?? ''), $semesterOptions, true)): ?>
                    <option value="<?= e($user['semester'] ?? '') ?>" selected><?= e($user['semester'] ?? '') ?></option>
                <?php endif; ?>
            </select>
            <button type="submit" class="h-8 w-full rounded-md bg-[#39308e] text-[10px] font-semibold text-white">Simpan perubahan</button>
        </section>
        <section class="rounded-xl border border-[#e5e5f0] bg-white p-4">
            <h2 class="text-xs font-semibold text-[#39308e]">Mata kuliah dan jadwal (FRS)</h2>
            <p class="mt-1 mb-2 text-[9px] leading-relaxed text-[#77758d]">Sumber data untuk dropdown tugas, pengelompokan, warna kalender, dan jadwal kuliah.</p>
            <div id="settings-course-list" class="divide-y divide-[#efeff6]">
                <?php foreach (($courses ?? []) as $index => $course): ?>
                    <div class="course-row py-2" data-course-row>
                        <div class="course-summary flex items-center gap-2">
                            <span class="h-2 w-2 shrink-0 rounded-full" style="background:<?= e($courseColors[$index % count($courseColors)]) ?>"></span>
                            <div class="min-w-0 flex-1">
                                <p class="course-summary-name text-[10px] font-medium"><?= e($course['name']) ?></p>
                                <p class="course-summary-detail text-[8px] text-[#77758d]"><?= e($course['day'] ?: 'Pilih hari') ?> · <?= e($course['time'] ?: 'Jam belum diatur') ?> · <?= e($course['room'] ?: 'Ruang belum diatur') ?></p>
                            </div>
                            <span class="course-summary-sks rounded-full bg-[#efeffb] px-2 py-1 text-[8px] text-[#39308e]"><?= (int) $course['sks'] ?> SKS</span>
                            <button type="button" data-edit-course class="text-[9px] font-semibold text-[#39308e]">Ubah</button>
                            <button type="button" data-remove-course class="text-[9px] font-semibold text-[#b42318]">Hapus</button>
                        </div>
                        <div class="course-editor mt-2 hidden grid-cols-2 gap-2">
                            <input type="text" name="matkul[<?= (int) $index ?>][nama]" value="<?= e($course['name']) ?>" placeholder="Nama mata kuliah" data-course-name class="col-span-2 h-8 rounded-md border border-[#d4d2ea] px-2 text-[10px]">
                            <input type="number" name="matkul[<?= (int) $index ?>][sks]" value="<?= (int) $course['sks'] ?>" min="0" max="10" placeholder="SKS" data-course-sks class="h-8 rounded-md border border-[#d4d2ea] px-2 text-[10px]">
                            <select name="matkul[<?= (int) $index ?>][hari]" data-course-day class="h-8 rounded-md border border-[#d4d2ea] bg-white px-2 text-[10px]">
                                <option value="">Hari</option>
                                <?php foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day): ?>
                                    <option value="<?= e($day) ?>" <?= $course['day'] === $day ? 'selected' : '' ?>><?= e($day) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="matkul[<?= (int) $index ?>][jam]" value="<?= e($course['time']) ?>" placeholder="Jam" data-course-time class="h-8 rounded-md border border-[#d4d2ea] px-2 text-[10px]">
                            <input type="text" name="matkul[<?= (int) $index ?>][ruangan]" value="<?= e($course['room']) ?>" placeholder="Ruangan" data-course-room class="h-8 rounded-md border border-[#d4d2ea] px-2 text-[10px]">
                            <button type="button" data-close-course class="col-span-2 h-8 rounded-md border border-[#d4d2ea] text-[10px] font-semibold text-[#39308e]">Selesai mengubah</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-3 flex items-center justify-between text-[9px]"><span class="text-[#77758d]">Total</span><strong id="settings-total-sks">0 SKS</strong></div>
            <button type="button" id="settings-add-course" class="mt-3 h-8 w-full rounded-lg border border-[#d4d2ea] text-[10px] font-semibold text-[#39308e]">+ Tambah mata kuliah</button>
        </section>
    </div>
</form>
<template id="settings-course-template">
    <div class="course-row py-2" data-course-row>
        <div class="course-summary flex items-center gap-2">
            <span class="h-2 w-2 shrink-0 rounded-full" style="background:__COLOR__"></span>
            <div class="min-w-0 flex-1"><p class="course-summary-name text-[10px] font-medium">Mata kuliah baru</p><p class="course-summary-detail text-[8px] text-[#77758d]">Pilih hari · Jam belum diatur · Ruang belum diatur</p></div>
            <span class="course-summary-sks rounded-full bg-[#efeffb] px-2 py-1 text-[8px] text-[#39308e]">0 SKS</span>
            <button type="button" data-edit-course class="text-[9px] font-semibold text-[#39308e]">Ubah</button>
            <button type="button" data-remove-course class="text-[9px] font-semibold text-[#b42318]">Hapus</button>
        </div>
        <div class="course-editor mt-2 grid grid-cols-2 gap-2">
            <input type="text" name="matkul[__INDEX__][nama]" placeholder="Nama mata kuliah" data-course-name class="col-span-2 h-8 rounded-md border border-[#d4d2ea] px-2 text-[10px]">
            <input type="number" name="matkul[__INDEX__][sks]" min="0" max="10" placeholder="SKS" data-course-sks class="h-8 rounded-md border border-[#d4d2ea] px-2 text-[10px]">
            <select name="matkul[__INDEX__][hari]" data-course-day class="h-8 rounded-md border border-[#d4d2ea] bg-white px-2 text-[10px]">
                <option value="">Hari</option><option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option>
            </select>
            <input type="text" name="matkul[__INDEX__][jam]" placeholder="Jam" data-course-time class="h-8 rounded-md border border-[#d4d2ea] px-2 text-[10px]">
            <input type="text" name="matkul[__INDEX__][ruangan]" placeholder="Ruangan" data-course-room class="h-8 rounded-md border border-[#d4d2ea] px-2 text-[10px]">
            <button type="button" data-close-course class="col-span-2 h-8 rounded-md border border-[#d4d2ea] text-[10px] font-semibold text-[#39308e]">Selesai mengubah</button>
        </div>
    </div>
</template>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('settings-course-list');
    const template = document.getElementById('settings-course-template');
    const addButton = document.getElementById('settings-add-course');
    const totalSks = document.getElementById('settings-total-sks');
    const colors = <?= json_encode($courseColors) ?>;
    function refreshRow(row) {
        const name = row.querySelector('[data-course-name]').value.trim();
        const sks = Number(row.querySelector('[data-course-sks]').value) || 0;
        const day = row.querySelector('[data-course-day]').value || 'Pilih hari';
        const time = row.querySelector('[data-course-time]').value.trim() || 'Jam belum diatur';
        const room = row.querySelector('[data-course-room]').value.trim() || 'Ruang belum diatur';
        row.querySelector('.course-summary-name').textContent = name || 'Mata kuliah baru';
        row.querySelector('.course-summary-detail').textContent = `${day} · ${time} · ${room}`;
        row.querySelector('.course-summary-sks').textContent = `${sks} SKS`;
    }
    function refreshTotal() {
        totalSks.textContent = [...list.querySelectorAll('[data-course-sks]')]
            .reduce((sum, input) => sum + (Number(input.value) || 0), 0) + ' SKS';
    }
    function reindexRows() {
        list.querySelectorAll('[data-course-row]').forEach((row, index) => {
            row.querySelector('[data-course-name]').name = `matkul[${index}][nama]`;
            row.querySelector('[data-course-sks]').name = `matkul[${index}][sks]`;
            row.querySelector('[data-course-day]').name = `matkul[${index}][hari]`;
            row.querySelector('[data-course-time]').name = `matkul[${index}][jam]`;
            row.querySelector('[data-course-room]').name = `matkul[${index}][ruangan]`;
        });
    }
    list.addEventListener('input', function (event) {
        const row = event.target.closest('[data-course-row]');
        if (row) refreshRow(row);
        refreshTotal();
    });
    list.addEventListener('change', function (event) {
        const row = event.target.closest('[data-course-row]');
        if (row) refreshRow(row);
    });
    list.addEventListener('click', function (event) {
        const row = event.target.closest('[data-course-row]');
        if (!row) return;
        if (event.target.closest('[data-remove-course]')) {
            row.remove();
            reindexRows();
            refreshTotal();
        } else if (event.target.closest('[data-edit-course]')) {
            const editor = row.querySelector('.course-editor');
            const editing = editor.classList.toggle('hidden') === false;
            row.querySelector('.course-summary').classList.toggle('hidden', editing);
        } else if (event.target.closest('[data-close-course]')) {
            row.querySelector('.course-editor').classList.add('hidden');
            row.querySelector('.course-summary').classList.remove('hidden');
        }
    });
    addButton.addEventListener('click', function () {
        const index = list.querySelectorAll('[data-course-row]').length;
        const html = template.innerHTML.replace(/__INDEX__/g, String(index)).replace(/__COLOR__/g, colors[index % colors.length]);
        list.insertAdjacentHTML('beforeend', html);
        refreshTotal();
    });
    list.querySelectorAll('[data-course-row]').forEach(refreshRow);
    refreshTotal();
});
</script>

<?php
require __DIR__ . '/partials/route-guard.php';

$semesterOptions = [
    'Ganjil 2026/2027 (Semester 1)', 'Genap 2026/2027 (Semester 2)',
    'Ganjil 2027/2028 (Semester 3)', 'Genap 2027/2028 (Semester 4)',
    'Ganjil 2028/2029 (Semester 5)', 'Genap 2028/2029 (Semester 6)',
    'Ganjil 2029/2030 (Semester 7)', 'Genap 2029/2030 (Semester 8)',
];
?>
<div class="min-h-[80vh] flex items-center justify-center px-2 py-6">
    <div class="w-full max-w-[740px] rounded-2xl border border-[#E7E6F4] bg-white p-7 shadow-sm sm:p-9">
        <p class="text-xs font-semibold text-[#39308E]">Pengguna baru</p>
        <h1 class="mt-1 text-[22px] sm:text-2xl font-bold tracking-tight text-[#1E1B3A]">Isi mata kuliah</h1>
        <p class="mt-1 text-xs sm:text-[13px] text-[#7C7998]">Data ini digunakan untuk tugas, kalender, dan jadwal.</p>
        <form action="index.php" method="post" class="mt-6">
            <?php csrf_field(); ?>
            <input type="hidden" name="action" value="save_frs">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="prodi" class="mb-1.5 block text-xs font-bold text-[#2D2A4A]">Program studi</label>
                    <input id="prodi" type="text" name="prodi" value="<?= e($user['program_studi']) ?>" placeholder="Contoh: Teknologi Rekayasa Internet" required class="w-full rounded-[10px] border border-[#D4D2EA] px-3.5 py-2.5 text-[13px]">
                </div>
                <div>
                    <label for="semester" class="mb-1.5 block text-xs font-bold text-[#2D2A4A]">Semester aktif</label>
                    <select id="semester" name="semester" required class="w-full rounded-[10px] border border-[#D4D2EA] bg-white px-3.5 py-2.5 text-[13px]">
                        <option value="">Pilih semester</option>
                        <?php foreach ($semesterOptions as $option): ?>
                            <option value="<?= e($option) ?>" <?= $user['semester'] === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <label class="block border-b border-[#EFEFFB] pb-2.5 text-xs font-bold text-[#2D2A4A]">Mata kuliah dan jadwal</label>
                <div id="matkul-list" class="divide-y divide-[#EFEFFB]">
                    <div class="matkul-row grid grid-cols-12 items-center gap-2.5 py-3">
                        <div class="col-span-12 sm:col-span-4"><input type="text" name="matkul[0][nama]" placeholder="Nama mata kuliah" required class="w-full rounded-[10px] border border-[#D4D2EA] px-3.5 py-2.5 text-[13px]"></div>
                        <div class="col-span-4 sm:col-span-1"><input type="number" name="matkul[0][sks]" min="1" max="10" placeholder="SKS" required class="sks-input w-full rounded-[10px] border border-[#D4D2EA] px-2.5 py-2.5 text-[13px]"></div>
                        <div class="col-span-8 sm:col-span-2">
                            <select name="matkul[0][hari]" class="w-full rounded-[10px] border border-[#D4D2EA] bg-white px-2 py-2.5 text-[13px]">
                                <option value="">Hari</option><?php foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day): ?><option value="<?= e($day) ?>"><?= e($day) ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-span-7 sm:col-span-3"><input type="text" name="matkul[0][jam]" placeholder="Jam, contoh 07.30 - 09.10" class="w-full rounded-[10px] border border-[#D4D2EA] px-3.5 py-2.5 text-[13px]"></div>
                        <div class="col-span-5 sm:col-span-2"><input type="text" name="matkul[0][ruangan]" placeholder="Ruangan" class="w-full rounded-[10px] border border-[#D4D2EA] px-3.5 py-2.5 text-[13px]"></div>
                    </div>
                </div>
            </div>
            <button type="button" id="btn-tambah-matkul" class="mt-3 w-full rounded-[10px] border border-[#D4D2EA] bg-white py-2.5 text-[13px] font-bold text-[#39308E]">+ Tambah mata kuliah</button>
            <div class="mt-4 flex items-center justify-between text-[13px]">
                <span class="text-[#7C7998]">Total SKS</span><span id="total-sks" class="font-bold text-[#1E1B3A]">0 SKS</span>
            </div>
            <button type="submit" class="mt-4 w-full rounded-[10px] bg-[#39308E] py-3 text-[13px] font-semibold text-white">Simpan daftar matkul</button>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('matkul-list');
    let rowIndex = list.querySelectorAll('.matkul-row').length;
    function updateTotal() {
        const total = [...list.querySelectorAll('.sks-input')].reduce((sum, input) => sum + (parseInt(input.value, 10) || 0), 0);
        document.getElementById('total-sks').textContent = total + ' SKS';
    }
    list.addEventListener('input', function (event) {
        if (event.target.classList.contains('sks-input')) updateTotal();
    });
    document.getElementById('btn-tambah-matkul').addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'matkul-row grid grid-cols-12 items-center gap-2.5 py-3';
        row.innerHTML = `<div class="col-span-12 sm:col-span-4"><input type="text" name="matkul[${rowIndex}][nama]" placeholder="Nama mata kuliah" required class="w-full rounded-[10px] border border-[#D4D2EA] px-3.5 py-2.5 text-[13px]"></div>
            <div class="col-span-4 sm:col-span-1"><input type="number" name="matkul[${rowIndex}][sks]" min="1" max="10" placeholder="SKS" required class="sks-input w-full rounded-[10px] border border-[#D4D2EA] px-2.5 py-2.5 text-[13px]"></div>
            <div class="col-span-8 sm:col-span-2"><select name="matkul[${rowIndex}][hari]" class="w-full rounded-[10px] border border-[#D4D2EA] bg-white px-2 py-2.5 text-[13px]"><option value="">Hari</option><option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option></select></div>
            <div class="col-span-7 sm:col-span-3"><input type="text" name="matkul[${rowIndex}][jam]" placeholder="Jam, contoh 07.30 - 09.10" class="w-full rounded-[10px] border border-[#D4D2EA] px-3.5 py-2.5 text-[13px]"></div>
            <div class="col-span-5 sm:col-span-2"><input type="text" name="matkul[${rowIndex}][ruangan]" placeholder="Ruangan" class="w-full rounded-[10px] border border-[#D4D2EA] px-3.5 py-2.5 text-[13px]"></div>`;
        list.appendChild(row);
        rowIndex++;
    });
    updateTotal();
});
</script>

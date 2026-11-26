<?php
require __DIR__ . '/partials/route-guard.php';

$streak = streak_metrics($todoItems);
$completed = array_values(array_filter($todoItems, static function ($todo) {
    return !empty($todo['completed_at']);
}));
usort($completed, static function ($a, $b) {
    return strcmp($b['completed_at'], $a['completed_at']);
});
$lastActivity = $completed[0]['completed_at'] ?? null;
$completedDates = array_fill_keys($streak['dates'], true);
?>
<div class="page-name mb-3">
    <h1 class="text-[22px] sm:text-2xl font-bold tracking-tight text-[#1E1B3A]">Streak harian</h1>
    <p class="text-xs text-[#77758d]">Task dan Study yang selesai sama-sama menambah streak</p>
</div>
<div class="grid grid-cols-1 gap-3 lg:grid-cols-2">
    <div class="space-y-3">
        <section class="rounded-xl border border-[#f1d0c8] bg-[#fff0eb] p-3 text-[#7c2d12]">
            <h2 class="text-[11px] font-semibold">Status streak</h2>
            <p class="mt-1 text-[30px] font-bold leading-none"><?= $streak['current'] ?> hari</p>
            <p class="mt-1 text-[11px]">Rekor terbaikmu: <?= $streak['best'] ?> hari</p>
            <p class="mt-2 text-[11px]"><?= $lastActivity ? 'Aktivitas terakhir: ' . e(indonesian_date($lastActivity, true)) : 'Belum ada aktivitas yang selesai.' ?></p>
            <p class="mt-3 text-[11px] font-medium">Riwayat 7 hari terakhir</p>
            <div class="mt-1.5 grid grid-cols-7 gap-1 text-center">
                <?php for ($offset = 6; $offset >= 0; $offset--):
                    $date = date('Y-m-d', strtotime('-' . $offset . ' days'));
                    $active = isset($completedDates[$date]);
                ?>
                    <div>
                        <span class="mx-auto flex h-6 w-6 items-center justify-center rounded-full <?= $active ? 'bg-[#8c3414] text-white' : 'bg-white text-[#8c3414]' ?> text-[11px] font-semibold"><?= $active ? '✓' : date('j', strtotime($date)) ?></span>
                        <span class="mt-0.5 block text-[9px]"><?= e(date('D', strtotime($date))) ?></span>
                    </div>
                <?php endfor; ?>
            </div>
            <a href="<?= e(app_url('todo-list')) ?>" class="mt-3 flex w-full items-center justify-center rounded-md bg-[#843112] px-3 py-2 text-[10px] font-semibold text-white">Kerjakan latihan</a>
        </section>
        <section class="rounded-xl border border-[#e5e5f0] bg-white p-3">
            <h2 class="border-b border-[#efeff6] pb-2 text-[13px] font-semibold text-[#7c2d12]">Riwayat aktivitas</h2>
            <ul class="divide-y divide-[#efeff6]">
                <?php if ($completed): foreach (array_slice($completed, 0, 6) as $todo): ?>
                    <li class="flex items-center justify-between gap-2 py-2">
                        <div>
                            <p class="text-[11px] font-medium text-[#302d45]"><?= e($todo['title']) ?></p>
                            <p class="text-[12px] text-[#77758d]"><?= e(indonesian_date($todo['completed_at'], true)) ?></p>
                        </div>
                        <span class="rounded-full bg-[#efeffb] px-2 py-0.5 text-[12px] text-[#39308e]"><?= e(ucfirst($todo['type'])) ?></span>
                    </li>
                <?php endforeach; else: ?>
                    <li class="py-2 text-[12px] text-[#77758d]">Belum ada aktivitas selesai.</li>
                <?php endif; ?>
            </ul>
        </section>
    </div>
    <div class="space-y-3">
        <section class="rounded-xl border border-[#e5e5f0] bg-white p-3">
            <h2 class="text-[13px] font-semibold text-[#7c2d12]">Cara kerja streak</h2>
            <p class="mt-1 border-b border-[#efeff6] pb-2 text-[12px] leading-relaxed text-[#77758d]">
                Selesaikan setidaknya satu Task atau Study pada hari ini agar streak tetap berjalan. Hari tanpa aktivitas memutus rangkaian streak.
            </p>
            <div class="flex gap-2 border-b border-[#efeff6] py-2">
                <span class="text-[#c2410c]" aria-hidden="true">●</span>
                <div><h3 class="text-[11px] font-semibold text-[#302d45]">Aktif</h3><p class="text-[12px] text-[#77758d]">Aktivitas selesai pada hari tersebut masuk dalam hitungan.</p></div>
            </div>
            <div class="flex gap-2 pt-2">
                <span class="text-[#8b879b]" aria-hidden="true">○</span>
                <div><h3 class="text-[11px] font-semibold text-[#302d45]">Belum aktif</h3><p class="text-[12px] text-[#77758d]">Selesaikan satu aktivitas untuk memulai atau melanjutkan streak.</p></div>
            </div>
        </section>
        <section class="rounded-xl border border-[#cbdcf5] bg-[#e8f2ff] p-3 text-[#1d4f91]">
            <h2 class="text-[10px] font-semibold">Tip</h2>
            <p class="mt-1 text-[12px] leading-relaxed">Tambahkan aktivitas harian di To-do List, lalu tandai selesai saat kamu mengerjakannya.</p>
        </section>
    </div>
</div>

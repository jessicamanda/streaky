<?php
require __DIR__ . '/partials/route-guard.php';

$today = date('Y-m-d');
$weekStart = date('Y-m-d', strtotime('monday this week'));
$weekEnd = date('Y-m-d', strtotime('sunday this week'));
$monthStart = date('Y-m-01');
$monthEnd = date('Y-m-t');
$activeTodos = array_values(array_filter($todoItems, static function ($todo) {
    return empty($todo['completed_at']);
}));
$weekTodos = array_values(array_filter($activeTodos, static function ($todo) use ($weekStart, $weekEnd) {
    $date = $todo['deadline'] ? substr($todo['deadline'], 0, 10) : '';
    return $date >= $weekStart && $date <= $weekEnd;
}));
$monthTodos = array_values(array_filter($activeTodos, static function ($todo) use ($monthStart, $monthEnd) {
    $date = $todo['deadline'] ? substr($todo['deadline'], 0, 10) : '';
    return $date >= $monthStart && $date <= $monthEnd;
}));
$recentTodos = array_slice($activeTodos, 0, 5);
$streak = streak_metrics($todoItems);
?>
<div class="page-name mb-4">
    <h1 class="text-[22px] sm:text-2xl font-bold tracking-tight text-[#1E1B3A]">Halo, <?= e($user['name']) ?></h1>
    <p class="text-sm text-bodytext dark:text-darklink"><?= e(indonesian_date('today')) ?></p>
</div>
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="card mb-0 border border-border shadow-none"><div class="card-body p-4">
                <p class="text-xs text-bodytext">Tugas Aktif</p><p class="mt-1 text-2xl font-semibold"><?= count($activeTodos) ?></p>
            </div></div>
            <div class="card mb-0 border border-border shadow-none"><div class="card-body p-4">
                <p class="text-xs text-bodytext">Deadline Minggu Ini</p><p class="mt-1 text-2xl font-semibold"><?= count($weekTodos) ?></p>
            </div></div>
            <div class="card mb-0 border border-border shadow-none"><div class="card-body p-4">
                <p class="text-xs text-bodytext">Agenda Bulan Ini</p><p class="mt-1 text-2xl font-semibold"><?= count($monthTodos) ?></p>
            </div></div>
            <div class="card mb-0 border border-[#f1d0c8] bg-[#fff1ed] shadow-none"><div class="card-body p-4 text-[#7c2d12]">
                <p class="text-xs">Streak Harian</p><p class="mt-1 text-2xl font-semibold"><?= $streak['current'] ?> hari</p>
                <p class="mt-1 text-[10px]">Rekor terbaik: <?= $streak['best'] ?> hari</p>
            </div></div>
        </div>
    </div>
    <div class="lg:col-span-8 col-span-12">
        <div class="card"><div class="card-body">
            <h2 class="mb-4 text-sm font-semibold text-[#312e81]">To-do list</h2>
            <ul class="space-y-3" aria-label="Daftar tugas">
                <?php if ($recentTodos): foreach ($recentTodos as $todo): ?>
                    <li class="flex items-start gap-2">
                        <form action="index.php" method="post">
                            <?php csrf_field(); ?>
                            <input type="hidden" name="action" value="toggle_todo">
                            <input type="hidden" name="todo_id" value="<?= (int) $todo['id'] ?>">
                            <input type="hidden" name="return_page" value="dashboard">
                            <button type="submit" class="mt-0.5 h-4 w-4 rounded border border-[#bdbce0]" aria-label="Selesaikan <?= e($todo['title']) ?>"></button>
                        </form>
                        <div class="min-w-0 flex-1">
                            <span class="block text-xs font-medium"><?= e($todo['title']) ?></span>
                            <span class="block text-[10px] text-bodytext"><?= e($todo['course_name']) ?><?= $todo['deadline'] ? ' · ' . e(date('d M', strtotime($todo['deadline']))) : '' ?></span>
                        </div>
                        <?php if ($todo['priority']): ?><span class="shrink-0 rounded-full bg-[#fff1ed] px-2 py-1 text-[9px] text-[#c2410c]"><?= e($todo['priority']) ?></span><?php endif; ?>
                    </li>
                <?php endforeach; else: ?>
                    <li class="text-xs text-bodytext">Belum ada tugas aktif.</li>
                <?php endif; ?>
            </ul>
            <a href="<?= e(app_url('todo-list')) ?>" class="mt-4 flex w-full items-center justify-center rounded-md border border-[#c7c9e8] px-3 py-2 text-xs font-medium text-[#312e81]">Lihat semua tugas</a>
        </div></div>
    </div>
    <div class="lg:col-span-4 col-span-12">
        <div class="card"><div class="card-body rounded-xl border border-[#f1d0c8] bg-[#fff1ed] p-4 text-[#7c2d12]">
            <p class="text-xs font-medium">Streak harian</p>
            <p class="mt-1 text-2xl font-bold"><?= $streak['current'] ?> <span class="text-sm">hari</span></p>
            <p class="mt-2 text-[10px]">Rekor terbaik: <?= $streak['best'] ?> hari</p>
            <a href="<?= e(app_url('daily-streak')) ?>" class="mt-3 flex w-full justify-center rounded-md bg-[#7c2d12] px-3 py-2 text-xs font-semibold text-white">Lihat streak</a>
        </div></div>
        <div class="card mt-5"><div class="card-body">
            <h2 class="mb-4 text-sm font-semibold text-[#312e81]">Agenda terdekat</h2>
            <ul class="space-y-3">
                <?php if ($recentTodos): foreach (array_slice($recentTodos, 0, 4) as $todo): ?>
                    <li>
                        <p class="text-xs font-semibold"><?= e($todo['deadline'] ? indonesian_date($todo['deadline']) : 'Tanpa deadline') ?></p>
                        <p class="text-xs text-bodytext"><?= e($todo['title']) ?></p>
                        <p class="text-[10px] text-bodytext">Mata Kuliah: <?= e($todo['course_name']) ?></p>
                    </li>
                <?php endforeach; else: ?>
                    <li class="text-xs text-bodytext">Belum ada agenda terdekat.</li>
                <?php endif; ?>
            </ul>
            <a href="<?= e(app_url('calendar')) ?>" class="mt-4 flex w-full justify-center rounded-md border border-[#c7c9e8] px-3 py-2 text-xs font-medium text-[#312e81]">Buka kalender</a>
        </div></div>
    </div>
</div>

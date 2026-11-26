<?php
require __DIR__ . '/partials/route-guard.php';

$calendarMonthValue = $calendarMonth->format('Y-m');
$monthTitle = date('F Y', $calendarMonth->getTimestamp());
$daysInMonth = (int) $calendarMonth->format('t');
$firstWeekday = (int) $calendarMonth->format('N');
$selectedDate = (string) ($_GET['date'] ?? date('Y-m-d'));
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate) || substr($selectedDate, 0, 7) !== $calendarMonthValue) {
    $selectedDate = $calendarMonthValue . '-' . date('d');
    if ((int) substr($selectedDate, 8, 2) > $daysInMonth) {
        $selectedDate = $calendarMonthValue . '-01';
    }
}
$monthTodos = array_values(array_filter($todoItems, static function ($todo) use ($calendarMonthValue) {
    return !empty($todo['deadline']) && substr($todo['deadline'], 0, 7) === $calendarMonthValue;
}));
$colors = ['#4f8edb', '#6a74ad', '#d28a5d', '#52aee0', '#6fce95', '#e32636', '#8b45e8'];
$previousMonth = $calendarMonth->modify('-1 month')->format('Y-m');
$nextMonth = $calendarMonth->modify('+1 month')->format('Y-m');
?>
<div class="page-name mb-4">
    <h1 class="text-[22px] sm:text-2xl font-bold tracking-tight text-[#1E1B3A]">Kalender Akademik</h1>
    <p class="text-sm text-bodytext">Pilih tanggal, lalu tambah agenda</p>
</div>
<div class="grid grid-cols-12 gap-5">
    <div class="col-span-12 lg:col-span-7">
        <div class="card min-h-[390px]"><div class="card-body p-5">
            <div class="flex items-center justify-between">
                <a class="text-sm text-[#312e81]" href="<?= e(app_url('calendar') . '&month=' . $previousMonth) ?>" aria-label="Bulan sebelumnya">‹</a>
                <h2 class="text-sm font-semibold text-[#312e81]"><?= e($monthTitle) ?></h2>
                <a class="text-sm text-[#312e81]" href="<?= e(app_url('calendar') . '&month=' . $nextMonth) ?>" aria-label="Bulan berikutnya">›</a>
            </div>
            <div class="mt-4 grid grid-cols-7 text-center">
                <?php foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day): ?>
                    <div class="pb-2 text-[9px] font-medium text-[#5a5a74]"><?= e($day) ?></div>
                <?php endforeach; ?>
                <?php for ($blank = 1; $blank < $firstWeekday; $blank++): ?><div class="h-[50px]"></div><?php endfor; ?>
                <?php for ($date = 1; $date <= $daysInMonth; $date++):
                    $dateValue = $calendarMonthValue . '-' . str_pad((string) $date, 2, '0', STR_PAD_LEFT);
                    $dateTodos = array_values(array_filter($monthTodos, static function ($todo) use ($dateValue) {
                        return substr($todo['deadline'], 0, 10) === $dateValue;
                    }));
                    $isSelected = $dateValue === $selectedDate;
                ?>
                    <div class="h-[50px] pt-1">
                        <button type="button" class="calendar-date mx-auto flex h-11 w-11 items-center justify-center rounded-lg text-xs transition <?= $isSelected ? 'bg-[#312e81] font-semibold text-white' : 'text-[#4b5563] hover:bg-[#f4f4fb]' ?>"
                            data-date="<?= e($dateValue) ?>" aria-pressed="<?= $isSelected ? 'true' : 'false' ?>"><?= $date ?></button>
                        <?php if ($dateTodos): ?><span class="mx-auto -mt-1 block h-[2px] w-6 rounded-full bg-[#4f8edb]"></span><?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
            <div class="mt-2 flex flex-wrap gap-3 text-[9px] text-[#687087]">
                <?php foreach ($courses as $index => $course): ?>
                    <span class="flex items-center gap-1"><i class="h-2 w-2 rounded-full" style="background:<?= e($colors[$index % count($colors)]) ?>"></i><?= e($course['name']) ?></span>
                <?php endforeach; ?>
            </div>
        </div></div>
    </div>
    <div class="col-span-12 lg:col-span-5">
        <div class="card min-h-[390px]"><div class="card-body p-5">
            <h2 id="agenda-heading" class="text-sm font-semibold text-[#312e81]">Tambah agenda - <?= e(date('d F', strtotime($selectedDate))) ?></h2>
            <p class="mt-1 text-[10px] text-[#687087]">Judul agenda (nama tugas atau aktivitas)</p>
            <form action="index.php" method="post" class="mt-3">
                <?php csrf_field(); ?>
                <input type="hidden" name="action" value="create_todo">
                <input type="hidden" name="return_page" value="calendar">
                <input id="agenda-type" type="hidden" name="type" value="task">
                <input id="agenda-priority" type="hidden" name="priority" value="Sedang">
                <input type="text" name="title" required placeholder="Contoh: Laporan praktikum" class="form-control h-11 w-full rounded-md border border-border px-3 text-xs">
                <label class="mt-3 block text-[10px] font-medium text-[#687087]">Mata kuliah</label>
                <select name="course_name" class="form-control mt-1 h-11 w-full rounded-md border border-border px-3 text-xs">
                    <?php foreach ($courses as $course): ?><option value="<?= e($course['name']) ?>"><?= e($course['name']) ?></option><?php endforeach; ?>
                </select>
                <p class="mt-3 text-[10px] text-[#687087]">Daftar dari FRS. Ubah melalui Pengaturan FRS.</p>
                <label for="agenda-date" class="mt-3 block text-[10px] font-medium text-[#687087]">Tanggal / deadline</label>
                <input id="agenda-date" name="deadline" type="date" value="<?= e($selectedDate) ?>" class="form-control mt-1 h-11 w-full rounded-md border border-border px-3 text-xs">
                <label class="mt-3 block text-[10px] font-medium text-[#687087]">Jenis aktivitas</label>
                <div class="mt-1 flex gap-2">
                    <button type="button" class="activity-option rounded-md bg-[#312e81] px-3 py-1.5 text-[10px] font-semibold text-white" data-value="task">Tugas</button>
                    <button type="button" class="activity-option rounded-md border px-3 py-1.5 text-[10px]" data-value="study">Study</button>
                </div>
                <label class="mt-3 block text-[10px] font-medium text-[#687087]">Prioritas</label>
                <div class="mt-1 flex gap-2">
                    <?php foreach (['Tinggi', 'Sedang', 'Rendah'] as $priority): ?>
                        <button type="button" class="priority-option rounded-md border px-3 py-1.5 text-[10px] <?= $priority === 'Sedang' ? 'bg-[#312e81] text-white' : 'text-[#687087]' ?>" data-value="<?= e($priority) ?>"><?= e($priority) ?></button>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="mt-4 h-11 w-full rounded-md bg-[#312e81] text-xs font-semibold text-white">Simpan agenda</button>
            </form>
        </div></div>
    </div>
    <div class="col-span-12 lg:col-span-7">
        <div class="card"><div class="card-body p-5">
            <h2 class="text-sm font-semibold text-[#312e81]">Agenda bulan ini</h2>
            <ul class="mt-3 space-y-3">
                <?php if ($monthTodos): foreach ($monthTodos as $todo): ?>
                    <li>
                        <p class="text-xs font-semibold"><?= e(indonesian_date($todo['deadline'])) ?></p>
                        <p class="mt-0.5 text-xs text-bodytext"><?= e($todo['title']) ?></p>
                        <p class="text-[9px] text-[#687087]">Mata Kuliah: <?= e($todo['course_name']) ?></p>
                    </li>
                <?php endforeach; else: ?>
                    <li class="text-xs text-bodytext">Belum ada agenda untuk bulan ini.</li>
                <?php endif; ?>
            </ul>
        </div></div>
    </div>
    <div class="col-span-12 lg:col-span-5">
        <div class="card"><div class="card-body p-5">
            <h2 class="text-sm font-semibold text-[#312e81]">Pengingat</h2>
            <ul class="mt-3 space-y-5">
                <?php $reminders = array_values(array_filter($todoItems, static function ($todo) { return empty($todo['completed_at']); })); ?>
                <?php if ($reminders): foreach (array_slice($reminders, 0, 3) as $todo): ?>
                    <li><p class="text-xs font-semibold"><?= e($todo['title']) ?></p><p class="mt-0.5 text-[9px] text-[#687087]"><?= e($todo['deadline'] ? indonesian_date($todo['deadline'], true) : 'Tanpa deadline') ?></p></li>
                <?php endforeach; else: ?><li class="text-xs text-bodytext">Belum ada pengingat.</li><?php endif; ?>
            </ul>
        </div></div>
    </div>
</div>
<script>
document.querySelectorAll('.calendar-date').forEach(function (button) {
    button.addEventListener('click', function () {
        document.querySelectorAll('.calendar-date').forEach(function (item) {
            item.classList.remove('bg-[#312e81]', 'font-semibold', 'text-white');
            item.setAttribute('aria-pressed', 'false');
        });
        button.classList.add('bg-[#312e81]', 'font-semibold', 'text-white');
        button.setAttribute('aria-pressed', 'true');
        var date = button.dataset.date;
        var selected = new Date(date + 'T00:00:00');
        document.getElementById('agenda-heading').textContent = 'Tambah agenda - ' +
            selected.getDate() + ' ' + selected.toLocaleDateString('id-ID', { month: 'long' });
        document.getElementById('agenda-date').value = date;
    });
});
document.querySelectorAll('.activity-option, .priority-option').forEach(function (button) {
    button.addEventListener('click', function () {
        var group = button.classList.contains('activity-option') ? '.activity-option' : '.priority-option';
        var target = button.classList.contains('activity-option') ? 'agenda-type' : 'agenda-priority';
        document.getElementById(target).value = button.dataset.value;
        document.querySelectorAll(group).forEach(function (item) {
            item.classList.remove('bg-[#312e81]', 'text-white', 'font-semibold');
            item.classList.add('border');
        });
        button.classList.add('bg-[#312e81]', 'text-white', 'font-semibold');
    });
});
</script>

<?php
require __DIR__ . '/partials/route-guard.php';

$palette = ['#148278', '#22a35a', '#718096', '#8b45e8', '#e32636', '#83c719', '#15a7dc', '#ff873b', '#3267ee', '#ed2772'];
$courseColors = [];
$courseCredits = [];
foreach ($courses as $index => $course) {
    $courseColors[$course['name']] = $palette[$index % count($palette)];
    $courseCredits[$course['name']] = (int) $course['sks'];
}
$itemsForJs = [];
foreach ($todoItems as $todo) {
    $itemsForJs[] = [
        'id' => (int) $todo['id'],
        'type' => ucfirst($todo['type']),
        'title' => $todo['title'],
        'matkul' => $todo['course_name'],
        'deadlineDate' => $todo['deadline'] ? substr($todo['deadline'], 0, 10) : '',
        'deadline' => $todo['deadline'] ? date('d M', strtotime($todo['deadline'])) : 'Tanpa deadline',
        'priority' => $todo['priority'],
        'sks' => $courseCredits[$todo['course_name']] ?? 0,
        'done' => !empty($todo['completed_at']),
    ];
}
?>
<div class="page-name mb-5">
    <h1 class="text-[22px] sm:text-2xl font-bold tracking-tight text-[#1E1B3A]">To-do list</h1>
    <p class="mt-0.5 text-xs sm:text-[13px] text-[#7C7998]">Tasks dan Study dipisahkan. Keduanya yang selesai dihitung ke streak.</p>
</div>
<div class="mb-5 flex items-center gap-2">
    <button type="button" id="tab-tasks" class="rounded-lg bg-[#39308E] px-4 py-2 text-xs font-semibold text-white" onclick="switchMainTab('tasks')">Tasks</button>
    <button type="button" id="tab-study" class="rounded-lg bg-[#EFEFFB] px-4 py-2 text-xs font-semibold text-[#39308E]" onclick="switchMainTab('study')">Study</button>
</div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
    <section class="lg:col-span-7 bg-white rounded-2xl border border-[#E7E6F4] p-6">
        <h2 id="list-card-title" class="text-[15px] font-bold text-[#1E1B3A]">Tasks</h2>
        <p id="list-card-subtitle" class="hidden mt-0.5 text-xs text-[#7C7998]">Aktivitas belajar mandiri, dikelompokkan per mata kuliah.</p>
        <div id="tasks-subtabs" class="mt-3 flex flex-wrap gap-2">
            <button type="button" id="subtab-matkul" onclick="switchSubTab('matkul')" class="rounded-lg bg-[#EFEFFB] px-3.5 py-1.5 text-xs font-semibold text-[#39308E]">Berdasarkan Mata Kuliah</button>
            <button type="button" id="subtab-prioritas" onclick="switchSubTab('prioritas')" class="rounded-lg bg-[#39308E] px-3.5 py-1.5 text-xs font-semibold text-white">Berdasarkan Prioritas</button>
        </div>
        <div id="todo-groups-container" class="mt-5 space-y-5"></div>
    </section>
    <section class="lg:col-span-5 bg-white rounded-2xl border border-[#E7E6F4] p-6">
        <h2 id="form-card-title" class="text-[15px] font-bold text-[#1E1B3A]">Tambah tugas</h2>
        <form action="index.php" method="post" class="mt-4 space-y-4">
            <?php csrf_field(); ?>
            <input type="hidden" name="action" value="create_todo">
            <input type="hidden" name="return_page" value="todo-list">
            <label for="todo-title" class="block text-xs font-bold text-[#2D2A4A]">Judul agenda (nama tugas atau aktivitas)</label>
            <input id="todo-title" type="text" name="title" placeholder="Contoh: Laporan praktikum" required class="w-full rounded-[10px] border border-[#D4D2EA] px-3.5 py-2.5 text-[13px]">
            <label for="todo-matkul" class="block text-xs font-bold text-[#2D2A4A]">Mata kuliah</label>
            <select id="todo-matkul" name="course_name" class="w-full rounded-[10px] border border-[#D4D2EA] px-3.5 py-2.5 text-[13px]">
                <?php foreach ($courses as $course): ?><option value="<?= e($course['name']) ?>"><?= e($course['name']) ?></option><?php endforeach; ?>
            </select>
            <p class="text-[11px] text-[#8E8CA8]">Daftar dari FRS. Mata kuliah baru bisa diubah di Pengaturan.</p>
            <label for="todo-deadline" class="block text-xs font-bold text-[#2D2A4A]">Tanggal / deadline</label>
            <input id="todo-deadline" type="date" name="deadline" value="<?= e(date('Y-m-d')) ?>" class="w-full rounded-[10px] border border-[#D4D2EA] px-3.5 py-2.5 text-[13px]">
            <div>
                <span class="mb-1.5 block text-xs font-bold text-[#2D2A4A]">Jenis aktivitas</span>
                <input type="hidden" id="input-jenis" name="type" value="task">
                <div class="flex gap-2">
                    <button type="button" id="btn-jenis-tasks" onclick="selectFormJenis('Tasks')" class="rounded-lg bg-[#39308E] px-4 py-1.5 text-xs font-semibold text-white">Tasks</button>
                    <button type="button" id="btn-jenis-study" onclick="selectFormJenis('Study')" class="rounded-lg bg-[#EFEFFB] px-4 py-1.5 text-xs font-semibold text-[#39308E]">Study</button>
                </div>
            </div>
            <div id="form-prioritas-wrapper">
                <span class="mb-1.5 block text-xs font-bold text-[#2D2A4A]">Prioritas</span>
                <input type="hidden" id="input-prioritas" name="priority" value="Sedang">
                <div class="flex gap-2">
                    <?php foreach (['Tinggi', 'Sedang', 'Rendah'] as $priority): ?>
                        <button type="button" data-prio="<?= e($priority) ?>" onclick="selectFormPrioritas('<?= e($priority) ?>')" class="prio-btn rounded-lg px-3.5 py-1.5 text-xs font-semibold <?= $priority === 'Sedang' ? 'bg-[#39308E] text-white' : 'bg-[#EFEFFB] text-[#39308E]' ?>"><?= e($priority) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
            <p id="form-study-note" class="hidden text-xs text-[#7C7998]">Study tidak memakai prioritas.</p>
            <button type="submit" class="w-full rounded-[10px] bg-[#39308E] py-2.5 text-[13px] font-semibold text-white">Simpan</button>
        </form>
    </section>
</div>
<script type="application/json" id="todo-data"><?= json_encode([
    'colors' => $courseColors,
    'credits' => $courseCredits,
    'items' => $itemsForJs,
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
<script>
const todoData = JSON.parse(document.getElementById('todo-data').textContent);
const matkulColors = todoData.colors;
const matkulSks = todoData.credits;
const todoItems = todoData.items;
const csrfToken = <?= json_encode(ensure_csrf_token()) ?>;
let currentMainTab = 'tasks';
let currentSubTab = 'prioritas';

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, character => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    }[character]));
}
function sortTaskItems(items) {
    const priorityOrder = { Tinggi: 0, Sedang: 1, Rendah: 2 };
    return [...items].sort((a, b) => {
        const priorityDifference = (priorityOrder[a.priority] ?? 3) - (priorityOrder[b.priority] ?? 3);
        if (priorityDifference) return priorityDifference;
        const deadlineDifference = (a.deadlineDate || '9999-12-31').localeCompare(b.deadlineDate || '9999-12-31');
        return deadlineDifference || b.sks - a.sks;
    });
}
function badgeHtml(item) {
    if (item.done) return '<span class="rounded-full border border-[#BBF7D0] bg-[#F0FDF4] px-2.5 py-0.5 text-[11px] font-semibold text-[#15803D]">Selesai</span>';
    if (item.type === 'Study') return '<span class="rounded-full border border-[#E0E7FF] bg-[#EEF2FF] px-2.5 py-0.5 text-[11px] font-semibold text-[#4338CA]">Study</span>';
    return `<span class="rounded-full border border-[#FDE68A] bg-[#FFFBEB] px-2.5 py-0.5 text-[11px] font-semibold text-[#D97706]">${escapeHtml(item.priority || 'Sedang')}</span>`;
}
function renderTodoItem(item, showCourse) {
    const dotColor = escapeHtml(matkulColors[item.matkul] || '#39308E');
    const subtitle = showCourse
        ? `<span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-full" style="background:${dotColor}"></span>${escapeHtml(item.matkul)} · ${item.sks} SKS · ${escapeHtml(item.deadline)}</span>`
        : `Deadline ${escapeHtml(item.deadline)}`;
    return `<div class="flex items-center justify-between gap-3 py-2.5">
        <div class="flex items-start gap-3 min-w-0">
            <form action="index.php" method="post">
                <input type="hidden" name="csrf_token" value="${escapeHtml(csrfToken)}">
                <input type="hidden" name="action" value="toggle_todo">
                <input type="hidden" name="todo_id" value="${item.id}">
                <input type="hidden" name="return_page" value="todo-list">
                <button type="submit" aria-label="${item.done ? 'Batalkan' : 'Selesaikan'} ${escapeHtml(item.title)}" class="mt-0.5 h-[18px] w-[18px] rounded-[5px] border ${item.done ? 'border-[#39308E] bg-[#39308E]' : 'border-[#BDBCE0] bg-white'}"></button>
            </form>
            <div class="min-w-0">
                <p class="truncate text-[13px] font-semibold ${item.done ? 'line-through text-[#8E8CA8]' : 'text-[#1E1B3A]'}">${escapeHtml(item.title)}</p>
                <p class="mt-0.5 text-[11px] text-[#8E8CA8]">${subtitle}</p>
            </div>
        </div>${badgeHtml(item)}
    </div>`;
}
function renderList() {
    const container = document.getElementById('todo-groups-container');
    const itemType = currentMainTab === 'tasks' ? 'Task' : 'Study';
    const filtered = todoItems.filter(item => item.type === itemType);
    let html = '';
    if (currentMainTab === 'tasks' && currentSubTab === 'prioritas') {
        [['Prioritas Tinggi', 'Tinggi'], ['Prioritas Sedang', 'Sedang'], ['Prioritas Rendah', 'Rendah'], ['Selesai', null]].forEach(([label, priority]) => {
            const items = sortTaskItems(filtered.filter(item => priority === null ? item.done : !item.done && item.priority === priority));
            if (!items.length) return;
            html += `<section><div class="flex justify-between border-b border-[#EFEFFB] pb-2"><span class="text-xs font-bold text-[#39308E]">${label}</span><span class="text-[11px] text-[#8E8CA8]">${items.length} tugas</span></div><div class="divide-y divide-[#F4F3FC]">${items.map(item => renderTodoItem(item, true)).join('')}</div></section>`;
        });
    } else {
        [...new Set(filtered.map(item => item.matkul))].forEach(course => {
            const items = currentMainTab === 'tasks'
                ? sortTaskItems(filtered.filter(item => item.matkul === course))
                : filtered.filter(item => item.matkul === course);
            html += `<section><div class="flex justify-between border-b border-[#EFEFFB] pb-2"><span class="text-xs font-bold text-[#39308E]">${escapeHtml(course)}</span><span class="text-[11px] text-[#8E8CA8]">${matkulSks[course] || 0} SKS · ${items.length} item</span></div><div class="divide-y divide-[#F4F3FC]">${items.map(item => renderTodoItem(item, false)).join('')}</div></section>`;
        });
    }
    container.innerHTML = html || '<p class="text-xs text-[#8E8CA8]">Belum ada aktivitas pada kategori ini.</p>';
}
function setButton(button, active, activeClass, inactiveClass) {
    button.className = active ? activeClass : inactiveClass;
}
function switchMainTab(tab) {
    currentMainTab = tab;
    const isTasks = tab === 'tasks';
    setButton(document.getElementById('tab-tasks'), isTasks, 'rounded-lg bg-[#39308E] px-4 py-2 text-xs font-semibold text-white', 'rounded-lg bg-[#EFEFFB] px-4 py-2 text-xs font-semibold text-[#39308E]');
    setButton(document.getElementById('tab-study'), !isTasks, 'rounded-lg bg-[#39308E] px-4 py-2 text-xs font-semibold text-white', 'rounded-lg bg-[#EFEFFB] px-4 py-2 text-xs font-semibold text-[#39308E]');
    document.getElementById('list-card-title').textContent = isTasks ? 'Tasks' : 'Study';
    document.getElementById('list-card-subtitle').classList.toggle('hidden', isTasks);
    document.getElementById('tasks-subtabs').classList.toggle('hidden', !isTasks);
    selectFormJenis(isTasks ? 'Tasks' : 'Study');
    renderList();
}
function switchSubTab(tab) {
    currentSubTab = tab;
    const matkulClass = 'rounded-lg bg-[#39308E] px-3.5 py-1.5 text-xs font-semibold text-white';
    const inactiveClass = 'rounded-lg bg-[#EFEFFB] px-3.5 py-1.5 text-xs font-semibold text-[#39308E]';
    setButton(document.getElementById('subtab-matkul'), tab === 'matkul', matkulClass, inactiveClass);
    setButton(document.getElementById('subtab-prioritas'), tab === 'prioritas', matkulClass, inactiveClass);
    renderList();
}
function selectFormJenis(type) {
    const isTasks = type === 'Tasks';
    document.getElementById('input-jenis').value = isTasks ? 'task' : 'study';
    document.getElementById('form-card-title').textContent = isTasks ? 'Tambah tugas' : 'Tambah aktivitas belajar';
    setButton(document.getElementById('btn-jenis-tasks'), isTasks, 'rounded-lg bg-[#39308E] px-4 py-1.5 text-xs font-semibold text-white', 'rounded-lg bg-[#EFEFFB] px-4 py-1.5 text-xs font-semibold text-[#39308E]');
    setButton(document.getElementById('btn-jenis-study'), !isTasks, 'rounded-lg bg-[#39308E] px-4 py-1.5 text-xs font-semibold text-white', 'rounded-lg bg-[#EFEFFB] px-4 py-1.5 text-xs font-semibold text-[#39308E]');
    document.getElementById('form-prioritas-wrapper').classList.toggle('hidden', !isTasks);
    document.getElementById('form-study-note').classList.toggle('hidden', isTasks);
}
function selectFormPrioritas(priority) {
    document.getElementById('input-prioritas').value = priority;
    document.querySelectorAll('.prio-btn').forEach(button => {
        button.className = button.dataset.prio === priority
            ? 'prio-btn rounded-lg bg-[#39308E] px-3.5 py-1.5 text-xs font-semibold text-white'
            : 'prio-btn rounded-lg bg-[#EFEFFB] px-3.5 py-1.5 text-xs font-semibold text-[#39308E]';
    });
}
document.addEventListener('DOMContentLoaded', renderList);
</script>

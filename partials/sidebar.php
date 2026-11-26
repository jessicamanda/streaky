<div class="flex h-full flex-col justify-between">
    <div>
        <div class="px-5 py-5 flex items-center gap-3">
            <div class="shrink-0 flex items-center">
                <img src="assets/images/logo-streaky.png" alt="Logo Streaky" class="h-12 w-12">
            </div>
            <div class="flex flex-col justify-center items-start">
                <p class="leading-none font-extrabold text-[#39308E]"
                    style="font-size: 25px; font-weight: 700; color: #39308E;">Streaky</p>
                <p class="mt-1 text-[11.5px] leading-[1.25] font-normal text-[#7C7998]">Keep the Streak,
                    Level Up</p>
            </div>
        </div>
        <div class="scroll-sidebar">
            <nav class="px-6">
                <ul id="sidebarnav">
                    <?php
                    $menu = [
                        'dashboard' => 'Dashboard',
                        'calendar' => 'Kalender Akademik',
                        'todo-list' => 'To-do List',
                        'daily-streak' => 'Streak Harian',
                        'settings' => 'Pengaturan',
                    ];
                    foreach ($menu as $menuPage => $label):
                    ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link dark-sidebar-link <?= $page === $menuPage ? 'active activemenu text-white' : '' ?>"
                                href="<?= e(app_url($menuPage)) ?>">
                                <span class="hide-menu shrink-0"><?= e($label) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php if ($page === 'add-frs'): ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link dark-sidebar-link active activemenu text-white" href="<?= e(app_url('add-frs')) ?>">
                                <span class="hide-menu shrink-0">Isi Mata Kuliah</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="sidebar-item">
                        <form action="index.php" method="post">
                            <?php csrf_field(); ?>
                            <input type="hidden" name="action" value="logout">
                            <button type="submit" class="sidebar-link dark-sidebar-link">
                                <span class="hide-menu shrink-0">Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
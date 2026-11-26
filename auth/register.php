<!DOCTYPE html>
<html lang="id" dir="ltr" data-color-theme="Blue_Theme" class="light selected" data-layout="vertical" data-boxed-layout="boxed" data-card="shadow">

<head>
    <?php require __DIR__ . '/../partials/head.php'; ?>
    <title>Daftar - Streaky</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="DEFAULT_THEME antialiased">
    <main class="min-h-screen w-full bg-[#F5F5FA] flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-[380px]">
            <div class="bg-white rounded-2xl border border-[#E7E6F4] px-7 pt-9 pb-7 shadow-[0_2px_12px_rgba(59,49,144,0.03)]">
                <div class="flex flex-col items-center text-center">
                    <img src="assets/images/logo-streaky.png" alt="Logo Streaky" class="h-20 w-20">
                    <h1 class="mt-1.5 text-[24px] leading-tight font-bold tracking-tight text-[#39308E]">Streaky</h1>
                    <p class="mt-0.5 text-[11.5px] text-[#7C7998]">Buat akunmu</p>
                </div>
                <?php
                require __DIR__ . '/../partials/route-guard.php';

                $flashError = $flashError ?? null;
                $flashSuccess = $flashSuccess ?? null;
                ?>
                <?php if ($flashError): ?>
                    <p role="alert" class="mt-4 rounded-lg bg-red-50 px-3 py-2 text-xs text-red-700"><?= e($flashError) ?></p>
                <?php endif; ?>
                <div class="mt-6 flex gap-2">
                    <a href="<?= e(app_url('login')) ?>" class="rounded-lg bg-[#EFEFFB] px-4 py-2 text-xs font-semibold text-[#39308E]">Masuk</a>
                    <a href="<?= e(app_url('register')) ?>" class="rounded-lg bg-[#39308E] px-4 py-2 text-xs font-semibold text-white">Daftar</a>
                </div>
                <form action="index.php" method="post" class="mt-5">
                    <?php csrf_field(); ?>
                    <input type="hidden" name="action" value="register">
                    <div class="mb-3">
                        <label for="name" class="mb-1.5 block text-xs font-semibold text-[#2D2A4A]">Nama Pengguna</label>
                        <input id="name" type="text" name="name" placeholder="Nama Pengguna" autocomplete="name" required autofocus
                            class="w-full rounded-[10px] border border-[#D4D2EA] bg-white px-3.5 py-2.5 text-[13px] text-[#1E1B3A]">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="mb-1.5 block text-xs font-semibold text-[#2D2A4A]">Email</label>
                        <input id="email" type="email" name="email" placeholder="nama@email.com" autocomplete="email" required
                            class="w-full rounded-[10px] border border-[#D4D2EA] bg-white px-3.5 py-2.5 text-[13px] text-[#1E1B3A]">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="mb-1.5 block text-xs font-semibold text-[#2D2A4A]">Password</label>
                        <input id="password" type="password" name="password" placeholder="Minimal 8 karakter" autocomplete="new-password" minlength="8" required
                            class="w-full rounded-[10px] border border-[#D4D2EA] bg-white px-3.5 py-2.5 text-[13px] text-[#1E1B3A]">
                    </div>
                    <div class="mb-6">
                        <label for="password_confirmation" class="mb-1.5 block text-xs font-semibold text-[#2D2A4A]">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Konfirmasi Password" autocomplete="new-password" minlength="8" required
                            class="w-full rounded-[10px] border border-[#D4D2EA] bg-white px-3.5 py-2.5 text-[13px] text-[#1E1B3A]">
                    </div>
                    <button type="submit" class="w-full cursor-pointer rounded-[10px] bg-[#39308E] py-2.5 text-[13px] font-semibold text-white">Daftar</button>
                    <p class="mt-5 text-center text-[12px] text-[#6E6B8A]">Sudah punya akun?
                        <a href="<?= e(app_url('login')) ?>" class="font-semibold text-[#39308E] hover:underline">Masuk</a>
                    </p>
                </form>
            </div>
        </div>
    </main>
    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>
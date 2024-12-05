<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SMK</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="">
<nav
    class="flex justify-between px-6 py-3 border-b items-center sticky top-0 bg-white z-10 shadow-md"
>
    <a href="/" class="logo justify-self-start flex gap-2 items-center">
        <img src="{{asset('assets/logo.png')}}" class="w-8" alt=""/>
        <h1 class="tracking-wide font-bold text-[#17A2B8]">SMK</h1>
    </a>

    <!-- Hamburger Menu -->
    <button
        class="block md:hidden justify-self-center"
        id="menu-toggle"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-800" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <!-- Navigation Menu -->
    <ul
        class="max-md:hidden flex gap-6 md:col-span-1 transform duration-200 ease-in-out justify-self-end"
        id="nav-menu"
    >
        <li>
            <a href="/"
               class="flex items-center gap-2 hover:border-b-2 hover:border-[#17A2B8] hover:text-[#17A2B8] transform duration-200 ease-in-out text-cyan-800 border-b-2 border-transparent">
                Beranda
            </a>
        </li>
        <li>
            <a href="/contact"
               class="flex items-center gap-2 hover:border-b-2 hover:border-[#17A2B8] hover:text-[#17A2B8] transform duration-200 ease-in-out text-cyan-800 border-b-2 border-transparent">
                Tentang Kami
            </a>
        </li>
    </ul>

    <a
        href="/admin/login"
        class="max-md:hidden justify-self-end px-6 py-2 bg-[#17A2B8] rounded text-white hover:bg-[#196E85] transform duration-200 ease-in-out active:scale-95 shadow"
    >
        <h1 class="text-white">Login</h1>
    </a>

    <!-- Mobile Menu -->
    <ul class="hidden md:hidden absolute top-[50px] left-0 w-full bg-white shadow-md transition duration-300 ease-in-out"
        id="mobile-menu">
        <li class="border-b">
            <a href="/" class="block py-2 px-4 text-cyan-800 hover:bg-gray-100">
                Beranda
            </a>
        </li>
        <li class="border-b">
            <a href="/contact" class="block py-2 px-4 text-cyan-800 hover:bg-gray-100">
                Tentang Kami
            </a>
        </li>
        <li class="border-b">
            <a
                href="/admin/login"
                class="block py-2 px-4 text-cyan-800 hover:bg-gray-100"
            >
                Login
            </a>
        </li>
    </ul>
</nav>
<main class="px-20 py-14 bg-gray-100 text-gray-800 w-full">
    <!-- Tentang Kami -->
    <section class="mb-10">
        <h2 class="text-2xl font-bold text-cyan-800 mb-4">Tentang Kami</h2>
        <p class="leading-7">
            Sistem Manajemen Karyawan (SMK) adalah platform digital yang dirancang untuk mempermudah perusahaan
            dalam mengelola data karyawan, absensi, rekapitulasi, pengelolaan akun, hingga pengelolaan
            Tunjangan
            Hari Raya (THR). SMK memberikan solusi terintegrasi untuk meningkatkan efisiensi dan akurasi dalam
            manajemen sumber daya manusia.
        </p>
    </section>

    <!-- Kenapa Memilih SMK -->
    <section class="flex justify-between max-md:flex-col gap-8">
        <div>
            <h3 class="text-xl font-semibold text-cyan-800 mb-3">Kenapa Memilih SMK?</h3>
            <div class="flex flex-col gap-6">
                <div
                    class="bg-white shadow-lg rounded-lg p-6 flex gap-4 hover:scale-105 transition duration-300 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#17A2B8]" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M3 3h18v18H3z"/>
                        <path d="M7 7h10M7 11h6M7 15h8"/>
                    </svg>
                    <div>
                        <h4 class="text-lg font-semibold text-cyan-800 mb-3">Pengelolaan Data Karyawan</h4>
                        <p>Memudahkan pencatatan dan pengelolaan data karyawan secara terpusat dan aman.</p>
                    </div>
                </div>
                <div
                    class="bg-white shadow-lg rounded-lg p-6 flex gap-4 hover:scale-105 transition duration-300 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#17A2B8]" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                    <div>
                        <h4 class="text-lg font-semibold text-cyan-800 mb-3">Absensi Otomatis</h4>
                        <p>Menyediakan fitur absensi digital dengan rekapitulasi otomatis dan real-time.</p>
                    </div>
                </div>
                <div
                    class="bg-white shadow-lg rounded-lg p-6 flex gap-4 hover:scale-105 transition duration-300 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#17A2B8]" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M20 12a8 8 0 1 1-16 0 8 8 0 0 1 16 0z"/>
                        <path d="M12 8v4h3"/>
                    </svg>
                    <div>
                        <h4 class="text-lg font-semibold text-cyan-800 mb-3">pengelolaan THR</h4>
                        <p>Fitur pengelolaan THR yang akurat sesuai peraturan yang berlaku.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="border rounded-lg shadow-lg w-full">
            <iframe class="w-full h-full"
                    id="gmap_canvas"
                    src="https://maps.google.com/maps?width=520&amp;height=400&amp;hl=en&amp;q=Jl.%20Imam%20Bonjol%20No.%20885,%20RT.%20004%20RW.%20008,%20Ardimulyo,%20Kec.%20Singosari,%20Kab.%20Malang%20-%20Jawa%20Timur%2065153%20Malang+(PT%20UD%20BINTANG%20PUTRA%20TIMUR)&amp;t=&amp;z=12&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
        </div>
    </section>
</main>

<footer class="bg-[#17A2B8] px-20 py-14 relative">
    <img
        src="{{asset('assets/bg-element.png')}}"
        alt=""
        class="absolute top-10 right-8 rotate-180"
    />
    <img
        src="{{asset('assets/bg-element.png')}}"
        alt=""
        class="absolute top-40 right-40 rotate-180"
    />

    <div class="flex max-md:justify-start">
        <div class="text mb-20 text-white max-md:mb-10">
            <h4 class="text-xl font-semibold mb-8">KANTOR PUSAT</h4>
            <p class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3 text-white" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2a8 8 0 0 1 8 8c0 5.25-8 12-8 12s-8-6.75-8-12a8 8 0 0 1 8-8z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                Kantor : Jl. Imam Bonjol No. 885, RT. 004 RW. 008, Ardimulyo, Kec. Singosari, Kab. Malang - Jawa Timur
                65153
            </p>
            <h6 class="flex items-center mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                    <path
                        d="M6.62 10.79a15.908 15.908 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.09-.21 11.91 11.91 0 004.41 1.09c.6 0 1.09.5 1.09 1.09v3.22c0 .6-.5 1.09-1.09 1.09C8.26 21.78 2 15.52 2 7.09 2 6.5 2.5 6 3.09 6h3.22c.6 0 1.09.5 1.09 1.09 0 1.52.38 3 1.09 4.41.17.39.06.85-.21 1.09l-2.2 2.2z"/>
                </svg>
                Telepon : 0341-453456
            </h6>
            <h6 class="flex items-center mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                    <path
                        d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0-8 5-8-5h16zm-8 7L4 8v10h16V8l-8 5z"/>
                </svg>
                Email : prudputrabintangtimur@gmail.com
            </h6>
        </div>
    </div>
</footer>
<div class="w-full text-center py-3 bg-[#0D313F]">
    <p class="text-[#869099]">
        Copyright © 2024
        <span class="text-white font-bold">SMK</span> All rights reserved.
    </p>
</div>
<script>
    document.getElementById('menu-toggle').addEventListener('click', () => {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>
</body>
</html>

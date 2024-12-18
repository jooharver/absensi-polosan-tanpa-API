<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SMK</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css"/>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="">
<nav
    class="flex justify-between px-6 py-3 border-b items-center sticky top-0 bg-white z-10 shadow-md"
>
    <a href="/" class="logo justify-self-start flex gap-2 items-center">
        <img src="{{asset('assets/logo.png')}}" class="w-8" alt=""/>
        <h1 class="tracking-wide font-bold text-[#17A2B8]">PresenSee</h1>
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
<main>
    <section
        class="m-6 bg-[#FBFCFF] rounded-lg flex flex-col items-center text-center p-4 pt-16 relative"
    >
        <img src="{{asset('assets/bg-element.png')}}" alt="" class="absolute top-4 left-4 max-md:hidden"/>
        <img
            src="{{asset('assets/bg-element.png')}}"
            alt=""
            class="absolute top-4 right-4 rotate-180 max-md:hidden"
        />

        <h1 class="text-[#17A2B8] text-2xl w-1/2 max-md:w-full font-medium" data-aos="fade-in">
            Sistem Manajemen Karyawan!
        </h1>
        <p class="text-[#196E85] text-base w-1/3 mb-8 max-md:w-full font-light tracking-wide" data-aos="fade-in">
            Platform ini dirancang untuk memudahkan proses pengelolaan data karyawan, absensi, rekapitulasi, pengelolaan
            akun, dan thr karyawan <b>PR UD PUTRA BINTANG TIMUR</b>.
        </p>
        {{-- <img src="{{asset('assets/img.png')}}" alt="" class="w-2/3 max-md:w-full rounded-lg" data-aos="fade-in"/> --}}
        <section class="mt-20 flex max-md:flex-col gap-16 mb-20 items-center" data-aos="slide-up">
            <!-- Gambar dengan efek responsif, shadow, dan hover -->
            <img
                src="{{asset('assets/atas.png')}}"
                alt="Deskripsi Gambar"
                class="rounded-lg shadow-2xl w-[700px] max-w-full h-auto ml-0 transform transition duration-300 hover:scale-105"
            />
            <!-- Kontainer Teks -->
            <div class="flex flex-col justify-center text-left max-w-2xl ml-0">
                <h1 class="text-4xl text-[#17A2B8] font-semibold mb-6">
                    Tentang PresenSee
                </h1>
                <p class="text-lg text-gray-700 leading-relaxed">
                    <strong>PresenSee</strong> adalah platform terintegrasi yang dirancang khusus untuk menyederhanakan dan meningkatkan efisiensi dalam manajemen sumber daya manusia di sebuah organisasi. Platform ini menawarkan berbagai fitur unggulan seperti pengelolaan data karyawan, pencatatan absensi secara real-time, perhitungan rekapitulasi gaji dan tunjangan, pengelolaan akun individu, serta perencanaan dan distribusi Tunjangan Hari Raya (THR) dengan lebih mudah, cepat, dan transparan. <br /><br />
                    Dengan menggunakan <strong>PresenSee</strong>, proses administratif yang sebelumnya kompleks dan memakan waktu kini dapat diselesaikan hanya dalam beberapa langkah sederhana. Semua data yang tersimpan pada platform ini terintegrasi secara otomatis, meminimalkan risiko kesalahan input data manual serta memastikan akurasi dan keakuratan informasi yang dikelola.<br /><br />
                </p>
            </div>
        </section>




    <aside id="profile" class="scrollto text-center" data-enllax-ratio=".2">
        <div class="row clearfix">
            <div class="section-heading">
                <h3>PROFILE</h3>
                <h2 class="section-title">Developer</h2>
            </div>
            <div class="profile-container">
              <!-- Profile 1 -->
              <div class="profile w-full max-w-4xl bg-white rounded-lg shadow-lg p-6 text-center mx-auto">
                <blockquote class="profile-card">
                    <img
                        src="{{asset('assets/fotojoo.jpg')}}"
                        alt="Eka Krisna Ferian"
                        class="w-24 h-24 mx-auto rounded-full mb-4"
                    />
                    <h4 class="text-lg font-semibold text-gray-800">Eka Krisna Ferian</h4>
                    <p class="text-sm text-gray-600">Backend Developer</p>
                    <div class="contact mt-4 flex flex-col gap-2 items-center">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500">📞</span>
                            <p class="text-sm text-gray-500 ml-2">0878-7046-3683</p>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500">✉️</span>
                            <p class="text-sm text-gray-500 ml-2">ekakrisnaferian@gmail.com</p>
                        </div>
                    </div>
                </blockquote>
        </div>

            <div class="profile w-full max-w-4xl bg-white rounded-lg shadow-lg p-6 text-center mx-auto">
                <blockquote class="profile-card">
                    <img
                        src="{{asset('assets/fotoku.jpg')}}"
                        alt="Aunurrofiq Farhann Zuhdi"
                        class="w-24 h-24 mx-auto rounded-full mb-4"
                    />
                    <h4 class="text-lg font-semibold text-gray-800">Aunurrofiq Farhan Z</h4>
                    <p class="text-sm text-gray-600">Frontend Developer</p>
                    <div class="contact mt-4 flex flex-col gap-2 items-center">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500">📞</span>
                            <p class="text-sm text-gray-500 ml-2">0821-3996-3605</p>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500">✉️</span>
                            <p class="text-sm text-gray-500 ml-2">Aunurrofiqfz@gmail.com</p>
                        </div>
                    </div>
                </blockquote>
        </div>

            <!-- Profile 3 -->
            <div class="profile w-full max-w-4xl bg-white rounded-lg shadow-lg p-6 text-center mx-auto">
                <blockquote class="profile-card">
                    <img
                        src="{{asset('assets/fotodapa.jpg')}}"
                        alt="Daffa Maulana S"
                        class="w-24 h-24 mx-auto rounded-full mb-4"
                    />
                    <h4 class="text-lg font-semibold text-gray-800">Daffa Maulana S</h4>
                    <p class="text-sm text-gray-600">Frontend Developer</p>
                    <div class="contact mt-4 flex flex-col gap-2 items-center">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500">📞</span>
                            <p class="text-sm text-gray-500 ml-2">0838-4573-4645</p>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500">✉️</span>
                            <p class="text-sm text-gray-500 ml-2">daffamaulanasatria@gmail.com</p>
                        </div>
                    </div>
                </blockquote>
        </div>
    </div>
</aside>

    </aside>
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
        <span class="text-white font-bold">PresenSee</span> All rights reserved.
    </p>
</div>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init();
    document.getElementById('menu-toggle').addEventListener('click', () => {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>
</body>
</html>

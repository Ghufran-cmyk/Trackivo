<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Akun - Trackivo</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E9EAE4;
        }

        /* Autofill Styling Fix: Mencegah warna putih/biru muda default browser */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #1B271F inset !important;
            box-shadow: 0 0 0 1000px #1B271F inset !important;
            -webkit-text-fill-color: #FFFFFF !important;
            caret-color: #FFFFFF !important;
            border-color: #3B4F41 !important;
            transition: background-color 9999s ease-in-out 0s;
        }

        /* Default input dark states */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            background-color: #1B271F;
            color: #FFFFFF;
            border-color: #3B4F41;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            background-color: #1B271F;
            border-color: #C8D3BE;
            color: #FFFFFF;
        }

        /* Custom Checkbox Dark Styling */
        input[type="checkbox"] {
            background-color: #1B271F !important;
            border-color: #4A6050 !important;
            accent-color: #C8D3BE !important;
        }
    </style>

    <!-- Three.js Import Map -->
    <script type="importmap">
    {
        "imports": {
            "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
            "three/addons/": "https://unpkg.com/three@0.160.0/examples/jsm/"
        }
    }
    </script>
</head>
<body class="min-h-screen flex items-center justify-center p-4 md:p-8 bg-[#E9EAE4] text-white">

    <!-- Kartu Login Utama (Dark-Sage Split Card) -->
    <div class="w-full max-w-[1080px] bg-[#243329] rounded-[28px] p-3.5 md:p-4 shadow-[0_24px_60px_rgba(24,35,28,0.22)] grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">
        
        <!-- Panel Kiri: 3D Cargo Container Showcase -->
        <div class="lg:col-span-6 relative rounded-[20px] overflow-hidden min-h-[420px] lg:min-h-[600px] bg-gradient-to-b from-[#DFE4DA] via-[#BAC6B7] to-[#2B3D31] flex flex-col justify-between">
            
            <!-- Header Atas Panel Kiri -->
            <div class="relative z-10 p-4 md:p-5 flex items-center justify-between pointer-events-none">
                <!-- Badge Logo Gelap Semi-Transparan -->
                <div class="bg-[#243329]/80 backdrop-blur-md px-4 py-2 rounded-full border border-white/10 flex items-center pointer-events-auto shadow-sm">
                    <img src="{{ asset('images/logonama.png') }}" alt="Trackivo" class="h-7 md:h-8 w-auto object-contain">
                </div>

                <!-- Pil Petunjuk Interaksi 360 Derajat -->
                <div class="bg-[#243329]/70 backdrop-blur-md text-[#E6ECE4] text-[11px] px-3 py-1.5 rounded-full border border-white/10 pointer-events-none flex items-center space-x-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-[#C8D3BE] animate-spin-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Geser 360° untuk memutar</span>
                </div>
            </div>

            <!-- Canvas WebGL 3D Penuh -->
            <canvas id="cargo-3d-canvas" class="absolute inset-0 w-full h-full cursor-grab active:cursor-grabbing"></canvas>

            <!-- Fallback Elemen Visual bila WebGL/CDN Tidak Tersedia -->
            <div id="cargo-fallback" class="hidden absolute inset-0 flex flex-col items-center justify-center pointer-events-none p-6 text-center">
                <div class="w-32 h-20 border-2 border-[#556E5C]/40 rounded-xl bg-[#243329]/30 backdrop-blur-sm flex items-center justify-center mb-3">
                    <span class="text-sm font-semibold tracking-wider text-[#E6ECE4]">TRACKIVO 40FT</span>
                </div>
                <p class="text-xs text-[#243329]/80 font-medium">Interactive 3D Shipping Container</p>
            </div>

            <!-- Overlay Teks Bawah Panel Kiri -->
            <div class="bg-gradient-to-t from-[#1B271F]/95 via-[#1B271F]/55 to-transparent pt-16 pb-7 px-6 text-center pointer-events-none relative z-10 mt-auto">
                <h2 class="text-base md:text-lg font-medium text-white leading-snug">
                    Pemantauan Status &amp; Riwayat Pengiriman Global.
                </h2>
                <p class="text-sm text-[#D5DFD2] mt-1">
                    Kendali penuh rantai pasok dan deteksi keterlambatan lintas benua.
                </p>
                <p class="text-[11px] text-[#9EB09E] mt-3 tracking-wide">
                    Trackivo Intelligence Hub — Asia/Jakarta (WIB) Active
                </p>
            </div>
        </div>

        <!-- Panel Kanan: Form Login Minimalis Dark-Sage -->
        <div class="lg:col-span-6 flex flex-col justify-center px-6 py-8 md:px-12 lg:px-14 text-white">
            <div class="w-full max-w-md mx-auto">
                
                <!-- Judul & Subjudul -->
                <div class="mb-7">
                    <h1 class="text-2xl md:text-[28px] font-semibold text-white tracking-tight">
                        Selamat Datang Kembali
                    </h1>
                    <p class="text-sm text-[#9FB0A2] mt-1.5">
                        Masukkan kredensial Anda untuk mengakses portal Trackivo.
                    </p>
                </div>

                <!-- Session Flash & Pesan Status -->
                @if (session('info'))
                    <div class="mb-5 bg-[#2B4333] border border-[#52775E] text-[#D3E8D8] text-xs px-4 py-3 rounded-xl flex items-center space-x-2">
                        <svg class="h-4 w-4 shrink-0 text-[#9BB59F]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-5 bg-[#2B4333] border border-[#52775E] text-[#D3E8D8] text-xs px-4 py-3 rounded-xl flex items-center space-x-2">
                        <svg class="h-4 w-4 shrink-0 text-[#9BB59F]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-5 bg-[#3B2527] border border-[#7A4045] text-[#F3C7C9] text-xs px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Login -->
                <form action="{{ route('login.attempt') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="text-xs font-medium text-[#C8D4C9] mb-2 block tracking-wide">
                            ALAMAT EMAIL
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}"
                               placeholder="nama@perusahaan.com" 
                               data-admin-email="admin@trackivo.com"
                               required 
                               autocomplete="email"
                               class="w-full bg-[#1B271F] border border-[#3B4F41] text-white placeholder-[#738779] rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#C8D3BE] focus:ring-1 focus:ring-[#C8D3BE] transition-all">
                    </div>

                    <!-- Password Input dengan Toggle Show/Hide -->
                    <div>
                        <label for="password" class="text-xs font-medium text-[#C8D4C9] mb-2 block tracking-wide">
                            KATA SANDI
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   placeholder="••••••••••••" 
                                   required 
                                   autocomplete="current-password"
                                   class="w-full bg-[#1B271F] border border-[#3B4F41] text-white placeholder-[#738779] rounded-xl pl-4 pr-11 py-3 text-sm focus:outline-none focus:border-[#C8D3BE] focus:ring-1 focus:ring-[#C8D3BE] transition-all">
                            <button type="button" 
                                    id="toggle-password" 
                                    aria-label="Tampilkan atau sembunyikan kata sandi" 
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#738779] hover:text-[#C8D4C9] transition-colors p-1 focus:outline-none">
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between text-xs py-1">
                        <label class="flex items-center space-x-2 text-[#B5C4B7] hover:text-white cursor-pointer select-none">
                            <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded border border-[#4A6050] bg-[#1B271F] accent-[#C8D3BE] text-[#243329] focus:ring-0 cursor-pointer">
                            <span>Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-[#C8D3BE] hover:bg-[#D7E0CE] text-[#1C2820] font-semibold rounded-xl py-3.5 px-5 text-sm transition-all shadow-sm mt-2 cursor-pointer flex items-center justify-center space-x-2 active:scale-[0.99]">
                        <span>Masuk ke Akun</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <!-- Link Registrasi Customer -->
                <div class="mt-7 text-center text-xs text-[#9FB0A2]">
                    Belum memiliki akun Trackivo? 
                    <a href="{{ route('register') }}" class="font-medium text-[#DCE5D5] hover:underline ml-1">
                        Daftar Akun Customer
                    </a>
                </div>

            </div>
        </div>

    </div>

    <!-- Script: Toggle Password Visibility -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('toggle-password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    eyeIcon.classList.toggle('hidden', isPassword);
                    eyeOffIcon.classList.toggle('hidden', !isPassword);
                });
            }
        });
    </script>

    <!-- Script: Three.js 3D Interactive Shipping Container (360 Derajat) -->
    <script type="module">
        import * as THREE from 'three';
        import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

        (function initContainer3D() {
            try {
                const canvas = document.getElementById('cargo-3d-canvas');
                if (!canvas) return;

                const parent = canvas.parentElement;

                // 1. Scene & Camera Setup
                const scene = new THREE.Scene();

                const camera = new THREE.PerspectiveCamera(
                    38,
                    parent.clientWidth / parent.clientHeight,
                    0.1,
                    100
                );
                camera.position.set(4.2, 2.0, 4.8);

                // 2. Renderer
                const renderer = new THREE.WebGLRenderer({
                    canvas: canvas,
                    antialias: true,
                    alpha: true,
                    powerPreference: 'high-performance'
                });
                renderer.setSize(parent.clientWidth, parent.clientHeight);
                renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
                renderer.toneMapping = THREE.ACESFilmicToneMapping;
                renderer.toneMappingExposure = 1.15;

                // 3. OrbitControls (360 Derajat Rotasi)
                const controls = new OrbitControls(camera, renderer.domElement);
                controls.enableDamping = true;
                controls.dampingFactor = 0.06;
                controls.enablePan = false;
                controls.enableZoom = false; // Mempertahankan pengalaman scroll halaman mulus
                controls.autoRotate = true;
                controls.autoRotateSpeed = 1.1;
                controls.target.set(0, 0.15, 0);

                // 4. Pencahayaan Studio Bersih
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.95);
                scene.add(ambientLight);

                const hemiLight = new THREE.HemisphereLight(0xDFE4DA, 0x1B271F, 1.1);
                hemiLight.position.set(0, 20, 0);
                scene.add(hemiLight);

                // Main Key Light
                const dirLight1 = new THREE.DirectionalLight(0xffffff, 1.6);
                dirLight1.position.set(6, 9, 6);
                scene.add(dirLight1);

                // Fill / Rim Light
                const dirLight2 = new THREE.DirectionalLight(0xBAC6B7, 1.0);
                dirLight2.position.set(-6, 5, -6);
                scene.add(dirLight2);

                // Subtle Under-bounce Light
                const dirLight3 = new THREE.DirectionalLight(0xC8D3BE, 0.4);
                dirLight3.position.set(0, -4, 3);
                scene.add(dirLight3);

                // 5. Root Group untuk Kontainer Kargo
                const containerGroup = new THREE.Group();
                scene.add(containerGroup);

                // Material Definisi
                const darkSageColor = 0x243329;
                const containerBodyMat = new THREE.MeshStandardMaterial({
                    color: darkSageColor,
                    roughness: 0.52,
                    metalness: 0.32
                });

                const steelFrameMat = new THREE.MeshStandardMaterial({
                    color: 0x1D2721,
                    roughness: 0.45,
                    metalness: 0.55
                });

                const shinySteelMat = new THREE.MeshStandardMaterial({
                    color: 0x8D9E8F,
                    roughness: 0.25,
                    metalness: 0.8
                });

                const cableMat = new THREE.MeshStandardMaterial({
                    color: 0xB5C4B7,
                    roughness: 0.3,
                    metalness: 0.85
                });

                // Geometri Utama Kontainer 40ft (Dimensi Proporsional: width=4.4, height=1.75, depth=1.7)
                const cW = 4.4;
                const cH = 1.75;
                const cD = 1.7;

                // Bodi Inti
                const coreBox = new THREE.Mesh(
                    new THREE.BoxGeometry(cW - 0.05, cH - 0.05, cD - 0.05),
                    containerBodyMat
                );
                containerGroup.add(coreBox);

                // Corrugated Ribs (Rusuk Logam Bergelombang 3D) di Dinding Depan, Belakang, & Atap
                const ribCountX = 32;
                const ribSpacingX = (cW - 0.5) / (ribCountX - 1);
                const ribGeoZ = new THREE.BoxGeometry(0.045, cH - 0.12, 0.035);
                const ribGeoY = new THREE.BoxGeometry(0.045, 0.025, cD - 0.12);

                for (let i = 0; i < ribCountX; i++) {
                    const posX = -((cW - 0.5) / 2) + i * ribSpacingX;

                    // Rusuk Dinding Depan (+Z)
                    const frontRib = new THREE.Mesh(ribGeoZ, containerBodyMat);
                    frontRib.position.set(posX, 0, (cD / 2) - 0.015);
                    containerGroup.add(frontRib);

                    // Rusuk Dinding Belakang (-Z)
                    const backRib = new THREE.Mesh(ribGeoZ, containerBodyMat);
                    backRib.position.set(posX, 0, -(cD / 2) + 0.015);
                    containerGroup.add(backRib);

                    // Rusuk Atap Kontainer (+Y)
                    const roofRib = new THREE.Mesh(ribGeoY, containerBodyMat);
                    roofRib.position.set(posX, (cH / 2) + 0.005, 0);
                    containerGroup.add(roofRib);
                }

                // Rusuk Dinding Ujung Kiri (-X)
                const ribCountZ = 12;
                const ribSpacingZ = (cD - 0.4) / (ribCountZ - 1);
                const ribGeoX = new THREE.BoxGeometry(0.035, cH - 0.12, 0.045);
                for (let j = 0; j < ribCountZ; j++) {
                    const posZ = -((cD - 0.4) / 2) + j * ribSpacingZ;
                    const leftRib = new THREE.Mesh(ribGeoX, containerBodyMat);
                    leftRib.position.set(-(cW / 2) + 0.015, 0, posZ);
                    containerGroup.add(leftRib);
                }

                // Rangka Baja Eksterior (Corner Posts & Rails)
                // 4 Tiang Sudut Vertikal
                const postGeo = new THREE.BoxGeometry(0.12, cH, 0.12);
                const postPositions = [
                    [-(cW / 2), 0, -(cD / 2)],
                    [-(cW / 2), 0, (cD / 2)],
                    [(cW / 2), 0, -(cD / 2)],
                    [(cW / 2), 0, (cD / 2)]
                ];
                postPositions.forEach(p => {
                    const post = new THREE.Mesh(postGeo, steelFrameMat);
                    post.position.set(p[0], p[1], p[2]);
                    containerGroup.add(post);
                });

                // 8 Corner Castings Logam di Tiap Sudut Kontainer
                const cornerCastGeo = new THREE.BoxGeometry(0.15, 0.15, 0.15);
                const cornerCastMat = new THREE.MeshStandardMaterial({
                    color: 0x141B16,
                    roughness: 0.35,
                    metalness: 0.7
                });
                for (let sx = -1; sx <= 1; sx += 2) {
                    for (let sy = -1; sy <= 1; sy += 2) {
                        for (let sz = -1; sz <= 1; sz += 2) {
                            const casting = new THREE.Mesh(cornerCastGeo, cornerCastMat);
                            casting.position.set(
                                sx * (cW / 2),
                                sy * (cH / 2),
                                sz * (cD / 2)
                            );
                            containerGroup.add(casting);
                        }
                    }
                }

                // Batang Baja Horizontal (Longitudinal Top & Bottom Rails)
                const railLongGeo = new THREE.BoxGeometry(cW, 0.1, 0.08);
                for (let sy = -1; sy <= 1; sy += 2) {
                    for (let sz = -1; sz <= 1; sz += 2) {
                        const rail = new THREE.Mesh(railLongGeo, steelFrameMat);
                        rail.position.set(0, sy * (cH / 2), sz * (cD / 2));
                        containerGroup.add(rail);
                    }
                }

                // Batang Baja Ujung (Transverse Top & Bottom Rails)
                const railTransGeo = new THREE.BoxGeometry(0.08, 0.1, cD);
                for (let sx = -1; sx <= 1; sx += 2) {
                    for (let sy = -1; sy <= 1; sy += 2) {
                        const rail = new THREE.Mesh(railTransGeo, steelFrameMat);
                        rail.position.set(sx * (cW / 2), sy * (cH / 2), 0);
                        containerGroup.add(rail);
                    }
                }

                // Detail Pintu Belakang Kontainer di Sisi Ujung Kanan (+X)
                const doorGroup = new THREE.Group();
                doorGroup.position.set((cW / 2) + 0.01, 0, 0);

                // Daun Pintu Ganda
                const doorPanelGeo = new THREE.BoxGeometry(0.04, cH - 0.16, (cD - 0.22) / 2);
                const leftDoor = new THREE.Mesh(doorPanelGeo, steelFrameMat);
                leftDoor.position.set(0, 0, -(cD - 0.22) / 4);
                doorGroup.add(leftDoor);

                const rightDoor = new THREE.Mesh(doorPanelGeo, steelFrameMat);
                rightDoor.position.set(0, 0, (cD - 0.22) / 4);
                doorGroup.add(rightDoor);

                // 4 Batang Besi Pengunci Vertikal (Locking Rods)
                const rodGeo = new THREE.CylinderGeometry(0.016, 0.016, cH - 0.12, 12);
                const rodPositionsZ = [
                    -(cD * 0.35),
                    -(cD * 0.14),
                    (cD * 0.14),
                    (cD * 0.35)
                ];

                rodPositionsZ.forEach(pz => {
                    const rod = new THREE.Mesh(rodGeo, shinySteelMat);
                    rod.position.set(0.035, 0, pz);
                    doorGroup.add(rod);

                    // Handle Pengunci di Tengah Batang
                    const handle = new THREE.Mesh(
                        new THREE.BoxGeometry(0.08, 0.02, 0.02),
                        shinySteelMat
                    );
                    handle.position.set(0.07, -0.15, pz);
                    doorGroup.add(handle);

                    // Cam Locks di Ujung Atas & Bawah
                    const camTop = new THREE.Mesh(
                        new THREE.BoxGeometry(0.05, 0.06, 0.04),
                        steelFrameMat
                    );
                    camTop.position.set(0.03, (cH / 2) - 0.1, pz);
                    doorGroup.add(camTop);

                    const camBottom = new THREE.Mesh(
                        new THREE.BoxGeometry(0.05, 0.06, 0.04),
                        steelFrameMat
                    );
                    camBottom.position.set(0.03, -(cH / 2) + 0.1, pz);
                    doorGroup.add(camBottom);
                });

                containerGroup.add(doorGroup);

                // 4 Kabel Sling Baja Crane & Cincin Pengait (Suspended Container Effect)
                const apexHookPoint = new THREE.Vector3(0, (cH / 2) + 1.25, 0);
                const slingCorners = [
                    new THREE.Vector3(-(cW / 2) + 0.06, (cH / 2), -(cD / 2) + 0.06),
                    new THREE.Vector3(-(cW / 2) + 0.06, (cH / 2), (cD / 2) - 0.06),
                    new THREE.Vector3((cW / 2) - 0.06, (cH / 2), -(cD / 2) + 0.06),
                    new THREE.Vector3((cW / 2) - 0.06, (cH / 2), (cD / 2) - 0.06)
                ];

                slingCorners.forEach(corner => {
                    const distance = corner.distanceTo(apexHookPoint);
                    const cableGeo = new THREE.CylinderGeometry(0.012, 0.012, distance, 8);
                    const cable = new THREE.Mesh(cableGeo, cableMat);

                    // Posisikan di tengah garis antara corner dan apex
                    const midPoint = new THREE.Vector3().addVectors(corner, apexHookPoint).multiplyScalar(0.5);
                    cable.position.copy(midPoint);

                    // Orientasikan silinder sejajar garis arah
                    const direction = new THREE.Vector3().subVectors(apexHookPoint, corner).normalize();
                    const orientation = new THREE.Quaternion();
                    orientation.setFromUnitVectors(new THREE.Vector3(0, 1, 0), direction);
                    cable.quaternion.copy(orientation);

                    containerGroup.add(cable);
                });

                // Cincin Pengait Crane Logam di Puncak
                const hookRing = new THREE.Mesh(
                    new THREE.TorusGeometry(0.12, 0.032, 16, 32),
                    shinySteelMat
                );
                hookRing.position.copy(apexHookPoint);
                hookRing.rotation.y = Math.PI / 4;
                containerGroup.add(hookRing);

                // Tekstur Resolusi Tinggi untuk Branding Dinding Kontainer (CanvasTexture 1024x512)
                const textureCanvas = document.createElement('canvas');
                textureCanvas.width = 1024;
                textureCanvas.height = 512;
                const ctx = textureCanvas.getContext('2d');

                function renderSideTexture(logoImg) {
                    ctx.clearRect(0, 0, 1024, 512);

                    // Latar Belakang Dark Sage Container
                    ctx.fillStyle = '#243329';
                    ctx.fillRect(0, 0, 1024, 512);

                    // Pola Rusuk Halus di Tekstur
                    ctx.strokeStyle = '#1E2B22';
                    ctx.lineWidth = 14;
                    for (let x = 32; x < 1024; x += 32) {
                        ctx.beginPath();
                        ctx.moveTo(x, 20);
                        ctx.lineTo(x, 492);
                        ctx.stroke();
                    }

                    // Aksen Garis Sage Terang di Sisi Bawah
                    ctx.fillStyle = '#9BB59F';
                    ctx.fillRect(40, 440, 944, 8);

                    // Technical Container Markings (Sudut Kanan Atas)
                    ctx.textAlign = 'right';
                    ctx.font = 'bold 30px monospace';
                    ctx.fillStyle = '#FFFFFF';
                    ctx.fillText('TRKV 408291 [7]', 960, 80);

                    ctx.font = 'bold 18px monospace';
                    ctx.fillStyle = '#C8D3BE';
                    ctx.fillText('MAX GROSS  30,480 KG / 67,200 LBS', 960, 115);
                    ctx.fillText('TARE WT     3,850 KG / 8,490 LBS', 960, 140);
                    ctx.fillText('NET PAYLOAD 26,630 KG / 58,710 LBS', 960, 165);
                    ctx.fillText('CARGO CUBE  76.4 CU.M / 2,700 CU.FT', 960, 190);

                    // Spesifikasi Teknis (Sudut Kiri Atas)
                    ctx.textAlign = 'left';
                    ctx.font = 'bold 22px "Plus Jakarta Sans", sans-serif';
                    ctx.fillStyle = '#C8D3BE';
                    ctx.fillText('TRACKIVO LOGISTICS INTELLIGENCE', 60, 75);

                    ctx.font = '16px monospace';
                    ctx.fillStyle = '#8FA392';
                    ctx.fillText('ISO CLASSIFICATION: 45G1 HIGH CUBE', 60, 105);
                    ctx.fillText('SEAL NO: TRKV-GLOBAL-2026', 60, 130);

                    // Logo Trackivo di Area Tengah Dinding
                    if (logoImg && logoImg.complete && logoImg.naturalWidth > 0) {
                        const targetW = 440;
                        const targetH = (logoImg.naturalHeight / logoImg.naturalWidth) * targetW;
                        const drawX = 60;
                        const drawY = 220;
                        ctx.drawImage(logoImg, drawX, drawY, targetW, targetH);
                    } else {
                        // Fallback Teks Putih Bold Jika Gambar Logo Belum Siap
                        ctx.font = '800 68px "Plus Jakarta Sans", sans-serif';
                        ctx.fillStyle = '#FFFFFF';
                        ctx.fillText('Trackivo', 60, 290);

                        ctx.font = '600 20px "Plus Jakarta Sans", sans-serif';
                        ctx.fillStyle = '#9BB59F';
                        ctx.fillText('GLOBAL SHIPMENT VISIBILITY PLATFORM', 64, 330);
                    }
                }

                // Render tekstur pertama kali (fallback text)
                renderSideTexture(null);

                const sideTexture = new THREE.CanvasTexture(textureCanvas);
                sideTexture.anisotropy = 8;
                sideTexture.needsUpdate = true;

                // Load Logo Asli Trackivo
                const brandImg = new Image();
                brandImg.crossOrigin = 'anonymous';
                brandImg.src = "{{ asset('images/logonama.png') }}";
                brandImg.onload = function () {
                    renderSideTexture(brandImg);
                    sideTexture.needsUpdate = true;
                };

                // Plane Decal untuk Sisi Depan (+Z)
                const decalMatFront = new THREE.MeshStandardMaterial({
                    map: sideTexture,
                    roughness: 0.5,
                    metalness: 0.3,
                    polygonOffset: true,
                    polygonOffsetFactor: -1,
                    polygonOffsetUnits: -1
                });
                const decalGeo = new THREE.PlaneGeometry(cW - 0.28, cH - 0.22);

                const frontDecal = new THREE.Mesh(decalGeo, decalMatFront);
                frontDecal.position.set(0, 0, (cD / 2) + 0.022);
                containerGroup.add(frontDecal);

                // Plane Decal untuk Sisi Belakang (-Z)
                const decalMatBack = new THREE.MeshStandardMaterial({
                    map: sideTexture,
                    roughness: 0.5,
                    metalness: 0.3,
                    polygonOffset: true,
                    polygonOffsetFactor: -1,
                    polygonOffsetUnits: -1
                });
                const backDecal = new THREE.Mesh(decalGeo, decalMatBack);
                backDecal.position.set(0, 0, -(cD / 2) - 0.022);
                backDecal.rotation.y = Math.PI;
                containerGroup.add(backDecal);

                // Bayangan Lembut di Bawah Kontainer (Drop Shadow Plane)
                const shadowCanvas = document.createElement('canvas');
                shadowCanvas.width = 256;
                shadowCanvas.height = 256;
                const sCtx = shadowCanvas.getContext('2d');
                const gradient = sCtx.createRadialGradient(128, 128, 20, 128, 128, 120);
                gradient.addColorStop(0, 'rgba(27, 39, 31, 0.45)');
                gradient.addColorStop(0.5, 'rgba(27, 39, 31, 0.2)');
                gradient.addColorStop(1, 'rgba(27, 39, 31, 0)');
                sCtx.fillStyle = gradient;
                sCtx.fillRect(0, 0, 256, 256);

                const shadowTexture = new THREE.CanvasTexture(shadowCanvas);
                const shadowPlane = new THREE.Mesh(
                    new THREE.PlaneGeometry(5.2, 2.8),
                    new THREE.MeshBasicMaterial({
                        map: shadowTexture,
                        transparent: true,
                        opacity: 0.85,
                        depthWrite: false
                    })
                );
                shadowPlane.position.set(0, -(cH / 2) - 0.32, 0);
                shadowPlane.rotation.x = -Math.PI / 2;
                scene.add(shadowPlane);

                // 6. Loop Animasi & Interaksi
                const clock = new THREE.Clock();
                let animationFrameId;

                function animate() {
                    animationFrameId = requestAnimationFrame(animate);

                    const elapsed = clock.getElapsedTime();

                    // Animasi Ayunan / Melayang Mikro Halus (Suspended In Air Effect)
                    containerGroup.position.y = Math.sin(elapsed * 1.1) * 0.045;
                    containerGroup.rotation.z = Math.sin(elapsed * 0.75) * 0.012;
                    containerGroup.rotation.x = Math.cos(elapsed * 0.65) * 0.008;

                    // Update OrbitControls
                    controls.update();

                    renderer.render(scene, camera);
                }

                animate();

                // 7. Penanganan Responsif Ukuran Layar (ResizeObserver)
                const resizeObserver = new ResizeObserver(() => {
                    if (!parent || parent.clientWidth === 0 || parent.clientHeight === 0) return;
                    camera.aspect = parent.clientWidth / parent.clientHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(parent.clientWidth, parent.clientHeight);
                });
                resizeObserver.observe(parent);

            } catch (err) {
                console.warn('Three.js Cargo 3D initialization fallback:', err);
                const fallback = document.getElementById('cargo-fallback');
                if (fallback) fallback.classList.remove('hidden');
            }
        })();
    </script>

</body>
</html>

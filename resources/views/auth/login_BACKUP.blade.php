<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Llantas Económicas Chalco</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-screen overflow-hidden">

    <div class="flex h-full" x-data="{ showPass: false }">

        {{-- ════════ IZQUIERDA: Imagen + Texto ════════ --}}
        <div class="hidden lg:flex w-1/2 relative overflow-hidden">
            <img src="{{ asset('img/fondo-local.jpg') }}"
                alt="Llantas Económicas Chalco" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/60 to-black/90"></div>
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-red-600 via-red-500 to-red-600"></div>

            <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-sm font-semibold text-gray-200 tracking-widest uppercase">Llantas Económicas Chalco</span>
                </div>

                <div class="max-w-lg">
                    <h1 class="text-5xl font-black text-white leading-tight mb-4 tracking-tight">
                        Sistema Integral de
                        <span class="text-red-500">Gestión</span>
                    </h1>
                    <p class="text-lg text-gray-300 leading-relaxed">
                        Control total de inventario, punto de venta y administración desde cualquier sucursal.
                    </p>
                    <div class="flex items-center gap-6 mt-8">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center ring-1 ring-white/10">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-400">5 sucursales</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center ring-1 ring-white/10">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-400">Tiempo real</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center ring-1 ring-white/10">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-400">Seguro</span>
                        </div>
                    </div>
                </div>

                <div class="text-xs text-gray-500">
                    © 2026 Llantas Económicas Chalco SA de CV
                </div>
            </div>
        </div>

        {{-- ════════ DERECHA: Formulario ════════ --}}
        <div class="w-full lg:w-1/2 flex flex-col bg-white">
            <div class="h-1.5 bg-gradient-to-r from-emerald-500 via-emerald-400 to-emerald-500"></div>

            <div class="flex-1 flex items-center justify-center px-8 lg:px-16 xl:px-24">
                <div class="w-full max-w-md">

                    <div class="lg:hidden flex items-center gap-3 mb-10">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                        <span class="text-sm font-semibold text-gray-500 tracking-widest uppercase">Llantas Económicas Chalco</span>
                    </div>

                    <div class="mb-10">
                        <h2 class="text-3xl font-bold text-gray-900">Bienvenido de vuelta</h2>
                        <p class="text-gray-400 mt-2">Ingresa tus credenciales para acceder a tu sucursal</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Usuario</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="text" name="usuario" value="{{ old('usuario') }}" required autofocus
                                    placeholder="admin o chalco"
                                    class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:bg-white transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Contraseña</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input :type="showPass ? 'text' : 'password'" name="password" required
                                    placeholder="••••••••"
                                    class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:bg-white transition">
                                <button type="button" @click="showPass = !showPass"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition">
                                    <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="showPass" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-gray-500">Mantener sesión iniciada</span>
                            </label>
                        </div>

                        @error('usuario')
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror

                        <button type="submit"
                            class="w-full py-4 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white rounded-xl font-bold text-base transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/30 mt-2 active:scale-[0.98]">
                            Iniciar Sesión
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>

                    <div class="mt-10 pt-8 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Accesos rápidos de prueba</p>

                        <button type="button" onclick="llenar('admin', 'password')"
                            class="w-full flex items-center gap-4 px-4 py-3 rounded-xl border border-gray-200 hover:bg-emerald-50 hover:border-emerald-300 hover:shadow-sm transition mb-3 text-left">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800 text-sm">Administración General</div>
                                <div class="text-xs text-gray-400">Acceso completo a todo el sistema</div>
                            </div>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full uppercase tracking-wide">Admin</span>
                        </button>

                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['Chalco' => 'chalco', 'Atlanta' => 'atlanta', 'Las Torres' => 'lastorres', 'Valle de Chalco' => 'valle'] as $nombre => $user)
                                <button type="button" onclick="llenar('{{ $user }}', 'password')"
                                    class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl border border-gray-200 text-sm text-gray-600 font-medium hover:bg-gray-50 hover:border-gray-300 hover:shadow-sm transition text-left">
                                    <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $nombre }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <p class="text-center text-xs text-gray-400 mt-8">
                        Protegido por Llantas Económicas © 2026
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function llenar(login, pass) {
            document.querySelector('input[name="usuario"]').value = login;
            document.querySelector('input[name="password"]').value = pass;
        }
    </script>
</body>
</html>
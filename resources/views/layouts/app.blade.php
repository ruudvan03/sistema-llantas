<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llantas Económicas Chalco</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .nav-scroll::-webkit-scrollbar { width: 0; }
    </style>
</head>
<body class="bg-[#F5F5F7] flex h-screen overflow-hidden text-gray-800">

    <aside class="w-[250px] bg-black flex flex-col shrink-0">

        {{-- Logo (sin recuadro, fundido con el sidebar negro puro) --}}
        <div class="px-6 pt-6 pb-4 flex items-center justify-center">
            <img src="{{ asset('img/logo-llantas.png') }}" alt="Llantas Económicas Chalco" class="w-full max-w-[190px] h-auto object-contain">
        </div>

        {{-- Usuario --}}
        <div class="px-4 pb-4">
            <div class="flex items-center gap-3 px-2">
                <div class="w-9 h-9 rounded-full bg-[#D32030] text-white flex items-center justify-center font-bold text-sm shrink-0">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'Usuario' }}</p>
                    <p class="text-[10px] text-[#D32030] uppercase tracking-wider font-bold">{{ Auth::user()->rol ?? 'Admin' }}</p>
                </div>
            </div>
        </div>

        <div class="mx-4 h-px bg-white/5"></div>

        {{-- Navegación --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto nav-scroll">
            @php
                $links = [
                    ['route' => 'dashboard', 'check' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'ventas.index', 'check' => 'ventas.index', 'label' => 'Punto de Venta', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['route' => 'ventas.historial', 'check' => 'ventas.historial', 'label' => 'Historial de Ventas', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                    ['route' => 'gastos.index', 'check' => 'gastos.*', 'label' => 'Corte de Caja', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['route' => 'inventario.index', 'check' => 'inventario.index', 'label' => 'Inventario', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                    ['route' => 'inventario.historial', 'check' => 'inventario.historial', 'label' => 'Historial Inventario', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['route' => 'clientes.index', 'check' => 'clientes.*', 'label' => 'Clientes', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['route' => 'reportes.index', 'check' => 'reportes.*', 'label' => 'Reportes', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ];
            @endphp

            @foreach($links as $link)
                @php $activo = request()->routeIs($link['check']); @endphp
                <a href="{{ route($link['route']) }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-medium transition-all
                   {{ $activo ? 'bg-[#D32030] text-white' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                    </svg>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="px-3 py-3 border-t border-white/5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-medium text-gray-600 hover:text-red-400 hover:bg-white/5 transition-all">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-14 flex items-center justify-between px-8 bg-white border-b border-gray-200/80 shrink-0">
            <h1 class="text-[15px] font-bold text-gray-800">@yield('header_title', 'Panel de Control')</h1>
            <span class="text-xs text-gray-400 font-medium">{{ now()->translatedFormat('d \d\e F, Y') }}</span>
        </header>
        <div class="flex-1 overflow-y-auto p-8 bg-[#F5F5F7]">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
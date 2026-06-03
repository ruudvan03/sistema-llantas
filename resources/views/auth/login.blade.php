<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Llantas Económicas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-image {
            background-image: url('https://images.unsplash.com/photo-1580274455191-1c62238fa333?q=80&w=1964&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gray-100 flex h-screen w-full overflow-hidden">

    <div class="hidden lg:flex lg:w-1/2 bg-image relative items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/70 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
        
        <div class="relative z-10 px-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-emerald-500 mb-6 shadow-2xl shadow-emerald-500/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h1 class="text-5xl font-bold text-white mb-4 tracking-tight">Llantas Económicas</h1>
            <p class="text-lg text-gray-300 font-medium max-w-md mx-auto">
                Sistema Integral de Inventario y Punto de Venta. Control total desde cualquier sucursal.
            </p>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 p-6 sm:p-12">
        <div class="w-full max-w-md bg-white p-10 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>

            <div class="mb-10 text-center">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Bienvenido de vuelta</h2>
                <p class="text-sm text-gray-500 mt-2">Ingresa tus credenciales para acceder a tu sucursal</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl text-gray-900 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:bg-white transition-all sm:text-sm"
                            placeholder="admin@llantas.com">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Contraseña</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-500 transition-colors">¿Olvidaste tu contraseña?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl text-gray-900 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent focus:bg-white transition-all sm:text-sm"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded cursor-pointer">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer">
                        Mantener sesión iniciada
                    </label>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform hover:-translate-y-0.5">
                        Iniciar Sesión
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400">Protegido por Llantas Económicas &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
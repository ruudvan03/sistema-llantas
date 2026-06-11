<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar Sesión | Llantas Económicas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      @keyframes float {
        0%,
        100% {
          transform: translateY(0px) rotate(0deg);
        }
        33% {
          transform: translateY(-20px) rotate(5deg);
        }
        66% {
          transform: translateY(10px) rotate(-3deg);
        }
      }

      @keyframes float-reverse {
        0%,
        100% {
          transform: translateY(0px) rotate(0deg);
        }
        33% {
          transform: translateY(15px) rotate(-5deg);
        }
        66% {
          transform: translateY(-25px) rotate(3deg);
        }
      }

      @keyframes blob {
        0%,
        100% {
          border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        }
        25% {
          border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
        }
        50% {
          border-radius: 50% 60% 30% 60% / 30% 60% 70% 40%;
        }
        75% {
          border-radius: 60% 40% 60% 30% / 60% 40% 30% 70%;
        }
      }

      @keyframes gradient-shift {
        0% {
          background-position: 0% 50%;
        }
        50% {
          background-position: 100% 50%;
        }
        100% {
          background-position: 0% 50%;
        }
      }

      @keyframes slide-up {
        from {
          opacity: 0;
          transform: translateY(40px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      @keyframes fade-in {
        from {
          opacity: 0;
        }
        to {
          opacity: 1;
        }
      }

      @keyframes pulse-ring {
        0% {
          transform: scale(0.8);
          opacity: 1;
        }
        100% {
          transform: scale(2.5);
          opacity: 0;
        }
      }

      @keyframes wiggle {
        0%,
        100% {
          transform: rotate(0deg);
        }
        25% {
          transform: rotate(3deg);
        }
        75% {
          transform: rotate(-3deg);
        }
      }

      @keyframes sparkle {
        0%,
        100% {
          opacity: 0;
          transform: scale(0) rotate(0deg);
        }
        50% {
          opacity: 1;
          transform: scale(1) rotate(180deg);
        }
      }

      @keyframes bounce-in {
        0% {
          transform: scale(0.3);
          opacity: 0;
        }
        50% {
          transform: scale(1.05);
        }
        70% {
          transform: scale(0.9);
        }
        100% {
          transform: scale(1);
          opacity: 1;
        }
      }

      @keyframes shimmer {
        0% {
          transform: translateX(-100%);
        }
        100% {
          transform: translateX(100%);
        }
      }

      @keyframes morph {
        0%,
        100% {
          border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%;
        }
        34% {
          border-radius: 70% 30% 46% 54% / 30% 29% 71% 70%;
        }
        67% {
          border-radius: 100% 60% 60% 100% / 100% 100% 60% 60%;
        }
      }

      @keyframes rotate-slow {
        from {
          transform: rotate(0deg);
        }
        to {
          transform: rotate(360deg);
        }
      }

      @keyframes wave {
        0%,
        100% {
          transform: translateY(0) scaleY(1);
        }
        50% {
          transform: translateY(-15px) scaleY(1.1);
        }
      }

      .animate-float {
        animation: float 6s ease-in-out infinite;
      }
      .animate-float-reverse {
        animation: float-reverse 7s ease-in-out infinite;
      }
      .animate-blob {
        animation: blob 8s ease-in-out infinite;
      }
      .animate-gradient {
        background-size: 300% 300%;
        animation: gradient-shift 8s ease infinite;
      }
      .animate-slide-up {
        animation: slide-up 0.8s ease-out forwards;
      }
      .animate-fade-in {
        animation: fade-in 1s ease-out forwards;
      }
      .animate-pulse-ring {
        animation: pulse-ring 2s ease-out infinite;
      }
      .animate-wiggle {
        animation: wiggle 2s ease-in-out infinite;
      }
      .animate-sparkle {
        animation: sparkle 3s ease-in-out infinite;
      }
      .animate-bounce-in {
        animation: bounce-in 0.8s ease-out forwards;
      }
      .animate-morph {
        animation: morph 8s ease-in-out infinite;
      }
      .animate-rotate-slow {
        animation: rotate-slow 20s linear infinite;
      }
      .animate-wave {
        animation: wave 3s ease-in-out infinite;
      }

      .delay-100 { animation-delay: 0.1s; }
      .delay-200 { animation-delay: 0.2s; }
      .delay-300 { animation-delay: 0.3s; }
      .delay-400 { animation-delay: 0.4s; }
      .delay-500 { animation-delay: 0.5s; }
      .delay-700 { animation-delay: 0.7s; }
      .delay-1000 { animation-delay: 1s; }
      .delay-1500 { animation-delay: 1.5s; }
      .delay-2000 { animation-delay: 2s; }
      .delay-2500 { animation-delay: 2.5s; }

      .glass {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
      }

      .input-focus-effect {
        transition: all 0.3s ease;
      }

      .input-focus-effect:focus {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(251, 146, 60, 0.15);
      }

      .btn-shimmer {
        position: relative;
        overflow: hidden;
      }

      .btn-shimmer::after {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
          to right,
          transparent 0%,
          rgba(255, 255, 255, 0.3) 50%,
          transparent 100%
        );
        transform: rotate(30deg);
        animation: shimmer 3s infinite;
      }

      .floating-icon {
        transition: all 0.3s ease;
      }

      .floating-icon:hover {
        transform: translateY(-5px) scale(1.1);
      }

      .card-hover {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      }

      .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 60px rgba(251, 146, 60, 0.12);
      }

      ::-webkit-scrollbar { width: 6px; }
      ::-webkit-scrollbar-track { background: #fff7ed; }
      ::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 3px; }

      .particle {
        position: absolute;
        pointer-events: none;
      }

      .wavy-underline {
        text-decoration-style: wavy;
        text-underline-offset: 4px;
      }
    </style>
  </head>
  <body class="min-h-screen relative" style="background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 25%, #fce7f3 50%, #ede9fe 75%, #e0f2fe 100%);">
    
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-20 -left-20 w-72 h-72 bg-gradient-to-br from-orange-200 to-amber-200 opacity-50 animate-blob animate-float" style="animation-delay: 0s"></div>
      <div class="absolute top-1/3 -right-16 w-64 h-64 bg-gradient-to-br from-pink-200 to-rose-200 opacity-40 animate-blob animate-float-reverse" style="animation-delay: 2s"></div>
      <div class="absolute -bottom-20 left-1/4 w-80 h-80 bg-gradient-to-br from-violet-200 to-purple-200 opacity-40 animate-blob" style="animation-delay: 4s"></div>
      <div class="absolute top-10 right-1/3 w-48 h-48 bg-gradient-to-br from-sky-200 to-cyan-200 opacity-40 animate-morph animate-float" style="animation-delay: 1s"></div>
      <div class="absolute bottom-1/4 right-10 w-56 h-56 bg-gradient-to-br from-emerald-200 to-teal-100 opacity-30 animate-blob animate-float-reverse" style="animation-delay: 3s"></div>

      <div class="particle animate-float" style="top: 15%; left: 10%; animation-delay: 0.5s">
        <svg width="40" height="40" viewBox="0 0 40 40" class="animate-rotate-slow">
          <circle cx="20" cy="20" r="15" fill="none" stroke="#fb923c" stroke-width="2" opacity="0.3" />
        </svg>
      </div>
      <div class="particle animate-float-reverse" style="top: 25%; right: 15%; animation-delay: 1.2s">
        <svg width="30" height="30" viewBox="0 0 30 30" class="animate-wiggle">
          <rect x="5" y="5" width="20" height="20" rx="4" fill="none" stroke="#f472b6" stroke-width="2" opacity="0.3" transform="rotate(45 15 15)" />
        </svg>
      </div>
      <div class="particle animate-float" style="top: 70%; left: 8%; animation-delay: 2s">
        <svg width="35" height="35" viewBox="0 0 35 35">
          <polygon points="17.5,2 33,30 2,30" fill="none" stroke="#a78bfa" stroke-width="2" opacity="0.3" />
        </svg>
      </div>
      <div class="particle animate-float-reverse" style="bottom: 20%; right: 8%; animation-delay: 0.8s">
        <svg width="25" height="25" viewBox="0 0 25 25" class="animate-rotate-slow" style="animation-duration: 15s">
          <path d="M12.5 2 L15.5 9.5 L23 12.5 L15.5 15.5 L12.5 23 L9.5 15.5 L2 12.5 L9.5 9.5 Z" fill="none" stroke="#fb923c" stroke-width="1.5" opacity="0.4" />
        </svg>
      </div>
      <div class="particle animate-float" style="top: 50%; left: 5%; animation-delay: 3s">
        <svg width="20" height="20" viewBox="0 0 20 20">
          <circle cx="10" cy="10" r="8" fill="#fbbf24" opacity="0.2" />
        </svg>
      </div>
      <div class="particle animate-float-reverse" style="top: 10%; left: 45%; animation-delay: 1.5s">
        <svg width="28" height="28" viewBox="0 0 28 28" class="animate-wiggle" style="animation-delay: 1s">
          <path d="M14 2 L17 11 L26 14 L17 17 L14 26 L11 17 L2 14 L11 11 Z" fill="#f9a8d4" opacity="0.3" />
        </svg>
      </div>

      <div class="particle w-2 h-2 rounded-full bg-orange-300 opacity-60 animate-sparkle" style="top: 20%; left: 30%"></div>
      <div class="particle w-1.5 h-1.5 rounded-full bg-pink-300 opacity-60 animate-sparkle delay-700" style="top: 40%; right: 25%"></div>
      <div class="particle w-2 h-2 rounded-full bg-violet-300 opacity-60 animate-sparkle delay-1500" style="bottom: 30%; left: 20%"></div>
      <div class="particle w-1 h-1 rounded-full bg-amber-400 opacity-60 animate-sparkle delay-2000" style="top: 60%; right: 35%"></div>
      <div class="particle w-1.5 h-1.5 rounded-full bg-rose-300 opacity-50 animate-sparkle delay-2500" style="top: 80%; left: 60%"></div>
      <div class="particle w-2 h-2 rounded-full bg-sky-300 opacity-50 animate-sparkle delay-1000" style="top: 12%; right: 40%"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
      <div class="w-full max-w-md">
        
        <div class="text-center mb-8 animate-bounce-in">
          <div class="inline-flex items-center justify-center relative mb-4">
            <div class="absolute w-20 h-20 rounded-full border-2 border-orange-300 opacity-30 animate-pulse-ring"></div>
            <div class="absolute w-20 h-20 rounded-full border-2 border-orange-300 opacity-20 animate-pulse-ring delay-500"></div>

            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-400 via-amber-400 to-yellow-400 animate-gradient flex items-center justify-center shadow-lg shadow-orange-200 animate-wiggle" style="animation-duration: 3s">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
          </div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-500 via-pink-500 to-violet-500 bg-clip-text text-transparent animate-gradient" style="background-size: 200% auto">
            Llantas Económicas
          </h1>
          <p class="text-sm text-amber-600/70 mt-2 animate-fade-in delay-300" style="opacity: 0">
            Ingresa tus credenciales para acceder a tu sucursal ✨
          </p>
        </div>

        <div class="glass rounded-3xl shadow-xl shadow-orange-100/50 border border-white/60 p-8 card-hover animate-slide-up" style="opacity: 0; animation-delay: 0.2s">

          <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="animate-slide-up delay-300" style="opacity: 0">
              <label class="block text-sm font-semibold text-amber-700 mb-2 ml-1">
                <span class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  Nombre de Usuario
                </span>
              </label>
              <div class="relative group">
                <input type="text" name="usuario" value="{{ old('usuario') }}" required autofocus placeholder="ej: admin" class="input-focus-effect w-full px-5 py-3.5 rounded-2xl bg-white/80 border-2 border-orange-100 focus:border-orange-400 focus:outline-none text-amber-800 placeholder-amber-300 text-sm font-medium transition-all duration-300 hover:border-orange-200" />
              </div>
              @error('usuario')
                  <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
              @enderror
            </div>

            <div class="animate-slide-up delay-400" style="opacity: 0">
              <label class="block text-sm font-semibold text-amber-700 mb-2 ml-1">
                <span class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                  Contraseña
                </span>
              </label>
              <div class="relative group">
                <input type="password" name="password" id="passwordInput" required placeholder="••••••••" class="input-focus-effect w-full px-5 py-3.5 rounded-2xl bg-white/80 border-2 border-orange-100 focus:border-orange-400 focus:outline-none text-amber-800 placeholder-amber-300 text-sm font-medium transition-all duration-300 hover:border-orange-200" />
                <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-amber-400 hover:text-orange-500 transition-colors duration-300">
                  <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                  </svg>
                </button>
              </div>
              @error('password')
                  <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
              @enderror
            </div>

            <div class="flex items-center justify-between animate-fade-in delay-500" style="opacity: 0; padding-top: 5px;">
              <label class="flex items-center gap-2.5 cursor-pointer group">
                <div class="relative">
                  <input type="checkbox" name="remember" id="remember_me" class="sr-only peer" />
                  <div class="w-5 h-5 rounded-lg border-2 border-orange-200 bg-white/80 peer-checked:bg-gradient-to-br peer-checked:from-orange-400 peer-checked:to-amber-400 peer-checked:border-orange-400 transition-all duration-300 flex items-center justify-center group-hover:border-orange-300"></div>
                  <svg class="absolute top-0.5 left-0.5 w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-300 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <span class="text-sm text-amber-600 group-hover:text-amber-700 transition-colors">Mantener sesión</span>
              </label>
              
              @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="text-sm font-semibold text-orange-500 hover:text-orange-600 transition-colors duration-300 relative group">
                ¿Olvidaste tu contraseña?
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-orange-400 to-amber-400 group-hover:w-full transition-all duration-300 rounded-full"></span>
              </a>
              @endif
            </div>

            <div class="animate-slide-up delay-500" style="opacity: 0; padding-top: 10px;">
              <button type="submit" class="btn-shimmer w-full py-4 rounded-2xl bg-gradient-to-r from-orange-400 via-amber-400 to-yellow-400 animate-gradient text-white font-bold text-sm tracking-wide shadow-lg shadow-orange-200 hover:shadow-xl hover:shadow-orange-300 hover:-translate-y-1 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-2 group">
                <span>Iniciar Sesión</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </button>
            </div>
          </form>

        </div>

        <div class="text-center mt-6 animate-fade-in delay-700" style="opacity: 0">
          <p class="text-xs text-amber-500 mt-2 tracking-wider">
            Protegido por Llantas Económicas &copy; {{ date('Y') }}
          </p>
        </div>
      </div>
    </div>

    <canvas id="trailCanvas" class="fixed inset-0 pointer-events-none z-50"></canvas>

    <script>
      // Password toggle
      function togglePassword() {
        const input = document.getElementById("passwordInput");
        const eyeIcon = document.getElementById("eyeIcon");
        const eyeOffIcon = document.getElementById("eyeOffIcon");

        if (input.type === "password") {
          input.type = "text";
          eyeIcon.classList.add("hidden");
          eyeOffIcon.classList.remove("hidden");
        } else {
          input.type = "password";
          eyeIcon.classList.remove("hidden");
          eyeOffIcon.classList.add("hidden");
        }
      }

      // Cursor trail effect
      const canvas = document.getElementById("trailCanvas");
      const ctx = canvas.getContext("2d");
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;

      window.addEventListener("resize", () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
      });

      const particles = [];
      const colors = ["#fb923c", "#fbbf24", "#f472b6", "#a78bfa", "#38bdf8"];

      class Particle {
        constructor(x, y) {
          this.x = x;
          this.y = y;
          this.size = Math.random() * 5 + 2;
          this.speedX = Math.random() * 2 - 1;
          this.speedY = Math.random() * 2 - 1;
          this.color = colors[Math.floor(Math.random() * colors.length)];
          this.life = 1;
          this.decay = Math.random() * 0.02 + 0.02;
        }

        update() {
          this.x += this.speedX;
          this.y += this.speedY;
          this.life -= this.decay;
          this.size *= 0.97;
        }

        draw() {
          ctx.save();
          ctx.globalAlpha = this.life;
          ctx.fillStyle = this.color;
          ctx.beginPath();
          ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
          ctx.fill();
          ctx.restore();
        }
      }

      let mouseX = 0, mouseY = 0;
      let isMoving = false;
      let moveTimeout;

      document.addEventListener("mousemove", (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        isMoving = true;

        for (let i = 0; i < 2; i++) {
          particles.push(new Particle(mouseX, mouseY));
        }

        clearTimeout(moveTimeout);
        moveTimeout = setTimeout(() => {
          isMoving = false;
        }, 100);
      });

      function animateTrail() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (let i = particles.length - 1; i >= 0; i--) {
          particles[i].update();
          particles[i].draw();

          if (particles[i].life <= 0 || particles[i].size <= 0.5) {
            particles.splice(i, 1);
          }
        }

        requestAnimationFrame(animateTrail);
      }

      animateTrail();

      // Input animation on focus
      document.querySelectorAll("input").forEach((input) => {
        input.addEventListener("focus", function () {
          this.parentElement.classList.add("scale-[1.02]");
          this.parentElement.style.transition = "transform 0.3s ease";
        });
        input.addEventListener("blur", function () {
          this.parentElement.classList.remove("scale-[1.02]");
        });
      });

      // Button ripple effect
      document.querySelector('button[type="submit"]').addEventListener("click", function (e) {
        // Permitimos que el formulario se envíe sin el preventDefault
        const btn = this;
        btn.style.transform = "scale(0.95)";
        
        setTimeout(() => {
          btn.style.transform = "scale(1) translateY(-4px)";
        }, 150);

        const rect = btn.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        for (let i = 0; i < 30; i++) {
          const p = new Particle(centerX, centerY);
          p.speedX = (Math.random() - 0.5) * 8;
          p.speedY = (Math.random() - 0.5) * 8;
          p.size = Math.random() * 6 + 3;
          particles.push(p);
        }
      });
    </script>
  </body>
</html>
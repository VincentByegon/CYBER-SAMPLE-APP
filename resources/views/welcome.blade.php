<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | BigTunes Cyber</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #F53003 0%, #FF4433 100%);
        }
        .hero-clip {
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }
    </style>
</head>
<body class="bg-white dark:bg-gray-900 min-h-screen flex flex-col">
    <nav class="w-full px-6 py-4 flex justify-between items-center bg-white dark:bg-gray-900 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="text-2xl font-bold text-[#1a237e] dark:text-[#90caf9] tracking-tight">BigTunes Cyber</span>
        </div>
        <div class="flex gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm px-4 py-2 rounded-md gradient-bg text-white font-semibold shadow hover:scale-105 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-semibold shadow hover:bg-gray-200 dark:hover:bg-gray-700 hover:scale-105 transition">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm px-4 py-2 rounded-md gradient-bg text-white font-semibold shadow hover:scale-105 transition">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>
    <main class="flex-1 flex flex-col items-center justify-center px-4 py-0">
        <section class="w-full relative overflow-hidden">
            <div class="absolute inset-0 hero-clip gradient-bg opacity-80 pointer-events-none"></div>
            <div class="relative z-10 max-w-3xl mx-auto text-center py-20">
                <h1 class="text-5xl md:text-6xl font-extrabold mb-6 text-white drop-shadow-lg tracking-tight">Welcome to <span class="bg-white/20 px-3 py-1 rounded-xl text-[#1a237e] dark:text-[#90caf9]">BigTunes Cyber</span></h1>
                <p class="text-xl md:text-2xl text-white/90 mb-8 font-medium">Kericho's trusted cyber hub along the Nairobi-Kisumu Highway.<br>Internet, printing, digital solutions & more.</p>
                <a href="{{ url('/dashboard') }}" class="inline-block px-10 py-4 rounded-full bg-white text-[#F53003] font-bold text-xl shadow-lg hover:bg-gray-100 hover:scale-105 transition">Get Started</a>
            </div>
        </section>
        <section class="mt-[-4rem] grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-5xl z-20 relative">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-10 flex flex-col items-center hover:scale-105 transition">
                <i class="fas fa-desktop text-4xl text-[#F53003] dark:text-[#FF4433] mb-4"></i>
                <h2 class="font-semibold text-2xl mb-2 text-gray-900 dark:text-white">Cyber Services</h2>
                <p class="text-gray-600 dark:text-gray-300 text-center">Internet access, printing, scanning, KRA, eCitizen, and digital solutions for everyone.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-10 flex flex-col items-center hover:scale-105 transition">
                <i class="fas fa-shield-alt text-4xl text-[#F53003] dark:text-[#FF4433] mb-4"></i>
                <h2 class="font-semibold text-2xl mb-2 text-gray-900 dark:text-white">Trusted & Secure</h2>
                <p class="text-gray-600 dark:text-gray-300 text-center">Your privacy and data security are our top priority. Professional, friendly staff always ready to help.</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-10 flex flex-col items-center hover:scale-105 transition">
                <i class="fas fa-map-marker-alt text-4xl text-[#F53003] dark:text-[#FF4433] mb-4"></i>
                <h2 class="font-semibold text-2xl mb-2 text-gray-900 dark:text-white">Prime Location</h2>
                <p class="text-gray-600 dark:text-gray-300 text-center">Find us in Kericho, easily accessible along the Nairobi-Kisumu Highway. Ample parking & fast service.</p>
            </div>
        </section>
        <section class="mt-20 w-full max-w-2xl mx-auto text-center">
            <h3 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">How to Get Started</h3>
            <ol class="list-decimal list-inside text-left mx-auto text-gray-700 dark:text-gray-200 text-lg mb-8 max-w-md">
                <li>Register or log in to your account.</li>
                <li>Explore our cyber services and solutions.</li>
                <li>Visit us in Kericho or contact us for more info!</li>
            </ol>
            <div class="flex justify-center gap-8">
                <a href="https://laravel.com/docs" target="_blank" class="text-[#F53003] dark:text-[#FF4433] font-semibold underline underline-offset-4 hover:text-[#d12a00]">Laravel Docs</a>
                <a href="https://laracasts.com" target="_blank" class="text-[#F53003] dark:text-[#FF4433] font-semibold underline underline-offset-4 hover:text-[#d12a00]">Laracasts</a>
            </div>
        </section>
        <section class="mt-20 w-full max-w-4xl mx-auto text-center">
            <div class="bg-gradient-to-r from-[#F53003] to-[#FF4433] rounded-2xl p-10 shadow-xl text-white">
                <h3 class="text-2xl md:text-3xl font-bold mb-4">Why Choose BigTunes Cyber?</h3>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-6 text-lg">
                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-white"></i> Fast, reliable internet & printing</li>
                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-white"></i> Secure document handling</li>
                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-white"></i> Friendly, professional staff</li>
                    <li class="flex items-center gap-3"><i class="fas fa-check-circle text-white"></i> Convenient location & parking</li>
                </ul>
            </div>
        </section>
    </main>
    <footer class="w-full py-8 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 border-t dark:border-gray-800 mt-16">
        <div class="mb-2 text-lg font-semibold text-[#1a237e] dark:text-[#90caf9]">BigTunes Cyber &mdash; Kericho, Nairobi-Kisumu Highway</div>
        <div>&copy; {{ date('Y') }} BigTunes Cyber. Serving Kericho and Kenya with trusted cyber services.</div>
    </footer>
</body>
</html>

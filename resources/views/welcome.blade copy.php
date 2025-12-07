<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5E3AFE',
                        secondary: '#5BD7E5',
                        accent: '#F2F4F6',
                        dark: '#0D1B2A',
                    },
                    fontFamily: {
                        sans: ['DM Sans', 'ui-sans-serif', 'system-ui'],
                    },
                    boxShadow: {
                        soft: '0 20px 50px -20px rgba(15, 23, 42, 0.35)',
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-white text-slate-900 antialiased">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-cyan-50"></div>
        <div class="absolute -left-32 -top-20 h-72 w-72 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="absolute -right-24 top-10 h-80 w-80 rounded-full bg-secondary/10 blur-3xl"></div>

        <header class="relative z-10">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-6">
                <a href="#" class="flex items-center gap-2 text-xl font-semibold text-primary">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-primary text-white">P</span>
                    <span>Pavo</span>
                </a>
                <nav class="hidden items-center gap-8 text-sm font-medium text-slate-700 lg:flex">
                    <a href="#about" class="hover:text-primary">About</a>
                    <a href="#features" class="hover:text-primary">Features</a>
                    <a href="#video" class="hover:text-primary">Video</a>
                    <a href="#pricing" class="hover:text-primary">Pricing</a>
                    <a href="#faq" class="hover:text-primary">FAQ</a>
                </nav>
                <div class="hidden items-center gap-3 lg:flex">
                    <a href="/login" class="text-sm font-semibold text-slate-800 hover:text-primary">Log In</a>
                    <a href="/register" class="rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-primary/30 transition hover:-translate-y-0.5 hover:bg-indigo-600">Sign Up</a>
                </div>
                <button class="lg:hidden rounded-full border border-slate-200 p-2 text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
        </header>

        <main class="relative z-10">
            <section class="mx-auto grid max-w-6xl items-center gap-12 px-6 pb-16 pt-10 lg:grid-cols-2 lg:pb-24 lg:pt-16">
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary">Launch faster with Pavo</span>
                    <h1 class="text-4xl font-bold leading-tight text-slate-900 sm:text-5xl">Landing page for your startup with reusable Tailwind blocks</h1>
                    <p class="text-lg text-slate-600">Crafted to match the Pavo layout, this page gives you hero, feature, video, pricing, and FAQ sections ready to plug into your product story.</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="/register" class="rounded-full bg-primary px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/30 transition hover:-translate-y-0.5 hover:bg-indigo-600">Get Started</a>
                        <a href="#video" class="rounded-full border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-800 transition hover:-translate-y-0.5 hover:border-primary hover:text-primary">Watch Demo</a>
                    </div>
                    <div class="flex flex-wrap items-center gap-6 pt-4">
                        <div class="flex items-center gap-2 text-sm text-slate-700"><span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary">5k</span>Customers</div>
                        <div class="flex items-center gap-2 text-sm text-slate-700"><span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-secondary/10 text-cyan-600">24/7</span>Support</div>
                        <div class="flex items-center gap-2 text-sm text-slate-700"><span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">99.9%</span>Uptime</div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -left-10 -top-10 h-24 w-24 rounded-full bg-primary/20 blur-2xl"></div>
                    <div class="absolute -right-6 bottom-4 h-24 w-24 rounded-full bg-secondary/30 blur-2xl"></div>
                    <div class="overflow-hidden rounded-3xl shadow-2xl shadow-indigo-100 ring-1 ring-slate-100">
                        <img src="https://themewagon.github.io/pavo/images/header-teamwork.svg" alt="Hero illustration" class="w-full">
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-6xl px-6 pb-14" id="about">
                <p class="text-center text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Trusted by teams worldwide</p>
                <div class="mt-6 grid grid-cols-2 items-center gap-6 opacity-80 sm:grid-cols-3 md:grid-cols-6">
                    <img src="https://themewagon.github.io/pavo/images/customer-logo-1.png" class="mx-auto h-8" alt="Logo 1">
                    <img src="https://themewagon.github.io/pavo/images/customer-logo-2.png" class="mx-auto h-8" alt="Logo 2">
                    <img src="https://themewagon.github.io/pavo/images/customer-logo-3.png" class="mx-auto h-8" alt="Logo 3">
                    <img src="https://themewagon.github.io/pavo/images/customer-logo-4.png" class="mx-auto h-8" alt="Logo 4">
                    <img src="https://themewagon.github.io/pavo/images/customer-logo-5.png" class="mx-auto h-8" alt="Logo 5">
                    <img src="https://themewagon.github.io/pavo/images/customer-logo-6.png" class="mx-auto h-8" alt="Logo 6">
                </div>
            </section>

            <section class="bg-white" id="features">
                <div class="mx-auto max-w-6xl px-6 py-16">
                    <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                        <div class="space-y-4">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Feature Pack</p>
                            <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl">Build polished sections without starting from scratch</h2>
                            <p class="text-lg text-slate-600">Sections mirror the Pavo layout: modular cards, clean typography, and gentle gradients to keep your story cohesive.</p>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl bg-white p-4 shadow-soft ring-1 ring-slate-100">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">1</div>
                                    <h3 class="mt-3 text-lg font-semibold">Hero + Social Proof</h3>
                                    <p class="text-sm text-slate-600">Attention-grabbing hero with logos to boost trust.</p>
                                </div>
                                <div class="rounded-2xl bg-white p-4 shadow-soft ring-1 ring-slate-100">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-secondary/10 text-cyan-600">2</div>
                                    <h3 class="mt-3 text-lg font-semibold">Feature Highlights</h3>
                                    <p class="text-sm text-slate-600">Show product strengths with icon-led cards.</p>
                                </div>
                                <div class="rounded-2xl bg-white p-4 shadow-soft ring-1 ring-slate-100">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">3</div>
                                    <h3 class="mt-3 text-lg font-semibold">Video Spotlight</h3>
                                    <p class="text-sm text-slate-600">Embed a demo or promo clip in seconds.</p>
                                </div>
                                <div class="rounded-2xl bg-white p-4 shadow-soft ring-1 ring-slate-100">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600">4</div>
                                    <h3 class="mt-3 text-lg font-semibold">Pricing & FAQ</h3>
                                    <p class="text-sm text-slate-600">Conversion-ready plans and answers.</p>
                                </div>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="absolute -left-8 -top-8 h-20 w-20 rounded-full bg-primary/20 blur-2xl"></div>
                            <div class="overflow-hidden rounded-3xl shadow-2xl shadow-indigo-100 ring-1 ring-slate-100">
                                <img src="https://themewagon.github.io/pavo/images/details-1.svg" alt="Feature illustration" class="w-full">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-accent" id="video">
                <div class="mx-auto max-w-6xl px-6 py-16">
                    <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                        <div class="space-y-4">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Watch It</p>
                            <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl">See Pavo in action</h2>
                            <p class="text-lg text-slate-600">Drop in your product demo and keep visitors engaged with a full-width responsive player.</p>
                            <ul class="space-y-3 text-sm text-slate-700">
                                <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>Responsive embed that adapts to every screen.</li>
                                <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>Lightweight Tailwind utilities—no custom CSS required.</li>
                                <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>Matches the original Pavo spacing and rhythm.</li>
                            </ul>
                        </div>
                        <div class="overflow-hidden rounded-3xl shadow-2xl shadow-indigo-100 ring-1 ring-slate-200">
                            <div class="aspect-video">
                                <iframe class="h-full w-full" src="https://www.youtube.com/embed/LXb3EKWsInQ" title="Pavo demo video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white">
                <div class="mx-auto max-w-6xl px-6 py-16">
                    <div class="grid gap-8 rounded-3xl bg-primary text-white p-10 shadow-soft lg:grid-cols-3">
                        <div class="space-y-2">
                            <p class="text-sm uppercase tracking-[0.2em] text-white/70">Numbers</p>
                            <h3 class="text-3xl font-bold">Metrics that mirror Pavo</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 sm:col-span-2">
                            <div>
                                <p class="text-sm uppercase text-white/70">Downloads</p>
                                <p class="text-3xl font-semibold">250k</p>
                                <p class="text-white/80">Across web and mobile</p>
                            </div>
                            <div>
                                <p class="text-sm uppercase text-white/70">Active Users</p>
                                <p class="text-3xl font-semibold">18k</p>
                                <p class="text-white/80">Engaged every month</p>
                            </div>
                            <div>
                                <p class="text-sm uppercase text-white/70">Integrations</p>
                                <p class="text-3xl font-semibold">35+</p>
                                <p class="text-white/80">Ready to connect</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-accent" id="pricing">
                <div class="mx-auto max-w-6xl px-6 py-16">
                    <div class="text-center space-y-3">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Pricing</p>
                        <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl">Choose the plan that fits</h2>
                        <p class="text-lg text-slate-600">Mirror Pavo's clean cards with clear CTAs and highlights.</p>
                    </div>
                    <div class="mt-10 grid gap-6 md:grid-cols-3">
                        <div class="rounded-3xl bg-white p-6 shadow-soft ring-1 ring-slate-100">
                            <p class="text-sm font-semibold text-primary">Starter</p>
                            <p class="mt-2 text-4xl font-bold">$19<span class="text-base font-medium text-slate-500">/mo</span></p>
                            <p class="mt-2 text-sm text-slate-600">Perfect for early projects.</p>
                            <ul class="mt-4 space-y-2 text-sm text-slate-700">
                                <li>Up to 3 projects</li>
                                <li>Email support</li>
                                <li>Community access</li>
                            </ul>
                            <a href="/register" class="mt-6 inline-flex w-full items-center justify-center rounded-full border border-primary px-4 py-2 text-sm font-semibold text-primary transition hover:-translate-y-0.5 hover:bg-primary hover:text-white">Get Starter</a>
                        </div>
                        <div class="rounded-3xl bg-slate-900 p-6 text-white shadow-2xl shadow-indigo-200 ring-2 ring-primary">
                            <p class="text-sm font-semibold text-secondary">Popular</p>
                            <p class="mt-2 text-4xl font-bold">$49<span class="text-base font-medium text-white/70">/mo</span></p>
                            <p class="mt-2 text-sm text-white/80">Ideal for growing teams.</p>
                            <ul class="mt-4 space-y-2 text-sm text-white/90">
                                <li>Unlimited projects</li>
                                <li>Priority support</li>
                                <li>Advanced analytics</li>
                            </ul>
                            <a href="/register" class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-indigo-500">Choose Plan</a>
                        </div>
                        <div class="rounded-3xl bg-white p-6 shadow-soft ring-1 ring-slate-100">
                            <p class="text-sm font-semibold text-primary">Enterprise</p>
                            <p class="mt-2 text-4xl font-bold">Custom</p>
                            <p class="mt-2 text-sm text-slate-600">Tailored for scale.</p>
                            <ul class="mt-4 space-y-2 text-sm text-slate-700">
                                <li>Dedicated success manager</li>
                                <li>SSO and security reviews</li>
                                <li>Custom SLAs</li>
                            </ul>
                            <a href="/contact" class="mt-6 inline-flex w-full items-center justify-center rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-800 transition hover:-translate-y-0.5 hover:border-primary hover:text-primary">Talk to sales</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white" id="faq">
                <div class="mx-auto max-w-5xl px-6 py-16">
                    <div class="text-center space-y-3">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">FAQ</p>
                        <h2 class="text-3xl font-bold text-slate-900 sm:text-4xl">Answers that match Pavo</h2>
                        <p class="text-lg text-slate-600">Use these accordions to handle common questions.</p>
                    </div>
                    <div class="mt-10 space-y-4">
                        <details class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-soft transition hover:-translate-y-0.5">
                            <summary class="flex cursor-pointer items-center justify-between text-lg font-semibold text-slate-900">How does the layout match Pavo?<span class="text-primary">+</span></summary>
                            <p class="pt-3 text-sm text-slate-600">Sections, spacing, and typography mirror the original Pavo landing so you get a familiar, polished feel out of the box.</p>
                        </details>
                        <details class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-soft transition hover:-translate-y-0.5">
                            <summary class="flex cursor-pointer items-center justify-between text-lg font-semibold text-slate-900">Can I swap assets easily?<span class="text-primary">+</span></summary>
                            <p class="pt-3 text-sm text-slate-600">Yes. Images are remote placeholders; replace them with your own or local files while keeping Tailwind classes intact.</p>
                        </details>
                        <details class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-soft transition hover:-translate-y-0.5">
                            <summary class="flex cursor-pointer items-center justify-between text-lg font-semibold text-slate-900">Is it mobile friendly?<span class="text-primary">+</span></summary>
                            <p class="pt-3 text-sm text-slate-600">All sections use responsive Tailwind utilities to adapt from phones to desktops.</p>
                        </details>
                        <details class="group rounded-2xl border border-slate-200 bg-white p-4 shadow-soft transition hover:-translate-y-0.5">
                            <summary class="flex cursor-pointer items-center justify-between text-lg font-semibold text-slate-900">Where do CTAs point?<span class="text-primary">+</span></summary>
                            <p class="pt-3 text-sm text-slate-600">Primary buttons link to register/login placeholders; adjust routes as needed for your app.</p>
                        </details>
                    </div>
                </div>
            </section>

            <section class="bg-slate-900 text-white">
                <div class="mx-auto max-w-6xl px-6 py-14 grid gap-8 lg:grid-cols-2 lg:items-center">
                    <div class="space-y-4">
                        <p class="text-sm uppercase tracking-[0.2em] text-white/70">Call to action</p>
                        <h2 class="text-3xl font-bold sm:text-4xl">Ready to launch with Pavo?</h2>
                        <p class="text-lg text-white/80">Use this Tailwind-first landing, swap your copy, and go live faster.</p>
                        <div class="flex flex-wrap gap-4">
                            <a href="/register" class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900 transition hover:-translate-y-0.5">Start for free</a>
                            <a href="/contact" class="rounded-full border border-white/30 px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:border-white">Contact sales</a>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-8 -top-8 h-16 w-16 rounded-full bg-primary/40 blur-xl"></div>
                        <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl backdrop-blur">
                            <img src="https://themewagon.github.io/pavo/images/details-2.svg" alt="CTA illustration" class="w-full">
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="relative z-10 border-t border-slate-100 bg-white">
            <div class="mx-auto flex max-w-6xl flex-col gap-4 px-6 py-8 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2 text-slate-800">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white">P</span>
                    <span class="font-semibold">Pavo</span>
                </div>
                <p class="text-slate-500">Built with TailwindCSS to match the original Pavo landing page.</p>
                <div class="flex gap-3">
                    <a href="#about" class="hover:text-primary">About</a>
                    <a href="#features" class="hover:text-primary">Features</a>
                    <a href="#pricing" class="hover:text-primary">Pricing</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

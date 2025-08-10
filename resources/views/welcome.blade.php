<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-zinc-50 antialiased">
    <head>
        @include('partials.head')
    </head>
    <body>
        <main class="mx-auto mb-8 max-w-[740px] px-4 mt-20">
            <h1 class="text-center font-medium text-5xl tracking-tight">How do you craft animations that feel right?</h1>
            <p class="text-center text-xl max-w-lg mx-auto mt-6">Learn the theory and practice behind great animations with this interactive learning experience.</p>
            <div class="mt-6 text-center">
                <span class="text-sm">Emil Kowalski</span>
            </div>
            <p class="text-lg mt-48 text-zinc-700">
                Coding animations is hard, and unfortunately, many tutorials follow a happy path. They cover simple animations that are great for beginners, but aren’t that helpful once you go past the basics.
            </p>
            <p class="text-lg mt-6 text-zinc-700">
                But it’s not just the code that makes an animation work. A bad easing or duration can ruin an otherwise great animation. But how do you know whether you made the right choices? You don’t because animations are tricky. <strong class="font-medium text-zinc-900">It just doesn’t feel right and you can’t tell why</strong>.
            </p>
            <h2 class="mt-20 font-medium tracking-tight text-2xl">
                What if you knew exactly how to craft great animations?
            </h2>
            <p class="text-lg mt-6 text-zinc-700">
                If you knew what a great animations consists of, you would know which easing to use, what the duration should be, what properties to animate to keep it performant, and so on.
            </p>
        </main>
    </body>
</html>

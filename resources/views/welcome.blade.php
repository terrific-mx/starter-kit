<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-zinc-50 antialiased">
    <head>
        @include('partials.head')
    </head>
    <body>
        <main class="
            mx-auto mb-8 max-w-[740px] px-4 mt-20
            [&>h1]:text-center [&>h1]:font-medium [&>h1]:text-5xl [&>h1]:tracking-tight
            [&>[data-lead]]:text-center [&>[data-lead]]:text-xl [&>[data-lead]]:text-zinc-900
            [&>p]:text-lg [&>p]:text-zinc-700
            [&>h2]:font-medium [&>h2]:tracking-tight [&>h2]:text-2xl
        ">
            <h1>Heading 1</h1>
            <p class="max-w-lg mx-auto mt-6" data-lead>Lead paragraph content goes here.</p>
            <div class="mt-6 text-center text-sm">
                Creado por <span>Oliver Servín</span>
            </div>
            <p class="mt-48">
                Paragraph content goes here.
            </p>
            <p class="mt-6">
                Paragraph content goes here.
            </p>
            <h2 class="mt-20">
                Heading 2
            </h2>
            <p class="mt-6">
                Paragraph content goes here.
            </p>
        </main>
    </body>
</html>

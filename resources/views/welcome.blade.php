<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-zinc-50 antialiased">
    <head>
        @include('partials.head')
    </head>
    <body>
        <header class="sticky flex justify-end p-6 gap-4">
            @auth
                <flux:button :href="route('dashboard')" variant="primary" color="amber" size="sm" wire:navigate>{{ __('Dashboard') }}</flux:button>
            @else
                <flux:button :href="route('login')" variant="subtle" size="sm" wire:navigate>{{ __('Login') }}</flux:button>
                <flux:button :href="route('register')" variant="primary" color="amber" size="sm" wire:navigate>{{ __('Get Started') }}</flux:button>
            @endauth
        </header>

        <main class="
            mx-auto mb-8 max-w-3xl px-4 mt-20
            text-zinc-700
            [&>h1]:text-center [&>h1]:font-medium [&>h1]:text-5xl [&>h1]:tracking-tight [&>h1]:text-zinc-950
            [&>h2]:font-medium [&>h2]:tracking-tight [&>h2]:text-2xl [&>h2]:text-zinc-950
            [&>[data-lead]]:text-center [&>[data-lead]]:text-xl [&>[data-lead]]:text-zinc-900
            [&>p]:text-lg
            [&>p>strong]:text-zinc-950
        ">
            <h1>Supercharge Your Team’s Productivity</h1>
            <p class="max-w-lg mx-auto mt-6" data-lead>
                Effortlessly automate your workflows and collaborate in real time, so your team can focus on what matters most and achieve more together.
            </p>
            <div class="mt-6 text-center text-sm">
                Creado por <span class="text-zinc-950">Oliver Servín</span>
            </div>

            <p class="mt-48">
                This cloud-based solution streamlines your team’s daily operations. It enables you to automate repetitive tasks and centralize project management.
            </p>
            <p class="mt-6">
                With less time spent on manual processes, your team can focus on delivering results. Every member works more efficiently and stays aligned on shared goals.
            </p>

            <flux:card class="min-h-72 mt-10 flex flex-col">
                <div class="flex-1 flex flex-col justify-end">
                    <div class="flex justify-center">
                        <flux:button size="sm">{{ __('Start demo') }}</flux:button>
                    </div>
                </div>
            </flux:card>

            <h2 class="mt-20">Everything You Need, All in One Place</h2>
            <p class="mt-6">
                At the heart of the platform is a <strong>visual workflow builder</strong> that lets you create, customize, and deploy automated workflows using a simple drag-and-drop interface.
            </p>
            <p class="mt-6">
                <strong>Real-time collaboration</strong> is built in, allowing you to share tasks, files, and updates instantly with your team. The solution integrates seamlessly with popular tools such as Slack, Google Workspace, and Trello, making it easy to fit into your existing workflow.
            </p>
            <p class="mt-6">
                An intuitive <strong>analytics dashboard</strong> provides insights into productivity, workflow efficiency, and team performance, helping you make data-driven decisions every day.
            </p>

            <h2 class="mt-20">Get Up and Running in Minutes</h2>
            <p class="mt-6">
                Getting started is simple. After signing up, you can invite your team and begin building workflows within minutes.
            </p>
            <p class="mt-6">
                The platform enables you to automate approvals, notifications, and data entry <strong>without any coding required</strong>. Its user-friendly dashboard keeps everyone on the same page, ensuring that projects move forward smoothly and nothing falls through the cracks.
            </p>

            <h2 class="mt-20">Unlock Your Team’s Full Potential</h2>
            <p class="mt-6">
                By automating repetitive tasks, the platform <strong>saves your team valuable time</strong> and reduces manual work.
            </p>
            <p class="mt-6">
                Collaboration is enhanced as everyone stays in sync with shared boards and real-time updates. Transparency increases as you monitor progress and quickly identify bottlenecks through powerful analytics.
            </p>
            <p class="mt-6">
                Whether you’re a startup or a large enterprise, this solution is designed to scale with your team and support your growth every step of the way.
            </p>

            <h2 class="mt-20">Start Your Journey Today</h2>
            <p class="mt-6">
                If you’re ready to transform the way your team works, <strong>sign up for a free 14-day trial</strong> and experience the benefits for yourself.
            </p>
            <p class="mt-6">
                There’s no credit card required, and you’ll discover how easy it is to boost productivity and streamline your workflow from day one.
            </p>

            <flux:card class="max-w-lg mx-auto mt-20">
                <h2 class="text-2xl font-medium tracking-tight text-zinc-950 text-center mt-2">Simple, Transparent Pricing</h2>
                <p class="text-center mt-4">Choose a plan that fits your team and get started with confidence—no hidden fees, ever.</p>
                <ul class="mt-9 gap-3 md:grid md:grid-cols-2 md:gap-6 text-sm">
                    <li class="flex gap-2">
                        <flux:icon.check-circle variant="mini" class="text-green-500" />
                        Unlimited workflows
                    </li>
                    <li class="flex gap-2">
                        <flux:icon.check-circle variant="mini" class="text-green-500" />
                        Real-time collaboration
                    </li>
                    <li class="flex gap-2">
                        <flux:icon.check-circle variant="mini" class="text-green-500" />
                        Seamless integrations
                    </li>
                    <li class="flex gap-2">
                        <flux:icon.check-circle variant="mini" class="text-green-500" />
                        Advanced analytics
                    </li>
                    <li class="flex gap-2">
                        <flux:icon.check-circle variant="mini" class="text-green-500" />
                        Priority email support
                    </li>
                    <li class="flex gap-2">
                        <flux:icon.check-circle variant="mini" class="text-green-500" />
                        Custom user roles
                    </li>
                    <li class="flex gap-2">
                        <flux:icon.check-circle variant="mini" class="text-green-500" />
                        Secure cloud hosting
                    </li>
                    <li class="flex gap-2">
                        <flux:icon.check-circle variant="mini" class="text-green-500" />
                        14-day free trial
                    </li>
                </ul>
                <flux:button :href="route('register')" variant="primary" color="amber" class="mt-9 w-full text-base!">
                    Get started now
                </flux:button>
            </flux:card>

            <div class="mt-64 flex items-center justify-between text-zinc-400">
                <p class="text-sm flex items-center gap-2.5">
                    <x-app-logo-icon class="size-4 text-zinc-300" />
                    <span><strong class="font-medium">flowpilot</strong>.com</span>
                </p>
                <p class="text-sm">por <strong class="font-medium">Oliver Servín</strong></p>
            </div>
        </main>
    </body>
</html>

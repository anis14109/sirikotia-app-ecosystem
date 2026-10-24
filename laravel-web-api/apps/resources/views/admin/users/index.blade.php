<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('User Role Management') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Assign roles to users via the user-role mapping interface.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm ring-1 ring-gray-200/70 dark:ring-gray-700/60 sm:rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-semibold">Users</h3>
                            <span class="inline-flex items-center rounded-full bg-indigo-50 dark:bg-indigo-900/40 px-2.5 py-0.5 text-xs font-semibold text-indigo-700 dark:text-indigo-300">
                                {{ $users->count() }} total
                            </span>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-5 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-700/50 dark:bg-green-900/30 dark:text-green-200">
                            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($users->isEmpty())
                        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 dark:border-gray-600 py-16 text-center">
                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                            <h4 class="mt-3 text-sm font-semibold text-gray-900 dark:text-gray-100">No users found</h4>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add users to start assigning roles.</p>
                        </div>
                    @else
                        {{-- Desktop table --}}
                        <div class="hidden md:block overflow-x-auto -mx-4 sm:-mx-6">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">User</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Email</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Roles</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/70">
                                    @foreach($users as $user)
                                        <tr class="group transition hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                            <td class="whitespace-nowrap px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                                        <span class="text-xs font-semibold uppercase">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                                    </div>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    @forelse($user->roles as $role)
                                                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold capitalize text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">{{ $role->name }}</span>
                                                    @empty
                                                        <span class="text-xs text-gray-400 dark:text-gray-500">No roles assigned</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/30 transition">Assign Roles</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile cards --}}
                        <div class="space-y-3 md:hidden">
                            @foreach($users as $user)
                                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800/60">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                                <span class="text-xs font-semibold uppercase">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                                <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-1.5">
                                        @forelse($user->roles as $role)
                                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-0.5 text-[11px] font-semibold capitalize text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">{{ $role->name }}</span>
                                        @empty
                                            <span class="text-xs text-gray-400 dark:text-gray-500">No roles assigned</span>
                                        @endforelse
                                    </div>
                                    <div class="mt-4 border-t border-gray-100 pt-3 dark:border-gray-700/70">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="w-full inline-flex items-center justify-center rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700/50 transition">Assign Roles</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

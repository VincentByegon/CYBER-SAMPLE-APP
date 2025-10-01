<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #18181b;
            color: #f3f4f6;
            margin: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #232946;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            padding: 2.5rem 2rem;
        }
        h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
            color: #ffd700;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #18181b;
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            padding: 1rem;
            text-align: left;
        }
        th {
            background: #232946;
            color: #ffd700;
            font-weight: 600;
        }
        td {
            background: #18181b;
            color: #f3f4f6;
            border-bottom: 1px solid #232946;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        .btn {
            padding: 0.5rem 1.2rem;
            border-radius: 6px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.2s;
        }
        .btn-approve {
            background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
            color: #fff;
        }
        .btn-approve:hover {
            background: linear-gradient(90deg, #16a34a 0%, #22c55e 100%);
        }
        .btn-delete {
            background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
        }
        .btn-delete:hover {
            background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%);
        }
        .approved {
            color: #22c55e;
            font-weight: bold;
        }
        .not-approved {
            color: #ef4444;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <x-layouts.app>
        <div class="p-6 bg-gray-900 min-h-screen">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-[#EDEDEC]">Users ({{ $users->count() }})</h1>
                {{-- Example: Add User button if needed --}}
                {{-- <a href="#" class="bg-blue-600 hover:bg-blue-700 text-[#EDEDEC] px-4 py-2 rounded-lg shadow">Add User</a> --}}
            </div>
            <div class="mt-6 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-green-600/20 mr-3">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400">Approved Users</p>
                            <p class="text-2xl font-bold text-[#EDEDEC]">{{ $users->where('approved', true)->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-red-600/20 mr-3">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400">Pending Approval</p>
                            <p class="text-2xl font-bold text-[#EDEDEC]">{{ $users->where('approved', false)->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-yellow-600/20 mr-3">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-400">Admins</p>
                            <p class="text-2xl font-bold text-[#EDEDEC]">{{ $users->where('admin', true)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 dark:text-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-700 bg-white dark:bg-gray-800 dark:text-white">
                    <thead class="bg-gray-700 dark:bg-gray-800 dark:text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Admin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#EDEDEC] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->approved)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-700 text-green-100">Approved</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-700 text-red-100">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->admin)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-700 text-yellow-100">Admin</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-700 text-gray-100">User</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex gap-2">
                                    @if(!$user->approved)
                                        <form action="{{ route('admin.users.approve', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Approve</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-layouts.app>
</body>
</html>

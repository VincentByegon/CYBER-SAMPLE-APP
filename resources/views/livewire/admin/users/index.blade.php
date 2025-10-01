<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6 text-yellow-500 text-center">User Management</h2>
    @if(session('success'))
        <div class="bg-green-600 text-white px-4 py-2 rounded mb-4 text-center">
            {{ session('success') }}
        </div>
    @endif
    <table class="min-w-full bg-gray-900 rounded-lg overflow-hidden">
        <thead>
            <tr>
                <th class="py-2 px-4 text-yellow-500">Name</th>
                <th class="py-2 px-4 text-yellow-500">Email</th>
                <th class="py-2 px-4 text-yellow-500">Status</th>
                <th class="py-2 px-4 text-yellow-500">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-b border-gray-700">
                <td class="py-2 px-4">{{ $user->name }}</td>
                <td class="py-2 px-4">{{ $user->email }}</td>
                <td class="py-2 px-4">
                    @if($user->approved)
                        <span class="text-green-400 font-bold">Approved</span>
                    @else
                        <span class="text-red-400 font-bold">Pending</span>
                    @endif
                </td>
                <td class="py-2 px-4">
                    <div class="flex gap-2">
                        @if(!$user->approved)
                            <button wire:click="approve({{ $user->id }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Approve</button>
                        @endif
                        <button wire:click="delete({{ $user->id }})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Delete</button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('QR Code Scanned Successfully') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Success!</h3>
                <p>{{ session('success') }}</p>
                <a href="{{ route('checkpoint.scan.form') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Scan Another QR Code</a>
            </div>
        </div>
    </div>
</x-app-layout>
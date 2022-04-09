<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($zoomToken)
                        Connected to Zoom. <a href="{{ route('zoom.disconnect') }}">Disconnect</a>
                    @else
                        <a href="{{ route('zoom.connect') }}">Connect with Zoom</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($slackToken)
                        Connected to Slack. <a href="{{ route('slack.disconnect') }}">Disconnect</a>
                    @else
                        <a href="{{ route('slack.connect') }}">Connect with Slack</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

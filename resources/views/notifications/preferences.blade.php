{{-- resources/views/notifications/preferences.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Notification Preferences</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Customize how and when you receive notifications.
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('notifications.preferences.update') }}" method="POST">
                    @csrf
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox"
                                               name="email_notifications"
                                               id="email_notifications"
                                               value="1"
                                               @checked(old('email_notifications', $preferences['email_notifications'] ?? true))
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="email_notifications" class="font-medium text-gray-700">Email Notifications</label>
                                        <p class="text-gray-500">Receive notifications via email</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox"
                                               name="web_notifications"
                                               id="web_notifications"
                                               value="1"
                                               @checked(old('web_notifications', $preferences['web_notifications'] ?? true))
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="web_notifications" class="font-medium text-gray-700">Web Notifications</label>
                                        <p class="text-gray-500">Receive notifications in the browser</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox"
                                               name="comment_notifications"
                                               id="comment_notifications"
                                               value="1"
                                               @checked(old('comment_notifications', $preferences['comment_notifications'] ?? true))
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="comment_notifications" class="font-medium text-gray-700">Comment Notifications</label>
                                        <p class="text-gray-500">Notify when someone comments on your posts</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox"
                                               name="reply_notifications"
                                               id="reply_notifications"
                                               value="1"
                                               @checked(old('reply_notifications', $preferences['reply_notifications'] ?? true))
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="reply_notifications" class="font-medium text-gray-700">Reply Notifications</label>
                                        <p class="text-gray-500">Notify when someone replies to your comments</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Save Preferences
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

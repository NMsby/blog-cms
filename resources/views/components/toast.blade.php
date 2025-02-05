<div
    x-data="{
        toasts: [],
        add(message, type = 'info', duration = 5000) {
            const toast = {
                id: Date.now(),
                message,
                type,
                show: true
            };
            this.toasts.push(toast);
            setTimeout(() => {
                this.remove(toast.id);
            }, duration);
        },
        remove(id) {
            this.toasts = this.toasts.filter(toast => toast.id !== id);
        }
    }"
    @notify.window="add($event.detail.message, $event.detail.type)"
    class="fixed top-4 right-4 z-50 space-y-2"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="toast.show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            :class="{
                'border-green-500': toast.type === 'success',
                'border-red-500': toast.type === 'error',
                'border-yellow-500': toast.type === 'warning',
                'border-blue-500': toast.type === 'info'
            }"
            class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 border-l-4"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900" x-text="toast.message"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button
                            @click="remove(toast.id)"
                            class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

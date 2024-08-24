<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="user-list">
                        @foreach ($users as $user)
                            <p>
                                <a href="#" class="user-link" data-user-id="{{ $user->id }}">{{ $user->name }}</a>
                            </p>
                        @endforeach
                    </div>
                    <!-- Chat container -->
                    <div id="chat-container" style="display: none;">
                        <div class="fixed w-full bg-green-400 h-16 pt-2 text-white flex justify-between shadow-md" style="top:0px;">
                            <!-- back button -->
                            <button id="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-12 h-12 my-1 text-green-100 ml-2">
                                    <path class="text-green-100 fill-current" d="M9.41 11H17a1 1 0 0 1 0 2H9.41l2.3 2.3a1 1 0 1 1-1.42 1.4l-4-4a1 1 0 0 1 0-1.4l4-4a1 1 0 0 1 1.42 1.4L9.4 11z" />
                                </svg>
                            </button>
                            <div id="chat-header" class="my-3 text-green-100 font-bold text-lg tracking-wide"></div>
                            <!-- 3 dots -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="icon-dots-vertical w-8 h-8 mt-2 mr-2">
                                <path class="text-green-100 fill-current" fill-rule="evenodd" d="M12 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4z" />
                            </svg>
                        </div>
                        <div id="chat-messages" class="mt-20 mb-16">
                            <!-- Messages will be appended here -->
                        </div>
                        <form id="chat-form">
                            <div class="fixed w-full flex justify-between bg-green-100" style="bottom: 0px;">
                                <textarea id="message-input" class="flex-grow m-2 py-2 px-4 mr-1 rounded-full border border-gray-300 bg-gray-200 resize-none" rows="1" placeholder="Message..." style="outline: none;"></textarea>
                                <button type="submit" style="outline: none;">
                                    <svg class="svg-inline--fa text-green-400 fa-paper-plane fa-w-16 w-12 h-12 py-2 mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="paper-plane" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M476 3.2L12.5 270.6c-18.1 10.4-15.8 35.6 2.2 43.2L121 358.4l287.3-253.2c5.5-4.9 13.3 2.6 8.6 8.3L176 407v80.5c0 23.6 28.5 32.9 42.5 15.8L282 426l124.6 52.2c14.2 6 30.4-2.9 33-18.2l72-432C515 7.8 493.3-6.8 476 3.2z" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="user-list">
                        @foreach ($users as $user)
                            <p>
                                <a href="#" class="user-link" data-user-id="{{ $user->id }}">{{ $user->name }}</a>
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chat Container -->
    <!-- Chat Container -->
    <div id="chat-container">
        <div id="chat-header">
            <button id="back-button">
                &larr; Back
            </button>
            <span id="chat-title">Chat with User</span>
        </div>
        <div id="chat-messages">
            <!-- Messages will be dynamically added here -->
            <!-- Example message structure -->
            <!-- <div class="message other"><b>Sender:</b> Message content</div> -->
            <!-- <div class="message self">Message content <b>: You</b></div> -->
        </div>
        <form id="chat-form">
            <textarea id="message-input" placeholder="Type your message..."></textarea>
            <button type="submit" id="send-button">Send</button>
        </form>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const userLinks = document.querySelectorAll('.user-link');
        const chatContainer = document.getElementById('chat-container');
        const chatHeader = document.getElementById('chat-header');
        const chatMessages = document.getElementById('chat-messages');
        const backButton = document.getElementById('back-button');
        const messageInput = document.getElementById('message-input');
        const chatForm = document.getElementById('chat-form');

        let currentUserId = null;

        userLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const userId = e.target.getAttribute('data-user-id');
                currentUserId = userId;
                chatHeader.querySelector('#chat-title').textContent = e.target.textContent;
                chatContainer.style.display = 'block';
                loadMessages(userId);
            });
        });

        backButton.addEventListener('click', () => {
            chatContainer.style.display = 'none';
        });

        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            if (messageInput.value.trim() !== '') {
                sendMessage(messageInput.value);
                messageInput.value = '';
            }
        });

        function loadMessages(userId) {
            fetch(`/api/chat/${userId}`)
                .then(response => response.json())
                .then(data => {
                    chatMessages.innerHTML = ''; // Clear previous messages
                    data.messages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `message ${message.sender === '{{ auth()->user()->name }}' ? 'self' : 'other'}`;
                        messageDiv.innerHTML = `<b>${message.sender}:</b> ${message.message}`;
                        chatMessages.appendChild(messageDiv);
                    });
                });
        }

        function sendMessage(message) {
            fetch(`/api/chat/${currentUserId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message self';
                    messageDiv.innerHTML = `${message} <b>: You</b>`;
                    chatMessages.appendChild(messageDiv);
                    chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom
                }
            });
        }
    });
    </script>
</x-app-layout>

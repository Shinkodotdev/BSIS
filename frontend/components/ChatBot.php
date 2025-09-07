<!-- AI Chat Bot -->
<div id="ai-chat-bot" class="fixed bottom-5 right-5 w-80 sm:w-72 xs:w-64 bg-white shadow-xl rounded-xl flex flex-col transition-all duration-300 z-50">
    <!-- Header -->
    <div class="bg-indigo-700 text-white px-4 py-3 flex justify-between items-center cursor-pointer" onclick="toggleMinimize()">
        <h3 id="chat-title" class="font-bold text-lg">Ask Barangay AI</h3>
        <div class="flex gap-2">
            <button id="minimize-btn" class="text-white text-sm">
                <i class="fas fa-minus"></i>
            </button>
            <button onclick="closeChat()" class="text-white text-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- Chat Content -->
    <div id="chat-content" class="p-4 h-64 overflow-y-auto bg-gray-50 flex flex-col gap-2">
        <!-- Messages will appear here -->
    </div>

    <!-- Input -->
    <div id="chat-input-container" class="flex p-2 border-t border-gray-200">
        <input id="chat-input" type="text" placeholder="Ask a question..." class="flex-grow px-3 py-2 border rounded-l-md focus:outline-none text-sm">
        <button onclick="sendMessage()" class="bg-indigo-700 text-white px-4 py-2 rounded-r-md hover:bg-indigo-600 text-sm">Send</button>
    </div>
</div>
<style>
@media (max-width: 640px) {
    #ai-chat-bot {
        right: 5%;
        bottom: 10px;
    }
    #chat-input {
        font-size: 0.875rem;
    }
}
</style>
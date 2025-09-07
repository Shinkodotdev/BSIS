const chatBot = document.getElementById('ai-chat-bot');
const chatContent = document.getElementById('chat-content');
const chatInputContainer = document.getElementById('chat-input-container');
const chatTitle = document.getElementById('chat-title');
const minimizeBtn = document.getElementById('minimize-btn');
const chatInput = document.getElementById('chat-input');

// Function to add a message to the chat
function addMessage(message, sender = 'ai') {
    const msg = document.createElement('div');
    msg.className = sender === 'user'
        ? 'self-end bg-indigo-100 text-indigo-700 p-2 rounded-lg max-w-xs break-words text-sm'
        : 'self-start bg-gray-200 text-gray-800 p-2 rounded-lg max-w-xs break-words text-sm';
    msg.textContent = message;
    chatContent.appendChild(msg);
    chatContent.scrollTop = chatContent.scrollHeight;
}

// Predefined options
const options = [
    { label: "View Services", response: "You can view our services and request documents under the Services & Documents section." },
    { label: "AI Health Reports", response: "Check the AI Health Reports section for community health updates and insights." },
    { label: "Emergency Contacts", response: "Emergency contacts are available in the Emergency Contacts section for quick access." },
    { label: "FAQs", response: "You can browse frequently asked questions or ask me anything related to barangay services." }
];

// Add option buttons to chat
function showOptions() {
    // Remove any existing options first
    const oldOptions = document.getElementById('options-container');
    if(oldOptions) oldOptions.remove();

    const optionsContainer = document.createElement('div');
    optionsContainer.id = 'options-container';
    optionsContainer.className = 'flex flex-col gap-2 mt-2';

    options.forEach(option => {
        const btn = document.createElement('button');
        btn.className = 'bg-indigo-100 text-indigo-700 p-2 rounded-md hover:bg-indigo-200 text-sm text-left';
        btn.textContent = option.label;
        btn.onclick = () => addMessage(option.response, 'ai');
        optionsContainer.appendChild(btn);
    });

    chatContent.appendChild(optionsContainer);
    chatContent.scrollTop = chatContent.scrollHeight;
}

// Welcome message
function showWelcome() {
    addMessage("Hello! 👋 I'm your Barangay AI assistant. How can I help you today?");
    showOptions(); // Show options immediately
}

// Send message
function sendMessage() {
    if (!chatInput.value) return;

    addMessage(chatInput.value, 'user');

    const userQuery = chatInput.value.toLowerCase();
    let aiResponse = "I'm here to assist you. Please select an option below for quick guidance.";

    if (userQuery.includes("services")) aiResponse = options[0].response;
    else if (userQuery.includes("health")) aiResponse = options[1].response;
    else if (userQuery.includes("contacts")) aiResponse = options[2].response;
    else if (userQuery.includes("faq")) aiResponse = options[3].response;

    addMessage(aiResponse, 'ai');
    showOptions();
    chatInput.value = '';
}

// Minimize toggle
function toggleMinimize() {
    if(chatContent.style.display === 'none') {
        chatContent.style.display = 'flex';
        chatInputContainer.style.display = 'flex';
        chatBot.classList.remove('w-16','h-16','rounded-full','justify-center','items-center','flex');
        chatBot.classList.add('w-80','sm:w-72','xs:w-64','rounded-xl','flex','flex-col');
        chatTitle.style.display = 'block';
        minimizeBtn.innerHTML = '<i class="fas fa-minus"></i>';
    } else {
        chatContent.style.display = 'none';
        chatInputContainer.style.display = 'none';
        chatBot.classList.remove('w-80','sm:w-72','xs:w-64','rounded-xl','flex','flex-col');
        chatBot.classList.add('w-16','h-16','rounded-full','flex','justify-center','items-center');
        chatTitle.style.display = 'none';
        minimizeBtn.innerHTML = '<i class="fas fa-plus"></i>';
    }
}

// Close chat completely
function closeChat() {
    chatBot.style.display = 'none';
}

// Initialize on page load
window.onload = showWelcome;

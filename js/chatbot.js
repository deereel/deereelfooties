// Simple chatbot for customer support
class ChatBot {
    constructor() {
        this.isOpen = false;
        this.responses = {
            'hello': 'Hi! Welcome to DeeReel Footies! How can I help you today?',
            'sizes': 'We have sizes from 36 to 45. Check our size guide for perfect fit!',
            'shipping': 'Free shipping on orders above ₦150,000 in Lagos, ₦250,000 in other states!',
            'payment': 'We accept bank transfers and online payments via Paystack.',
            'return': 'We offer 7-day return policy for unworn shoes in original packaging.',
            'custom': 'Yes! We create custom designs. Contact us with your ideas!',
            'default': 'I can help with sizes, shipping, payments, returns, and custom orders. What would you like to know?'
        };
        this.init();
    }
    
    init() {
        this.createChatWidget();
        this.bindEvents();
    }
    
    createChatWidget() {
        const chatHTML = `
            <div id="chatbot-widget" class="position-fixed" style="bottom: 20px; right: 20px; z-index: 1000;">
                <div id="chat-button" class="btn btn-primary rounded-circle shadow" style="width: 60px; height: 60px;">
                    <i class="fas fa-comments fa-lg"></i>
                </div>
                <div id="chat-window" class="card shadow" style="width: 300px; height: 400px; display: none; margin-bottom: 10px;">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>DeeReel Support</span>
                            <button id="close-chat" class="btn btn-sm text-white">&times;</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="chat-messages" class="p-3" style="height: 280px; overflow-y: auto;">
                            <div class="bot-message mb-2">
                                <small class="text-muted">Bot</small>
                                <div class="bg-light p-2 rounded">Hi! I'm here to help with your shoe shopping! 👟</div>
                            </div>
                        </div>
                        <div class="border-top p-2">
                            <div class="input-group">
                                <input type="text" id="chat-input" class="form-control" placeholder="Type your message...">
                                <button id="send-message" class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', chatHTML);
    }
    
    bindEvents() {
        document.getElementById('chat-button').addEventListener('click', () => this.toggleChat());
        document.getElementById('close-chat').addEventListener('click', () => this.toggleChat());
        document.getElementById('send-message').addEventListener('click', () => this.sendMessage());
        document.getElementById('chat-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });
    }
    
    toggleChat() {
        const chatWindow = document.getElementById('chat-window');
        this.isOpen = !this.isOpen;
        chatWindow.style.display = this.isOpen ? 'block' : 'none';
    }
    
    sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        if (!message) return;
        
        this.addMessage(message, 'user');
        input.value = '';
        
        setTimeout(() => {
            const response = this.getResponse(message);
            this.addMessage(response, 'bot');
        }, 1000);
    }
    
    addMessage(message, sender) {
        const messagesContainer = document.getElementById('chat-messages');
        const messageHTML = `
            <div class="${sender}-message mb-2">
                <small class="text-muted">${sender === 'user' ? 'You' : 'Bot'}</small>
                <div class="${sender === 'user' ? 'bg-primary text-white ms-auto' : 'bg-light'} p-2 rounded" style="max-width: 80%;">
                    ${message}
                </div>
            </div>
        `;
        messagesContainer.insertAdjacentHTML('beforeend', messageHTML);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    getResponse(message) {
        const lowerMessage = message.toLowerCase();
        for (const [key, response] of Object.entries(this.responses)) {
            if (lowerMessage.includes(key)) {
                return response;
            }
        }
        return this.responses.default;
    }
}

// Initialize chatbot
document.addEventListener('DOMContentLoaded', () => {
    new ChatBot();
});
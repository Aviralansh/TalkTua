<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #121212;
            color: rgb(159, 123, 186);
            height: 100vh;
            overflow: hidden;
        }

        .chat-container {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: #121212;
            font-family: Arial, sans-serif;
            scrollbar-width: none;
        }

        .chat-container::-webkit-scrollbar {
            display: none;
        }
        
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            scrollbar-width: none;
            display: flex;
            flex-direction: column;
            gap: 8px; /
            max-height: calc(100vh - 80px); 
        }

        .chat-messages::-webkit-scrollbar {
            display: none;
        }
        
        .message {
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 80%;
            word-wrap: break-word;
            display: flex;
            flex-direction: column;
            gap: 4px; 
        }
        
        .user-message {
            background: rgb(159, 123, 186);
            color: #121212;
            margin-left: auto;
        }
        
        .bot-message {
            background: #1e1e1e;
            color: rgb(159, 123, 186);
            margin-right: auto;
            white-space: pre-wrap;
        }
        
        .chat-input {
            display: flex;
            padding: 20px;
            background: #1e1e1e;
            border-top: 1px solid #2d2d2d;
            height: 80px; 
        }
        
        .chat-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid rgb(159, 123, 186);
            border-radius: 20px;
            margin-right: 10px;
            background: #121212;
            color: rgb(159, 123, 186);
        }

        .chat-input input::placeholder {
            color: rgba(159, 123, 186, 0.7);
        }

        .chat-input input:focus {
            outline: none;
            border-color: rgb(179, 143, 206);
        }
        
        .chat-input button {
            padding: 10px 20px;
            background: rgb(159, 123, 186);
            color: #121212;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .chat-input button:hover {
            background: rgb(179, 143, 206);
        }
        
        .timestamp {
            font-size: 0.8em;
            color: whitesmoke;
        }
        #loading-spinner {
        text-align: center;
        margin-top: 10px;
    }

    .spinner {
        border: 4px solid #f3f3f3; /* Light grey */
        border-top: 4px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-messages" id="chat-messages"></div>
        <div class="chat-input">
            <input type="text" id="message-input" placeholder="Type your message..." autocomplete="off">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <div id="loading-spinner" style="display: none;">
        <div class="spinner"></div>
    </div>

    <script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Fetch API key from Laravel backend
async function fetchVultrApiKey() {
    const response = await fetch('/api/vultr-key');
    const data = await response.json();
    return data.vultr_key;
}

// Fetch Vultr API key on page load
let vultrApiKey;
fetchVultrApiKey().then(key => {
    vultrApiKey = key;  // Store the key to use later
});

// Add welcome message from the bot on page load
window.onload = function() {
    addMessage("Welcome! How can I assist you today?", false, new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
};

function addMessage(message, isUser = false, timestamp) {
    const messagesDiv = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;
    
    const messageContent = document.createElement('div');
    messageContent.textContent = message;
    
    const timestampDiv = document.createElement('div');
    timestampDiv.className = 'timestamp';
    timestampDiv.textContent = timestamp;
    
    messageDiv.appendChild(messageContent);
    messageDiv.appendChild(timestampDiv);
    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

async function sendMessage() {
    const input = document.getElementById('message-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    addMessage(message, true, new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
    input.value = '';

    // Show loading spinner
    const loader = document.getElementById('loading-spinner');
    loader.style.display = 'block';
    
    if (!vultrApiKey) {
        console.error('API key is not loaded yet.');
        return;
    }

    try {
        const response = await fetch('https://api.vultrinference.com/v1/chat/completions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${vultrApiKey}`, // Add API key here
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                model: 'llama2-7b-chat-Q5_K_M', // Replace with your actual model name
                messages: [{
                    role: 'user',
                    content: message
                }],
                max_tokens: 512,
                seed: -1,
                temperature: 0.6,
                top_k: 30,
                top_p: 0.5,
                stream: false // Ensuring we are not streaming
            })
        });

        const data = await response.json();
        const assistantMessage = data.choices[0].message.content; // Extract content from the response

        // Display the full response from the assistant
        addMessage(assistantMessage, false, new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
    } catch (error) {
        console.error('Error:', error);
        addMessage('Timeout! This error is related to Vultr’s service, not this app!', false, new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
    } finally {
        // Hide loading spinner after response is received or on error
        const loader = document.getElementById('loading-spinner');
        loader.style.display = 'none';
    }
}

document.getElementById('message-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});
</script>


</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI CV Generator</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
        .chat-container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #1f2937; }
        #chatbox { height: 450px; overflow-y: auto; border: 1px solid #e5e7eb; padding: 15px; margin-bottom: 15px; border-radius: 8px; background: #fafafa; }
        .message { margin: 10px 0; display: flex; flex-direction: column; }
        .message.user { align-items: flex-end; }
        .message.bot { align-items: flex-start; }
        .bubble { padding: 10px 15px; border-radius: 20px; max-width: 80%; line-height: 1.4; word-wrap: break-word; }
        .user .bubble { background: #3b82f6; color: white; border-bottom-right-radius: 5px; }
        .bot .bubble { background: #e5e7eb; color: #1f2937; border-bottom-left-radius: 5px; }
        .bot .bubble a { color: #2563eb; font-weight: bold; text-decoration: underline; }
        form { display: flex; gap: 10px; }
        input[type="text"] { flex: 1; padding: 12px; border: 1px solid #ccc; border-radius: 24px; outline: none; }
        button { padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 24px; cursor: pointer; font-weight: bold; }
        button:hover { background: #059669; }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100vh;
            background: #343a40;
            color: white;
            padding: 20px;
            overflow-y: auto;
        }
        .sidebar h4 {
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar li {
            margin-bottom: 10px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h4>My Routes</h4>
    <ul>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('profile.edit') }}">Profile Edit</a></li>
        <li><a href="{{ route('chatbot') }}">Chatbot</a></li>
        <li><a href="{{ route('cv-generator') }}">CV Generator</a></li>
        <li><a href="{{ route('transcribe') }}">Transcribe</a></li>
        <li><a href="{{ route('register') }}">Register</a></li>
        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('password.request') }}">Forgot Password</a></li>
    </ul>
</div>
<div class="main-content">
<div class="chat-container">
    <h2>AI CV Generator Interview</h2>
    <div id="chatbox"></div>
    <form id="chatForm">
        <input type="text" id="message" placeholder="Type your answer here..." autocomplete="off">
        <button type="submit" id="sendBtn">Send</button>
    </form>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const chatbox = document.getElementById('chatbox');
    const form = document.getElementById('chatForm');
    const messageInput = document.getElementById('message');
    const sendBtn = document.getElementById('sendBtn');

    // Check if there's a conversation ID, if not, we start fresh.
    let conversationId = localStorage.getItem('cv_conversation_id') || "";

    // Welcome message if fresh
    if(!conversationId) {
        setTimeout(() => {
            appendMessage('bot', "Hello! I am your AI CV writer. I'm going to ask you a few questions to gather your professional details. First, what is your full name and email address?");
        }, 500);
    } else {
        appendMessage('bot', "Welcome back! You can resume building your CV. What would you like to add?");
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        let message = messageInput.value.trim();
        if (!message) return;

        appendMessage('user', message);
        messageInput.value = '';
        messageInput.disabled = true;
        sendBtn.disabled = true;

        try {
            const res = await axios.post('/cv/chat', {
                message: message,
                conversation_id: conversationId
            }, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            conversationId = res.data.conversation_id;
            localStorage.setItem('cv_conversation_id', conversationId);

            // The bot reply might contain a url so we use innerHTML (carefully) for links
            appendMessage('bot', formatLink(res.data.reply));
        } catch (error) {
            console.error(error);
            appendMessage('bot', "Sorry, I encountered an error recording that. Please try again.");
        } finally {
            messageInput.disabled = false;
            sendBtn.disabled = false;
            messageInput.focus();
        }
    });

    function appendMessage(sender, htmlContent) {
        let div = document.createElement('div');
        div.classList.add('message', sender);

        let bubble = document.createElement('div');
        bubble.classList.add('bubble');
        bubble.innerHTML = htmlContent;

        div.appendChild(bubble);
        chatbox.appendChild(div);
        chatbox.scrollTop = chatbox.scrollHeight;
    }

    function formatLink(text) {
        // Automatically convert /cv/xyz links to actual HTML links
        text = text.replace(/\/cv\/(\d+)/g, '<a href="/cv/$1" target="_blank">View Your CV Here!</a>');
        // Also convert other URLs
        return text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>');
    }
</script>
</body>
</html>

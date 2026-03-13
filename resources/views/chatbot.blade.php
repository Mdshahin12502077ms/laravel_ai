<!DOCTYPE html>
<html>
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Laravel AI Chatbot</title>
<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
body{
font-family:Arial;
background:#f5f5f5;
}
.chat-container{
width:500px;
margin:auto;
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 0 10px #ccc;
}
#chatbox{
height:400px;
overflow-y:auto;
border:1px solid #ddd;
padding:10px;
margin-bottom:10px;
}
.message{
margin:5px 0;
}
.user{
color:blue;
}
.bot{
color:green;
}
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
<h2>AI Chatbot</h2>
<div id="chatbox"></div>
<form id="chatForm">
<input type="text" id="message" placeholder="Type message..." required>
<button type="submit">Send</button>
</form>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
let conversationId = localStorage.getItem('conversation_id');
const chatbox = document.getElementById('chatbox');
const form = document.getElementById('chatForm');
const messageInput = document.getElementById('message');

form.addEventListener('submit', async function(e) {
    e.preventDefault();
    let message = messageInput.value;
    appendMessage('You', message, 'user');
    messageInput.value = '';
    const res = await axios.post('/chat/send', {
        message: message,
        conversation_id: conversationId
    }, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });
    conversationId = res.data.conversation_id;
    localStorage.setItem('conversation_id', conversationId);
    appendMessage('Bot', res.data.reply, 'bot');
});

function appendMessage(sender, text, className) {
    let div = document.createElement('div');
    div.classList.add('message', className);
    div.innerHTML = sender + ": " + text;
    chatbox.appendChild(div);
    chatbox.scrollTop = chatbox.scrollHeight;
</script>
</body>
</html>

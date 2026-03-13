<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Audio Transcriber</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }

        .container { max-width: 600px; width: 100%; background: #1e293b; border-radius: 16px; padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.4); }

        h1 { text-align: center; font-size: 28px; margin-bottom: 8px; background: linear-gradient(135deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .subtitle { text-align: center; color: #94a3b8; font-size: 14px; margin-bottom: 30px; }

        .upload-area { border: 2px dashed #334155; border-radius: 12px; padding: 40px 20px; text-align: center; cursor: pointer; transition: all 0.3s ease; margin-bottom: 20px; position: relative; }
        .upload-area:hover, .upload-area.dragover { border-color: #38bdf8; background: rgba(56,189,248,0.05); }
        .upload-area .icon { font-size: 48px; margin-bottom: 10px; }
        .upload-area p { color: #94a3b8; font-size: 14px; }
        .upload-area .filename { color: #38bdf8; font-weight: bold; margin-top: 10px; font-size: 15px; }

        input[type="file"] { display: none; }

        .language-row { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
        .language-row label { font-size: 14px; color: #94a3b8; white-space: nowrap; }
        .language-row select { flex: 1; padding: 10px 14px; border-radius: 8px; border: 1px solid #334155; background: #0f172a; color: #e2e8f0; font-size: 14px; outline: none; }
        .language-row select:focus { border-color: #38bdf8; }

        .submit-btn { width: 100%; padding: 14px; background: linear-gradient(135deg, #38bdf8, #818cf8); color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: bold; cursor: pointer; transition: transform 0.2s, opacity 0.2s; }
        .submit-btn:hover { transform: translateY(-1px); opacity: 0.9; }
        .submit-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .loading { display: none; text-align: center; margin-top: 20px; }
        .loading.active { display: block; }
        .spinner { width: 40px; height: 40px; border: 4px solid #334155; border-top-color: #38bdf8; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 10px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading p { color: #94a3b8; font-size: 14px; }

        .error-msg { background: #7f1d1d; color: #fca5a5; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-size: 14px; }

        .formats { text-align: center; color: #475569; font-size: 12px; margin-top: 15px; }
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
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
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
<div class="container">
    <h1>🎙️ Audio Transcriber</h1>
    <p class="subtitle">Upload an audio file and we'll transcribe it using AI</p>

    @if($errors->any())
        <div class="error-msg">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form id="transcribeForm" action="{{ route('transcribe.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="upload-area" id="dropZone" onclick="document.getElementById('audioFile').click()">
            <div class="icon">📁</div>
            <p>Click or drag & drop your audio file here</p>
            <div class="filename" id="fileName"></div>
        </div>

        <input type="file" name="audio" id="audioFile" accept=".mp3,.wav,.webm,.ogg,.m4a,.flac">

        <div class="language-row">
            <label for="language">Language:</label>
            <select name="language" id="language">
                <option value="en">English</option>
                <option value="bn">Bengali</option>
                <option value="hi">Hindi</option>
                <option value="ar">Arabic</option>
                <option value="es">Spanish</option>
                <option value="fr">French</option>
                <option value="de">German</option>
                <option value="zh">Chinese</option>
                <option value="ja">Japanese</option>
                <option value="ko">Korean</option>
            </select>
        </div>

        <button type="submit" class="submit-btn" id="submitBtn" disabled>Transcribe Audio</button>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Transcribing your audio... this may take a moment.</p>
        </div>
    </form>

    <p class="formats">Supported formats: MP3, WAV, WebM, OGG, M4A, FLAC (max 25MB)</p>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('audioFile');
    const fileName = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('transcribeForm');
    const loading = document.getElementById('loading');

    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            fileName.textContent = '✅ ' + this.files[0].name;
            submitBtn.disabled = false;
        }
    });

    ['dragenter', 'dragover'].forEach(e => {
        dropZone.addEventListener(e, (ev) => { ev.preventDefault(); dropZone.classList.add('dragover'); });
    });
    ['dragleave', 'drop'].forEach(e => {
        dropZone.addEventListener(e, (ev) => { ev.preventDefault(); dropZone.classList.remove('dragover'); });
    });

    dropZone.addEventListener('drop', (ev) => {
        const files = ev.dataTransfer.files;
        if (files.length) {
            fileInput.files = files;
            fileName.textContent = '✅ ' + files[0].name;
            submitBtn.disabled = false;
        }
    });

    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
        loading.classList.add('active');
    });
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transcript - {{ $transcript->original_filename }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; padding: 40px 20px; }

        .container { max-width: 800px; margin: 0 auto; }

        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; background: linear-gradient(135deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 8px; }

        .meta { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; margin-bottom: 30px; }
        .meta-item { background: #1e293b; padding: 8px 16px; border-radius: 20px; font-size: 13px; color: #94a3b8; }
        .meta-item strong { color: #e2e8f0; }

        .transcript-box { background: #1e293b; border-radius: 16px; padding: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); margin-bottom: 20px; }
        .transcript-box h2 { font-size: 18px; color: #38bdf8; margin-bottom: 15px; }
        .transcript-text { font-size: 16px; line-height: 1.8; color: #cbd5e1; white-space: pre-wrap; word-wrap: break-word; }

        .actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn { padding: 12px 24px; border-radius: 10px; border: none; font-size: 14px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; transition: transform 0.2s, opacity 0.2s; }
        .btn:hover { transform: translateY(-1px); opacity: 0.9; }
        .btn-copy { background: #38bdf8; color: #0f172a; }
        .btn-new { background: #334155; color: #e2e8f0; }
        .btn-print { background: #818cf8; color: white; }

        .copied-toast { position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); background: #22c55e; color: white; padding: 10px 20px; border-radius: 8px; display: none; font-size: 14px; font-weight: bold; z-index: 99; }

        @media print {
            body { background: white; color: #333; padding: 20px; }
            .actions, .copied-toast { display: none !important; }
            .transcript-box { box-shadow: none; background: white; border: 1px solid #ddd; }
            .transcript-text { color: #333; }
            .header h1 { -webkit-text-fill-color: #333; }
            .meta-item { background: #f1f1f1; color: #333; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🎙️ Transcript Result</h1>
    </div>

    <div class="meta">
        <span class="meta-item">📄 <strong>{{ $transcript->original_filename }}</strong></span>
        <span class="meta-item">🌐 Language: <strong>{{ strtoupper($transcript->language ?? 'EN') }}</strong></span>
        <span class="meta-item">📅 <strong>{{ $transcript->created_at->format('d M Y, h:i A') }}</strong></span>
    </div>

    <div class="transcript-box">
        <h2>Transcription</h2>
        <div class="transcript-text" id="transcriptText">{{ $transcript->text }}</div>
    </div>

    <div class="actions">
        <button class="btn btn-copy" onclick="copyText()">📋 Copy Text</button>
        <a href="{{ route('transcribe') }}" class="btn btn-new">🔄 New Transcription</a>
        <button class="btn btn-print" onclick="window.print()">🖨️ Print</button>
    </div>
</div>

<div class="copied-toast" id="toast">✅ Copied to clipboard!</div>

<script>
    function copyText() {
        const text = document.getElementById('transcriptText').innerText;
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.getElementById('toast');
            toast.style.display = 'block';
            setTimeout(() => { toast.style.display = 'none'; }, 2000);
        });
    }
</script>

</body>
</html>

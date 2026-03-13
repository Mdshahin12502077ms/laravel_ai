<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resume->data['fullName'] ?? 'CV' }} - Resume</title>
    <style>
        body { font-family: 'Georgia', serif; background: #e9ecef; color: #333; margin: 0; padding: 40px; }
        .cv-page { max-width: 800px; margin: 0 auto; background: white; padding: 50px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 5px; }
        h1 { font-size: 36px; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 2px; color: #2c3e50; }
        .contact-info { font-size: 14px; color: #7f8c8d; margin-bottom: 30px; border-bottom: 2px solid #ecf0f1; padding-bottom: 20px; }
        .contact-info a { color: #2980b9; text-decoration: none; }
        h2 { font-size: 22px; color: #2980b9; border-bottom: 1px solid #ecf0f1; padding-bottom: 5px; margin-top: 30px; text-transform: uppercase; }
        .summary { font-size: 16px; line-height: 1.6; }
        .item { margin-bottom: 20px; }
        .item-title { font-weight: bold; font-size: 18px; color: #34495e; }
        .item-subtitle { color: #7f8c8d; font-style: italic; font-size: 15px; }
        .item-date { color: #95a5a6; font-size: 14px; margin-bottom: 5px; }
        ul { margin-top: 5px; padding-left: 20px; line-height: 1.5; }
        li { margin-bottom: 5px; }
        .skills-list { display: flex; flex-wrap: wrap; gap: 10px; padding: 0; list-style: none; }
        .skills-list li { background: #ecf0f1; padding: 5px 12px; border-radius: 15px; font-size: 14px; color: #2c3e50; font-weight: bold; }
        
        @media print {
            body { background: white; padding: 0; }
            .cv-page { box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>

<div class="cv-page">
    <h1>{{ $resume->data['fullName'] ?? 'Your Name' }}</h1>
    <div class="contact-info">
        @if(!empty($resume->data['email'])) Email: <a href="mailto:{{ $resume->data['email'] }}">{{ $resume->data['email'] }}</a> @endif
        @if(!empty($resume->data['phone'])) | Phone: {{ $resume->data['phone'] }} @endif
    </div>

    @if(!empty($resume->data['summary']))
    <h2>Professional Summary</h2>
    <div class="summary">
        <p>{{ $resume->data['summary'] }}</p>
    </div>
    @endif

    @if(!empty($resume->data['experience']))
    <h2>Experience</h2>
    @foreach($resume->data['experience'] as $exp)
        <div class="item">
            <div class="item-title">{{ $exp['jobTitle'] ?? $exp['title'] ?? 'Job Title' }} - {{ $exp['company'] ?? 'Company' }}</div>
            <div class="item-date">{{ $exp['dates'] ?? $exp['duration'] ?? $exp['date'] ?? '' }}</div>
            @if(!empty($exp['description']))
                <p>{{ $exp['description'] }}</p>
            @endif
        </div>
    @endforeach
    @endif

    @if(!empty($resume->data['education']))
    <h2>Education</h2>
    @foreach($resume->data['education'] as $edu)
        <div class="item">
            <div class="item-title">{{ $edu['degree'] ?? $edu['title'] ?? 'Degree' }} - {{ $edu['institution'] ?? $edu['school'] ?? 'Institution' }}</div>
            <div class="item-date">{{ $edu['dates'] ?? $edu['year'] ?? '' }}</div>
        </div>
    @endforeach
    @endif

    @if(!empty($resume->data['skills']))
    <h2>Skills</h2>
    <ul class="skills-list">
        @foreach($resume->data['skills'] as $skill)
            @if(is_string($skill))
                <li>{{ $skill }}</li>
            @elseif(is_array($skill) && isset($skill['name']))
                <li>{{ $skill['name'] }}</li>
            @endif
        @endforeach
    </ul>
    @endif
</div>

<div style="text-align: center; margin-top: 30px;">
    <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #2980b9; color: white; border: none; border-radius: 5px; font-size: 16px;">Print / Save as PDF</button>
</div>

</body>
</html>

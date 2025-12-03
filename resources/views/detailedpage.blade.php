<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Berita - Full Detail Page</title>
  <style>
    :root{
      --red:#c8102e;
      --deep-red:#9a0f24;
      --white:#ffffff;
      --muted:#6b7280;
      --card-shadow: 0 6px 18px rgba(0,0,0,0.08);
      --radius:18px;
    }

    body{
      margin:0;font-family:Inter, system-ui;background:#fff;color:#111;padding:24px;
    }

    .container{max-width:900px;margin:0 auto}

    .detail-hero{width:100%;height:380px;object-fit:cover;border-radius:20px;box-shadow:var(--card-shadow)}
    .btn-like{background:linear-gradient(180deg,var(--red),var(--deep-red));color:#fff;padding:10px 16px;border-radius:10px;border:0;cursor:pointer;font-size:16px;margin-top:10px}

    .comment-box{margin-top:30px}
    .comment-form{display:flex;gap:10px;margin-top:8px}
    .comment-form input{flex:1;padding:10px;border-radius:10px;border:1px solid #ddd}

    .comment{background:#fff;padding:12px;margin-top:10px;border-radius:12px;box-shadow:var(--card-shadow)}
  </style>
</head>
<body>
<div class="container">

  <!-- LANGSUNG FULL PAGE DETAIL -->
  <img src="https://picsum.photos/id/1015/1200/700" class="detail-hero" />

  <h1 style="margin-top:20px">Judul Berita Pertama</h1>
  <div style="color:var(--muted);font-size:14px;margin-bottom:10px">oleh Redaksi • 3 Des 2025</div>

  <p style="line-height:1.7">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum at vulputate mi. Aenean egestas, nisl id hendrerit posuere, lacus sem cursus dolor, eget cursus ipsum urna nec augue. Curabitur at diam non massa malesuada aliquet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse pharetra felis vel magna tempus luctus.</p>

  <button class="btn-like" onclick="addLike()">❤ <span id="likeCount">0</span></button>

  <div class="comment-box">
    <h2>Komentar (<span id="commentCount">0</span>)</h2>

    <div class="comment-form">
      <input id="commentInput" placeholder="Tulis komentar..." />
      <button onclick="addComment()">Kirim</button>
    </div>

    <div id="commentsList"></div>
  </div>

</div>

<script>
let likes = 0;
let comments = [];

function addLike(){
  likes++;
  document.getElementById('likeCount').innerText = likes;
}

function addComment(){
  const input = document.getElementById('commentInput');
  const text = input.value.trim();
  if(!text) return;
  comments.push(text);
  input.value = '';
  renderComments();
}

function renderComments(){
  const list = document.getElementById('commentsList');
  list.innerHTML = comments.map(c=>`<div class='comment'>${c}</div>`).join('');
  document.getElementById('commentCount').innerText = comments.length;
}
</script>
</body>
</html>

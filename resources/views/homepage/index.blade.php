<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>100 Free Images Gallery</title>
  <style>
    :root {
      --gap: 10px;
      --min-col: 180px;
    }
    body {
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      margin: 0;
      padding: 20px;
      background: #f7f7f8;
      color: #111;
    }
    header {
      margin-bottom: 14px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
    }
    h1 { font-size: 1.1rem; margin: 0; }
    p.small { margin: 0; color: #555; font-size: .9rem; }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(var(--min-col), 1fr));
      gap: var(--gap);
    }

    figure {
      margin: 0;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(16,24,40,0.06);
      display: flex;
      flex-direction: column;
      min-height: 120px;
    }

    img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      display: block;
      background: #eee;
    }

    figcaption {
      padding: 8px 10px;
      font-size: .85rem;
      color: #444;
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:8px;
    }

    a.source {
      font-size: .75rem;
      color: #0b63d6;
      text-decoration: none;
    }

    footer {
      margin-top: 16px;
      font-size: .85rem;
      color: #666;
    }

    /* simple responsive tweak */
    @media (max-width:420px){
      img { height: 140px; }
    }
  </style>
</head>
<body>
  <header>
    <div>
      <h1>100 Free Images (picsum.photos)</h1>
      <p class="small">Images are loaded from <strong>picsum.photos</strong> using deterministic seeds. All images are free placeholders.</p>
    </div>
    <div>
      <button id="regen">Reload different images</button>
    </div>
  </header>

  <main>
    <section id="gallery" class="grid" aria-live="polite">
      <!-- images will be injected here by JavaScript -->
    </section>

    <noscript>
      <p style="color:#b00">JavaScript is required to load all 100 images automatically. Enable JS or manually add <code>&lt;img src="https://picsum.photos/seed/1/800/600"&gt;</code> tags.</p>
    </noscript>
  </main>

  <footer>
    <p>Image source: <a class="source" href="https://picsum.photos" target="_blank" rel="noopener">picsum.photos</a> — free to use as placeholders.</p>
  </footer>

  <script>
    // Generates 100 <figure><img/></figure> entries with deterministic seeds.
    const gallery = document.getElementById('gallery');

    function buildGallery(seedOffset = 0) {
      gallery.innerHTML = '';
      // create 100 images
      for (let i = 1; i <= 100; i++) {
        const seed = i + seedOffset;
        // Using the seed-based endpoint keeps images consistent across reloads when same seed is used.
        const src = `https://picsum.photos/seed/${seed}/800/600`;
        const fig = document.createElement('figure');

        const img = document.createElement('img');
        img.src = src;
        img.alt = `Random placeholder image ${i}`;
        img.loading = 'lazy';
        img.decoding = 'async';

        const cap = document.createElement('figcaption');
        const left = document.createElement('span');
        left.textContent = `#${i}`;

        const right = document.createElement('a');
        right.textContent = 'picsum';
        right.href = src;
        right.target = '_blank';
        right.rel = 'noopener';
        right.className = 'source';

        cap.appendChild(left);
        cap.appendChild(right);

        fig.appendChild(img);
        fig.appendChild(cap);
        gallery.appendChild(fig);
      }
      // scroll to top when regenerated
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // initial build
    buildGallery(0);

    // button to change seeds (get different images)
    document.getElementById('regen').addEventListener('click', () => {
      // use a simple random offset to vary the seeds
      const offset = Math.floor(Math.random() * 10000) + 1;
      buildGallery(offset);
    });
  </script>
</body>
</html>

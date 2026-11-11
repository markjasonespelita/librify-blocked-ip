@extends('layouts.main')

@section('title', 'Release Notes')

@section('content')
<div class="rn-container">
    <header class="rn-header">
        <h1>📦 Release Notes</h1>
        <p>Latest updates and improvements to the platform</p>
    </header>

    <div id="release-notes" class="rn-list">
        <div class="rn-loading">Loading release notes…</div>
    </div>
</div>

<style>
/* =========================
   Layout
========================= */
.rn-container {
    max-width: 900px;
    margin: auto;
    padding: 32px 20px;
}

.rn-header {
    margin-bottom: 40px;
}

.rn-header h1 {
    font-size: 32px;
    font-weight: 800;
}

.rn-header p {
    color: #6c757d;
    margin-top: 6px;
}

/* =========================
   Cards
========================= */
.rn-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 28px;
    box-shadow: 0 12px 30px rgba(0,0,0,.08);
    margin-bottom: 32px;
}

.rn-card-header {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.rn-version {
    font-size: 22px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.rn-badge {
    background: #0d6efd;
    color: #fff;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 999px;
}

/* =========================
   Meta
========================= */
.rn-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #6c757d;
}

.rn-meta img {
    width: 28px;
    height: 28px;
    border-radius: 50%;
}

/* =========================
   Body
========================= */
.rn-body {
    margin-top: 20px;
    line-height: 1.75;
}

.rn-body h1,
.rn-body h2,
.rn-body h3 {
    margin-top: 22px;
}

.rn-body ul {
    padding-left: 20px;
}

.rn-body code {
    background: #f1f3f5;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 90%;
}

/* =========================
   Footer
========================= */
.rn-footer {
    margin-top: 20px;
}

.rn-footer a {
    font-weight: 600;
    font-size: 14px;
    color: #0d6efd;
    text-decoration: none;
}

.rn-footer a:hover {
    text-decoration: underline;
}

/* =========================
   States
========================= */
.rn-loading {
    color: #6c757d;
    font-size: 15px;
    text-align: center;
    padding: 40px;
}

/* =========================
   Responsive
========================= */
@media (max-width: 600px) {
    .rn-card {
        padding: 20px;
    }

    .rn-version {
        font-size: 18px;
    }
}
</style>

<script>
(function () {
    const OWNER = "{{ config('app.github_owner') }}";
    const REPO = "{{ config('app.github_repo') }}";
    const TOKEN = '{{ config("app.github_token") }}';

    const container = document.getElementById('release-notes');

    async function loadMarked() {
        if (window.marked) return;
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/marked/marked.min.js';
        document.head.appendChild(script);
        return new Promise(resolve => script.onload = resolve);
    }

    async function fetchReleases() {
        try {
            const res = await fetch(`https://api.github.com/repos/${OWNER}/${REPO}/releases`, {
                headers: {
                    Authorization: `token ${TOKEN}`,
                    Accept: 'application/vnd.github+json'
                }
            });

            if (!res.ok) throw new Error('GitHub API error');

            const releases = await res.json();
            renderReleases(releases);
        } catch (err) {
            console.error(err);
            container.innerHTML = `<div class="rn-loading">Failed to load release notes.</div>`;
        }
    }

    function renderReleases(releases) {
        if (!releases.length) {
            container.innerHTML = `<div class="rn-loading">No releases found.</div>`;
            return;
        }

        let html = '';

        releases.forEach((release, index) => {
            const date = new Date(release.published_at).toLocaleDateString();
            const author = release.author?.login ?? 'Unknown';
            const avatar = release.author?.avatar_url ?? '';
            const isLatest = index === 0;

            html += `
                <article class="rn-card">
                    <div class="rn-card-header">
                        <div class="rn-version">
                            ${release.tag_name}
                            ${isLatest ? `<span class="rn-badge">Latest</span>` : ''}
                        </div>

                        <div class="rn-meta">
                            ${avatar ? `<img src="${avatar}" alt="${author}">` : ''}
                            <span>${author}</span>
                            <span>•</span>
                            <span>${date}</span>
                        </div>
                    </div>

                    <div class="rn-body">
                        ${marked.parse(release.body || '_No release notes provided._')}
                    </div>

                    <div class="rn-footer">
                        <a href="${release.html_url}" target="_blank">
                            View on GitHub →
                        </a>
                    </div>
                </article>
            `;
        });

        container.innerHTML = html;
    }

    document.addEventListener('DOMContentLoaded', async () => {
        await loadMarked();
        fetchReleases();
    });
})();
</script>
@endsection

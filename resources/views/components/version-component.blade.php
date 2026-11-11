<small id="app-version" class="fw-bold text-light">v...</small>

<script>
const GITHUB_TOKEN = "{{ config('app.github_token') }}";
const OWNER = "{{ config('app.github_owner') }}";
const REPO = "{{ config('app.github_repo') }}";

fetch(`https://api.github.com/repos/${OWNER}/${REPO}/releases/latest`, {
    headers: {
        'Authorization': `Bearer ${GITHUB_TOKEN}`,
        'Accept': 'application/vnd.github+json'
    }
})
.then(res => res.json())
.then(data => {
    document.getElementById('app-version').textContent = data.tag_name;
})
.catch(err => {
    console.error(err);
    document.getElementById('app-version').textContent = 'v?';
});
</script>

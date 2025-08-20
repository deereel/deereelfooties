function schedulePost(postId) {
    document.getElementById('schedulePostId').value = postId;
    new bootstrap.Modal(document.getElementById('scheduleModal')).show();
}

function viewPost(postId) {
    fetch(`api/get-post.php?id=${postId}`)
        .then(response => response.json())
        .then(data => {
            alert(`Topic: ${data.topic}\n\nContent:\n${data.content}`);
        });
}

function downloadPost(postId) {
    window.open(`api/download-post.php?id=${postId}`, '_blank');
}

function downloadZip(postId) {
    window.open(`api/download-zip.php?id=${postId}`, '_blank');
}

// Auto-generate posts twice a week
function setupAutoGeneration() {
    const now = new Date();
    const dayOfWeek = now.getDay();
    
    // Generate on Tuesday (2) and Friday (5) at 6 AM or 6 PM
    if ((dayOfWeek === 2 || dayOfWeek === 5) && 
        (now.getHours() === 6 || now.getHours() === 18)) {
        
        fetch('api/auto-generate.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'auto_generate'})
        });
    }
}

// Check every hour for auto-generation
setInterval(setupAutoGeneration, 3600000);
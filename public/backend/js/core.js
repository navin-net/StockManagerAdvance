const btnFullscreen = document.getElementById('btnFullscreen');
const icon = btnFullscreen?.querySelector('i');
function enterFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    }
}
function exitFullscreen() {
    if (document.fullscreenElement) {
        document.exitFullscreen();
    }
}
function toggleFullscreen() {
    if (!document.fullscreenElement) {
        enterFullscreen();
    } else {
        exitFullscreen();
    }
}
if (btnFullscreen) {
    btnFullscreen.addEventListener('click', toggleFullscreen);
}
document.addEventListener('keydown', function (e) {
    if (['INPUT', 'TEXTAREA'].includes(e.target.tagName)) return;
    if (e.target.isContentEditable) return;
    if (e.key === 'F11') {
        e.preventDefault();
        toggleFullscreen();
    }
    if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'f') {
        e.preventDefault();
        toggleFullscreen();
    }
});
document.addEventListener('fullscreenchange', () => {
    if (document.fullscreenElement) {
        document.body.classList.add('is-fullscreen');
        if (icon) {
            icon.classList.replace('bi-arrows-fullscreen', 'bi-fullscreen-exit');
        }
    } else {
        document.body.classList.remove('is-fullscreen');
        if (icon) {
            icon.classList.replace('bi-fullscreen-exit', 'bi-arrows-fullscreen');
        }
    }
});


import './bootstrap';
import './custom-editor';

// Scroll reveal
document.addEventListener('DOMContentLoaded', function() {
    if (!window.IntersectionObserver) return;
    var revealEls = document.querySelectorAll('.reveal');
    if (!revealEls.length) return;
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    revealEls.forEach(function(el) { observer.observe(el); });
});

// PWA Service Worker Registration
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        // Only register on http(s) — never on file:// or other protocols
        if (!['http:', 'https:'].includes(location.protocol)) return;

        navigator.serviceWorker.register('/sw.js').then(function(registration) {
            // Check for updates periodically
            if (registration.waiting) {
                registration.waiting.postMessage({ type: 'SKIP_WAITING' });
            }
            registration.addEventListener('updatefound', function() {
                var sw = registration.installing;
                if (!sw) return;
                sw.addEventListener('statechange', function() {
                    if (sw.state === 'installed' && navigator.serviceWorker.controller) {
                        // New version installed — silently activate
                        sw.postMessage({ type: 'SKIP_WAITING' });
                    }
                });
            });
        }, function(err) {
            // Silent — don't spam console on dev / unsupported envs
        });
    });

    // Reload once when a new SW takes control
    var refreshing = false;
    navigator.serviceWorker.addEventListener('controllerchange', function() {
        if (refreshing) return;
        refreshing = true;
        location.reload();
    });
}

// Reading Progress Bar
document.addEventListener('DOMContentLoaded', function() {
    var bar = document.getElementById('reading-progress');
    var article = document.querySelector('article.md\\:col-span-8, .prose-bn');
    if (!bar || !article) return;
    window.addEventListener('scroll', function() {
        var articleTop = article.getBoundingClientRect().top + window.scrollY;
        var articleBottom = articleTop + article.offsetHeight;
        var scrolled = window.scrollY + window.innerHeight;
        var progress = Math.min(100, Math.max(0, ((window.scrollY - articleTop) / (articleBottom - articleTop - window.innerHeight)) * 100));
        bar.style.width = progress + '%';
    }, { passive: true });
});

// Image Lightbox for article body images
document.addEventListener('DOMContentLoaded', function() {
    var articleBody = document.querySelector('.prose-bn, .article-body');
    if (!articleBody) return;
    var images = articleBody.querySelectorAll('img');
    if (!images.length) return;

    // Create overlay
    var overlay = document.createElement('div');
    overlay.className = 'lightbox-overlay';
    overlay.innerHTML = '<img class="lightbox-img" src="" alt="">';
    document.body.appendChild(overlay);
    var lbImg = overlay.querySelector('.lightbox-img');

    images.forEach(function(img) {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', function() {
            lbImg.src = img.src;
            lbImg.alt = img.alt;
            overlay.classList.add('active');
            document.body.classList.add('lightbox-active');
        });
    });

    overlay.addEventListener('click', function() {
        overlay.classList.remove('active');
        document.body.classList.remove('lightbox-active');
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            overlay.classList.remove('active');
            document.body.classList.remove('lightbox-active');
        }
    });
});

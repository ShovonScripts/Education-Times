class CustomEditor {
    constructor(textarea, options = {}) {
        this.textarea = textarea;
        this.options = Object.assign({
            height: 400,
            placeholder: '',
        }, options);

        this.isSource = false;
        this.isFullscreen = false;

        this.wrapper = null;
        this.toolbar = null;
        this.contentEditable = null;
        this.sourceArea = null;
        this.statusBar = null;
        this.overlay = null;

        this.init();
    }

    init() {
        this.buildUI();
        this.syncContent();
        this.bindEvents();
        this.textarea.style.display = 'none';
    }

    buildUI() {
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'custom-editor-wrapper';

        this.toolbar = document.createElement('div');
        this.toolbar.className = 'custom-editor-toolbar';
        this.toolbar.innerHTML = this.toolbarHTML();

        this.contentEditable = document.createElement('div');
        this.contentEditable.className = 'custom-editor-content';
        this.contentEditable.contentEditable = 'true';
        this.contentEditable.innerHTML = this.textarea.value || '';

        this.sourceArea = document.createElement('textarea');
        this.sourceArea.className = 'custom-editor-source';
        this.sourceArea.style.display = 'none';

        this.statusBar = document.createElement('div');
        this.statusBar.className = 'custom-editor-status';
        this.statusBar.innerHTML = '<span class="ce-words">শব্দ: 0</span><span class="ce-html">HTML</span>';

        this.wrapper.appendChild(this.toolbar);
        this.wrapper.appendChild(this.contentEditable);
        this.wrapper.appendChild(this.sourceArea);
        this.wrapper.appendChild(this.statusBar);

        this.textarea.parentNode.insertBefore(this.wrapper, this.textarea);
    }

    toolbarHTML() {
        return `
            <button type="button" class="ce-btn" data-cmd="undo" title="পূর্বাবস্থা"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10h10a5 5 0 0 1 0 10H9"/><path d="M3 10l4-4m-4 4l4 4"/></svg></button>
            <button type="button" class="ce-btn" data-cmd="redo" title="পুনরায়"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10H11a5 5 0 0 0 0 10h4"/><path d="M21 10l-4-4m4 4l-4 4"/></svg></button>
            <span class="ce-divider"></span>
            <button type="button" class="ce-btn" data-cmd="bold" title="বোল্ড"><b style="font-family:serif">B</b></button>
            <button type="button" class="ce-btn" data-cmd="italic" title="ইটালিক"><i style="font-family:serif">I</i></button>
            <button type="button" class="ce-btn" data-cmd="underline" title="আন্ডারলাইন"><u style="font-family:serif">U</u></button>
            <button type="button" class="ce-btn" data-cmd="strikeThrough" title="স্ট্রাইক"><s style="font-family:serif">S</s></button>
            <span class="ce-divider"></span>
            <select class="ce-select" data-cmd="fontSize" title="ফন্ট সাইজ">
                <option value="">সাইজ</option>
                <option value="5">১০</option>
                <option value="6">১২</option>
                <option value="7">১৪</option>
                <option value="3">১৬</option>
                <option value="4">১৮</option>
                <option value="5">২০</option>
                <option value="6">২৪</option>
                <option value="7">৩২</option>
            </select>
            <select class="ce-select" data-cmd="formatBlock" title="ফরম্যাট">
                <option value="">ফরম্যাট</option>
                <option value="<p>">অনুচ্ছেদ</option>
                <option value="<h2>">শিরোনাম ২</option>
                <option value="<h3>">শিরোনাম ৩</option>
                <option value="<h4>">শিরোনাম ৪</option>
            </select>
            <span class="ce-divider"></span>
            <button type="button" class="ce-btn" data-cmd="justifyLeft" title="বামে"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 10h14M3 14h18M3 18h14"/></svg></button>
            <button type="button" class="ce-btn" data-cmd="justifyCenter" title="মাঝে"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M5 10h14M3 14h18M5 18h14"/></svg></button>
            <button type="button" class="ce-btn" data-cmd="justifyRight" title="ডানে"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M7 10h14M3 14h18M7 18h14"/></svg></button>
            <span class="ce-divider"></span>
            <button type="button" class="ce-btn" data-cmd="insertUnorderedList" title="বুলেট লিস্ট"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg></button>
            <button type="button" class="ce-btn" data-cmd="insertOrderedList" title="নাম্বার লিস্ট"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 6h11M10 12h11M10 18h11M4 6h1v4M4 10h2M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg></button>
            <button type="button" class="ce-btn" data-cmd="blockquote" title="ব্লককোট"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/></svg></button>
            <span class="ce-divider"></span>
            <button type="button" class="ce-btn" data-cmd="link" title="লিংক"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg></button>
            <button type="button" class="ce-btn" data-cmd="image" title="ছবি"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg></button>
            <span class="ce-divider"></span>
            <button type="button" class="ce-btn" data-cmd="source" title="HTML"> <code>&lt;/&gt;</code></button>
            <button type="button" class="ce-btn" data-cmd="fullscreen" title="পূর্ণ পর্দা"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg></button>
        `;
    }

    syncContent() {
        this.textarea.value = this.contentEditable.innerHTML;
        this.updateWordCount();
    }

    updateWordCount() {
        const text = this.contentEditable.innerText || '';
        const words = text.replace(/[\s]+/g, ' ').trim().split(' ').filter(Boolean).length;
        const wordEl = this.statusBar.querySelector('.ce-words');
        if (wordEl) wordEl.textContent = 'শব্দ: ' + words;
    }

    bindEvents() {
        this.toolbar.addEventListener('click', (e) => {
            const btn = e.target.closest('.ce-btn');
            if (!btn) return;
            const cmd = btn.dataset.cmd;
            if (cmd) this.execCommand(cmd, btn);
        });

        this.toolbar.addEventListener('change', (e) => {
            if (e.target.tagName === 'SELECT') {
                this.execCommand(e.target.dataset.cmd, e.target);
            }
        });

        this.contentEditable.addEventListener('input', () => {
            this.syncContent();
        });

        this.contentEditable.addEventListener('keyup', () => {
            this.updateToolbarState();
        });

        this.contentEditable.addEventListener('mouseup', () => {
            this.updateToolbarState();
        });

        this.sourceArea.addEventListener('input', () => {
            this.textarea.value = this.sourceArea.value;
        });

        const form = this.textarea.closest('form');
        if (form) {
            form.addEventListener('submit', () => {
                this.syncContent();
            });
        }

        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                this.syncContent();
                const form = this.textarea.closest('form');
                if (form) form.submit();
            }
        });
    }

    execCommand(cmd, target) {
        switch (cmd) {
            case 'bold':
            case 'italic':
            case 'underline':
            case 'strikeThrough':
            case 'justifyLeft':
            case 'justifyCenter':
            case 'justifyRight':
            case 'justifyFull':
            case 'insertUnorderedList':
            case 'insertOrderedList':
            case 'outdent':
            case 'indent':
                document.execCommand(cmd, false, null);
                this.syncContent();
                this.updateToolbarState();
                break;

            case 'formatBlock':
                const blockVal = target.value;
                if (blockVal) {
                    document.execCommand('formatBlock', false, blockVal);
                    target.value = '';
                }
                this.syncContent();
                break;

            case 'fontSize':
                const sizeVal = target.value;
                if (sizeVal) {
                    document.execCommand('fontSize', false, sizeVal);
                    target.value = '';
                }
                this.syncContent();
                break;

            case 'blockquote':
                document.execCommand('formatBlock', false, '<blockquote>');
                this.syncContent();
                break;

            case 'link':
                this.showLinkDialog();
                break;

            case 'image':
                this.showImageDialog();
                break;

            case 'source':
                this.toggleSource();
                break;

            case 'fullscreen':
                this.toggleFullscreen();
                break;

            case 'undo':
                document.execCommand('undo', false, null);
                this.syncContent();
                break;

            case 'redo':
                document.execCommand('redo', false, null);
                this.syncContent();
                break;
        }
    }

    updateToolbarState() {
        const btns = this.toolbar.querySelectorAll('.ce-btn[data-cmd]');
        btns.forEach(btn => {
            const cmd = btn.dataset.cmd;
            if (['bold', 'italic', 'underline', 'strikeThrough'].includes(cmd)) {
                try {
                    if (document.queryCommandState(cmd)) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                } catch(e) {}
            }
        });
    }

    toggleSource() {
        this.isSource = !this.isSource;
        if (this.isSource) {
            this.sourceArea.value = this.contentEditable.innerHTML;
            this.contentEditable.style.display = 'none';
            this.sourceArea.style.display = 'block';
        } else {
            this.contentEditable.innerHTML = this.sourceArea.value;
            this.textarea.value = this.sourceArea.value;
            this.contentEditable.style.display = 'block';
            this.sourceArea.style.display = 'none';
            this.syncContent();
        }
    }

    toggleFullscreen() {
        this.isFullscreen = !this.isFullscreen;
        if (this.isFullscreen) {
            this.wrapper.classList.add('custom-editor-fullscreen');
            document.body.style.overflow = 'hidden';
        } else {
            this.wrapper.classList.remove('custom-editor-fullscreen');
            document.body.style.overflow = '';
        }
    }

    showLinkDialog() {
        const selection = window.getSelection();
        const selectedText = selection ? selection.toString() : '';
        const currentUrl = selectedText ? '' : '';

        const overlay = document.createElement('div');
        overlay.className = 'ce-dialog-overlay';
        overlay.innerHTML = `
            <div class="ce-dialog">
                <h3>লিংক যোগ করুন</h3>
                <label>URL</label>
                <input type="url" id="ceLinkUrl" placeholder="https://..." value="${currentUrl}">
                <label>টেক্সট</label>
                <input type="text" id="ceLinkText" placeholder="লিংকের টেক্সট" value="${selectedText}">
                <div class="ce-dialog-actions">
                    <button type="button" class="btn-outline" onclick="this.closest('.ce-dialog-overlay').remove()">বাতিল</button>
                    <button type="button" class="btn-primary" onclick="this.closest('.ce-dialog-overlay').querySelector('#ceLinkUrl').closest('.ce-dialog-overlay')._editor.insertLink()">যোগ করুন</button>
                </div>
            </div>
        `;
        overlay._editor = this;
        document.body.appendChild(overlay);

        const urlInput = overlay.querySelector('#ceLinkUrl');
        setTimeout(() => urlInput.focus(), 100);

        overlay.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') this.insertLink(overlay);
            if (e.key === 'Escape') overlay.remove();
        });
    }

    insertLink(overlay) {
        if (!overlay) overlay = document.querySelector('.ce-dialog-overlay');
        if (!overlay) return;
        const url = overlay.querySelector('#ceLinkUrl').value.trim();
        const text = overlay.querySelector('#ceLinkText').value.trim() || url;
        if (!url) return;

        const sel = window.getSelection();
        if (sel && sel.toString()) {
            document.execCommand('insertHTML', false, `<a href="${url}" target="_blank">${sel.toString()}</a>`);
        } else {
            document.execCommand('insertHTML', false, `<a href="${url}" target="_blank">${text}</a>`);
        }
        this.syncContent();
        overlay.remove();
    }

    showImageDialog() {
        if (typeof window.editorImageUploadUrl !== 'string') {
            const url = prompt('ছবির URL দিন:');
            if (url) this.insertImage(url);
            return;
        }

        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.onchange = () => {
            const file = input.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            fetch(window.editorImageUploadUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData,
            })
                .then(r => r.json())
                .then(data => { if (data.url) this.insertImage(data.url); })
                .catch(() => alert('ছবি আপলোড ব্যর্থ হয়েছে!'));
        };

        input.click();
    }

    insertImage(url) {
        const img = `<img src="${url}" alt="" style="max-width:100%">`;
        document.execCommand('insertHTML', false, img);
        this.syncContent();
    }

}

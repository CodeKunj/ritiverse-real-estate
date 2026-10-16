/**
 * RiTiVerse Admin Panel - Core Interactive Engine
 * Handles tabs, modals, file preview, YouTube ID extraction, dynamic fields, toasts
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Sidebar Toggle
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('is-open');
        });

        // Close sidebar on click outside
        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('is-open') && !sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                sidebar.classList.remove('is-open');
            }
        });
    }

    // 2. Tab Navigation
    const tabBtns = document.querySelectorAll('.tab-btn');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const group = btn.closest('.tab-wrapper') || document;
            const targetId = btn.getAttribute('data-tab');

            group.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            group.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            btn.classList.add('active');
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });

    // 3. File Input Auto Preview
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                const parent = this.closest('.form-group') || this.parentElement;
                const previewImg = parent.querySelector('.preview-target');
                const filenameLabel = parent.querySelector('.filename-target');

                reader.onload = (e) => {
                    if (previewImg) {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                    }
                };
                reader.readAsDataURL(this.files[0]);

                if (filenameLabel) {
                    filenameLabel.textContent = this.files[0].name + ' (will convert to .webp)';
                }
            }
        });
    });

    // 4. Modal Open & Close Handler
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('is-active');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('is-active');
            document.body.style.overflow = '';
        }
    };

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('is-active');
                document.body.style.overflow = '';
            }
        });
    });

    // 5. YouTube Video URL Auto ID & Thumbnail Extraction
    const youtubeInput = document.getElementById('youtubeUrlInput');
    const youtubePreviewBox = document.getElementById('youtubePreviewBox');
    const youtubeIframe = document.getElementById('youtubePreviewIframe');

    if (youtubeInput && youtubePreviewBox && youtubeIframe) {
        const checkYoutube = () => {
            const url = youtubeInput.value.trim();
            const match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
            if (match && match[1]) {
                const videoId = match[1];
                youtubePreviewBox.style.display = 'block';
                youtubeIframe.src = `https://www.youtube.com/embed/${videoId}`;
            } else {
                youtubePreviewBox.style.display = 'none';
                youtubeIframe.src = '';
            }
        };

        youtubeInput.addEventListener('input', checkYoutube);
        youtubeInput.addEventListener('change', checkYoutube);
    }
});

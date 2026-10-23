{{-- Global Confirmation / Action Preview Modal (SIMAGANG Standard) --}}
<div class="modal fade" id="globalConfirmationModal" tabindex="-1" aria-labelledby="globalConfirmationModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-slate-200 dark:border-slate-800 shadow-2xl rounded-2xl overflow-hidden bg-white dark:bg-slate-900">
            <!-- Modal Header with dynamic color theme -->
            <div id="gcm-header" class="modal-header py-2.5 px-3.5 text-white bg-blue-600 transition-colors">
                <h5 class="modal-title text-xs mb-0 text-white font-bold uppercase tracking-wider d-flex align-items-center gap-2" id="globalConfirmationModalLabel">
                    <i id="gcm-icon" class="bi bi-question-circle-fill fs-6"></i>
                    <span id="gcm-title">Konfirmasi Tindakan</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Batal"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-3 sm:p-4 text-xs space-y-3">
                <!-- Summary Info Box (Entitas & Detail) -->
                <div id="gcm-summary-box" class="p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-xs">
                    <div id="gcm-summary-content" class="text-slate-800 dark:text-slate-200 leading-relaxed">
                        <!-- Populated via JS -->
                    </div>
                </div>

                <!-- Custom / Form Preview Area (opsional jika ada preview berkas/dropzone) -->
                <div id="gcm-preview-area" class="hidden">
                    <div id="gcm-preview-content"></div>
                </div>

                <!-- Informatif Alert Box (Dampak / Penjelasan Aksi) -->
                <div id="gcm-alert-box" class="p-3 text-xs border rounded-xl d-flex align-items-start gap-2 bg-sky-50 text-sky-900 border-sky-200 dark:bg-sky-950/40 dark:text-sky-200 dark:border-sky-800">
                    <i id="gcm-alert-icon" class="bi bi-info-circle-fill text-sky-600 dark:text-sky-400 mt-0.5 flex-shrink-0 fs-6"></i>
                    <div id="gcm-alert-text" class="leading-relaxed">
                        Pastikan data yang Anda periksa sudah benar sebelum melanjutkan. Tindakan ini akan diproses langsung ke server.
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-slate-50 dark:bg-slate-800/60 border-top border-slate-200 dark:border-slate-800 py-2 px-3.5 d-flex justify-content-between align-items-center">
                <button type="button" id="gcm-btn-cancel" class="bg-slate-500 hover:bg-slate-600 active:bg-slate-700 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow-sm transition-all border-0" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" id="gcm-btn-confirm" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow-sm transition-all border-0 d-inline-flex align-items-center gap-1.5 cursor-pointer">
                    <span id="gcm-btn-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <i id="gcm-btn-icon" class="bi bi-check-lg"></i>
                    <span id="gcm-btn-text">Ya, Lanjutkan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
window.SimangModal = (function () {
    let pendingCallback = null;
    let pendingForm = null;

    function getModalEl() {
        return document.getElementById('globalConfirmationModal');
    }

    function getBsModal() {
        const el = getModalEl();
        if (!el || typeof bootstrap === 'undefined') return null;
        return bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
    }

    /**
     * Show Standard Confirmation Modal
     * @param {Object} options
     * - title: String (Judul modal)
     * - icon: String (Class Bootstrap Icon, e.g. 'bi-trash-fill')
     * - theme: 'blue' | 'rose' | 'emerald' | 'amber' (default 'blue')
     * - summaryHtml: String (HTML ringkasan data)
     * - alertText: String (Penjelasan dampak)
     * - confirmText: String (Label tombol konfirmasi)
     * - confirmIcon: String (Ikon tombol konfirmasi)
     * - onConfirm: Function (Callback saat dikonfirmasi)
     * - form: HTMLFormElement (Form opsional yang akan di-submit otomatis saat dikonfirmasi)
     */
    function confirm(options) {
        options = options || {};
        const title = options.title || 'Konfirmasi Tindakan';
        const icon = options.icon || 'bi-question-circle-fill';
        const theme = options.theme || 'blue';
        const summaryHtml = options.summaryHtml || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
        const alertText = options.alertText || 'Pastikan data yang Anda periksa sudah benar sebelum melanjutkan ke sistem.';
        const confirmText = options.confirmText || 'Ya, Lanjutkan';
        const confirmIcon = options.confirmIcon || 'bi-check-lg';

        pendingCallback = typeof options.onConfirm === 'function' ? options.onConfirm : null;
        pendingForm = options.form || null;

        // Elements
        const header = document.getElementById('gcm-header');
        const titleEl = document.getElementById('gcm-title');
        const iconEl = document.getElementById('gcm-icon');
        const summaryContent = document.getElementById('gcm-summary-content');
        const alertTextEl = document.getElementById('gcm-alert-text');
        const alertBox = document.getElementById('gcm-alert-box');
        const alertIcon = document.getElementById('gcm-alert-icon');
        const btnConfirm = document.getElementById('gcm-btn-confirm');
        const btnCancel = document.getElementById('gcm-btn-cancel');
        const btnText = document.getElementById('gcm-btn-text');
        const btnIcon = document.getElementById('gcm-btn-icon');
        const btnSpinner = document.getElementById('gcm-btn-spinner');

        // Reset state
        if (btnConfirm) {
            btnConfirm.disabled = false;
            btnConfirm.classList.remove('opacity-75', 'cursor-not-allowed');
        }
        if (btnCancel) btnCancel.disabled = false;
        if (btnSpinner) btnSpinner.classList.add('d-none');
        if (btnIcon) btnIcon.className = 'bi ' + confirmIcon;
        if (btnText) btnText.textContent = confirmText;

        if (titleEl) titleEl.textContent = title;
        if (iconEl) iconEl.className = 'bi ' + icon + ' fs-6';
        if (summaryContent) summaryContent.innerHTML = summaryHtml;
        if (alertTextEl) alertTextEl.innerHTML = alertText;

        // Theme Classes for Header, Alert, and Confirm Button
        if (header && btnConfirm && alertBox && alertIcon) {
            header.className = 'modal-header py-2.5 px-3.5 text-white transition-colors';
            btnConfirm.className = 'px-4 py-2 rounded-lg text-xs font-semibold shadow-sm transition-all border-0 d-inline-flex align-items-center gap-1.5 cursor-pointer text-white';
            alertBox.className = 'p-3 text-xs border rounded-xl d-flex align-items-start gap-2';

            if (theme === 'rose' || theme === 'danger') {
                header.classList.add('bg-rose-600');
                btnConfirm.classList.add('bg-rose-600', 'hover:bg-rose-700');
                alertBox.classList.add('bg-rose-50', 'text-rose-900', 'border-rose-200', 'dark:bg-rose-950/40', 'dark:text-rose-200', 'dark:border-rose-800');
                alertIcon.className = 'bi bi-exclamation-triangle-fill text-rose-600 dark:text-rose-400 mt-0.5 flex-shrink-0 fs-6';
            } else if (theme === 'emerald' || theme === 'success') {
                header.classList.add('bg-emerald-600');
                btnConfirm.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
                alertBox.classList.add('bg-emerald-50', 'text-emerald-900', 'border-emerald-200', 'dark:bg-emerald-950/40', 'dark:text-emerald-200', 'dark:border-emerald-800');
                alertIcon.className = 'bi bi-check-circle-fill text-emerald-600 dark:text-emerald-400 mt-0.5 flex-shrink-0 fs-6';
            } else if (theme === 'amber' || theme === 'warning') {
                header.classList.add('bg-amber-600');
                btnConfirm.classList.add('bg-amber-600', 'hover:bg-amber-700');
                alertBox.classList.add('bg-amber-50', 'text-amber-900', 'border-amber-200', 'dark:bg-amber-950/40', 'dark:text-amber-200', 'dark:border-amber-800');
                alertIcon.className = 'bi bi-exclamation-diamond-fill text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0 fs-6';
            } else {
                header.classList.add('bg-blue-600');
                btnConfirm.classList.add('bg-blue-600', 'hover:bg-blue-700');
                alertBox.classList.add('bg-sky-50', 'text-sky-900', 'border-sky-200', 'dark:bg-sky-950/40', 'dark:text-sky-200', 'dark:border-sky-800');
                alertIcon.className = 'bi bi-info-circle-fill text-sky-600 dark:text-sky-400 mt-0.5 flex-shrink-0 fs-6';
            }
        }

        const modal = getBsModal();
        if (modal) modal.show();
    }

    // Attach Confirm Click Listener Once
    document.addEventListener('DOMContentLoaded', function () {
        const btnConfirm = document.getElementById('gcm-btn-confirm');
        const btnCancel = document.getElementById('gcm-btn-cancel');
        const btnSpinner = document.getElementById('gcm-btn-spinner');
        const btnIcon = document.getElementById('gcm-btn-icon');
        const btnText = document.getElementById('gcm-btn-text');

        if (btnConfirm) {
            btnConfirm.addEventListener('click', function () {
                // Set Loading state
                btnConfirm.disabled = true;
                btnConfirm.classList.add('opacity-75', 'cursor-not-allowed');
                if (btnCancel) btnCancel.disabled = true;
                if (btnSpinner) btnSpinner.classList.remove('d-none');
                if (btnIcon) btnIcon.classList.add('d-none');
                if (btnText) btnText.textContent = 'Memproses...';

                if (pendingForm) {
                    const formToSubmit = pendingForm;
                    pendingForm = null;
                    formToSubmit.submit();
                    return;
                }

                if (typeof pendingCallback === 'function') {
                    const cb = pendingCallback;
                    pendingCallback = null;
                    try {
                        cb();
                    } catch (e) {
                        console.error('SimangModal callback error:', e);
                    }
                }
            });
        }

        // Global Declarative Trigger Support: [data-simang-confirm]
        document.body.addEventListener('click', function (e) {
            const trigger = e.target.closest('[data-simang-confirm]');
            if (!trigger) return;

            e.preventDefault();
            e.stopPropagation();

            const title = trigger.getAttribute('data-simang-title') || 'Konfirmasi Tindakan';
            const message = trigger.getAttribute('data-simang-confirm') || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            const theme = trigger.getAttribute('data-simang-theme') || 'blue';
            const icon = trigger.getAttribute('data-simang-icon') || 'bi-question-circle-fill';
            const alertText = trigger.getAttribute('data-simang-alert') || 'Tindakan ini akan langsung diproses ke sistem.';
            const confirmText = trigger.getAttribute('data-simang-btn-text') || 'Ya, Lanjutkan';
            const confirmIcon = trigger.getAttribute('data-simang-btn-icon') || 'bi-check-lg';

            const form = trigger.closest('form');

            confirm({
                title: title,
                icon: icon,
                theme: theme,
                summaryHtml: message,
                alertText: alertText,
                confirmText: confirmText,
                confirmIcon: confirmIcon,
                form: form,
                onConfirm: function () {
                    if (trigger.tagName === 'A' && trigger.href && trigger.href !== '#' && !trigger.href.startsWith('javascript:')) {
                        window.location.href = trigger.href;
                    }
                }
            });
        });
    });

    return {
        confirm: confirm
    };
})();
</script>

let chartLibraryPromise = null;

function onReady(callback) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback, { once: true });
        return;
    }

    callback();
}

function loadChartLibrary() {
    if (window.Chart) {
        return Promise.resolve(window.Chart);
    }

    if (chartLibraryPromise) {
        return chartLibraryPromise;
    }

    chartLibraryPromise = new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
        script.async = true;
        script.onload = () => resolve(window.Chart);
        script.onerror = () => reject(new Error('Unable to load Chart.js'));
        document.head.appendChild(script);
    });

    return chartLibraryPromise;
}

function initSidebar() {
    const toggle = document.querySelector('[data-sidebar-toggle]');
    const overlay = document.querySelector('[data-sidebar-overlay]');

    if (!toggle || !overlay) {
        return;
    }

    const closeSidebar = () => document.body.classList.remove('sidebar-open');
    const toggleSidebar = () => document.body.classList.toggle('sidebar-open');

    toggle.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) {
            closeSidebar();
        }
    });
}

function initReveals() {
    const nodes = [...document.querySelectorAll('[data-reveal]')];

    nodes.forEach((node) => {
        const delay = node.dataset.revealDelay;
        if (delay) {
            node.style.setProperty('--reveal-delay', `${delay}ms`);
        }
    });

    if (!nodes.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        nodes.forEach((node) => node.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.12,
            rootMargin: '0px 0px -10% 0px',
        },
    );

    nodes.forEach((node) => observer.observe(node));
}

function initToasts() {
    const toasts = [...document.querySelectorAll('[data-toast]')];

    toasts.forEach((toast, index) => {
        const timeout = Number(toast.dataset.timeout || 4200) + index * 120;
        const closeButton = toast.querySelector('[data-toast-close]');

        const closeToast = () => {
            if (toast.classList.contains('is-leaving')) {
                return;
            }

            toast.classList.add('is-leaving');
            window.setTimeout(() => toast.remove(), 220);
        };

        requestAnimationFrame(() => toast.classList.add('is-visible'));
        window.setTimeout(closeToast, timeout);

        if (closeButton) {
            closeButton.addEventListener('click', closeToast);
        }
    });
}

function setButtonLoadingState(button, label) {
    if (!button || button.disabled) {
        return;
    }

    button.disabled = true;

    if (button.tagName === 'INPUT') {
        button.value = label;
        return;
    }

    button.classList.add('is-loading');
    button.dataset.originalLabel = button.dataset.originalLabel || button.innerHTML;
    button.innerHTML = `<span class="button-spinner" aria-hidden="true"></span>${label}`;
}

function initPendingStates() {
    const forms = [...document.querySelectorAll('form.prevent-double, form[data-pending-text]')];

    forms.forEach((form) => {
        form.addEventListener(
            'submit',
            () => {
                const button = form.querySelector('button[type="submit"], input[type="submit"]');
                const label =
                    form.dataset.pendingText ||
                    button?.dataset.pendingText ||
                    'Processing...';

                setButtonLoadingState(button, label);
            },
            { once: true },
        );
    });
}

function initCharts() {
    const canvases = [...document.querySelectorAll('[data-chart-config]')];

    if (!canvases.length) {
        return;
    }

    const renderCharts = async () => {
        try {
            const Chart = await loadChartLibrary();

            canvases.forEach((canvas) => {
                if (canvas.dataset.chartReady === 'true') {
                    return;
                }

                try {
                    const config = JSON.parse(canvas.dataset.chartConfig);
                    const context = canvas.getContext('2d');

                    if (!context) {
                        return;
                    }

                    new Chart(context, config);
                    canvas.dataset.chartReady = 'true';
                } catch (error) {
                    console.error('Failed to initialize chart', error);
                }
            });
        } catch (error) {
            console.error(error);
        }
    };

    if (!('IntersectionObserver' in window)) {
        renderCharts();
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            if (entries.some((entry) => entry.isIntersecting)) {
                observer.disconnect();
                renderCharts();
            }
        },
        {
            rootMargin: '220px',
        },
    );

    canvases.forEach((canvas) => observer.observe(canvas));
}

function initPageTransitions() {
    const links = [...document.querySelectorAll('a[href]')];

    links.forEach((link) => {
        link.addEventListener('click', (event) => {
            if (
                event.defaultPrevented ||
                event.metaKey ||
                event.ctrlKey ||
                event.shiftKey ||
                event.altKey ||
                link.target === '_blank' ||
                link.hasAttribute('download')
            ) {
                return;
            }

            const href = link.getAttribute('href');

            if (!href || href.startsWith('#')) {
                return;
            }

            const url = new URL(link.href, window.location.href);

            if (url.origin !== window.location.origin) {
                return;
            }

            document.body.classList.add('is-transitioning');
        });
    });

    window.addEventListener('pageshow', () => {
        document.body.classList.remove('is-transitioning');
    });
}

onReady(() => {
    requestAnimationFrame(() => document.body.classList.add('is-page-ready'));
    initSidebar();
    initReveals();
    initToasts();
    initPendingStates();
    initCharts();
    initPageTransitions();
});

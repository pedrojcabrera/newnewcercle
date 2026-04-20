<!-- SCRIPTS -->
<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

<script>
function initNativeTables() {
    const tables = document.querySelectorAll('table.miTabla:not([data-native-table="1"]):not([data-native-skip="1"])');

    tables.forEach((table) => {
        const tbody = table.tBodies && table.tBodies[0] ? table.tBodies[0] : null;
        if (!tbody) {
            return;
        }

        const allRows = Array.from(tbody.rows);
        if (!allRows.length) {
            return;
        }

        // Cacheamos una vez los textos para evitar recomputar innerText constantemente.
        const rowCache = allRows.map((row) => {
            const cells = Array.from(row.cells).map((cell) => (cell.textContent || '').replace(/\s+/g, ' ').trim());
            return {
                row,
                cells,
                searchText: cells.join(' ').toLowerCase(),
            };
        });

        const state = {
            search: '',
            pageSize: 15,
            page: 1,
            filteredIndexes: rowCache.map((_, index) => index),
        };

        const tools = document.createElement('div');
        tools.className = 'd-flex flex-wrap gap-2 justify-content-between align-items-center mb-2';

        const leftTools = document.createElement('div');
        leftTools.className = 'd-flex flex-wrap align-items-center gap-2';

        const searchInput = document.createElement('input');
        searchInput.type = 'search';
        searchInput.className = 'form-control form-control-sm';
        searchInput.style.maxWidth = '260px';
        searchInput.placeholder = 'Buscar (Enter)...';

        const sizeSelect = document.createElement('select');
        sizeSelect.className = 'form-select form-select-sm';
        sizeSelect.style.width = 'auto';
        [10, 15, 25, 50, 100].forEach((n) => {
            const option = document.createElement('option');
            option.value = String(n);
            option.textContent = String(n);
            if (n === state.pageSize) {
                option.selected = true;
            }
            sizeSelect.appendChild(option);
        });

        const sizeLabel = document.createElement('small');
        sizeLabel.className = 'text-muted';
        sizeLabel.textContent = 'Filas por pagina';

        leftTools.appendChild(searchInput);
        leftTools.appendChild(sizeLabel);
        leftTools.appendChild(sizeSelect);

        const rightTools = document.createElement('div');
        rightTools.className = 'd-flex flex-wrap align-items-center gap-2';

        const info = document.createElement('small');
        info.className = 'text-muted';

        const btnFirst = document.createElement('button');
        btnFirst.type = 'button';
        btnFirst.className = 'btn btn-outline-secondary btn-sm';
        btnFirst.textContent = '<<';

        const btnPrev = document.createElement('button');
        btnPrev.type = 'button';
        btnPrev.className = 'btn btn-outline-secondary btn-sm';
        btnPrev.textContent = '<';

        const btnNext = document.createElement('button');
        btnNext.type = 'button';
        btnNext.className = 'btn btn-outline-secondary btn-sm';
        btnNext.textContent = '>';

        const btnLast = document.createElement('button');
        btnLast.type = 'button';
        btnLast.className = 'btn btn-outline-secondary btn-sm';
        btnLast.textContent = '>>';

        rightTools.appendChild(info);
        rightTools.appendChild(btnFirst);
        rightTools.appendChild(btnPrev);
        rightTools.appendChild(btnNext);
        rightTools.appendChild(btnLast);

        tools.appendChild(leftTools);
        tools.appendChild(rightTools);

        const wrapper = table.closest('.table-responsive-sm') || table.parentElement;
        wrapper.parentNode.insertBefore(tools, wrapper);

        function applyFilters() {
            const query = state.search.toLowerCase();
            state.filteredIndexes = [];

            for (let index = 0; index < rowCache.length; index++) {
                if (!query || rowCache[index].searchText.includes(query)) {
                    state.filteredIndexes.push(index);
                }
            }

        }

        function renderPage() {
            applyFilters();

            const total = state.filteredIndexes.length;
            const pages = Math.max(1, Math.ceil(total / state.pageSize));
            if (state.page > pages) {
                state.page = pages;
            }

            const start = (state.page - 1) * state.pageSize;
            const end = Math.min(start + state.pageSize, total);
            const visibleIndexMap = Object.create(null);

            for (let i = start; i < end; i++) {
                visibleIndexMap[state.filteredIndexes[i]] = true;
            }

            for (let i = 0; i < allRows.length; i++) {
                allRows[i].style.display = visibleIndexMap[i] ? '' : 'none';
            }

            info.textContent = total
                ? `Mostrando ${start + 1}-${end} de ${total}`
                : 'Sin resultados';

            btnFirst.disabled = state.page <= 1;
            btnPrev.disabled = state.page <= 1;
            btnNext.disabled = state.page >= pages;
            btnLast.disabled = state.page >= pages;
        }

        searchInput.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter') {
                return;
            }

            event.preventDefault();
            state.search = searchInput.value || '';
            state.page = 1;
            renderPage();
        });

        searchInput.addEventListener('input', () => {
            if ((searchInput.value || '').trim() !== '') {
                return;
            }

            state.search = '';
            state.page = 1;
            renderPage();
        });

        sizeSelect.addEventListener('change', () => {
            state.pageSize = parseInt(sizeSelect.value, 10) || 15;
            state.page = 1;
            renderPage();
        });

        btnFirst.addEventListener('click', () => {
            state.page = 1;
            renderPage();
        });

        btnPrev.addEventListener('click', () => {
            state.page = Math.max(1, state.page - 1);
            renderPage();
        });

        btnNext.addEventListener('click', () => {
            const pages = Math.max(1, Math.ceil(state.filteredIndexes.length / state.pageSize));
            state.page = Math.min(pages, state.page + 1);
            renderPage();
        });

        btnLast.addEventListener('click', () => {
            state.page = Math.max(1, Math.ceil(state.filteredIndexes.length / state.pageSize));
            renderPage();
        });

        table.dataset.nativeTable = '1';
        renderPage();
    });
}

function normalizeAdminActionButtons() {
    const returnTitles = ['cancelar', 'volver', 'salir', 'regresar'];
    const returnLinks = document.querySelectorAll('.admin-main a.btn[title]');

    returnLinks.forEach((link) => {
        const title = (link.getAttribute('title') || '').trim().toLowerCase();
        if (!returnTitles.includes(title)) {
            return;
        }

        if (link.closest('.ico-acciones')) {
            return;
        }

        // En estos bloques el layout ya se controla por CSS especifico.
        if (link.closest('.botones-superiores')) {
            return;
        }

        const parent = link.parentElement;
        if (!parent) {
            return;
        }

        parent.classList.add('admin-action-layout');
        link.classList.add('admin-return-btn');

        const elementChildren = Array.from(parent.children).filter((child) => child.nodeType === 1);
        if (elementChildren.length > 1) {
            parent.classList.add('has-multiple-actions');
        } else {
            parent.classList.remove('has-multiple-actions');
        }

        Array.from(parent.children).forEach((child) => {
            if (child === link) {
                return;
            }

            child.classList.add('admin-action-btn');
        });
    });
}

function bindActionBarsToNearestForm() {
    const bars = document.querySelectorAll('.admin-main .botones-superiores');

    bars.forEach((bar) => {
        const closestForm = bar.closest('form');
        const block = bar.closest('.container, .card, .admin-surface, .admin-main');
        const fallbackForm = block ? block.querySelector('form') : null;
        const targetForm = closestForm || fallbackForm;

        if (!targetForm) {
            return;
        }

        if (!targetForm.id) {
            targetForm.id = 'admin-form-' + Math.random().toString(36).slice(2, 10);
        }

        bar.querySelectorAll('button[type="submit"]').forEach((submitBtn) => {
            if (submitBtn.closest('form')) {
                return;
            }

            submitBtn.setAttribute('form', targetForm.id);
        });
    });
}

function upgradeLegacyFormActionBars() {
    const returnKeywords = ['cancelar', 'volver', 'salir', 'regresar'];
    const forms = document.querySelectorAll('.admin-main form');

    forms.forEach((form) => {
        const legacyBars = form.querySelectorAll('div.d-flex.justify-content-between');

        legacyBars.forEach((bar) => {
            if (bar.classList.contains('botones-superiores')) {
                return;
            }

            const submitButton = bar.querySelector('button[type="submit"], input[type="submit"]');
            if (!submitButton) {
                return;
            }

            const actionButtons = Array.from(bar.children).filter((child) =>
                child.matches('a.btn, button.btn, input[type="submit"].btn')
            );

            if (!actionButtons.length) {
                return;
            }

            const returnAction = actionButtons.find((action) => {
                const title = (action.getAttribute('title') || '').trim().toLowerCase();
                const text = (action.textContent || '').trim().toLowerCase();
                return returnKeywords.some((kw) => title.includes(kw) || text.includes(kw));
            });

            if (!returnAction) {
                return;
            }

            bar.classList.add('botones-superiores');
        });
    });
}

function placeAndCloneActionBars() {
    const bars = Array.from(document.querySelectorAll('.admin-main .botones-superiores'))
        .filter((bar) => {
            // No mover barras que contengan "Agregar nuevo"
            const text = bar.textContent || '';
            if (text.includes('Agregar nuevo')) {
                return false;
            }
            return bar.dataset.actionClone !== '1';
        });

    bars.forEach((bar) => {
        const targetBlock = bar.closest('form') || bar.closest('.container, .card, .admin-surface, .admin-main');
        if (!targetBlock) {
            return;
        }

        // Limpieza defensiva por si hay clones previos en el bloque.
        targetBlock.querySelectorAll(':scope > .botones-superiores[data-action-clone="1"]').forEach((node) => {
            node.remove();
        });

        // La botonera original debe quedar al pie del bloque objetivo.
        if (bar.parentElement !== targetBlock || bar !== targetBlock.lastElementChild) {
            targetBlock.appendChild(bar);
        }

        bar.dataset.actionPrimary = '1';
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        upgradeLegacyFormActionBars();
        placeAndCloneActionBars();
        bindActionBarsToNearestForm();
        normalizeAdminActionButtons();
        window.setTimeout(initNativeTables, 0);
    }, { once: true });
} else {
    upgradeLegacyFormActionBars();
    placeAndCloneActionBars();
    bindActionBarsToNearestForm();
    normalizeAdminActionButtons();
    window.setTimeout(initNativeTables, 0);
}
</script>
<!-- ---------------------------------------------------- -->
<script>
// Agregar una entrada en el historial para evitar el retroceso
window.history.pushState(null, "", window.location.href);
window.onpopstate = function() {
    window.history.pushState(null, "", window.location.href);
};
</script>

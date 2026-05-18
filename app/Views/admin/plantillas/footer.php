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
        tools.className = 'mb-2';
        tools.classList.add('native-table-tools');

        const topRow = document.createElement('div');
        topRow.className = 'd-flex justify-content-between align-items-center gap-2';
        topRow.classList.add('native-table-tools-row', 'native-table-tools-row-top');

        const topLeft = document.createElement('div');
        topLeft.className = 'd-flex align-items-center gap-2';
        topLeft.classList.add('native-table-tools-top-left');

        const topRight = document.createElement('div');
        topRight.className = 'd-flex align-items-center gap-2';
        topRight.classList.add('native-table-tools-top-right');

        const bottomRow = document.createElement('div');
        bottomRow.className = 'd-flex justify-content-between align-items-center gap-2';
        bottomRow.classList.add('native-table-tools-row', 'native-table-tools-row-bottom');

        const bottomLeft = document.createElement('div');
        bottomLeft.className = 'd-flex align-items-center gap-2';
        bottomLeft.classList.add('native-table-tools-bottom-left');

        const bottomRight = document.createElement('div');
        bottomRight.className = 'd-flex align-items-center gap-2';
        bottomRight.classList.add('native-table-tools-bottom-right');

        const searchInput = document.createElement('input');
        searchInput.type = 'search';
        searchInput.className = 'form-control form-control-sm';
        searchInput.style.maxWidth = '260px';
        searchInput.placeholder = 'Buscar (Enter)...';

        const filterMode = table.dataset.nativeFilterMode || '';
        const filterName = table.dataset.nativeFilterName || 'estado';
        const filterValue = table.dataset.nativeFilterValue || 'todos';

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

        topLeft.appendChild(searchInput);

        if (filterMode === 'event-status') {
            const filterWrap = document.createElement('div');
            filterWrap.className = 'd-flex align-items-center gap-3';
            filterWrap.classList.add('native-table-filter-group');

            const options = [
                { value: 'todos', label: 'Todos' },
                { value: 'proximos', label: 'Próximos' },
                { value: 'encurso', label: 'En curso' },
                { value: 'cerrados', label: 'Cerrados' },
                { value: 'finalizados', label: 'Sin cerrar' },
            ];

            options.forEach((option, index) => {
                const optionWrap = document.createElement('div');
                optionWrap.className = 'form-check form-check-inline m-0';

                const input = document.createElement('input');
                input.type = 'radio';
                input.className = 'form-check-input';
                input.name = `${filterName}-${table.id || 'table'}-filter`;
                input.id = `${filterName}-${table.id || 'table'}-${option.value}-${index}`;
                input.value = option.value;
                input.checked = option.value === filterValue;

                input.addEventListener('change', () => {
                    if (!input.checked) {
                        return;
                    }

                    const url = new window.URL(window.location.href);
                    if (input.value === 'todos') {
                        url.searchParams.delete(filterName);
                    } else {
                        url.searchParams.set(filterName, input.value);
                    }
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                });

                const label = document.createElement('label');
                label.className = 'form-check-label';
                label.htmlFor = input.id;
                label.textContent = option.label;

                optionWrap.appendChild(input);
                optionWrap.appendChild(label);
                filterWrap.appendChild(optionWrap);
            });

            topRight.appendChild(filterWrap);
        }

        bottomLeft.appendChild(sizeLabel);
        bottomLeft.appendChild(sizeSelect);

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

        bottomRight.appendChild(info);
        bottomRight.appendChild(btnFirst);
        bottomRight.appendChild(btnPrev);
        bottomRight.appendChild(btnNext);
        bottomRight.appendChild(btnLast);

        topRow.appendChild(topLeft);
        topRow.appendChild(topRight);
        bottomRow.appendChild(bottomLeft);
        bottomRow.appendChild(bottomRight);
        tools.appendChild(topRow);
        tools.appendChild(bottomRow);

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

function initAdminSidebarToggle() {
    const shell = document.querySelector('.admin-shell');
    const button = document.querySelector('.admin-sidebar-toggle');

    if (!shell || !button) {
        return;
    }

    const storageKey = 'adminSidebarCollapsed';

    const applyState = (collapsed) => {
        shell.classList.toggle('is-sidebar-collapsed', collapsed);
        button.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        button.setAttribute('title', collapsed ? 'Restaurar menú' : 'Contraer menú');

        const icon = button.querySelector('i');
        if (icon) {
            icon.className = collapsed ? 'bi bi-layout-sidebar bi-rotate-180' : 'bi bi-layout-sidebar-inset';
        }

        const label = button.querySelector('span');
        if (label) {
            label.textContent = collapsed ? 'Restaurar menú' : 'Contraer menú';
        }
    };

    applyState(window.localStorage.getItem(storageKey) === '1');

    button.addEventListener('click', () => {
        const collapsed = !shell.classList.contains('is-sidebar-collapsed');
        window.localStorage.setItem(storageKey, collapsed ? '1' : '0');
        applyState(collapsed);
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
        initAdminSidebarToggle();
        upgradeLegacyFormActionBars();
        placeAndCloneActionBars();
        bindActionBarsToNearestForm();
        normalizeAdminActionButtons();
        window.setTimeout(initNativeTables, 0);
    }, { once: true });
} else {
    initAdminSidebarToggle();
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

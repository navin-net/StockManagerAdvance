(() => {
    'use strict';
    const $ = window.jQuery;
    if (!$?.fn.dataTable) return;
    const config = window.tableInterface;
    const t = config.labels;
    const create = (tag, className, text) => {
        const node = document.createElement(tag);
        node.className = className;
        if (text !== undefined) node.textContent = text;
        return node;
    };
    const icon = name => {
        const node = create('i', `bi bi-${name}`);
        node.setAttribute('aria-hidden', 'true');
        return node;
    };
    const button = (name, label, handler) => {
        const node = create('button', 'dt-tool-button');
        node.type = 'button'; node.title = label; node.setAttribute('aria-label', label);
        node.append(icon(name)); node.addEventListener('click', handler);
        return node;
    };
    // Page-specific options continue to control queries, ordering and permissions.
    $.extend(true, $.fn.dataTable.defaults, {
        autoWidth: false,
        layout: {topStart: 'search', topEnd: 'pageLength', bottomStart: 'info', bottomEnd: 'paging'},
    });

    $(document).on('init.dt.tableInterface', (event, settings) => {
        if (event.target !== settings.nTable) return;
        const table = settings.nTable;
        const api = new $.fn.dataTable.Api(settings);
        const wrapper = api.table().container();
        if (wrapper.dataset.tableInterface) return;
        wrapper.dataset.tableInterface = 'true';
        wrapper.classList.add('dt-workspace');
        table.classList.add('dt-workspace-table');
        document.body.classList.add('has-table-workspace');
        const [title, symbol] = config.tables[table.id] || [t.records, 'table'];
        const modal = table.closest('.modal');
        const host = table.closest('.card-body');
        if (host && !modal) {
            host.classList.add('dt-host-body');
            host.closest('.card')?.classList.add('dt-host-card');
        }
        // Only the table scrolls. Toolbars and menus stay outside the scrolling area.
        const oldScroll = wrapper.parentElement;
        if (oldScroll.classList.contains('table-responsive') && oldScroll.children.length === 1) {
            oldScroll.before(wrapper); oldScroll.remove();
        }
        wrapper.parentElement.classList.add('dt-wrap-host');
        const heading = create('div', 'dt-workspace-heading');
        const identity = create('div', 'dt-workspace-identity');
        const mark = create('span', 'dt-workspace-mark'); mark.append(icon(symbol));
        const headingText = create('div', 'dt-workspace-copy');
        const titleLine = create('div', 'dt-workspace-title-line');
        const headingTitle = create('h2', 'dt-workspace-title', title);
        const count = create('span', 'dt-record-count');
        titleLine.append(headingTitle, count);
        headingText.append(titleLine, create('p', 'dt-workspace-subtitle', t.subtitle));
        identity.append(mark, headingText);
        const actions = create('div', 'dt-workspace-actions');
        heading.append(identity, actions);
        wrapper.prepend(heading);
        const tools = create('div', 'dt-workspace-tools');
        const searchArea = create('div', 'dt-workspace-search');
        const options = create('div', 'dt-workspace-options');
        tools.append(searchArea, options); heading.after(tools);

        const customCard = wrapper.closest('.pl-card');
        const oldToolbar = customCard?.querySelector(':scope > .pl-toolbar');
        const customSearch = oldToolbar?.querySelector('.pl-search');
        const search = customSearch || wrapper.querySelector('.dt-search');
        if (search) {
            searchArea.append(search);
            const input = search.querySelector('input');
            if (input) {
                input.placeholder = t.search;
                if (!input.getAttribute('aria-label')) input.setAttribute('aria-label', t.search);
                input.setAttribute('autocomplete', 'off');
                if (!search.querySelector('button')) {
                    const clear = button('x-lg', t.clearSearch, () => {
                        input.value = '';
                        input.dispatchEvent(new Event('input', {bubbles: true}));
                        api.search('').draw(); input.focus();
                    });
                    clear.classList.add('dt-search-clear'); search.append(clear);
                    const updateClear = () => { clear.hidden = !input.value; };
                    input.addEventListener('input', updateClear); updateClear();
                }
            }
        }
        const length = wrapper.querySelector('.dt-length');
        const customLength = oldToolbar?.querySelector('#plLen');
        if (customLength) {
            const group = create('label', 'dt-custom-length', t.rows); group.append(customLength); options.append(group);
        } else if (length) {
            options.append(length);
            const label = length.querySelector('label');
            if (label) {
                // Keep the native association with the generated select.
                [...label.childNodes].filter(node => node.nodeType === Node.TEXT_NODE).forEach(node => node.remove());
                label.prepend(document.createTextNode(t.rows + ' '));
            }
        }
        const selection = create('span', 'dt-selection-count');
        selection.setAttribute('role', 'status'); selection.hidden = true; options.prepend(selection);
        const density = button('distribute-vertical', t.compact, () => {
            const compact = wrapper.classList.toggle('dt-compact');
            density.setAttribute('aria-pressed', String(compact));
            density.title = compact ? t.comfortable : t.compact;
            density.setAttribute('aria-label', density.title);
            try { localStorage.setItem('table-density', compact ? 'compact' : 'comfortable'); } catch {}
        });
        let compact = false;
        try { compact = localStorage.getItem('table-density') === 'compact'; } catch {}
        wrapper.classList.toggle('dt-compact', compact);
        density.setAttribute('aria-pressed', String(compact));
        if (compact) { density.title = t.comfortable; density.setAttribute('aria-label', t.comfortable); }
        options.append(density);
        if (settings.ajax) {
            options.append(button('arrow-clockwise', t.refresh, () => api.ajax.reload(null, false)));
        }
        // Reuse existing action nodes, retaining their IDs and event handlers.
        const customActions = oldToolbar?.querySelector('.pl-actions') || customCard?.closest('.pl')?.querySelector('.pl-head .pl-actions');
        const legacyActions = !modal && host?.querySelector(':scope > .row .dropdown, :scope > .d-flex > .dropdown');
        if (customActions) actions.append(customActions);
        else if (legacyActions) {
            const oldRow = legacyActions.closest('.row, .d-flex');
            const add = [...legacyActions.querySelectorAll('.dropdown-item')].find(item => /^(add|create)/i.test(item.id) || /\/create(?:$|[?#])/.test(item.getAttribute('href') || ''));
            if (add) {
                const listItem = add.closest('li');
                add.classList.remove('dropdown-item'); add.classList.add('btn', 'btn-primary');
                actions.append(add); if (listItem) listItem.hidden = true;
            }
            const toggle = legacyActions.querySelector('.dropdown-toggle');
            toggle?.classList.remove('btn-primary'); toggle?.classList.add('btn-outline-custom');
            actions.append(legacyActions);
            if (oldRow && !oldRow.textContent.trim() && !oldRow.querySelector('button,input,a')) oldRow.hidden = true;
        }
        const filters = customCard?.querySelector(':scope > .pl-tabs');
        if (filters) { filters.classList.add('dt-workspace-filters'); tools.after(filters); }
        const customSelection = oldToolbar?.querySelector('#selectionChip') || customCard?.querySelector(':scope > .pl-selbar');
        if (customSelection) { customSelection.classList.add('dt-workspace-selection'); (filters || tools).after(customSelection); }
        if (oldToolbar) oldToolbar.hidden = true;

        const viewport = create('div', 'dt-grid-viewport');
        viewport.tabIndex = 0; viewport.setAttribute('role', 'region'); viewport.setAttribute('aria-label', `${title} — ${t.scroll}`);
        table.before(viewport); viewport.append(table);
        settings.aoColumns.forEach((column, index) => {
            const key = String(column.mData);
            const numeric = /^(stock_quantity|quantity|cost_price|selling_price|sale_price|total_amount|grand_total|paid|balance|amount|subtotal|revenue)$/.test(key);
            if (numeric) { api.column(index).header()?.classList.add('dt-number'); $(api.column(index).nodes()).addClass('dt-number'); column.sClass = `${column.sClass || ''} dt-number`; }
            if (key === 'action') { api.column(index).header()?.classList.add('dt-actions-cell'); column.sClass = `${column.sClass || ''} dt-actions-cell`; $(api.column(index).nodes()).addClass('dt-actions-cell'); }
        });
        [...wrapper.children].filter(row => row.classList.contains('row') || row.classList.contains('dt-layout-row') || row.classList.contains('pl-foot')).forEach(row => {
            if (row.querySelector('.dt-info,.dt-paging')) row.classList.add('dt-workspace-footer');
            if (!row.querySelector('table,.dt-info,.dt-paging,input,select,button')) row.hidden = true;
        });
        const selected = () => {
            const checks = [...table.querySelectorAll('tbody input[type=checkbox]:checked')];
            selection.hidden = checks.length === 0;
            selection.textContent = `${checks.length} ${t.selected}`;
        };
        const draw = () => {
            const info = api.page.info();
            count.textContent = `${Number(info.recordsTotal || 0).toLocaleString(document.documentElement.lang)} ${t.records}`;
            table.querySelectorAll('input[type=checkbox]').forEach(input => {
                if (!input.getAttribute('aria-label')) input.setAttribute('aria-label', input.closest('thead') ? t.selectAll : t.selectRow);
            });
            const empty = table.querySelector('td.dt-empty,td.dataTables_empty');
            if (empty) {
                const state = create('div', 'dt-empty-state');
                const emptyMark = create('span', 'dt-empty-mark'); emptyMark.append(icon(info.recordsTotal ? 'search' : 'inbox'));
                state.append(emptyMark, create('strong', '', info.recordsTotal ? t.noResults : t.empty), create('p', '', info.recordsTotal ? t.searchHint : t.emptyHint));
                empty.replaceChildren(state);
            }
            table.querySelectorAll('[data-bs-toggle=dropdown]').forEach(trigger => {
                window.bootstrap?.Dropdown.getOrCreateInstance(trigger, {
                    popperConfig: defaults => ({...defaults, strategy: 'fixed'}),
                });
            });
            selected();
        };
        $(table).on('draw.dt.tableInterface', draw).on('processing.dt.tableInterface', (_event, _settings, busy) => {
            wrapper.setAttribute('aria-busy', String(busy)); wrapper.classList.toggle('dt-is-loading', busy);
        }).on('change.tableInterface', 'input[type=checkbox]', () => queueMicrotask(selected));
        draw();
    });
})();

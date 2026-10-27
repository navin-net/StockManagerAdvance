(() => {
    'use strict';
    const notify = (form, messages) => {
        const box = form.querySelector('[data-record-feedback]');
        if (!box) return;
        box.replaceChildren();
        const heading = document.createElement('strong');
        heading.textContent = 'Please check your changes.';
        box.append(heading);
        const list = document.createElement('ul');
        messages.forEach(message => { const item = document.createElement('li'); item.textContent = String(message); list.append(item); });
        box.append(list);
        box.hidden = false;
        box.focus();
    };
    document.querySelectorAll('[data-record-form]').forEach(form => {
        form.addEventListener('submit', async event => {
            event.preventDefault();
            if (!form.reportValidity() || form.dataset.saving === 'true') return;
            form.dataset.saving = 'true';
            const button = form.querySelector('[type=submit]');
            const label = button?.innerHTML;
            if (button) { button.disabled = true; button.textContent = 'Saving…'; }
            form.querySelector('[data-record-feedback]')?.setAttribute('hidden', '');
            form.querySelectorAll('[aria-invalid=true]').forEach(field => { field.removeAttribute('aria-invalid'); field.classList.remove('is-invalid'); });
            try {
                const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' }, credentials: 'same-origin' });
                const isJson = response.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await response.json() : {};
                if (!response.ok) {
                    const errors = data.errors || {};
                    Object.keys(errors).forEach(name => {
                        const inputName = name.replace(/\.([^.]+)/g, '[$1]');
                        const field = form.elements.namedItem(inputName);
                        if (field instanceof HTMLElement) { field.setAttribute('aria-invalid', 'true'); field.classList.add('is-invalid'); }
                    });
                    notify(form, Object.values(errors).flat().length ? Object.values(errors).flat() : [data.message || (response.status === 419 ? 'Your session expired. Refresh this page and try again.' : 'Unable to save these changes. Please try again.')]);
                    return;
                }
                window.location.assign(data.redirect || (response.redirected ? response.url : form.dataset.successUrl || window.location.href));
            } catch {
                notify(form, ['The server could not be reached. Your changes are still here; please try again.']);
            } finally {
                form.dataset.saving = 'false';
                if (button) { button.disabled = false; button.innerHTML = label; }
            }
        });
    });
    document.querySelectorAll('[data-image-preview]').forEach(input => {
        let objectUrl;
        input.addEventListener('change', () => {
            const target = document.getElementById(input.dataset.imagePreview);
            const file = input.files?.[0];
            if (!target || !file || !file.type.startsWith('image/')) return;
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = URL.createObjectURL(file);
            target.src = objectUrl;
            target.hidden = false;
        });
    });
    document.querySelectorAll('[data-gallery-image]').forEach(button => {
        button.addEventListener('click', () => {
            const image = document.getElementById(button.dataset.galleryTarget || 'record-main-image');
            if (image) image.src = button.dataset.galleryImage;
            document.querySelectorAll('[data-gallery-image]').forEach(item => { item.classList.remove('is-active'); item.setAttribute('aria-pressed', 'false'); });
            button.classList.add('is-active');
            button.setAttribute('aria-pressed', 'true');
        });
    });
    document.querySelectorAll('[data-print-record]').forEach(button => button.addEventListener('click', () => window.print()));
    document.querySelectorAll('[data-transaction-editor]').forEach(editor => {
        const body = editor.querySelector('[data-items-body]');
        const picker = editor.querySelector('[data-product-picker]');
        const mode = editor.dataset.transactionEditor;
        const priceKey = mode === 'purchase' ? 'cost_price' : 'sale_price';
        const products = JSON.parse(editor.querySelector('[data-product-data]').textContent);
        const productMap = new Map(products.map(product => [String(product.id), product]));
        let rowIndex = Number(editor.dataset.nextIndex || body.rows.length);
        const recalculate = () => {
            let cents = 0;
            let units = 0;
            [...body.rows].forEach(row => {
                const qty = Number(row.querySelector('[data-line-quantity]')?.value || 0);
                const price = Number(row.querySelector('[data-line-price]')?.value || 0);
                const line = Math.round(price * 100) * qty;
                cents += line;
                units += qty;
                const total = row.querySelector('[data-line-total]');
                if (total) total.textContent = (line / 100).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            });
            editor.querySelectorAll('[data-order-total]').forEach(item => { item.textContent = (cents / 100).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }); });
            editor.querySelectorAll('[data-order-units]').forEach(item => { item.textContent = String(units); });
            editor.querySelectorAll('[data-order-lines]').forEach(item => { item.textContent = String(body.rows.length); });
            const empty = editor.querySelector('[data-empty-items]');
            if (empty) empty.hidden = body.rows.length !== 0;
            [...picker.options].forEach(option => { option.disabled = [...body.rows].some(row => row.dataset.productId === option.value); });
        };
        const add = () => {
            const product = productMap.get(picker.value);
            if (!product) return;
            if ([...body.rows].some(row => row.dataset.productId === String(product.id))) return;
            const row = document.createElement('tr');
            row.dataset.productId = String(product.id);
            const description = document.createElement('td');
            const title = document.createElement('strong'); title.textContent = product.name;
            const code = document.createElement('small'); code.textContent = product.code || '';
            const id = document.createElement('input'); id.type = 'hidden'; id.name = `items[${rowIndex}][product_id]`; id.value = product.id;
            description.append(title, code, id); row.append(description);
            [['quantity', 1, '1', '1', 'lineQuantity'], [priceKey, product[mode === 'purchase' ? 'cost_price' : 'selling_price'], '0', '0.01', 'linePrice']].forEach(([name, value, min, step, flag]) => {
                const cell = document.createElement('td');
                const input = document.createElement('input');
                Object.assign(input, { type: 'number', name: `items[${rowIndex}][${name}]`, value, min, step, required: true, className: 'form-control' });
                input.setAttribute('aria-label', `${product.name} ${name.replace('_', ' ')}`);
                input.dataset[flag] = '';
                cell.append(input); row.append(cell);
            });
            const total = document.createElement('td'); total.className = 'number'; total.dataset.lineTotal = ''; row.append(total);
            const action = document.createElement('td'); const remove = document.createElement('button');
            remove.type = 'button'; remove.className = 'record-remove'; remove.dataset.removeLine = ''; remove.setAttribute('aria-label', `Remove ${product.name}`);
            const icon = document.createElement('i'); icon.className = 'bi bi-trash3'; icon.setAttribute('aria-hidden', 'true'); remove.append(icon); action.append(remove); row.append(action);
            body.append(row); rowIndex++; picker.value = ''; recalculate();
        };
        editor.querySelector('[data-add-product]').addEventListener('click', add);
        body.addEventListener('input', recalculate);
        body.addEventListener('click', event => { const button = event.target.closest('[data-remove-line]'); if (button) { button.closest('tr').remove(); recalculate(); } });
        editor.addEventListener('submit', event => {
            if (!body.rows.length) { event.preventDefault(); event.stopImmediatePropagation(); notify(editor, ['Add at least one product before saving.']); }
        }, true);
        recalculate();
    });
})();

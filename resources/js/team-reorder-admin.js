(function () {
    'use strict';

    var config  = window.teamReorderAdmin || {};
    var dragging = null;

    function initDragDrop() {
        document.querySelectorAll('.team-reorder-list').forEach(function (list) {
            list.querySelectorAll('.team-reorder-item').forEach(bindItem);
        });
    }

    function bindItem(item) {
        item.addEventListener('dragstart', function (e) {
            dragging = item;
            item.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        });

        item.addEventListener('dragend', function () {
            item.classList.remove('dragging');
            item.classList.remove('drag-over');
            dragging = null;
        });

        item.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            if (!dragging || dragging === item) return;
            item.classList.add('drag-over');
        });

        item.addEventListener('dragleave', function () {
            item.classList.remove('drag-over');
        });

        item.addEventListener('drop', function (e) {
            e.preventDefault();
            item.classList.remove('drag-over');
            if (!dragging || dragging === item) return;

            var list      = item.parentNode;
            var items     = Array.from(list.children);
            var fromIndex = items.indexOf(dragging);
            var toIndex   = items.indexOf(item);

            if (fromIndex < toIndex) {
                list.insertBefore(dragging, item.nextSibling);
            } else {
                list.insertBefore(dragging, item);
            }
        });
    }

    function collectOrder() {
        var order = [];
        var index = 0;

        document.querySelectorAll('.team-reorder-group').forEach(function (group) {
            group.querySelectorAll('.team-reorder-item').forEach(function (item) {
                order.push({
                    id: parseInt(item.dataset.id, 10),
                    menu_order: index++
                });
            });
        });

        return order;
    }

    function setLoading(on) {
        ['top', 'bottom'].forEach(function (pos) {
            var btn     = document.getElementById('save-order-' + pos);
            var spinner = document.getElementById('spinner-' + pos);
            if (btn)     btn.disabled = on;
            if (spinner) spinner.classList.toggle('is-active', on);
        });
    }

    function showNotice(type, msg) {
        var el = document.getElementById('team-reorder-notice');
        if (!el) return;
        el.className        = 'notice-' + type;
        el.textContent      = msg;
        el.style.display    = 'block';
        el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function saveOrder() {
        var order = collectOrder();
        setLoading(true);

        fetch(config.restUrl, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce':   config.nonce
            },
            body: JSON.stringify({ order: order })
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            setLoading(false);
            if (data.success) {
                showNotice('success', 'Order saved — ' + data.updated + ' members updated.');
            } else {
                showNotice('error', 'Save failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(function (err) {
            setLoading(false);
            showNotice('error', 'Network error: ' + err.message);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initDragDrop();

        ['top', 'bottom'].forEach(function (pos) {
            var btn = document.getElementById('save-order-' + pos);
            if (btn) btn.addEventListener('click', saveOrder);
        });
    });
}());

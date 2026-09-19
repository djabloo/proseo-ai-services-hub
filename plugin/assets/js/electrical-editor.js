(function () {
    'use strict';
    var config = window.proseoElectricalEditor;
    if (!config) { return; }
    var project = config.project || { name: '', symbols: [], wires: [] };
    project.symbols = Array.isArray(project.symbols) ? project.symbols : [];
    project.wires = Array.isArray(project.wires) ? project.wires : [];
    var canvas = document.getElementById('proseo-schematic-canvas');
    var nameInput = document.getElementById('proseo-project-name');
    var status = document.getElementById('proseo-save-status');
    var wireMode = false;
    var wireStart = null;
    var dragged = null;
    var symbolNames = { contactor: 'KM1', terminal: 'X1', fuse: 'F1', lamp: 'H1' };

    nameInput.value = project.name || '';
    function element(name, attributes) {
        var node = document.createElementNS('http://www.w3.org/2000/svg', name);
        Object.keys(attributes || {}).forEach(function (key) { node.setAttribute(key, attributes[key]); });
        return node;
    }
    function uuid() { return 'item-' + Date.now() + '-' + Math.random().toString(16).slice(2); }
    function snap(value) { return Math.round(value / 20) * 20; }
    function point(event) {
        var rect = canvas.getBoundingClientRect();
        return { x: snap((event.clientX - rect.left) * 1200 / rect.width), y: snap((event.clientY - rect.top) * 700 / rect.height) };
    }
    function drawSymbol(symbol) {
        var group = element('g', { class: 'proseo-symbol', 'data-id': symbol.id, transform: 'translate(' + symbol.x + ' ' + symbol.y + ')' });
        if (symbol.type === 'terminal') { group.appendChild(element('rect', { x: -26, y: -18, width: 52, height: 36, rx: 2 })); }
        else if (symbol.type === 'fuse') { group.appendChild(element('rect', { x: -30, y: -12, width: 60, height: 24 })); }
        else if (symbol.type === 'lamp') { group.appendChild(element('circle', { cx: 0, cy: 0, r: 22 })); group.appendChild(element('path', { d: 'M-14,-14 L14,14 M14,-14 L-14,14' })); }
        else { group.appendChild(element('rect', { x: -32, y: -24, width: 64, height: 48 })); group.appendChild(element('path', { d: 'M-22,12 L-8,-8 L8,8 L22,-12' })); }
        group.appendChild(element('circle', { class: 'proseo-pin', cx: -42, cy: 0, r: 4 }));
        group.appendChild(element('circle', { class: 'proseo-pin', cx: 42, cy: 0, r: 4 }));
        var label = element('text', { x: 0, y: 42, 'text-anchor': 'middle' });
        label.textContent = symbol.label;
        group.appendChild(label);
        group.addEventListener('pointerdown', function (event) { if (!wireMode) { dragged = { symbol: symbol, pointerId: event.pointerId }; group.setPointerCapture(event.pointerId); event.stopPropagation(); } });
        return group;
    }
    function render() {
        canvas.innerHTML = '';
        var defs = element('defs');
        var pattern = element('pattern', { id: 'grid', width: 20, height: 20, patternUnits: 'userSpaceOnUse' });
        pattern.appendChild(element('path', { d: 'M 20 0 L 0 0 0 20', fill: 'none', stroke: '#d7dce2', 'stroke-width': 1 })); defs.appendChild(pattern); canvas.appendChild(defs);
        canvas.appendChild(element('rect', { width: 1200, height: 700, fill: 'url(#grid)' }));
        project.wires.forEach(function (wire) { canvas.appendChild(element('line', { class: 'proseo-wire', x1: wire.x1, y1: wire.y1, x2: wire.x2, y2: wire.y2 })); });
        project.symbols.forEach(function (symbol) { canvas.appendChild(drawSymbol(symbol)); });
    }
    document.querySelectorAll('.proseo-symbol-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var type = button.getAttribute('data-symbol-type');
            var count = project.symbols.filter(function (item) { return item.type === type; }).length + 1;
            project.symbols.push({ id: uuid(), type: type, label: symbolNames[type].replace('1', String(count)), x: 180 + (count % 4) * 120, y: 140 + (count % 3) * 100 });
            render();
        });
    });
    canvas.addEventListener('pointermove', function (event) { if (dragged) { var p = point(event); dragged.symbol.x = p.x; dragged.symbol.y = p.y; render(); } });
    canvas.addEventListener('pointerup', function () { dragged = null; });
    canvas.addEventListener('click', function (event) {
        if (!wireMode || event.target !== canvas) { return; }
        var p = point(event);
        if (!wireStart) { wireStart = p; status.textContent = config.strings.wireStart; return; }
        project.wires.push({ id: uuid(), x1: wireStart.x, y1: wireStart.y, x2: p.x, y2: p.y });
        wireStart = null; wireMode = false; document.getElementById('proseo-wire-mode').classList.remove('is-active'); status.textContent = ''; render();
    });
    document.getElementById('proseo-wire-mode').addEventListener('click', function (event) { wireMode = !wireMode; wireStart = null; event.currentTarget.classList.toggle('is-active', wireMode); status.textContent = wireMode ? config.strings.wireStart : ''; });
    document.getElementById('proseo-clear-project').addEventListener('click', function () { project.symbols = []; project.wires = []; render(); });
    document.getElementById('proseo-save-project').addEventListener('click', function () {
        project.name = nameInput.value;
        fetch(config.apiUrl, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': config.nonce }, body: JSON.stringify(project) })
            .then(function (response) { if (!response.ok) { throw new Error('save'); } return response.json(); })
            .then(function (saved) { project = saved; status.textContent = config.strings.saved; })
            .catch(function () { status.textContent = config.strings.saveFailed; });
    });
    render();
}());

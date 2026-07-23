<?php
// fetch existing batches on load
$existingBatches = [];
if (file_exists(__DIR__ . '/batches.json')) {
    $json = file_get_contents(__DIR__ . '/batches.json');
    $existingBatches = json_decode($json, true);
    if (!is_array($existingBatches)) {
        $existingBatches = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Roommate Split Calculator</title>
<style>
    body {
        font-family: system-ui, sans-serif;
        background: #f5f5f7;
        color: #111;
        margin: 0;
        padding: 2rem 1rem 6rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .app-shell {
        width: 100%;
        max-width: 900px;
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 20px 40px rgba(0,0,0,.08);
        padding: 1.5rem 1.5rem 2rem;
        margin-bottom: 2rem;
    }

    h1 {
        margin: 0 0 .5rem;
        font-size: 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: baseline;
        gap: .5rem;
    }
    h1 span.sub {
        font-size: .8rem;
        font-weight: 400;
        color: #666;
    }

    .batch-header {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem 1rem;
        margin-bottom: 1rem;
    }

    .batch-header > div {
        flex: 1 1 140px;
        min-width: 140px;
    }

    label {
        font-size: .75rem;
        font-weight: 500;
        color: #444;
        display: block;
        margin-bottom: .35rem;
    }

    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        box-sizing: border-box;
        border-radius: .5rem;
        border: 1px solid #ccc;
        padding: .6rem .7rem;
        font-size: .9rem;
        background: #fff;
    }

    input[type="number"] {
        text-align: right;
    }

    textarea {
        min-height: 2.8rem;
        resize: vertical;
    }

    .items-table-wrapper {
        overflow-x: auto;
        border: 1px solid #e2e2e2;
        border-radius: .75rem;
        background: #fafafa;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: .9rem;
        min-width: 620px;
    }

    thead th {
        text-align: left;
        font-weight: 600;
        color: #333;
        background: #f0f0f0;
        padding: .6rem .75rem;
        border-bottom: 1px solid #ddd;
        white-space: nowrap;
    }

    tbody td {
        padding: .5rem .75rem;
        border-bottom: 1px solid #eee;
        background: #fff;
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    tfoot td {
        font-weight: 600;
        font-size: .9rem;
        background: #f9f9f9;
        padding: .6rem .75rem;
        border-top: 1px solid #ddd;
    }

    .row-actions button,
    .controls-row button,
    .save-row button {
        appearance: none;
        border: 0;
        border-radius: .5rem;
        background: #111;
        color: #fff;
        font-size: .8rem;
        font-weight: 500;
        line-height: 1;
        padding: .6rem .8rem;
        cursor: pointer;
        white-space: nowrap;
    }

    .row-actions button.delete {
        background: #d42d2d;
    }

    .controls-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        margin: 1rem 0 0;
    }

    .totals-card {
        display: flex;
        flex-wrap: wrap;
        gap: .75rem 1rem;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: .75rem;
        padding: .75rem 1rem;
        font-size: .9rem;
        line-height: 1.4;
    }

    .totals-card > div {
        flex: 1 1 120px;
        min-width: 120px;
    }

    .totals-card .label {
        font-size: .7rem;
        color: #666;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .totals-card .value {
        font-size: 1rem;
        font-weight: 600;
    }

    .save-row {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: .75rem;
        margin-top: 1.25rem;
    }

    .old-batches {
        width: 100%;
        max-width: 900px;
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 20px 40px rgba(0,0,0,.05);
        padding: 1.5rem;
    }

    .batch-card {
        border: 1px solid #e5e7eb;
        border-radius: .75rem;
        padding: 1rem 1rem 1.25rem;
        margin-bottom: 1rem;
        background: #fff;
    }

    .batch-card-header {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .5rem 1rem;
        margin-bottom: .5rem;
        font-size: .8rem;
        color: #444;
    }
    .batch-card-header .title {
        font-weight: 600;
        font-size: .9rem;
        color: #000;
    }
    .batch-card-header .date {
        color: #666;
        font-size: .75rem;
    }

    .batch-totals {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem 1rem;
        font-size: .8rem;
        line-height: 1.4;
        background: #fafafa;
        border: 1px solid #eee;
        border-radius: .5rem;
        padding: .5rem .75rem;
        margin-top: .5rem;
    }
    .batch-totals div {
        min-width: 120px;
        flex: 1 1 120px;
    }

    .no-batches {
        text-align: center;
        color: #777;
        font-size: .85rem;
        padding: 2rem 0;
    }

    @media (max-width:600px){
        h1 {flex-direction: column; align-items:flex-start;}
        .batch-header {flex-direction: column;}
        .controls-row {flex-direction: column; align-items:stretch;}
        .totals-card {flex-direction: column;}
        .save-row {flex-direction: column; align-items:stretch;}
    }
</style>
</head>
<body>

<div class="app-shell">
    <h1>
        Roommate Split
        <span class="sub">Jacob / Ryan / Marlin</span>
    </h1>

    <div class="batch-header">
        <div>
            <label for="batchName">Batch name / note</label>
            <input id="batchName" type="text" placeholder="e.g. Costco run, living room stuff, etc." />
        </div>
        <div style="flex:1 1 100%;">
            <label for="batchDesc">Details (optional)</label>
            <textarea id="batchDesc" placeholder="Paper towels, rug, lamp, etc. Any notes."></textarea>
        </div>
    </div>

    <div class="items-table-wrapper">
        <table id="itemsTable">
            <thead>
                <tr>
                    <th style="min-width:200px;">Item</th>
                    <th style="width:110px;">Price ($)</th>
                    <th style="width:130px;">Who benefits?</th>
                    <th style="width:70px;"> </th>
                </tr>
            </thead>
            <tbody id="itemsBody">
                <!-- rows injected here -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">
                        <div class="controls-row">
                            <button id="addRowBtn" type="button">+ Add Line Item</button>
                            <div class="totals-card">
                                <div>
                                    <div class="label">Jacob owes</div>
                                    <div class="value" id="totalJacob">$0.00</div>
                                </div>
                                <div>
                                    <div class="label">Ryan owes</div>
                                    <div class="value" id="totalRyan">$0.00</div>
                                </div>
                                <div>
                                    <div class="label">Marlin owes</div>
                                    <div class="value" id="totalMarlin">$0.00</div>
                                </div>
                                <div>
                                    <div class="label">Batch total</div>
                                    <div class="value" id="totalAll">$0.00</div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="save-row">
        <button id="saveBatchBtn" type="button">Save This Batch</button>
        <small style="color:#666; font-size:.7rem; line-height:1.4;">
            Saving locks this math to history below.
        </small>
    </div>
</div>


<div class="old-batches">
    <h2 style="margin-top:0; font-size:1rem; font-weight:600; color:#111;">Saved Batches</h2>

    <div id="batchesContainer">
        <?php if (count($existingBatches) === 0): ?>
            <div class="no-batches">No batches yet.</div>
        <?php else: ?>
            <?php
            // newest first
            $existingBatches = array_reverse($existingBatches);
            foreach ($existingBatches as $batch):
                $bn = htmlspecialchars($batch['batchName'] ?? '');
                $bd = nl2br(htmlspecialchars($batch['batchDesc'] ?? ''));
                $ts = htmlspecialchars($batch['timestamp'] ?? '');
                $tj = number_format($batch['totals']['jacob'] ?? 0, 2);
                $tr = number_format($batch['totals']['ryan'] ?? 0, 2);
                $tm = number_format($batch['totals']['marlin'] ?? 0, 2);
                $ta = number_format($batch['totals']['all'] ?? 0, 2);
            ?>
            <div class="batch-card">
                <div class="batch-card-header">
                    <div class="title"><?= $bn !== '' ? $bn : '[no name]' ?></div>
                    <div class="date"><?= $ts ?></div>
                    <?php if ($bd !== ''): ?>
                        <div style="flex-basis:100%; font-size:.8rem; color:#444;"><?= $bd ?></div>
                    <?php endif; ?>
                </div>

                <div class="batch-totals">
                    <div><strong>Jacob:</strong> $<?= $tj ?></div>
                    <div><strong>Ryan:</strong> $<?= $tr ?></div>
                    <div><strong>Marlin:</strong> $<?= $tm ?></div>
                    <div><strong>Total:</strong> $<?= $ta ?></div>
                </div>

                <div style="overflow-x:auto; margin-top:.75rem;">
                    <table style="min-width:500px; font-size:.8rem; border-collapse:collapse; width:100%;">
                        <thead>
                            <tr style="background:#f9f9f9;">
                                <th style="text-align:left; border-bottom:1px solid #ddd; padding:.4rem .5rem;">Item</th>
                                <th style="text-align:right; border-bottom:1px solid #ddd; padding:.4rem .5rem;">Price</th>
                                <th style="text-align:left; border-bottom:1px solid #ddd; padding:.4rem .5rem;">Who</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($batch['items'] as $it):
                            $iname = htmlspecialchars($it['name']);
                            $iprice = number_format($it['price'], 2);
                            $iwho = htmlspecialchars($it['payerType']);
                        ?>
                            <tr>
                                <td style="border-bottom:1px solid #eee; padding:.4rem .5rem;"><?= $iname ?></td>
                                <td style="border-bottom:1px solid #eee; padding:.4rem .5rem; text-align:right;">$<?= $iprice ?></td>
                                <td style="border-bottom:1px solid #eee; padding:.4rem .5rem;"><?= $iwho ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
// ---------------------------
// CONFIG
// ---------------------------
const SPLIT_RULES = {
    "generic":  { jacob: 1/3,   ryan: 1/3,   marlin: 1/3   },
    "jacob":    { jacob: 0.66,  ryan: 0.17,  marlin: 0.17  },
    "ryan":     { jacob: 0.17,  ryan: 0.66,  marlin: 0.17  },
    "marlin":   { jacob: 0.17,  ryan: 0.17,  marlin: 0.66  },
};

// Round helper
function toMoney(n){
    return "$" + (Math.round(n*100)/100).toFixed(2);
}

// ---------------------------
// DOM refs
// ---------------------------
const itemsBody = document.getElementById('itemsBody');
const addRowBtn = document.getElementById('addRowBtn');
const totalJacobEl = document.getElementById('totalJacob');
const totalRyanEl = document.getElementById('totalRyan');
const totalMarlinEl = document.getElementById('totalMarlin');
const totalAllEl = document.getElementById('totalAll');
const saveBatchBtn = document.getElementById('saveBatchBtn');

const batchNameEl = document.getElementById('batchName');
const batchDescEl = document.getElementById('batchDesc');

// Keep internal data model in sync with UI fields
let rowData = []; // {id, nameEl, priceEl, whoEl}

// ---------------------------
// Row creation
// ---------------------------
function createRow() {
    const rowId = crypto.randomUUID();

    const tr = document.createElement('tr');
    tr.dataset.rowid = rowId;

    const tdName = document.createElement('td');
    const nameInput = document.createElement('input');
    nameInput.type = "text";
    nameInput.placeholder = "Item (paper towels, lamp, etc.)";
    nameInput.style.width = "100%";
    tdName.appendChild(nameInput);

    const tdPrice = document.createElement('td');
    const priceInput = document.createElement('input');
    priceInput.type = "number";
    priceInput.min = "0";
    priceInput.step = "0.01";
    priceInput.placeholder = "0.00";
    priceInput.style.width = "100%";
    tdPrice.appendChild(priceInput);

    const tdWho = document.createElement('td');
    const whoSelect = document.createElement('select');
    // generic first (default)
    [
        {val:"generic", label:"Shared 33/33/33"},
        {val:"jacob",   label:"Jacob personal"},
        {val:"ryan",    label:"Ryan personal"},
        {val:"marlin",  label:"Marlin personal"},
    ].forEach(optData=>{
        const opt = document.createElement('option');
        opt.value = optData.val;
        opt.textContent = optData.label;
        whoSelect.appendChild(opt);
    });
    tdWho.appendChild(whoSelect);

    const tdActions = document.createElement('td');
    tdActions.className = "row-actions";
    const delBtn = document.createElement('button');
    delBtn.type = "button";
    delBtn.textContent = "Delete";
    delBtn.className = "delete";
    tdActions.appendChild(delBtn);

    tr.appendChild(tdName);
    tr.appendChild(tdPrice);
    tr.appendChild(tdWho);
    tr.appendChild(tdActions);

    itemsBody.appendChild(tr);

    // track refs
    rowData.push({
        id: rowId,
        nameEl: nameInput,
        priceEl: priceInput,
        whoEl: whoSelect,
        trEl: tr,
    });

    // listeners that update totals without nuking focus
    [priceInput, whoSelect].forEach(el=>{
        el.addEventListener('input', recalcTotals);
        el.addEventListener('change', recalcTotals);
    });
    nameInput.addEventListener('input', ()=>{/* no cost impact */});

    delBtn.addEventListener('click', ()=>{
        // remove from DOM + data model
        tr.remove();
        rowData = rowData.filter(r => r.id !== rowId);
        recalcTotals();
    });

    return tr;
}

// ---------------------------
// Totals calc for the "current batch"
// ---------------------------
function recalcTotals() {
    let jacobTotal = 0;
    let ryanTotal = 0;
    let marlinTotal = 0;
    let allTotal = 0;

    rowData.forEach(r => {
        const price = parseFloat(r.priceEl.value || "0");
        if (isNaN(price) || price <= 0) return;
        const who = r.whoEl.value || "generic";
        const rule = SPLIT_RULES[who] || SPLIT_RULES['generic'];

        jacobTotal += price * rule.jacob;
        ryanTotal  += price * rule.ryan;
        marlinTotal+= price * rule.marlin;
        allTotal   += price;
    });

    totalJacobEl.textContent = toMoney(jacobTotal);
    totalRyanEl.textContent  = toMoney(ryanTotal);
    totalMarlinEl.textContent= toMoney(marlinTotal);
    totalAllEl.textContent   = toMoney(allTotal);
}

// ---------------------------
// Save batch
// ---------------------------
async function saveBatch() {
    // build payload
    const items = rowData.map(r => {
        const price = parseFloat(r.priceEl.value || "0") || 0;
        return {
            name: r.nameEl.value.trim(),
            price: price,
            payerType: r.whoEl.value || "generic"
        };
    }).filter(it => it.name !== "" && it.price > 0);

    // compute totals same way backend will, for immediate UI
    let jacobTotal = 0, ryanTotal = 0, marlinTotal = 0, allTotal = 0;
    items.forEach(it => {
        const rule = SPLIT_RULES[it.payerType] || SPLIT_RULES["generic"];
        jacobTotal += it.price * rule.jacob;
        ryanTotal  += it.price * rule.ryan;
        marlinTotal+= it.price * rule.marlin;
        allTotal   += it.price;
    });

    const payload = {
        mode: "saveBatch",
        batchName: batchNameEl.value.trim(),
        batchDesc: batchDescEl.value.trim(),
        totals: {
            jacob: jacobTotal,
            ryan: ryanTotal,
            marlin: marlinTotal,
            all: allTotal
        },
        items: items
    };

    // send to PHP
    const res = await fetch('data_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });
    const data = await res.json();

    if (data && data.ok) {
        // prepend new batch card to history
        prependBatchCard(data.savedBatch);

        // reset UI for new batch
        batchNameEl.value = "";
        batchDescEl.value = "";
        itemsBody.innerHTML = "";
        rowData = [];
        recalcTotals();
        createRow(); // start them with a fresh blank row
    } else {
        alert('Error saving batch.');
    }
}

// ---------------------------
// History card rendering (client side after save)
// ---------------------------
function prependBatchCard(batch){
    const container = document.getElementById('batchesContainer');
    // if "No batches yet." is there, remove it
    if (container.querySelector('.no-batches')) {
        container.innerHTML = "";
    }

    const card = document.createElement('div');
    card.className = 'batch-card';

    // header
    const header = document.createElement('div');
    header.className = 'batch-card-header';

    const titleDiv = document.createElement('div');
    titleDiv.className = 'title';
    titleDiv.textContent = batch.batchName && batch.batchName !== '' ? batch.batchName : '[no name]';

    const dateDiv = document.createElement('div');
    dateDiv.className = 'date';
    dateDiv.textContent = batch.timestamp;

    header.appendChild(titleDiv);
    header.appendChild(dateDiv);

    if (batch.batchDesc && batch.batchDesc.trim() !== "") {
        const descDiv = document.createElement('div');
        descDiv.style.flexBasis = "100%";
        descDiv.style.fontSize = ".8rem";
        descDiv.style.color = "#444";
        descDiv.innerText = batch.batchDesc;
        header.appendChild(descDiv);
    }

    // totals
    const totalsWrap = document.createElement('div');
    totalsWrap.className = 'batch-totals';
    totalsWrap.innerHTML = `
        <div><strong>Jacob:</strong> ${toMoney(batch.totals.jacob)}</div>
        <div><strong>Ryan:</strong> ${toMoney(batch.totals.ryan)}</div>
        <div><strong>Marlin:</strong> ${toMoney(batch.totals.marlin)}</div>
        <div><strong>Total:</strong> ${toMoney(batch.totals.all)}</div>
    `;

    // items list
    const tableWrap = document.createElement('div');
    tableWrap.style.overflowX = "auto";
    tableWrap.style.marginTop = ".75rem";

    const tbl = document.createElement('table');
    tbl.style.minWidth = "500px";
    tbl.style.fontSize = ".8rem";
    tbl.style.borderCollapse = "collapse";
    tbl.style.width = "100%";

    const thead = document.createElement('thead');
    thead.innerHTML = `
        <tr style="background:#f9f9f9;">
            <th style="text-align:left; border-bottom:1px solid #ddd; padding:.4rem .5rem;">Item</th>
            <th style="text-align:right; border-bottom:1px solid #ddd; padding:.4rem .5rem;">Price</th>
            <th style="text-align:left; border-bottom:1px solid #ddd; padding:.4rem .5rem;">Who</th>
        </tr>`;
    tbl.appendChild(thead);

    const tbody = document.createElement('tbody');
    batch.items.forEach(it=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td style="border-bottom:1px solid #eee; padding:.4rem .5rem;">${it.name}</td>
            <td style="border-bottom:1px solid #eee; padding:.4rem .5rem; text-align:right;">${toMoney(it.price)}</td>
            <td style="border-bottom:1px solid #eee; padding:.4rem .5rem;">${it.payerType}</td>
        `;
        tbody.appendChild(tr);
    });
    tbl.appendChild(tbody);
    tableWrap.appendChild(tbl);

    // assemble card
    card.appendChild(header);
    card.appendChild(totalsWrap);
    card.appendChild(tableWrap);

    // prepend
    container.insertBefore(card, container.firstChild);
}

// ---------------------------
// Init
// ---------------------------
addRowBtn.addEventListener('click', ()=>{
    createRow();
});
saveBatchBtn.addEventListener('click', saveBatch);

// start with 1 blank row
createRow();
recalcTotals();
</script>

</body>
</html>

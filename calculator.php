<!doctype html>
<html lang="fa">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>ماشین حساب خورشیدی رسا</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <!-- FIX: favicon path corrected to root-relative, type corrected to image/jpeg -->
  <link rel="shortcut icon" href="/icon.jpg" type="image/jpeg">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <style>
    :root{
      --bg1:#225e78;--bg2:#083a4b;
      --card-bg:rgba(255,255,255,0.08);
      --muted:#cfe8db;--accent:#00e676;--text:#e8f5e9;
    }
    html,body{height:100%;margin:0;font-family:"Inter",sans-serif;background:linear-gradient(135deg,var(--bg1),var(--bg2));color:var(--text);-webkit-font-smoothing:antialiased;}
    .dashboard{max-width:1100px;margin:40px auto;padding:30px;border-radius:20px;background:var(--card-bg);backdrop-filter:blur(18px);border:1px solid rgba(255,255,255,0.12);}
    .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
    .header h1{margin:0;font-weight:700}
    .lang-switch button{padding:8px 14px;border-radius:10px;border:none;cursor:pointer;background:rgba(255,255,255,0.12);color:var(--text);}
    .lang-switch .active{background:var(--accent);color:#012}
    .grid{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-top:12px}
    .card{background:rgba(255,255,255,0.06);padding:18px;border-radius:14px}
    .appliance-menu{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:14px;margin-bottom:18px}
    .appliance-option{background:rgba(255,255,255,0.08);border-radius:12px;padding:14px;text-align:center;cursor:pointer;border:1px solid rgba(255,255,255,0.06);transition:transform .14s ease,box-shadow .14s ease;color:var(--text);}
    .appliance-option:hover{transform:translateY(-6px);box-shadow:0 12px 28px rgba(0,0,0,0.35)}
    .appliance-option img{width:40px;height:40px;margin-bottom:8px;opacity:0.95}
    .appliance-option span{display:block;font-weight:600}
    .appliance{background:rgba(0,0,0,0.25);padding:12px;border-radius:10px;margin-bottom:12px;color:var(--text);}
    input[type="number"],input[type="text"]{width:100%;padding:8px;border-radius:8px;border:none;margin-top:6px;background:rgba(255,255,255,0.06);color:var(--text);box-sizing:border-box;}
    /* FIX: input validation error state */
    input.input-error{border:1px solid #ff5252 !important;background:rgba(255,82,82,0.12) !important;}
    .validation-msg{font-size:11px;color:#ff5252;margin-top:4px;display:none;}
    .calc-btn{margin-top:12px;padding:10px 16px;border-radius:12px;border:none;background:var(--accent);color:#012;font-weight:700;cursor:pointer;}
    .kpi{background:rgba(255,255,255,0.03);padding:12px;border-radius:10px;margin-top:10px;display:flex;justify-content:space-between;align-items:center;}
    .kpi-value{font-weight:700;color:#e8f5e9}
    .appliance-header{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:8px}
    .appliance-header .title{display:flex;gap:8px;align-items:center;flex:1;min-width:0}
    .remove-btn{background:#ff5252;color:white;border:none;padding:6px 10px;border-radius:8px;cursor:pointer}
    .export-controls{display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap}
    .export-controls .control{min-width:120px}
    select{background:rgba(0,0,0,0.7);color:var(--text);border:1px solid rgba(255,255,255,0.2);padding:8px;border-radius:8px;}
    select option{background:#0b1114;color:#e8f5e9;}
    canvas{width:100% !important;height:260px !important;border-radius:8px;background:transparent;display:block;}
    .pdf-preview{display:flex;flex-direction:column;gap:8px;align-items:stretch}
    /* FIX: table headers now use bilingual spans */
    table{width:100%;border-collapse:collapse;}
    th,td{padding:6px 8px;border-bottom:1px solid rgba(255,255,255,0.08);color:var(--text);font-size:0.9rem;}
    th{color:var(--muted);font-weight:600;text-align:start;}

    /* NEW: Panel recommendation section */
    .panel-rec-section{margin-top:14px;border-top:1px solid rgba(255,255,255,0.1);padding-top:14px;}
    .panel-rec-title{font-weight:700;font-size:0.95rem;margin-bottom:10px;color:var(--accent);}
    .panel-rec-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(0,230,118,0.12);border:1px solid rgba(0,230,118,0.35);border-radius:10px;padding:8px 14px;margin-bottom:12px;}
    .panel-rec-badge .badge-icon{font-size:1.3rem;}
    .panel-rec-badge .badge-text{font-size:0.85rem;line-height:1.4;}
    .panel-rec-badge .badge-text strong{display:block;font-size:1.05rem;color:var(--accent);}
    .panel-comparison-table{width:100%;border-collapse:collapse;margin-top:6px;}
    .panel-comparison-table th{color:var(--muted);font-size:0.8rem;padding:6px 8px;border-bottom:1px solid rgba(255,255,255,0.1);}
    .panel-comparison-table td{padding:7px 8px;font-size:0.85rem;border-bottom:1px solid rgba(255,255,255,0.06);}
    .panel-comparison-table tr.recommended-row td{background:rgba(0,230,118,0.07);font-weight:600;}
    .rec-star{color:var(--accent);font-size:0.75rem;margin-inline-start:4px;}
    .roof-estimate{font-size:0.8rem;color:var(--muted);margin-top:8px;padding:7px 10px;background:rgba(255,255,255,0.04);border-radius:8px;}

    /* NEW: Inverter & battery recommendation — reuses panel-rec-* classes, adds a side-by-side layout */
    .dual-badge-row{display:flex;gap:12px;flex-wrap:wrap;}
    .dual-badge-row .panel-rec-badge{flex:1;min-width:220px;margin-bottom:0;}
    .storage-subsection{margin-top:16px;}
    .storage-subsection-title{font-weight:600;font-size:0.85rem;color:var(--muted);margin-bottom:8px;}

    /* NEW: custom panel override input */
    .custom-panel-row{display:flex;align-items:center;gap:10px;margin-top:10px;flex-wrap:wrap;}
    .custom-panel-row label{font-size:0.82rem;color:var(--muted);white-space:nowrap;}
    .custom-panel-row input{width:90px;flex:none;margin-top:0;}

    /* NEW: 2x12 interactive hour-of-day grid (replaces manual hours input) */
    .hour-grid-wrap{margin-top:6px;}
    .hour-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:3px;user-select:none;-webkit-user-select:none;touch-action:none;}
    .hour-cell{position:relative;aspect-ratio:1/1;border-radius:4px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.06);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:9px;color:var(--muted);transition:background .1s ease,color .1s ease;}
    .hour-cell:hover{background:rgba(255,255,255,0.2);}
    .hour-cell.active{background:var(--accent);color:#012;font-weight:700;border-color:var(--accent);}
    .hour-grid-quick{display:flex;gap:6px;margin-top:6px;}
    .hour-quick-btn{background:rgba(255,255,255,0.08);color:var(--text);border:1px solid rgba(255,255,255,0.1);border-radius:6px;padding:4px 9px;font-size:0.72rem;cursor:pointer;}
    .hour-quick-btn:hover{background:rgba(255,255,255,0.16);}
    .hour-grid-summary{font-size:0.78rem;color:var(--muted);margin-top:6px;}
    .hour-grid-summary strong{color:var(--text);}

    @media (max-width:980px){.grid{grid-template-columns:1fr}canvas{height:220px !important}}
  </style>
</head>
<body>
  <div class="dashboard" id="app" dir="rtl">
    <div class="header">
      <div>
        <h1 id="title-fa">ماشین حساب خورشیدی رسا</h1>
        <h1 id="title-en" style="display:none">Rasa Solar Calculator</h1>
      </div>
      <div class="lang-switch">
        <button id="btn-fa" class="active">فارسی</button>
        <button id="btn-en">English</button>
      </div>
    </div>

    <div class="grid">
      <!-- LEFT PANEL -->
      <div class="card">
        <h2 id="select-title-fa">انتخاب وسیله</h2>
        <h2 id="select-title-en" style="display:none">Select Appliance</h2>

        <div class="appliance-menu">
          <div class="appliance-option" onclick="addAppliance('tv')">
            <img src="https://img.icons8.com/ios-filled/100/ffffff/tv.png" alt="">
            <span class="name-fa">تلویزیون</span><span class="name-en" style="display:none">TV</span>
          </div>
          <div class="appliance-option" onclick="addAppliance('fridge')">
            <img src="https://img.icons8.com/ios-filled/100/ffffff/fridge.png" alt="">
            <span class="name-fa">یخچال</span><span class="name-en" style="display:none">Fridge</span>
          </div>
          <div class="appliance-option" onclick="addAppliance('ac')">
            <img src="https://img.icons8.com/ios-filled/100/ffffff/air-conditioner.png" alt="">
            <span class="name-fa">کولر گازی</span><span class="name-en" style="display:none">AC</span>
          </div>
          <div class="appliance-option" onclick="addAppliance('lights')">
            <img src="https://img.icons8.com/ios-filled/100/ffffff/light-on.png" alt="">
            <span class="name-fa">روشنایی</span><span class="name-en" style="display:none">Lighting</span>
          </div>
          <div class="appliance-option" onclick="addAppliance('pc')">
            <img src="https://img.icons8.com/ios-filled/100/ffffff/laptop.png" alt="">
            <span class="name-fa">کامپیوتر</span><span class="name-en" style="display:none">Computer</span>
          </div>
          <div class="appliance-option" onclick="addAppliance('washing')">
            <img src="https://img.icons8.com/ios-filled/100/ffffff/washing-machine.png" alt="">
            <span class="name-fa">ماشین لباسشویی</span><span class="name-en" style="display:none">Washing</span>
          </div>
          <div class="appliance-option" onclick="addAppliance('custom')">
            <img src="https://img.icons8.com/ios-filled/100/ffffff/plus.png" alt="">
            <span class="name-fa">وسیله دلخواه</span><span class="name-en" style="display:none">Custom</span>
          </div>
        </div>

        <div id="appliance-container"></div>

        <h3 id="solar-fa">تنظیمات خورشیدی</h3>
        <h3 id="solar-en" style="display:none">Solar Settings</h3>

        <label id="sun-fa">ساعات مفید تابش</label>
        <label id="sun-en" style="display:none">Sun hours</label>
        <!-- FIX: min attribute added to prevent 0 silently -->
        <input type="number" id="sun-hours" value="5" min="0.1" step="0.5">
        <div class="validation-msg" id="sun-error-fa">ساعات تابش باید بیشتر از صفر باشد</div>
        <div class="validation-msg" id="sun-error-en" style="display:none">Sun hours must be greater than zero</div>

        <label id="eff-fa">راندمان (%)</label>
        <label id="eff-en" style="display:none">Efficiency (%)</label>
        <!-- FIX: min/max to prevent division by zero and nonsensical values -->
        <input type="number" id="eff" value="80" min="10" max="100">
        <div class="validation-msg" id="eff-error-fa">راندمان باید بین ۱۰ تا ۱۰۰ درصد باشد</div>
        <div class="validation-msg" id="eff-error-en" style="display:none">Efficiency must be between 10 and 100%</div>

        <!-- NEW: Custom panel wattage override -->
        <div class="custom-panel-row" style="margin-top:14px;">
          <label id="custpanel-fa">توان پنل دلخواه (اختیاری):</label>
          <label id="custpanel-en" style="display:none">Custom panel wattage (optional):</label>
          <input type="number" id="custom-panel-w" placeholder="—" min="10" max="1000" step="10" style="width:90px;margin-top:0;">
          <span style="font-size:0.8rem;color:var(--muted);">W</span>
        </div>
        <div style="font-size:0.78rem;color:var(--muted);margin-top:4px;" id="custpanel-hint-fa">خالی بگذارید تا پنل بهینه خودکار پیشنهاد شود</div>
        <div style="font-size:0.78rem;color:var(--muted);margin-top:4px;display:none" id="custpanel-hint-en">Leave blank for automatic panel recommendation</div>

        <!-- NEW: Battery / inverter settings -->
        <h3 id="storage-settings-fa" style="margin-top:18px">تنظیمات باتری و اینورتر</h3>
        <h3 id="storage-settings-en" style="display:none;margin-top:18px">Battery &amp; Inverter Settings</h3>

        <label id="autonomy-fa">روزهای پشتیبان بدون آفتاب (روز)</label>
        <label id="autonomy-en" style="display:none">Backup autonomy (days)</label>
        <input type="number" id="autonomy-days" value="1" min="0.5" max="10" step="0.5">
        <div class="validation-msg" id="autonomy-error-fa">روزهای پشتیبان باید بیشتر از صفر باشد</div>
        <div class="validation-msg" id="autonomy-error-en" style="display:none">Autonomy days must be greater than zero</div>

        <label id="dod-fa">عمق تخلیه مجاز باتری (%)</label>
        <label id="dod-en" style="display:none">Battery depth of discharge (%)</label>
        <input type="number" id="dod" value="80" min="10" max="100">
        <div class="validation-msg" id="dod-error-fa">عمق تخلیه باید بین ۱۰ تا ۱۰۰ درصد باشد</div>
        <div class="validation-msg" id="dod-error-en" style="display:none">DoD must be between 10 and 100%</div>

        <label id="sysvolt-fa">ولتاژ سیستم باتری</label>
        <label id="sysvolt-en" style="display:none">Battery bank voltage</label>
        <select id="system-voltage">
          <option value="12">12V</option>
          <option value="24" selected>24V</option>
          <option value="48">48V</option>
        </select>

        <div class="custom-panel-row" style="margin-top:14px;">
          <label id="custinv-fa">توان اینورتر دلخواه (اختیاری):</label>
          <label id="custinv-en" style="display:none">Custom inverter power (optional):</label>
          <input type="number" id="custom-inverter-w" placeholder="—" min="100" max="20000" step="100" style="width:90px;margin-top:0;">
          <span style="font-size:0.8rem;color:var(--muted);">W</span>
        </div>

        <div class="custom-panel-row" style="margin-top:8px;">
          <label id="custbatt-fa">ظرفیت باتری دلخواه (اختیاری):</label>
          <label id="custbatt-en" style="display:none">Custom battery capacity (optional):</label>
          <input type="number" id="custom-battery-kwh" placeholder="—" min="0.1" max="50" step="0.1" style="width:90px;margin-top:0;">
          <span style="font-size:0.8rem;color:var(--muted);">kWh</span>
        </div>
        <div style="font-size:0.78rem;color:var(--muted);margin-top:4px;" id="custstorage-hint-fa">خالی بگذارید تا اینورتر و باتری بهینه خودکار پیشنهاد شود</div>
        <div style="font-size:0.78rem;color:var(--muted);margin-top:4px;display:none" id="custstorage-hint-en">Leave blank for automatic inverter/battery recommendation</div>

        <button class="calc-btn" id="calc-btn-fa">محاسبه</button>
        <button class="calc-btn" id="calc-btn-en" style="display:none">Calculate</button>
      </div>

      <!-- RIGHT PANEL -->
      <div class="card" id="results" style="display:none">
        <h2 id="results-fa">نتایج</h2>
        <h2 id="results-en" style="display:none">Results</h2>

        <!-- FIX: export button labels are now bilingual -->
        <div class="export-controls">
          <div class="control">
            <button id="export-btn" class="calc-btn">
              <span class="label-fa">خروجی PDF (عمودی)</span>
              <span class="label-en" style="display:none">Export PDF (Portrait)</span>
            </button>
          </div>
          <div class="control">
            <button id="export-btn-land" class="calc-btn" style="background:#555;color:#fff">
              <span class="label-fa">خروجی PDF (افقی)</span>
              <span class="label-en" style="display:none">Export PDF (Landscape)</span>
            </button>
          </div>
          <div class="control">
            <!-- FIX: PDF background label bilingual -->
            <label style="font-size:12px;color:#cfe8db;display:block;margin-bottom:6px">
              <span class="label-fa">پس‌زمینه PDF</span>
              <span class="label-en" style="display:none">PDF Background</span>
            </label>
            <select id="pdf-bg-select">
              <option value="gradient">Gradient (blue)</option>
              <option value="dark">Dark</option>
            </select>
          </div>
          <div class="control">
            <!-- FIX: Page size label bilingual -->
            <label style="font-size:12px;color:#cfe8db;display:block;margin-bottom:6px">
              <span class="label-fa">اندازه صفحه</span>
              <span class="label-en" style="display:none">Page Size</span>
            </label>
            <select id="pdf-size-select">
              <option value="a4">A4</option>
              <option value="letter">Letter</option>
            </select>
          </div>
        </div>

        <div id="export-area" class="pdf-preview">
          <canvas id="energyChart"></canvas>

          <!-- KPI 1: Daily consumption -->
          <div class="kpi">
            <div>
              <div id="kpi1-title-fa">مصرف روزانه (kWh)</div>
              <div id="kpi1-title-en" style="display:none">Daily Consumption (kWh)</div>
            </div>
            <div class="kpi-value" id="kpi1">0</div>
          </div>

          <!-- KPI 2: Required system power -->
          <div class="kpi">
            <div>
              <div id="kpi2-title-fa">توان سیستم مورد نیاز (W)</div>
              <div id="kpi2-title-en" style="display:none">Required System Power (W)</div>
            </div>
            <div class="kpi-value" id="kpi2">0</div>
          </div>

          <!-- KPI 3: FIX — now shows recommended panel watt dynamically, not hardcoded 300W -->
          <div class="kpi">
            <div>
              <div id="kpi3-title-fa">تعداد پنل پیشنهادی</div>
              <div id="kpi3-title-en" style="display:none">Recommended Panel Count</div>
              <!-- sub-label showing which panel watt was used -->
              <div id="kpi3-subtitle" style="font-size:0.75rem;color:var(--muted);margin-top:2px;"></div>
            </div>
            <div class="kpi-value" id="kpi3">0</div>
          </div>

          <!-- NEW: Peak simultaneous load — drives inverter sizing -->
          <div class="kpi">
            <div>
              <div id="kpi4-title-fa">حداکثر بار همزمان (W)</div>
              <div id="kpi4-title-en" style="display:none">Peak Simultaneous Load (W)</div>
              <div style="font-size:0.75rem;color:var(--muted);margin-top:2px;" id="kpi4-subtitle"></div>
            </div>
            <div class="kpi-value" id="kpi4">0</div>
          </div>

          <!-- NEW: Panel recommendation section -->
          <div class="panel-rec-section" id="panel-rec-section">
            <div class="panel-rec-title">
              <span id="panel-rec-title-fa">⚡ پیشنهاد پنل خورشیدی</span>
              <span id="panel-rec-title-en" style="display:none">⚡ Panel Recommendation</span>
            </div>

            <!-- auto recommendation badge -->
            <div class="panel-rec-badge" id="rec-badge">
              <span class="badge-icon">☀️</span>
              <div class="badge-text">
                <span id="rec-badge-label-fa">پنل پیشنهادی</span>
                <span id="rec-badge-label-en" style="display:none">Recommended panel</span>
                <strong id="rec-badge-value">—</strong>
                <span id="rec-badge-reason" style="font-size:0.78rem;color:var(--muted);">—</span>
              </div>
            </div>

            <!-- comparison table of a few panel-count/wattage combinations -->
            <table class="panel-comparison-table" id="panel-compare-table">
              <thead>
                <tr>
                  <!-- FIX: bilingual table headers -->
                  <th><span class="th-fa">ترکیب پنل</span><span class="th-en" style="display:none">Combination</span></th>
                  <th><span class="th-fa">توان کل (W)</span><span class="th-en" style="display:none">Total Output (W)</span></th>
                  <th><span class="th-fa">تطابق</span><span class="th-en" style="display:none">Fit</span></th>
                  <th><span class="th-fa">مساحت (m²)</span><span class="th-en" style="display:none">Roof Area (m²)</span></th>
                </tr>
              </thead>
              <tbody id="panel-compare-body"></tbody>
            </table>

            <!-- roof area estimate note -->
            <div class="roof-estimate" id="roof-note">
              <span id="roof-fa">تخمین مساحت بر اساس ابعاد فیزیکی واقعی هر پنل محاسبه شده؛ پنل‌های پرتوان‌تر بزرگ‌تر هستند.</span>
              <span id="roof-en" style="display:none">Area is estimated from each panel's real physical footprint — higher-wattage panels are larger.</span>
            </div>
          </div>

          <!-- NEW: Inverter & battery recommendation section -->
          <div class="panel-rec-section" id="storage-rec-section">
            <div class="panel-rec-title">
              <span id="storage-rec-title-fa">🔋 پیشنهاد اینورتر و باتری</span>
              <span id="storage-rec-title-en" style="display:none">🔋 Inverter &amp; Battery Recommendation</span>
            </div>

            <!-- side-by-side badges: inverter + battery -->
            <div class="dual-badge-row">
              <div class="panel-rec-badge" id="inv-badge">
                <span class="badge-icon">🔌</span>
                <div class="badge-text">
                  <span id="inv-badge-label-fa">اینورتر پیشنهادی</span>
                  <span id="inv-badge-label-en" style="display:none">Recommended inverter</span>
                  <strong id="inv-badge-value">—</strong>
                  <span id="inv-badge-reason" style="font-size:0.78rem;color:var(--muted);">—</span>
                </div>
              </div>
              <div class="panel-rec-badge" id="batt-badge">
                <span class="badge-icon">🔋</span>
                <div class="badge-text">
                  <span id="batt-badge-label-fa">باتری پیشنهادی</span>
                  <span id="batt-badge-label-en" style="display:none">Recommended battery bank</span>
                  <strong id="batt-badge-value">—</strong>
                  <span id="batt-badge-reason" style="font-size:0.78rem;color:var(--muted);">—</span>
                </div>
              </div>
            </div>

            <!-- inverter comparison table -->
            <div class="storage-subsection">
              <div class="storage-subsection-title">
                <span id="inv-table-title-fa">گزینه‌های اینورتر</span>
                <span id="inv-table-title-en" style="display:none">Inverter options</span>
              </div>
              <table class="panel-comparison-table" id="inv-compare-table">
                <thead>
                  <tr>
                    <th><span class="th-fa">ترکیب اینورتر</span><span class="th-en" style="display:none">Combination</span></th>
                    <th><span class="th-fa">توان کل (W)</span><span class="th-en" style="display:none">Total Output (W)</span></th>
                    <th><span class="th-fa">تطابق</span><span class="th-en" style="display:none">Fit</span></th>
                  </tr>
                </thead>
                <tbody id="inv-compare-body"></tbody>
              </table>
            </div>

            <!-- battery comparison table -->
            <div class="storage-subsection">
              <div class="storage-subsection-title">
                <span id="batt-table-title-fa">گزینه‌های باتری</span>
                <span id="batt-table-title-en" style="display:none">Battery options</span>
              </div>
              <table class="panel-comparison-table" id="batt-compare-table">
                <thead>
                  <tr>
                    <th><span class="th-fa">ترکیب باتری</span><span class="th-en" style="display:none">Combination</span></th>
                    <th><span class="th-fa">ظرفیت کل (kWh)</span><span class="th-en" style="display:none">Total Capacity (kWh)</span></th>
                    <th><span class="th-fa">معادل (Ah)</span><span class="th-en" style="display:none">Equivalent (Ah)</span></th>
                    <th><span class="th-fa">تطابق</span><span class="th-en" style="display:none">Fit</span></th>
                  </tr>
                </thead>
                <tbody id="batt-compare-body"></tbody>
              </table>
            </div>

            <div class="roof-estimate" id="storage-note">
              <span id="storage-note-fa">توان اینورتر بر اساس حداکثر بار همزمان با حاشیه ایمنی برای راه‌اندازی موتورها محاسبه شده؛ ظرفیت باتری بر اساس مصرف روزانه، روزهای پشتیبان و عمق تخلیه مجاز است.</span>
              <span id="storage-note-en" style="display:none">Inverter sizing includes a safety margin for motor start-up surges; battery capacity is based on daily consumption, backup days, and allowed depth of discharge.</span>
            </div>
          </div>

          <!-- Appliance preview table — FIX: all headers bilingual -->
          <div id="preview-table" style="margin-top:8px">
            <table id="appliance-preview-table">
              <thead>
                <tr>
                  <th><span class="th-fa">نام</span><span class="th-en" style="display:none">Name</span></th>
                  <th style="text-align:end"><span class="th-fa">توان (W)</span><span class="th-en" style="display:none">Power (W)</span></th>
                  <th style="text-align:end"><span class="th-fa">ساعت</span><span class="th-en" style="display:none">Hours</span></th>
                  <th style="text-align:end"><span class="th-fa">روزانه (Wh)</span><span class="th-en" style="display:none">Daily (Wh)</span></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const EXPORT_WATERMARK_TEXT = "rasa-energy.ir";
    const EXPORT_WATERMARK_IMAGE = null;
    const EXPORT_WATERMARK_OPACITY = 0.06;
    const EXPORT_WATERMARK_FONT_SIZE = 96;

    const applianceData = {
      tv:      { fa:"تلویزیون",         en:"TV",              power:80,   hours:4, start:19 },
      fridge:  { fa:"یخچال",            en:"Fridge",          power:150,  hours:8, start:0  },
      ac:      { fa:"کولر گازی",        en:"Air Conditioner", power:1200, hours:5, start:13 },
      lights:  { fa:"روشنایی",          en:"Lighting",        power:200,  hours:6, start:18 },
      pc:      { fa:"کامپیوتر",         en:"Computer",        power:150,  hours:5, start:9  },
      washing: { fa:"ماشین لباسشویی",  en:"Washing Machine", power:500,  hours:1, start:10 },
      custom:  { fa:"وسیله دلخواه",    en:"Custom Appliance",power:0,    hours:0, start:0  }
    };

    // Build a contiguous default set of active hours [start, start+hours) wrapping at 24
    function defaultActiveHours(data) {
      const list = [];
      for (let i = 0; i < data.hours; i++) list.push((data.start + i) % 24);
      return list;
    }

    // ─── PANEL RECOMMENDATION ───────────────────────────────────────────────────
    // Real range of panel sizes sold today, from small (150W) to large (700W).
    // The goal each time: find the panel count/wattage combo that wastes the
    // least capacity, checking 1 panel first, then 2, then more — so a 550W
    // load gets "1 × 550W" (exact fit), not an awkward "2 × 500W" (82% waste)
    // just because 2-panel combos happened to be checked first.
    const PANEL_WATTAGES = [150, 200, 300, 400, 450, 500, 550, 600, 620, 650, 700];
    const MAX_PANELS_TO_CONSIDER = 8; // don't check absurd panel counts for tiny loads

    function faDigits(s) {
      const map = ["۰","۱","۲","۳","۴","۵","۶","۷","۸","۹"];
      return String(s).replace(/[0-9]/g, d => map[d]);
    }

    // Approximate real physical footprint for a panel of a given wattage.
    function panelArea(w) {
      return Math.max(0.3, w / 205);
    }

    function bestFitForCount(requiredW, count) {
      const maxW = PANEL_WATTAGES[PANEL_WATTAGES.length - 1];
      const neededPerPanel = requiredW / count;
      const watt = PANEL_WATTAGES.find(w => w >= neededPerPanel - 1e-9) || maxW;
      const totalCapacity = watt * count;
      const wastePct = ((totalCapacity - requiredW) / requiredW) * 100;
      return { count, watt, totalCapacity, wastePct, area: panelArea(watt) * count };
    }

    // Try 1 panel, then 2, then 3... and keep the option with the least wasted
    // capacity. This naturally favors "1 well-matched panel" when one exists,
    // and only reaches for more (higher-wattage) panels when the load genuinely
    // needs it — covering everything from a single 150W panel up to many 700W
    // panels for heavy-duty systems.
    function getPanelOptions(requiredW) {
      if (!requiredW || requiredW <= 0) {
        const watt = 300;
        return [{ count:1, watt, totalCapacity:watt, wastePct:0, area:panelArea(watt) }];
      }

      const maxW = PANEL_WATTAGES[PANEL_WATTAGES.length - 1];
      const minCount = Math.max(1, Math.ceil(requiredW / maxW));
      const maxCount = Math.max(minCount, Math.min(minCount + MAX_PANELS_TO_CONSIDER, minCount * 3));

      let options = [];
      for (let count = minCount; count <= maxCount; count++) {
        options.push(bestFitForCount(requiredW, count));
      }

      // De-duplicate identical (count, watt) combos
      const seen = new Set();
      options = options.filter(o => {
        const key = o.count + "x" + o.watt;
        if (seen.has(key)) return false;
        seen.add(key);
        return true;
      });

      // Drop options that are strictly worse in both count and waste than another
      options = options.filter(o =>
        !options.some(other => other !== o && other.count < o.count && other.wastePct < o.wastePct)
      );

      options.sort((a, b) => a.count - b.count);
      return options.slice(0, 3);
    }

    // Recommend the fewest-panel combo that stays within a sane oversize
    // margin (options are sorted by count ascending, so this naturally prefers
    // "1 well-matched panel" or "fewer, higher-wattage panels" over splitting
    // into more, smaller ones — only reaching for more panels when the waste
    // from fewer panels would be excessive).
    function pickRecommendedIndex(options) {
      const WASTE_THRESHOLD = 20; // %
      let idx = options.findIndex(o => o.wastePct <= WASTE_THRESHOLD);
      if (idx === -1) {
        idx = options.reduce((best, o, i) => (o.wastePct < options[best].wastePct ? i : best), 0);
      }
      return idx;
    }

    // ─── INVERTER RECOMMENDATION ────────────────────────────────────────────────
    // Standard off-the-shelf inverter power ratings (continuous, watts).
    const INVERTER_WATTAGES = [300, 500, 800, 1000, 1500, 2000, 2500, 3000, 4000, 5000, 6000, 8000, 10000, 12000];
    const MAX_INVERTERS_TO_CONSIDER = 4; // beyond ~4 stacked units, real installs move to 3-phase gear
    // Continuous rating is sized above the peak load to leave headroom for
    // inductive/motor start-up surges (fridge/AC/pump compressors briefly draw
    // several times their running watts when they switch on).
    const INVERTER_SURGE_MARGIN = 1.25;

    function bestFitInverterForCount(requiredW, count) {
      const maxW = INVERTER_WATTAGES[INVERTER_WATTAGES.length - 1];
      const neededPerUnit = requiredW / count;
      const watt = INVERTER_WATTAGES.find(w => w >= neededPerUnit - 1e-9) || maxW;
      const totalCapacity = watt * count;
      const wastePct = ((totalCapacity - requiredW) / requiredW) * 100;
      return { count, watt, totalCapacity, wastePct };
    }

    // requiredW here is the raw peak load — the surge margin is applied inside.
    function getInverterOptions(requiredW) {
      const effectiveW = Math.max(0, requiredW) * INVERTER_SURGE_MARGIN;
      if (!effectiveW || effectiveW <= 0) {
        const watt = 500;
        return [{ count:1, watt, totalCapacity:watt, wastePct:0 }];
      }

      const maxW = INVERTER_WATTAGES[INVERTER_WATTAGES.length - 1];
      const minCount = Math.max(1, Math.ceil(effectiveW / maxW));
      const maxCount = Math.max(minCount, Math.min(minCount + MAX_INVERTERS_TO_CONSIDER, minCount * 3));

      let options = [];
      for (let count = minCount; count <= maxCount; count++) {
        options.push(bestFitInverterForCount(effectiveW, count));
      }

      const seen = new Set();
      options = options.filter(o => {
        const key = o.count + "x" + o.watt;
        if (seen.has(key)) return false;
        seen.add(key);
        return true;
      });

      options = options.filter(o =>
        !options.some(other => other !== o && other.count < o.count && other.wastePct < o.wastePct)
      );

      options.sort((a, b) => a.count - b.count);
      return options.slice(0, 3);
    }

    // ─── BATTERY RECOMMENDATION ─────────────────────────────────────────────────
    // Standard lithium battery module capacities sold today (Wh per unit).
    const BATTERY_WH_OPTIONS = [1200, 2400, 3600, 4800, 5000, 7200, 10000, 14000, 15000, 20000];
    const MAX_BATTERIES_TO_CONSIDER = 8;
    const BATTERY_ROUNDTRIP_EFF = 0.95; // charge/discharge conversion loss

    function bestFitBatteryForCount(requiredWh, count) {
      const maxWh = BATTERY_WH_OPTIONS[BATTERY_WH_OPTIONS.length - 1];
      const neededPerUnit = requiredWh / count;
      const wh = BATTERY_WH_OPTIONS.find(w => w >= neededPerUnit - 1e-9) || maxWh;
      const totalCapacity = wh * count;
      const wastePct = ((totalCapacity - requiredWh) / requiredWh) * 100;
      return { count, wh, totalCapacity, wastePct };
    }

    // requiredWh = usable daily energy × autonomy days, already grossed up for DoD + round-trip losses.
    function getBatteryOptions(requiredWh) {
      if (!requiredWh || requiredWh <= 0) {
        const wh = 2400;
        return [{ count:1, wh, totalCapacity:wh, wastePct:0 }];
      }

      const maxWh = BATTERY_WH_OPTIONS[BATTERY_WH_OPTIONS.length - 1];
      const minCount = Math.max(1, Math.ceil(requiredWh / maxWh));
      const maxCount = Math.max(minCount, Math.min(minCount + MAX_BATTERIES_TO_CONSIDER, minCount * 3));

      let options = [];
      for (let count = minCount; count <= maxCount; count++) {
        options.push(bestFitBatteryForCount(requiredWh, count));
      }

      const seen = new Set();
      options = options.filter(o => {
        const key = o.count + "x" + o.wh;
        if (seen.has(key)) return false;
        seen.add(key);
        return true;
      });

      options = options.filter(o =>
        !options.some(other => other !== o && other.count < o.count && other.wastePct < o.wastePct)
      );

      options.sort((a, b) => a.count - b.count);
      return options.slice(0, 3);
    }

    // Peak simultaneous wattage across the 24h grid — the true driver of inverter
    // sizing (unlike panels/battery, which care about total daily energy, an
    // inverter only has to survive the worst single hour of overlapping loads).
    function getPeakLoadWatts() {
      const hourly = new Array(24).fill(0);
      document.querySelectorAll(".appliance").forEach(ap => {
        const power = parseFloat(ap.querySelector(".power").value) || 0;
        let selectedHours = [];
        try { selectedHours = JSON.parse(ap.dataset.selectedHours || "[]"); } catch(e) {}
        selectedHours.forEach(h => { if (h >= 0 && h < 24) hourly[h] += power; });
      });
      return Math.max(0, ...hourly);
    }

    // Required usable storage in Wh: daily energy × backup days, grossed up so that
    // draining it to the allowed DoD (and absorbing round-trip losses) still covers
    // the target — e.g. an 80% DoD battery needs to be ~1/0.8 bigger than the raw need.
    function getRequiredBatteryWh(dailyWh, autonomyDays, dodPct) {
      const dod = Math.max(0.1, (dodPct || 80) / 100);
      return (dailyWh * (autonomyDays || 1)) / dod / BATTERY_ROUNDTRIP_EFF;
    }

    // ─── VALIDATION ─────────────────────────────────────────────────────────────
    function validateInputs() {
      const isFa = document.getElementById("app").dir === "rtl";
      let valid = true;

      const sunEl  = document.getElementById("sun-hours");
      const effEl  = document.getElementById("eff");
      const sunVal = parseFloat(sunEl.value);
      const effVal = parseFloat(effEl.value);

      const showErr = (inputEl, errFaId, errEnId, show) => {
        inputEl.classList.toggle("input-error", show);
        document.getElementById(errFaId).style.display = (show && isFa)  ? "block" : "none";
        document.getElementById(errEnId).style.display = (show && !isFa) ? "block" : "none";
      };

      if (!sunVal || sunVal <= 0) {
        showErr(sunEl, "sun-error-fa", "sun-error-en", true);
        valid = false;
      } else {
        showErr(sunEl, "sun-error-fa", "sun-error-en", false);
      }

      if (!effVal || effVal < 10 || effVal > 100) {
        showErr(effEl, "eff-error-fa", "eff-error-en", true);
        valid = false;
      } else {
        showErr(effEl, "eff-error-fa", "eff-error-en", false);
      }

      const autonomyEl  = document.getElementById("autonomy-days");
      const dodEl       = document.getElementById("dod");
      const autonomyVal = parseFloat(autonomyEl.value);
      const dodVal      = parseFloat(dodEl.value);

      if (!autonomyVal || autonomyVal <= 0) {
        showErr(autonomyEl, "autonomy-error-fa", "autonomy-error-en", true);
        valid = false;
      } else {
        showErr(autonomyEl, "autonomy-error-fa", "autonomy-error-en", false);
      }

      if (!dodVal || dodVal < 10 || dodVal > 100) {
        showErr(dodEl, "dod-error-fa", "dod-error-en", true);
        valid = false;
      } else {
        showErr(dodEl, "dod-error-fa", "dod-error-en", false);
      }

      // FIX: validate appliance inputs (no negative values)
      document.querySelectorAll(".power,.hours").forEach(inp => {
        const v = parseFloat(inp.value);
        if (v < 0) { inp.value = 0; }
      });

      return valid;
    }

    // ─── HOUR GRID (2×12 interactive 24h selector) ─────────────────────────────
    // Shared drag state: while dragging, every cell entered is set to `mode`
    // (true = turn on, false = turn off), determined by the first cell touched.
    const hourDragState = { active: false, mode: true };

    function buildHourGrid(card, initialActiveHours) {
      const grid = card.querySelector(".hour-grid");
      grid.innerHTML = "";
      const activeSet = new Set(initialActiveHours || []);
      for (let h = 0; h < 24; h++) {
        const cell = document.createElement("div");
        cell.className = "hour-cell" + (activeSet.has(h) ? " active" : "");
        cell.dataset.hour = String(h);
        cell.textContent = String(h);
        cell.title = `${String(h).padStart(2,"0")}:00–${String((h+1)%24).padStart(2,"0")}:00`;
        grid.appendChild(cell);
      }
      attachHourGridEvents(card);
      updateHourCount(card);
    }

    function attachHourGridEvents(card) {
      const grid = card.querySelector(".hour-grid");

      const setCell = (cell, on) => {
        cell.classList.toggle("active", on);
      };

      grid.querySelectorAll(".hour-cell").forEach(cell => {
        cell.addEventListener("mousedown", (e) => {
          e.preventDefault();
          hourDragState.active = true;
          hourDragState.mode = !cell.classList.contains("active");
          setCell(cell, hourDragState.mode);
          updateHourCount(card);
        });
        cell.addEventListener("mouseenter", () => {
          if (!hourDragState.active) return;
          setCell(cell, hourDragState.mode);
          updateHourCount(card);
        });
        cell.addEventListener("touchstart", (e) => {
          e.preventDefault();
          hourDragState.active = true;
          hourDragState.mode = !cell.classList.contains("active");
          setCell(cell, hourDragState.mode);
          updateHourCount(card);
        }, { passive: false });
      });

      grid.addEventListener("touchmove", (e) => {
        if (!hourDragState.active) return;
        e.preventDefault();
        const touch = e.touches[0];
        const el = document.elementFromPoint(touch.clientX, touch.clientY);
        if (el && el.classList.contains("hour-cell") && el.closest(".hour-grid") === grid) {
          setCell(el, hourDragState.mode);
          updateHourCount(card);
        }
      }, { passive: false });

      const allBtn   = card.querySelector(".hour-quick-all");
      const clearBtn = card.querySelector(".hour-quick-clear");
      if (allBtn) allBtn.addEventListener("click", () => {
        grid.querySelectorAll(".hour-cell").forEach(c => setCell(c, true));
        updateHourCount(card);
      });
      if (clearBtn) clearBtn.addEventListener("click", () => {
        grid.querySelectorAll(".hour-cell").forEach(c => setCell(c, false));
        updateHourCount(card);
      });
    }

    function updateHourCount(card) {
      const grid = card.querySelector(".hour-grid");
      const activeCells = grid.querySelectorAll(".hour-cell.active");
      const count = activeCells.length;
      const hiddenInput = card.querySelector(".hours");
      hiddenInput.value = count;
      const countEl = card.querySelector(".hour-count");
      if (countEl) countEl.textContent = count;
      card.dataset.selectedHours = JSON.stringify([...activeCells].map(c => parseInt(c.dataset.hour, 10)));
      // Mirror the behavior of the custom-panel-w input: only auto-recalculate
      // if results are already showing, otherwise wait for the Calculate button.
      if (document.getElementById("results").style.display !== "none") calculate();
    }

    // End a drag no matter where the pointer/finger is released
    document.addEventListener("mouseup", () => { hourDragState.active = false; });
    document.addEventListener("touchend", () => { hourDragState.active = false; });
    document.addEventListener("touchcancel", () => { hourDragState.active = false; });

    // ─── APPLIANCES ─────────────────────────────────────────────────────────────
    function addAppliance(key) {
      const data = applianceData[key];
      if (!data) return;
      const container = document.getElementById("appliance-container");
      const idKey = key + "-" + Date.now();
      const card = document.createElement("div");
      card.className = "appliance";
      card.id = "card-" + idKey;

      const isCustom = key === "custom";
      // All name elements start hidden; correct one is shown after append based on active language
      const titleHtml = isCustom
        ? `<div class="title"><input type="text" class="name-fa-input" placeholder="نام وسیله (فارسی)" value="${data.fa}" style="display:none"><input type="text" class="name-en-input" placeholder="Appliance name (EN)" value="${data.en}" style="display:none"></div>`
        : `<div class="title"><span class="name-fa" style="display:none">${data.fa}</span><span class="name-en" style="display:none">${data.en}</span></div>`;

      card.innerHTML = `
        <div class="appliance-header">
          ${titleHtml}
          <button class="remove-btn" onclick="removeAppliance('${idKey}')">✕</button>
        </div>
        <label class="label-fa">توان (وات)</label><label class="label-en" style="display:none">Power (W)</label>
        <input type="number" class="power" value="${data.power}" min="0">
        <label class="label-fa">ساعت استفاده (روی ساعات فعال کلیک یا بکشید)</label><label class="label-en" style="display:none">Active hours (click or drag on the grid)</label>
        <div class="hour-grid-wrap">
          <div class="hour-grid"></div>
          <div class="hour-grid-quick">
            <button type="button" class="hour-quick-btn hour-quick-all"><span class="label-fa">انتخاب کل روز</span><span class="label-en" style="display:none">Select all day</span></button>
            <button type="button" class="hour-quick-btn hour-quick-clear"><span class="label-fa">پاک کردن</span><span class="label-en" style="display:none">Clear</span></button>
          </div>
          <div class="hour-grid-summary">
            <span class="label-fa">تعداد ساعت انتخاب‌شده:</span><span class="label-en" style="display:none">Selected hours:</span>
            <strong class="hour-count">0</strong>
          </div>
        </div>
        <input type="hidden" class="hours" value="${data.hours}">
      `;
      container.appendChild(card);
      buildHourGrid(card, defaultActiveHours(data));

      // Apply current language to the newly created card immediately
      const isFa = document.getElementById("app").dir === "rtl";
      card.querySelectorAll(".label-fa").forEach(el => el.style.display = isFa ? "" : "none");
      card.querySelectorAll(".label-en").forEach(el => el.style.display = isFa ? "none" : "");
      const nameFaSpan = card.querySelector(".name-fa");
      const nameEnSpan = card.querySelector(".name-en");
      if (nameFaSpan) nameFaSpan.style.display = isFa ? "" : "none";
      if (nameEnSpan) nameEnSpan.style.display = isFa ? "none" : "";

      if (isCustom) {
        const faInput = card.querySelector(".name-fa-input");
        const enInput = card.querySelector(".name-en-input");
        faInput.addEventListener("input", calculate);
        enInput.addEventListener("input", calculate);
        faInput.style.display = isFa ? "" : "none";
        enInput.style.display = isFa ? "none" : "";
      }
    }

    function removeAppliance(key) {
      const el = document.getElementById("card-" + key);
      if (el) el.remove();
      // FIX: hide results if no appliances remain
      if (document.querySelectorAll(".appliance").length === 0) {
        document.getElementById("results").style.display = "none";
      } else {
        calculate();
      }
    }

    // ─── CHART ──────────────────────────────────────────────────────────────────
    let chart = null; // single reference, no more window.chart dual-assignment

    function createGradient(ctx, height) {
      const g = ctx.createLinearGradient(0, 0, 0, height);
      g.addColorStop(0, "rgba(0,230,118,1)");
      g.addColorStop(1, "rgba(0,230,118,0.12)");
      return g;
    }

    function wrapLabel(label, maxChars = 12) {
      if (!label) return "";
      if (label.indexOf("\n") !== -1) return label;
      const words = String(label).split(" ");
      let line = "", lines = [];
      for (let i = 0; i < words.length; i++) {
        const test = line ? (line + " " + words[i]) : words[i];
        if (test.length > maxChars) {
          if (line) lines.push(line);
          if (words[i].length > maxChars) {
            lines.push(...(words[i].match(new RegExp('.{1,' + maxChars + '}', 'g')) || [words[i]]));
            line = "";
          } else { line = words[i]; }
        } else { line = test; }
      }
      if (line) lines.push(line);
      return lines.join("\n");
    }

    function updateChart(labels, values) {
      const canvas = document.getElementById("energyChart");
      const ctx = canvas.getContext("2d");
      if (chart) chart.destroy();
      const gradient = createGradient(ctx, canvas.height || 260);
      chart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: labels.map(l => wrapLabel(l, 12)),
          datasets: [{
            label: document.getElementById("app").dir === "rtl" ? "مصرف روزانه (Wh)" : "Daily consumption (Wh)",
            data: values,
            backgroundColor: gradient,
            borderColor: "rgba(0,230,118,1)",
            borderWidth: 2,
            borderRadius: 8,
            maxBarThickness: 50
          }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          animation: false,
          layout: { padding: { top:8, right:8, bottom:36, left:8 } },
          scales: {
            x: { ticks: { color:"#e8f5e9", autoSkip:false, maxRotation:45 }, grid: { display:false } },
            y: { beginAtZero:true, ticks: { color:"#e8f5e9" } }
          },
          plugins: { legend: { labels: { color:"#e8f5e9" } }, tooltip: { mode:"index", intersect:false } }
        }
      });
      window.chart = chart; // kept for PDF export compatibility
    }

    // ─── PANEL RECOMMENDATION UI ─────────────────────────────────────────────────
    function renderPanelRecommendation(requiredW) {
      const isFa = document.getElementById("app").dir === "rtl";

      // Check if user has overridden the panel wattage
      const customW = parseFloat(document.getElementById("custom-panel-w").value);
      const useCustom = customW && customW >= 10;

      const options = getPanelOptions(requiredW);
      const recIdx  = pickRecommendedIndex(options);
      const recOption = options[recIdx];

      const activeOption = useCustom
        ? { count: requiredW > 0 ? Math.ceil(requiredW / customW) : 1, watt: customW,
            totalCapacity: (requiredW > 0 ? Math.ceil(requiredW / customW) : 1) * customW,
            area: (requiredW > 0 ? Math.ceil(requiredW / customW) : 1) * panelArea(customW),
            wastePct: 0,
            reasonEn: "User-specified panel wattage", reasonFa: "توان پنل تعیین‌شده توسط کاربر" }
        : recOption;

      const activeLabelEn = activeOption.count + " × " + activeOption.watt + "W";
      const activeLabelFa = faDigits(activeOption.count) + " × " + faDigits(activeOption.watt) + " وات";
      const activeReasonEn = activeOption.reasonEn || "Best-fit combination for this load";
      const activeReasonFa = activeOption.reasonFa || "بهترین ترکیب متناسب با این میزان مصرف";

      // Badge
      document.getElementById("rec-badge-value").textContent  = isFa ? activeLabelFa  : activeLabelEn;
      document.getElementById("rec-badge-reason").textContent = isFa ? activeReasonFa : activeReasonEn;

      // Comparison table — show all generated combinations
      const tbody = document.getElementById("panel-compare-body");
      tbody.innerHTML = "";

      options.forEach((opt, i) => {
        const isRec    = !useCustom && i === recIdx;
        const roofArea = opt.area.toFixed(1);
        const fitLabel = opt.wastePct <= 0.5
          ? (isFa ? "دقیق" : "Exact")
          : (isFa ? `${faDigits(opt.wastePct.toFixed(0))}%+` : `+${opt.wastePct.toFixed(0)}%`);
        const comboLabel = isFa
          ? `${faDigits(opt.count)} × ${faDigits(opt.watt)} وات`
          : `${opt.count} × ${opt.watt}W`;

        const tr = document.createElement("tr");
        if (isRec) tr.className = "recommended-row";
        const starHtml = isRec ? `<span class="rec-star">${isFa ? "★ پیشنهادی" : "★ Recommended"}</span>` : "";

        tr.innerHTML = `
          <td>${comboLabel}${starHtml}</td>
          <td>${formatNumber(opt.totalCapacity)}</td>
          <td>${fitLabel}</td>
          <td>~${roofArea}</td>
        `;
        tbody.appendChild(tr);
      });

      // KPI3 subtitle
      const subtitle = document.getElementById("kpi3-subtitle");
      subtitle.textContent = isFa
        ? `${faDigits(activeOption.count)} × ${faDigits(activeOption.watt)} وات`
        : `${activeOption.count} × ${activeOption.watt}W`;

      document.getElementById("panel-rec-section").style.display = requiredW > 0 ? "block" : "none";
    }

    // ─── INVERTER & BATTERY RECOMMENDATION UI ───────────────────────────────────
    function renderStorageRecommendation(peakW, requiredWh) {
      const isFa = document.getElementById("app").dir === "rtl";

      // ── Inverter ──
      const customInvW = parseFloat(document.getElementById("custom-inverter-w").value);
      const useCustomInv = customInvW && customInvW >= 100;

      const invOptions = getInverterOptions(peakW);
      const invRecIdx   = pickRecommendedIndex(invOptions);
      const invRecOption = invOptions[invRecIdx];
      const effectivePeakW = Math.max(0, peakW) * INVERTER_SURGE_MARGIN;

      const activeInv = useCustomInv
        ? { count: effectivePeakW > 0 ? Math.ceil(effectivePeakW / customInvW) : 1, watt: customInvW,
            totalCapacity: (effectivePeakW > 0 ? Math.ceil(effectivePeakW / customInvW) : 1) * customInvW,
            reasonEn: "User-specified inverter power", reasonFa: "توان اینورتر تعیین‌شده توسط کاربر" }
        : invRecOption;

      const invLabelEn = activeInv.count + " × " + activeInv.watt + "W";
      const invLabelFa = faDigits(activeInv.count) + " × " + faDigits(activeInv.watt) + " وات";
      const invReasonEn = activeInv.reasonEn || "Sized for peak load with surge headroom";
      const invReasonFa = activeInv.reasonFa || "متناسب با حداکثر بار همراه با حاشیه راه‌اندازی";

      document.getElementById("inv-badge-value").textContent  = isFa ? invLabelFa  : invLabelEn;
      document.getElementById("inv-badge-reason").textContent = isFa ? invReasonFa : invReasonEn;

      const invBody = document.getElementById("inv-compare-body");
      invBody.innerHTML = "";
      invOptions.forEach((opt, i) => {
        const isRec = !useCustomInv && i === invRecIdx;
        const fitLabel = opt.wastePct <= 0.5
          ? (isFa ? "دقیق" : "Exact")
          : (isFa ? `${faDigits(opt.wastePct.toFixed(0))}%+` : `+${opt.wastePct.toFixed(0)}%`);
        const comboLabel = isFa
          ? `${faDigits(opt.count)} × ${faDigits(opt.watt)} وات`
          : `${opt.count} × ${opt.watt}W`;
        const tr = document.createElement("tr");
        if (isRec) tr.className = "recommended-row";
        const starHtml = isRec ? `<span class="rec-star">${isFa ? "★ پیشنهادی" : "★ Recommended"}</span>` : "";
        tr.innerHTML = `
          <td>${comboLabel}${starHtml}</td>
          <td>${formatNumber(opt.totalCapacity)}</td>
          <td>${fitLabel}</td>
        `;
        invBody.appendChild(tr);
      });

      // ── Battery ──
      const customBattKwh = parseFloat(document.getElementById("custom-battery-kwh").value);
      const useCustomBatt = customBattKwh && customBattKwh >= 0.1;
      const customBattWh  = customBattKwh * 1000;
      const sysVoltage    = parseFloat(document.getElementById("system-voltage").value) || 24;

      const battOptions = getBatteryOptions(requiredWh);
      const battRecIdx  = pickRecommendedIndex(battOptions);
      const battRecOption = battOptions[battRecIdx];

      const activeBatt = useCustomBatt
        ? { count: requiredWh > 0 ? Math.ceil(requiredWh / customBattWh) : 1, wh: customBattWh,
            totalCapacity: (requiredWh > 0 ? Math.ceil(requiredWh / customBattWh) : 1) * customBattWh,
            reasonEn: "User-specified battery capacity", reasonFa: "ظرفیت باتری تعیین‌شده توسط کاربر" }
        : battRecOption;

      const activeBattKwh = activeBatt.totalCapacity / 1000;
      const battLabelEn = activeBatt.count + " × " + (activeBatt.wh/1000).toFixed(1) + "kWh";
      const battLabelFa = faDigits(activeBatt.count) + " × " + faDigits((activeBatt.wh/1000).toFixed(1)) + " کیلووات‌ساعت";
      const battReasonEn = activeBatt.reasonEn || "Sized for backup days at the allowed discharge depth";
      const battReasonFa = activeBatt.reasonFa || "متناسب با روزهای پشتیبان در عمق تخلیه مجاز";

      document.getElementById("batt-badge-value").textContent  = isFa ? battLabelFa  : battLabelEn;
      document.getElementById("batt-badge-reason").textContent = isFa ? battReasonFa : battReasonEn;

      const battBody = document.getElementById("batt-compare-body");
      battBody.innerHTML = "";
      battOptions.forEach((opt, i) => {
        const isRec = !useCustomBatt && i === battRecIdx;
        const fitLabel = opt.wastePct <= 0.5
          ? (isFa ? "دقیق" : "Exact")
          : (isFa ? `${faDigits(opt.wastePct.toFixed(0))}%+` : `+${opt.wastePct.toFixed(0)}%`);
        const comboLabel = isFa
          ? `${faDigits(opt.count)} × ${faDigits((opt.wh/1000).toFixed(1))} kWh`
          : `${opt.count} × ${(opt.wh/1000).toFixed(1)}kWh`;
        const ah = (opt.totalCapacity / sysVoltage).toFixed(0);
        const tr = document.createElement("tr");
        if (isRec) tr.className = "recommended-row";
        const starHtml = isRec ? `<span class="rec-star">${isFa ? "★ پیشنهادی" : "★ Recommended"}</span>` : "";
        tr.innerHTML = `
          <td>${comboLabel}${starHtml}</td>
          <td>${(opt.totalCapacity/1000).toFixed(1)}</td>
          <td>${isFa ? faDigits(ah) : ah}</td>
          <td>${fitLabel}</td>
        `;
        battBody.appendChild(tr);
      });

      // KPI4 subtitle (peak load → inverter choice)
      const kpi4Subtitle = document.getElementById("kpi4-subtitle");
      kpi4Subtitle.textContent = isFa
        ? `${isFa ? "اینورتر:" : "Inverter:"} ${invLabelFa}`
        : `Inverter: ${invLabelEn}`;

      document.getElementById("storage-rec-section").style.display = (peakW > 0 || requiredWh > 0) ? "block" : "none";

      return { activeInv, activeBatt, activeBattKwh, sysVoltage };
    }


    // ─── CALCULATE ───────────────────────────────────────────────────────────────
    function calculate() {
      if (!validateInputs()) return;

      const powers = document.querySelectorAll(".power");
      const hours  = document.querySelectorAll(".hours");
      let totalWh  = 0;
      for (let i = 0; i < powers.length; i++) {
        totalWh += (parseFloat(powers[i].value) || 0) * (parseFloat(hours[i].value) || 0);
      }

      const sun = parseFloat(document.getElementById("sun-hours").value) || 1;
      const eff = (parseFloat(document.getElementById("eff").value) || 80) / 100;
      const kWh = totalWh / 1000;
      const requiredWatt = (totalWh / sun) / eff;

      // Determine active panel combo (recommended option, or user's custom wattage)
      const customW    = parseFloat(document.getElementById("custom-panel-w").value);
      const useCustom  = customW && customW >= 10;
      let panelCount;
      if (useCustom) {
        panelCount = requiredWatt > 0 ? Math.ceil(requiredWatt / customW) : 0;
      } else {
        const options = getPanelOptions(requiredWatt);
        const recIdx  = pickRecommendedIndex(options);
        panelCount    = options[recIdx].count;
      }

      document.getElementById("kpi1").textContent = kWh.toFixed(2);
      document.getElementById("kpi2").textContent = Math.round(requiredWatt);
      document.getElementById("kpi3").textContent = panelCount > 0 ? panelCount : "—";
      document.getElementById("results").style.display = "block";


      renderPanelRecommendation(requiredWatt);

      // Inverter is sized off peak simultaneous load; battery off daily energy × autonomy.
      const peakW = getPeakLoadWatts();
      const autonomyDays = parseFloat(document.getElementById("autonomy-days").value) || 1;
      const dodPct = parseFloat(document.getElementById("dod").value) || 80;
      const requiredBatteryWh = getRequiredBatteryWh(totalWh, autonomyDays, dodPct);

      document.getElementById("kpi4").textContent = Math.round(peakW);

      renderStorageRecommendation(peakW, requiredBatteryWh);

      // Build appliance rows for chart + table
      const labels = [], values = [], applianceRows = [];
      document.querySelectorAll(".appliance").forEach(ap => {
        const nameFaInput = ap.querySelector(".name-fa-input");
        const nameEnInput = ap.querySelector(".name-en-input");
        let nameFa = "", nameEn = "";
        if (nameFaInput || nameEnInput) {
          nameFa = nameFaInput ? nameFaInput.value.trim() : "";
          nameEn = nameEnInput ? nameEnInput.value.trim() : "";
        } else {
          nameFa = ap.querySelector(".name-fa")?.textContent || "";
          nameEn = ap.querySelector(".name-en")?.textContent || "";
        }
        const power   = parseFloat(ap.querySelector(".power").value) || 0;
        const hoursVal= parseFloat(ap.querySelector(".hours").value) || 0;
        const wh      = power * hoursVal;
        const label   = document.getElementById("app").dir === "rtl"
          ? (nameFa || nameEn || "وسیله")
          : (nameEn || nameFa || "Appliance");
        labels.push(label);
        values.push(wh);
        applianceRows.push({ nameFa, nameEn, power, hours: hoursVal, wh });
      });

      window.__applianceRows = applianceRows;

      // Appliance preview table
      const tbody = document.querySelector("#appliance-preview-table tbody");
      tbody.innerHTML = "";
      applianceRows.forEach(r => {
        const name = document.getElementById("app").dir === "rtl"
          ? (r.nameFa || r.nameEn || "—")
          : (r.nameEn || r.nameFa || "—");
        const tr = document.createElement("tr");
        tr.innerHTML = `<td>${escapeHtml(name)}</td>
                        <td style="text-align:end">${formatNumber(r.power)}</td>
                        <td style="text-align:end">${formatNumber(r.hours)}</td>
                        <td style="text-align:end">${formatNumber(r.wh)}</td>`;
        tbody.appendChild(tr);
      });

      updateChart(labels, values);
    }

    // ─── LANGUAGE SWITCH ─────────────────────────────────────────────────────────
    // IDs that are translatable content (NOT the lang buttons themselves)
    const FA_IDS = [
      "title-fa","select-title-fa","solar-fa","sun-fa","eff-fa",
      "sun-error-fa","eff-error-fa","custpanel-fa","custpanel-hint-fa",
      "calc-btn-fa","results-fa","kpi1-title-fa","kpi2-title-fa",
      "kpi3-title-fa","panel-rec-title-fa","rec-badge-label-fa",
      "roof-fa",
      "storage-settings-fa","autonomy-fa","autonomy-error-fa","dod-fa","dod-error-fa",
      "sysvolt-fa","custinv-fa","custbatt-fa","custstorage-hint-fa",
      "kpi4-title-fa","storage-rec-title-fa","inv-badge-label-fa","batt-badge-label-fa",
      "inv-table-title-fa","batt-table-title-fa","storage-note-fa"
    ];
    const EN_IDS = [
      "title-en","select-title-en","solar-en","sun-en","eff-en",
      "sun-error-en","eff-error-en","custpanel-en","custpanel-hint-en",
      "calc-btn-en","results-en","kpi1-title-en","kpi2-title-en",
      "kpi3-title-en","panel-rec-title-en","rec-badge-label-en",
      "roof-en",
      "storage-settings-en","autonomy-en","autonomy-error-en","dod-en","dod-error-en",
      "sysvolt-en","custinv-en","custbatt-en","custstorage-hint-en",
      "kpi4-title-en","storage-rec-title-en","inv-badge-label-en","batt-badge-label-en",
      "inv-table-title-en","batt-table-title-en","storage-note-en"
    ];

    function setLang(lang) {
      const isFa = lang === "fa";
      document.getElementById("app").dir = isFa ? "rtl" : "ltr";

      // Show/hide translatable ID elements (safe — lang buttons not in these lists)
      FA_IDS.forEach(id => { const el = document.getElementById(id); if (el) el.style.display = isFa ? "" : "none"; });
      EN_IDS.forEach(id => { const el = document.getElementById(id); if (el) el.style.display = isFa ? "none" : ""; });

      // class-based label/header visibility
      document.querySelectorAll(".label-fa,.th-fa").forEach(el => el.style.display = isFa ? "" : "none");
      document.querySelectorAll(".label-en,.th-en").forEach(el => el.style.display = isFa ? "none" : "");

      // appliance menu tiles (the clickable icons at the top)
      document.querySelectorAll(".appliance-option .name-fa").forEach(el => el.style.display = isFa ? "" : "none");
      document.querySelectorAll(".appliance-option .name-en").forEach(el => el.style.display = isFa ? "none" : "");

      // appliance name spans / inputs
      document.querySelectorAll(".appliance").forEach(ap => {
        const nameFaSpan  = ap.querySelector(".name-fa");
        const nameEnSpan  = ap.querySelector(".name-en");
        const nameFaInput = ap.querySelector(".name-fa-input");
        const nameEnInput = ap.querySelector(".name-en-input");
        if (nameFaSpan)  nameFaSpan.style.display  = isFa ? "" : "none";
        if (nameEnSpan)  nameEnSpan.style.display  = isFa ? "none" : "";
        if (nameFaInput) nameFaInput.style.display = isFa ? "" : "none";
        if (nameEnInput) nameEnInput.style.display = isFa ? "none" : "";
      });

      // chart dataset label
      if (chart) {
        chart.data.datasets[0].label = isFa ? "مصرف روزانه (Wh)" : "Daily consumption (Wh)";
        chart.update();
      }

      document.getElementById("btn-fa").classList.toggle("active", isFa);
      document.getElementById("btn-en").classList.toggle("active", !isFa);

      // Re-render panel + storage recommendations in new language if results are visible
      if (document.getElementById("results").style.display !== "none") {
        const requiredWatt = parseFloat(document.getElementById("kpi2").textContent) || 0;
        renderPanelRecommendation(requiredWatt);

        const peakW = parseFloat(document.getElementById("kpi4").textContent) || 0;
        const totalWh = window.__applianceRows
          ? window.__applianceRows.reduce((s, r) => s + r.wh, 0)
          : 0;
        const autonomyDays = parseFloat(document.getElementById("autonomy-days").value) || 1;
        const dodPct = parseFloat(document.getElementById("dod").value) || 80;
        const requiredBatteryWh = getRequiredBatteryWh(totalWh, autonomyDays, dodPct);
        renderStorageRecommendation(peakW, requiredBatteryWh);
      }
    }

    // ─── PDF EXPORT ──────────────────────────────────────────────────────────────
    async function exportChartToPDF({
      filenamePrefix = "solar-report",
      orientation    = "portrait",
      pageFormat     = "a4",
      backgroundMode = "gradient",
      watermarkText  = EXPORT_WATERMARK_TEXT,
      watermarkImage = EXPORT_WATERMARK_IMAGE,
      watermarkOpacity    = EXPORT_WATERMARK_OPACITY,
      watermarkFontSize   = EXPORT_WATERMARK_FONT_SIZE
    } = {}) {
      try {
        if (typeof calculate === "function") calculate();
        await new Promise(r => requestAnimationFrame(() => requestAnimationFrame(r)));
        await new Promise(r => setTimeout(r, 120));

        const isFa = document.getElementById("app").dir === "rtl";

        // Capture chart as image first
        let chartDataUrl = null;
        try {
          if (chart && typeof chart.toBase64Image === "function") chartDataUrl = chart.toBase64Image();
        } catch(e) {}
        if (!chartDataUrl) {
          try {
            const cv = document.getElementById("energyChart");
            if (cv) chartDataUrl = cv.toDataURL("image/png");
          } catch(e) {}
        }

        const isLandscape = orientation === "landscape";
        const rows = window.__applianceRows || [];

        // ── page pixel dimensions at 96dpi equivalent ──
        // A4 portrait = 794×1123px, landscape = 1123×794px
        // Letter portrait = 816×1056px
        let pageW, pageH;
        if (pageFormat === "letter") {
          pageW = isLandscape ? 1056 : 816;
          pageH = isLandscape ? 816  : 1056;
        } else {
          pageW = isLandscape ? 1123 : 794;
          pageH = isLandscape ? 794  : 1123;
        }

        const pad  = 52; // px padding on all sides
        const gap  = 20;
        const contentW = pageW - pad * 2;

        // colours
        const bg      = backgroundMode === "dark"
          ? "#0b1114"
          : "linear-gradient(180deg,#0b2b3a 0%,#083a4b 100%)";
        const textCol  = "#e8f5e9";
        const mutedCol = "#cfe8db";
        const accCol   = "#00e676";

        // ── build off-screen container — fixed to exact page size ──
        const wrap = document.createElement("div");
        wrap.style.cssText = [
          "position:fixed", "left:-9999px", "top:0", "z-index:-9999",
          `width:${pageW}px`,
          `height:${pageH}px`,          // exact page height — fills A4
          "box-sizing:border-box",
          `padding:${pad}px`,
          "font-family:Inter,sans-serif",
          `background:${bg}`,
          "display:flex", "flex-direction:column",
          "justify-content:space-between",  // sections spread to fill page
          "overflow:hidden"
        ].join(";");

        // ── HEADER ──
        const hdr = document.createElement("div");
        hdr.style.cssText = `display:flex;justify-content:space-between;align-items:center;margin-bottom:${gap}px;`;
        hdr.innerHTML = `
          <div style="font-weight:700;font-size:26px;color:${textCol}">
            ${isFa ? "گزارش خورشیدی رسا" : "Rasa Solar Report"}
          </div>
          <div style="font-size:13px;color:${mutedCol}">${new Date().toLocaleString()}</div>`;
        wrap.appendChild(hdr);

        // ── KPI ROW (3 boxes) ──
        const kpiRow = document.createElement("div");
        kpiRow.style.cssText = `display:flex;gap:${gap}px;margin-bottom:${gap}px;`;

        const requiredWatt = parseFloat(document.getElementById("kpi2").textContent) || 0;
        const customW      = parseFloat(document.getElementById("custom-panel-w").value);
        const useCustom    = customW && customW >= 10;
        const pdfOptions   = getPanelOptions(requiredWatt);
        const pdfRecIdx    = pickRecommendedIndex(pdfOptions);
        const activeTier   = useCustom
          ? { w:customW, labelEn:customW+"W (custom)", labelFa:customW+" وات (دلخواه)",
              reasonEn:"User-specified", reasonFa:"تعیین‌شده توسط کاربر" }
          : { w:pdfOptions[pdfRecIdx].watt,
              labelEn: pdfOptions[pdfRecIdx].count+" × "+pdfOptions[pdfRecIdx].watt+"W",
              labelFa: faDigits(pdfOptions[pdfRecIdx].count)+" × "+faDigits(pdfOptions[pdfRecIdx].watt)+" وات",
              reasonEn: "Best-fit combination for this load",
              reasonFa: "بهترین ترکیب متناسب با این میزان مصرف" };
        const panelCount = requiredWatt > 0
          ? (useCustom ? Math.ceil(requiredWatt / customW) : pdfOptions[pdfRecIdx].count)
          : "—";

        const kpiData = [
          { label: isFa ? "مصرف روزانه (kWh)" : "Daily (kWh)",       value: document.getElementById("kpi1").textContent },
          { label: isFa ? "توان سیستم (W)"    : "System power (W)",   value: document.getElementById("kpi2").textContent },
          { label: isFa ? `پنل‌ها (${activeTier.labelFa})` : `Panels (${activeTier.labelEn})`, value: panelCount }
        ];
        kpiData.forEach(d => {
          const box = document.createElement("div");
          box.style.cssText = `flex:1;padding:18px;border-radius:10px;background:rgba(255,255,255,0.06);`;
          box.innerHTML = `<div style="font-size:13px;color:${mutedCol};margin-bottom:6px">${d.label}</div>
                           <div style="font-weight:700;font-size:28px;color:${textCol}">${d.value}</div>`;
          kpiRow.appendChild(box);
        });
        wrap.appendChild(kpiRow);

        // ── PANEL RECOMMENDATION BADGE ──
        const recBadge = document.createElement("div");
        recBadge.style.cssText = `display:flex;align-items:center;gap:12px;padding:16px 20px;border-radius:10px;background:rgba(0,230,118,0.1);border:1px solid rgba(0,230,118,0.3);margin-bottom:${gap}px;`;
        recBadge.innerHTML = `
          <div style="font-size:32px;line-height:1">☀️</div>
          <div>
            <div style="font-size:13px;color:${mutedCol}">${isFa ? "پنل پیشنهادی" : "Recommended panel"}</div>
            <div style="font-weight:700;font-size:20px;color:${accCol}">${isFa ? activeTier.labelFa : activeTier.labelEn}</div>
            <div style="font-size:13px;color:${mutedCol}">${isFa ? activeTier.reasonFa : activeTier.reasonEn}</div>
          </div>`;
        wrap.appendChild(recBadge);

        // ── MAIN ROW: chart left, appliance table right ──
        const mainRow = document.createElement("div");
        mainRow.style.cssText = `display:flex;gap:${gap}px;margin-bottom:${gap}px;align-items:flex-start;`;

        // chart column
        const chartCol = document.createElement("div");
        chartCol.style.cssText = "flex:0 0 58%;";
        if (chartDataUrl) {
          const img = document.createElement("img");
          img.src = chartDataUrl;
          img.style.cssText = "width:100%;height:220px;object-fit:contain;display:block;border-radius:8px;";
          chartCol.appendChild(img);
        } else {
          const ph = document.createElement("div");
          ph.style.cssText = `width:100%;height:160px;background:rgba(255,255,255,0.05);border-radius:8px;display:flex;align-items:center;justify-content:center;color:${mutedCol};font-size:12px;`;
          ph.textContent = isFa ? "نمودار در دسترس نیست" : "Chart unavailable";
          chartCol.appendChild(ph);
        }
        mainRow.appendChild(chartCol);

        // appliance table column
        const tableCol = document.createElement("div");
        tableCol.style.cssText = "flex:1;min-width:0;";

        const tTitle = document.createElement("div");
        tTitle.style.cssText = `font-weight:700;font-size:16px;color:${textCol};margin-bottom:10px;`;
        tTitle.textContent = isFa ? "جزئیات وسایل" : "Appliance details";
        tableCol.appendChild(tTitle);

        if (rows.length === 0) {
          const empty = document.createElement("div");
          empty.style.cssText = `font-size:11px;color:${mutedCol};`;
          empty.textContent = isFa ? "وسیله‌ای اضافه نشده" : "No appliances added";
          tableCol.appendChild(empty);
        } else {
          // header row
          const theadRow = document.createElement("div");
          theadRow.style.cssText = `display:grid;grid-template-columns:1fr 44px 32px 48px;gap:4px;padding:4px 6px;font-size:12px;color:${mutedCol};font-weight:600;border-bottom:1px solid rgba(255,255,255,0.15);margin-bottom:4px;`;
          theadRow.innerHTML = `
            <div>${isFa ? "نام" : "Name"}</div>
            <div style="text-align:right">W</div>
            <div style="text-align:right">h</div>
            <div style="text-align:right">Wh</div>`;
          tableCol.appendChild(theadRow);

          rows.forEach((r, i) => {
            const name = isFa ? (r.nameFa || r.nameEn || "—") : (r.nameEn || r.nameFa || "—");
            const row2 = document.createElement("div");
            row2.style.cssText = `display:grid;grid-template-columns:1fr 44px 32px 48px;gap:4px;padding:5px 6px;font-size:13px;color:${textCol};background:${i%2===0?"rgba(255,255,255,0.04)":"transparent"};border-radius:4px;`;
            row2.innerHTML = `
              <div style="font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${escapeHtml(name)}</div>
              <div style="text-align:right;color:${mutedCol}">${formatNumber(r.power)}</div>
              <div style="text-align:right;color:${mutedCol}">${formatNumber(r.hours)}</div>
              <div style="text-align:right;color:${mutedCol}">${formatNumber(r.wh)}</div>`;
            tableCol.appendChild(row2);
          });
        }
        mainRow.appendChild(tableCol);
        wrap.appendChild(mainRow);

        // ── PANEL OPTIONS ──
        const compTitle = document.createElement("div");
        compTitle.style.cssText = `font-weight:700;font-size:16px;color:${textCol};margin-bottom:10px;`;
        compTitle.textContent = isFa ? "گزینه‌های پنل پیشنهادی" : "Panel options";
        wrap.appendChild(compTitle);

        const compGrid = document.createElement("div");
        compGrid.style.cssText = `display:flex;gap:${gap}px;margin-bottom:${gap}px;`;
        pdfOptions.forEach((opt, i) => {
          const isRec  = !useCustom && i === pdfRecIdx;
          const fitLabel = opt.wastePct <= 0.5
            ? (isFa ? "دقیق" : "Exact")
            : (isFa ? `${faDigits(opt.wastePct.toFixed(0))}%+` : `+${opt.wastePct.toFixed(0)}%`);
          const comboLabel = isFa
            ? `${faDigits(opt.count)} × ${faDigits(opt.watt)} وات`
            : `${opt.count} × ${opt.watt}W`;
          const box  = document.createElement("div");
          box.style.cssText = `flex:1;padding:16px;border-radius:8px;background:${isRec ? "rgba(0,230,118,0.1)" : "rgba(255,255,255,0.04)"};border:1px solid ${isRec ? "rgba(0,230,118,0.35)" : "rgba(255,255,255,0.08)"};`;
          box.innerHTML = `
            <div style="font-weight:700;font-size:16px;color:${isRec ? accCol : textCol}">${comboLabel}${isRec ? " ★" : ""}</div>
            <div style="font-size:13px;color:${mutedCol};margin-top:4px">${isFa ? "کل:" : "Total:"} <strong style="color:${textCol}">${opt.totalCapacity}W (${fitLabel})</strong></div>
            <div style="font-size:10px;color:${mutedCol}">${isFa ? "مساحت:" : "Roof:"} <strong style="color:${textCol}">~${opt.area.toFixed(1)} m²</strong></div>`;
          compGrid.appendChild(box);
        });
        wrap.appendChild(compGrid);

        // ── INVERTER & BATTERY RECOMMENDATION ──
        const peakWForPdf = getPeakLoadWatts();
        const autonomyDaysPdf = parseFloat(document.getElementById("autonomy-days").value) || 1;
        const dodPdf = parseFloat(document.getElementById("dod").value) || 80;
        const totalWhPdf = rows.reduce((s, r) => s + r.wh, 0);
        const requiredWhPdf = getRequiredBatteryWh(totalWhPdf, autonomyDaysPdf, dodPdf);
        const sysVoltagePdf = parseFloat(document.getElementById("system-voltage").value) || 24;

        const customInvWPdf = parseFloat(document.getElementById("custom-inverter-w").value);
        const useCustomInvPdf = customInvWPdf && customInvWPdf >= 100;
        const invOptionsPdf = getInverterOptions(peakWForPdf);
        const invRecIdxPdf  = pickRecommendedIndex(invOptionsPdf);
        const activeInvPdf  = useCustomInvPdf
          ? { count: Math.max(1, Math.ceil((peakWForPdf * INVERTER_SURGE_MARGIN) / customInvWPdf)), watt: customInvWPdf }
          : invOptionsPdf[invRecIdxPdf];

        const customBattKwhPdf = parseFloat(document.getElementById("custom-battery-kwh").value);
        const useCustomBattPdf = customBattKwhPdf && customBattKwhPdf >= 0.1;
        const battOptionsPdf = getBatteryOptions(requiredWhPdf);
        const battRecIdxPdf  = pickRecommendedIndex(battOptionsPdf);
        const activeBattPdf  = useCustomBattPdf
          ? { count: Math.max(1, Math.ceil(requiredWhPdf / (customBattKwhPdf*1000))), wh: customBattKwhPdf*1000 }
          : battOptionsPdf[battRecIdxPdf];

        const invLabelPdf  = isFa
          ? `${faDigits(activeInvPdf.count)} × ${faDigits(activeInvPdf.watt)} وات`
          : `${activeInvPdf.count} × ${activeInvPdf.watt}W`;
        const battLabelPdf = isFa
          ? `${faDigits(activeBattPdf.count)} × ${faDigits((activeBattPdf.wh/1000).toFixed(1))} kWh`
          : `${activeBattPdf.count} × ${(activeBattPdf.wh/1000).toFixed(1)}kWh`;
        const battAhPdf = (activeBattPdf.count * activeBattPdf.wh / sysVoltagePdf).toFixed(0);

        const storTitle = document.createElement("div");
        storTitle.style.cssText = `font-weight:700;font-size:16px;color:${textCol};margin-bottom:10px;`;
        storTitle.textContent = isFa ? "پیشنهاد اینورتر و باتری" : "Inverter & battery recommendation";
        wrap.appendChild(storTitle);

        const storGrid = document.createElement("div");
        storGrid.style.cssText = `display:flex;gap:${gap}px;`;

        const invBox = document.createElement("div");
        invBox.style.cssText = `flex:1;padding:16px;border-radius:8px;background:rgba(0,230,118,0.1);border:1px solid rgba(0,230,118,0.35);`;
        invBox.innerHTML = `
          <div style="font-size:13px;color:${mutedCol}">${isFa ? "اینورتر پیشنهادی" : "Recommended inverter"}</div>
          <div style="font-weight:700;font-size:18px;color:${accCol}">${invLabelPdf}</div>
          <div style="font-size:11px;color:${mutedCol};margin-top:4px">${isFa ? "حداکثر بار همزمان:" : "Peak load:"} <strong style="color:${textCol}">${Math.round(peakWForPdf)}W</strong></div>`;
        storGrid.appendChild(invBox);

        const battBox = document.createElement("div");
        battBox.style.cssText = `flex:1;padding:16px;border-radius:8px;background:rgba(0,230,118,0.1);border:1px solid rgba(0,230,118,0.35);`;
        battBox.innerHTML = `
          <div style="font-size:13px;color:${mutedCol}">${isFa ? "باتری پیشنهادی" : "Recommended battery bank"}</div>
          <div style="font-weight:700;font-size:18px;color:${accCol}">${battLabelPdf}</div>
          <div style="font-size:11px;color:${mutedCol};margin-top:4px">${isFa ? "معادل:" : "Equivalent:"} <strong style="color:${textCol}">${isFa ? faDigits(battAhPdf) : battAhPdf} Ah @ ${sysVoltagePdf}V</strong></div>`;
        storGrid.appendChild(battBox);

        wrap.appendChild(storGrid);

        // ── FOOTER ──
        const footer = document.createElement("div");
        footer.style.cssText = `display:flex;justify-content:space-between;align-items:center;padding-top:10px;border-top:1px solid rgba(255,255,255,0.1);font-size:10px;color:${mutedCol};`;
        footer.innerHTML = `<div>${isFa ? "تهیه‌شده توسط ماشین حساب خورشیدی رسا" : "Generated by Rasa Solar Calculator"}</div><div>info@rasa-energy.ir</div>`;
        wrap.appendChild(footer);

        // ── WATERMARK ──
        if (watermarkText || watermarkImage) {
          const wm = document.createElement("div");
          wm.style.cssText = "position:absolute;left:50%;top:50%;transform:translate(-50%,-50%) rotate(-30deg);pointer-events:none;z-index:9999;width:100%;height:100%;display:flex;align-items:center;justify-content:center;text-align:center;";
          wm.style.opacity = String(watermarkOpacity);
          if (watermarkImage) {
            const wimg = document.createElement("img");
            wimg.src = watermarkImage;
            wimg.style.cssText = "max-width:55%;max-height:55%;object-fit:contain;";
            wm.appendChild(wimg);
          } else {
            wm.style.fontFamily    = "Inter,sans-serif";
            wm.style.fontWeight    = "700";
            wm.style.color         = "#ffffff";
            wm.style.fontSize      = watermarkFontSize + "px";
            wm.style.letterSpacing = "6px";
            wm.textContent = watermarkText;
          }
          wrap.style.position = "relative"; // needed for absolute watermark
          wrap.appendChild(wm);
        }

        document.body.appendChild(wrap);
        await new Promise(r => requestAnimationFrame(r));

        const rendered = await html2canvas(wrap, {
          scale: 2,
          useCORS: true,
          backgroundColor: null,
          logging: false,
          width:  pageW,
          height: pageH,
          windowWidth:  pageW,
          windowHeight: pageH
        });
        document.body.removeChild(wrap);

        const imgData  = rendered.toDataURL("image/png");
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({ unit:"px", format:pageFormat, orientation, hotfixes:["px_scaling"] });
        const pw  = pdf.internal.pageSize.getWidth();
        const ph2 = pdf.internal.pageSize.getHeight();

        // Image is exactly pageW x pageH — fill the PDF page completely
        pdf.addImage(imgData, "PNG", 0, 0, pw, ph2, undefined, "FAST");

        const now2 = new Date();
        const pad2 = n => String(n).padStart(2,"0");
        const ts   = `${now2.getFullYear()}${pad2(now2.getMonth()+1)}${pad2(now2.getDate())}-${pad2(now2.getHours())}${pad2(now2.getMinutes())}`;
        pdf.save(`${filenamePrefix}-${ts}.pdf`);

      } catch(err) {
        console.error("Export error:", err);
        const isFa = document.getElementById("app").dir === "rtl";
        alert(isFa ? "خطا در خروجی PDF. کنسول را بررسی کنید." : "PDF export failed. Check the console.");
      }
    }

    // ─── HELPERS ─────────────────────────────────────────────────────────────────
    function formatNumber(n) {
      if (n === null || n === undefined || n === "") return "—";
      return Number(n).toLocaleString(undefined, { maximumFractionDigits:2 });
    }
    function escapeHtml(s) {
      if (!s && s !== 0) return "";
      return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m]);
    }

    // ─── INIT ────────────────────────────────────────────────────────────────────
    document.addEventListener("DOMContentLoaded", () => {
      document.getElementById("calc-btn-fa").onclick = calculate;
      document.getElementById("calc-btn-en").onclick = calculate;
      document.getElementById("btn-fa").onclick = () => setLang("fa");
      document.getElementById("btn-en").onclick = () => setLang("en");
      document.getElementById("custom-panel-w").addEventListener("input", () => {
        if (document.getElementById("results").style.display !== "none") calculate();
      });
      ["autonomy-days","dod","system-voltage","custom-inverter-w","custom-battery-kwh"].forEach(id => {
        document.getElementById(id).addEventListener("input", () => {
          if (document.getElementById("results").style.display !== "none") calculate();
        });
        document.getElementById(id).addEventListener("change", () => {
          if (document.getElementById("results").style.display !== "none") calculate();
        });
      });

      document.getElementById("export-btn").addEventListener("click", () => {
        exportChartToPDF({
          pageFormat: document.getElementById("pdf-size-select").value || "a4",
          orientation: "portrait",
          backgroundMode: document.getElementById("pdf-bg-select").value || "gradient"
        });
      });
      document.getElementById("export-btn-land").addEventListener("click", () => {
        exportChartToPDF({
          pageFormat: document.getElementById("pdf-size-select").value || "a4",
          orientation: "landscape",
          backgroundMode: document.getElementById("pdf-bg-select").value || "gradient"
        });
      });

      setLang("fa");
      updateChart([], []);
    });
  </script>
</body>
</html>
<?php 
$conn = new mysqli("localhost", "root", "", "login");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $conn->query("UPDATE students SET isActive=0 WHERE id = '$id'");
    header("Location: manage_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students | LibFlow AI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style2.css">
    <style>
        /* ── Search highlight ── */
        .highlight {
            background: rgba(99, 102, 241, 0.15);
            border-radius: 3px;
            padding: 0 2px;
            font-weight: 700;
            color: var(--primary);
        }

        /* ── No-results row ── */
        #no-results {
            display: none;
        }
        #no-results td {
            text-align: center;
            padding: 40px;
            color: var(--text-muted);
            font-size: 14px;
        }

        /* ── Search input styles ── */
        .search-wrapper {
            position: relative;
            flex: 1;
        }
        .search-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
        }
        .search-wrapper input {
            width: 100%;
            padding: 12px 40px 12px 45px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            outline: none;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-wrapper input:focus {
            border-color: var(--primary, #6366f1);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        /* ── Clear button inside search ── */
        #clear-search {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 13px;
            display: none;
            padding: 4px 6px;
            border-radius: 4px;
            transition: color 0.15s;
        }
        #clear-search:hover { color: #ef4444; }

        /* ── Search count badge ── */
        #search-count {
            font-family: monospace;
            font-size: 12px;
            color: var(--text-muted);
            white-space: nowrap;
            padding: 6px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            display: none;
        }

        /* ── Filter chips ── */
        .filter-chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            padding: 0 0 4px;
        }
        .chip {
            padding: 5px 14px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: var(--text-muted);
            transition: all 0.15s;
            user-select: none;
        }
        .chip:hover, .chip.active {
            background: var(--primary, #6366f1);
            border-color: var(--primary, #6366f1);
            color: #fff;
        }

        /* ── Table row hidden when filtered out ── */
        tbody tr.hidden-row { display: none; }

        /* ── Fade-in animation for visible rows ── */
        tbody tr:not(.hidden-row) { animation: rowIn 0.18s ease both; }
        @keyframes rowIn {
            from { opacity: 0; transform: translateY(4px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2 style="color: var(--primary); margin-bottom: 40px; padding-left: 20px;">
            <i class="fas fa-book-reader"></i> LibAdmin
        </h2>
        <div class="nav-container">
            <a href="admin.php"            class="nav-btn"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="manage_students.php"  class="nav-btn active"><i class="fas fa-users"></i> Students</a>
            <a href="activity_logs.php"    class="nav-btn"><i class="fas fa-history"></i> Logs</a>
            <a href="analytics.php"        class="nav-btn"><i class="fas fa-brain"></i> Analysis</a>
            <hr style="border:0;border-top:1px solid #eee;margin:20px 10px;">
            <a href="logout.php" class="nav-btn" style="color:#ff4d4d;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- MAIN -->
    <div class="main-content">

        

        <!-- Header -->
        <header style="display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;">
            <div>
                <h1 style="font-weight:800;">Student Registry</h1>
                <p style="color:var(--text-muted);">View and manage authorized library users.</p>
            </div>
            <div style="display:flex; gap:10px;">
            <!-- New Import Button -->
            <button class="btn-modern" style="background:#6c757d; border:none; cursor:pointer;" onclick="document.getElementById('fileImport').click()">
                <i class="fas fa-file-import"></i> Import File
            </button>
            <a href="admin.php" class="btn-modern" style="text-decoration:none;">
                <i class="fas fa-plus"></i> New Student
            </a>
            </div>
        </header>

        <!-- Search bar card -->
        <div class="card" style="padding:20px;display:flex;flex-direction:column;gap:12px;">

            <!-- Search row -->
            <div style="display:flex;gap:12px;align-items:center;">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input
                        type="text"
                        id="studentSearch"
                        placeholder="Search by name, ID, or email…"
                        onkeyup="filterStudents()"
                        autocomplete="off"
                        spellcheck="false"
                    >
                    <button id="clear-search" onclick="clearSearch()" title="Clear search">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <span id="search-count"></span>
            </div>

            <!-- Filter chips -->
            <div class="filter-chips" id="filter-chips">
                <span class="chip active" onclick="setChip(this, 'all')">All</span>
                <span class="chip" onclick="setChip(this, 'name')">Name</span>
                <span class="chip" onclick="setChip(this, 'id')">Student ID</span>
                <span class="chip" onclick="setChip(this, 'email')">Email</span>
            </div>
        </div>

        <!-- Table card -->
        <div class="card">
            <table id="studentTable">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>   
                </thead>
                <tbody id="studentTableBody">
                    <?php
                    $sql    = "SELECT * FROM students WHERE isActive=1 ORDER BY id DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Store searchable values in data attributes
                            $sid   = htmlspecialchars($row['student_id']);
                            $name  = htmlspecialchars($row['fullname']);
                            $email = htmlspecialchars($row['email']);
                            $id    = $row['id'];
                            // ... inside your while loop ...
                            echo "
                            <tr data-id='$sid' data-name='$name' data-email='$email'>
                                <td class='cell-id' style='font-family:monospace;font-weight:700;color:var(--primary);'>$sid</td>
                                <td class='cell-name' style='font-weight:600;'>$name</td>
                                <td class='cell-email' style='color:var(--text-muted);'>$email</td>
                                <td style='text-align:right;'>
                                    <div style='display:flex;gap:8px;justify-content:flex-end;'>
                                        <button onclick=\"showQR('$sid', '$name')\" 
                                                style='border:none;background:rgba(108, 99, 255, 0.1);color:var(--primary);padding:8px 12px;border-radius:8px;cursor:pointer;'
                                                title='View QR Pass'>
                                            <i class='fas fa-qrcode'></i>
                                        </button>

                                        <button class='btn-edit' style='border:none;background:#eef2ff;color:var(--primary);padding:8px 12px;border-radius:8px;cursor:pointer;'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        
                                        <a href='manage_students.php?delete_id=$id'
                                        onclick=\"return confirm('Delete this student?')\"
                                        style='background:#fef2f2;color:#ef4444;padding:8px 12px;border-radius:8px;text-decoration:none;display:inline-flex;align-items:center;'>
                                            <i class='fas fa-trash'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>";
                        }
                    }
                    ?>
                    <!-- No-results row (shown by JS when nothing matches) -->
                    <tr id="no-results">
                        <td colspan="4">
                            <i class="fas fa-search" style="font-size:28px;margin-bottom:10px;display:block;opacity:0.3;"></i>
                            No students match <strong id="no-results-query"></strong>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div id="qrModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; justify-content:center; align-items:center;">
                <div id="printableArea" style="background:white; padding:30px; border-radius:20px; width:320px; text-align:center; position:relative;">
                    <h2 style="color:var(--primary); margin-bottom:5px;">Student Pass</h2>
                    <p style="font-size:12px; color:#666; margin-bottom:15px;">Official Library QR Code</p>
                    
                    <div style="background:#f9f9f9; padding:15px; border-radius:15px; border:2px dashed #ddd; margin-bottom:15px;">
                        <img id="qrTarget" src="" style="width:200px; height:200px; display:block; margin:0 auto;">
                    </div>
                    
                    <h3 id="targetName" style="margin:5px 0;"></h3>
                    <p id="targetID" style="font-family:monospace; font-weight:700; color:var(--primary);"></p>
                    
                    <div style="margin-top:20px; display:flex; gap:10px;" class="no-print">
                        <button onclick="window.print()" style="flex:1; background:var(--primary); color:white; border:none; padding:10px; border-radius:8px; cursor:pointer;">
                            <i class="fas fa-print"></i> Print
                        </button>
                        <button onclick="document.getElementById('qrModal').style.display='none'" style="flex:1; background:#eee; border:none; padding:10px; border-radius:8px; cursor:pointer;">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

            <style>
            /* CSS to handle clean printing */
            @media print {
                body * { visibility: hidden; }
                #printableArea, #printableArea * { visibility: visible; }
                #printableArea { position: fixed; left: 0; top: 0; width: 100%; height: auto; border: none; box-shadow: none; }
                .no-print { display: none !important; }
            }

            
            </style>

            <!-- Total count footer -->
            <div style="padding:12px 0 0;border-top:1px solid #e2e8f0;margin-top:12px;font-size:13px;color:var(--text-muted);">
                Showing <strong id="visible-count">0</strong> of
                <strong id="total-count">0</strong> students
            </div>
        </div>

    </div><!-- /main-content -->

    <script>





    function showQR(sid, name) {
    const modal = document.getElementById('qrModal');
    const img = document.getElementById('qrTarget');
    const nameLabel = document.getElementById('targetName');
    const idLabel = document.getElementById('targetID');

    // Generate QR Code URL
    const qrUrl = `qrcodes/${sid}.png`; // Assuming you have pre-generated QR codes stored in this directory
    
    img.src = qrUrl;
    nameLabel.textContent = name;
    idLabel.textContent = sid;
    
    modal.style.display = 'flex';
}
    /* ══════════════════════════════════════════════════
       SmartLib — Live Search (onkeyup)
    ══════════════════════════════════════════════════ */

    // Which column(s) to search: 'all' | 'name' | 'id' | 'email'
    let searchField = 'all';

    // Cache DOM references
    const input       = document.getElementById('studentSearch');
    const clearBtn    = document.getElementById('clear-search');
    const countBadge  = document.getElementById('search-count');
    const visibleEl   = document.getElementById('visible-count');
    const totalEl     = document.getElementById('total-count');
    const noResults   = document.getElementById('no-results');
    const noResQuery  = document.getElementById('no-results-query');
    const tbody       = document.getElementById('studentTableBody');

    // All data rows (exclude the no-results helper row)
    const allRows = () => [...tbody.querySelectorAll('tr:not(#no-results)')];

    // Init total count on page load
    window.addEventListener('DOMContentLoaded', () => {
        const total = allRows().length;
        totalEl.textContent   = total;
        visibleEl.textContent = total;
    });

    /* ── Main filter function — called onkeyup ── */
    function filterStudents() {
        const raw   = input.value;
        const query = raw.trim().toLowerCase();

        // Show / hide clear button
        clearBtn.style.display = raw.length ? 'block' : 'none';

        let visible = 0;

        allRows().forEach(row => {
            const sid   = (row.dataset.id    || '').toLowerCase();
            const name  = (row.dataset.name  || '').toLowerCase();
            const email = (row.dataset.email || '').toLowerCase();

            // Decide which fields to check based on chip selection
            let haystack = '';
            if      (searchField === 'id')    haystack = sid;
            else if (searchField === 'name')  haystack = name;
            else if (searchField === 'email') haystack = email;
            else                              haystack = sid + ' ' + name + ' ' + email;

            const matches = !query || haystack.includes(query);

            if (matches) {
                row.classList.remove('hidden-row');
                visible++;
                // Highlight matching text in visible cells
                highlightCells(row, query);
            } else {
                row.classList.add('hidden-row');
            }
        });

        // Update counters
        visibleEl.textContent = visible;

        // Show/hide no-results row
        noResults.style.display = (visible === 0 && query) ? 'table-row' : 'none';
        if (visible === 0) noResQuery.textContent = '"' + raw + '"';

        // Show/hide count badge
        if (query) {
            countBadge.style.display = 'inline';
            countBadge.textContent   = visible + ' result' + (visible !== 1 ? 's' : '');
        } else {
            countBadge.style.display = 'none';
        }
    }

    /* ── Highlight matched text inside a row's cells ── */
    function highlightCells(row, query) {
        const cellMap = {
            '.cell-id':    ['all','id'],
            '.cell-name':  ['all','name'],
            '.cell-email': ['all','email'],
        };

        Object.entries(cellMap).forEach(([sel, fields]) => {
            const cell = row.querySelector(sel);
            if (!cell) return;

            // Restore original text first
            if (cell.dataset.original === undefined) {
                cell.dataset.original = cell.textContent;
            }
            const original = cell.dataset.original;

            if (!query || !fields.includes(searchField)) {
                cell.innerHTML = original;
                return;
            }

            // Escape special regex chars in query
            const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex   = new RegExp('(' + escaped + ')', 'gi');
            cell.innerHTML = original.replace(regex, '<mark class="highlight">$1</mark>');
        });
    }

    /* ── Clear search ── */
    function clearSearch() {
        input.value = '';
        clearBtn.style.display = 'none';
        filterStudents();
        input.focus();
    }

    /* ── Filter chip selection ── */
    function setChip(el, field) {
        document.querySelectorAll('#filter-chips .chip').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        searchField = field;
        // Update placeholder
        const placeholders = {
            all:   'Search by name, ID, or email…',
            name:  'Search by name…',
            id:    'Search by student ID…',
            email: 'Search by email…',
        };
        input.placeholder = placeholders[field];
        // Re-filter with current query
        filterStudents();
    }

    /* ── Keyboard shortcuts ── */
    document.addEventListener('keydown', e => {
        // Ctrl+K or / to focus search
        if ((e.ctrlKey && e.key === 'k') || (e.key === '/' && document.activeElement !== input)) {
            e.preventDefault();
            input.focus();
            input.select();
        }
        // Escape to clear
        if (e.key === 'Escape' && document.activeElement === input) {
            clearSearch();
        }
    });
    </script>

</body>
</html>
<?php $conn->close(); ?>
<?php
<<<<<<< HEAD
$pageTitle = "Report Generation";
$activePage = "report";
=======
$pageTitle = "Committee Management";
$activePage = "committee";

$committees = [
    [
        'name' => 'ETHICS AND GRIEVANCE COMMITTEE',
        'users' => [
            [
                'name' => '1. Yolanda Pinzon',
                'member_id' => '25475',
                'duty_hours' => '52.00',
                'rate' => '62.50',
                'transpo_allowance' => '3,250.00',
                'less' => [
                    'RCBC' => '250.00',
                    'NORF' => '200.00',
                    'Rice' => '310.00'
                ],
                'regular_savings_deposit' => '2,490.00'
            ],
            [
                'name' => '2. Randolph Macato',
                'member_id' => '3034',
                'duty_hours' => '52.00',
                'rate' => '62.50',
                'transpo_allowance' => '3,250.00',
                'less' => [
                    'RCBC' => '500.00',
                    'NORF' => '200.00',
                    'Rice' => '310.00'
                ],
                'regular_savings_deposit' => '2,240.00'
            ],
            [
                'name' => '3. Virginia Danganan',
                'member_id' => '013890',
                'duty_hours' => '52.00',
                'rate' => '62.50',
                'transpo_allowance' => '3,250.00',
                'less' => [
                    'RCBC' => '1,000.00',
                    'NORF' => '200.00',
                    'Rice' => '310.00'
                ],
                'regular_savings_deposit' => '1,740.00'
            ]
        ]
    ],
    [
        'name' => 'MEDIATION AND CONCILIATION COMMITTEE',
        'users' => [
            [
                'name' => '1. Ferdinand Santos',
                'member_id' => '8622',
                'duty_hours' => '52.00',
                'rate' => '62.50',
                'transpo_allowance' => '3,250.00',
                'less' => [
                    'RCBC' => '1,500.00',
                    'NORF' => '200.00',
                    'Rice' => '310.00'
                ],
                'regular_savings_deposit' => '1,240.00'
            ],
            [
                'name' => '2. Loreta Gualberto',
                'member_id' => '742399',
                'duty_hours' => '52.00',
                'rate' => '62.50',
                'transpo_allowance' => '3,250.00',
                'less' => [
                    'RCBC' => '500.00',
                    'NORF' => '200.00',
                    'Rice' => '310.00'
                ],
                'regular_savings_deposit' => '2,240.00'
            ],
            [
                'name' => '3. Mary Jane B. Merioles',
                'member_id' => '23306',
                'duty_hours' => '52.00',
                'rate' => '62.50',
                'transpo_allowance' => '3,250.00',
                'less' => [
                    'RCBC' => '500.00',
                    'NORF' => '200.00',
                    'Rice' => '310.00'
                ],
                'regular_savings_deposit' => '2,240.00'
            ]
        ]
    ],
    [
        'name' => 'GENDER AND DEVELOPMENT COMMITTEE',
        'users' => [
            [
                'name' => '1. Jose Ferdinand S. Mendoza',
                'member_id' => '1958',
                'duty_hours' => '52.00',
                'rate' => '62.50',
                'transpo_allowance' => '3,250.00',
                'less' => [
                    'RCBC' => '1,500.00',
                    'NORF' => '200.00',
                    'Rice' => '310.00'
                ],
                'regular_savings_deposit' => '1,240.00'
            ],
            [
                'name' => '2. Ma Helen Lamo',
                'member_id' => '3102',
                'duty_hours' => '52.00',
                'rate' => '62.50',
                'transpo_allowance' => '3,250.00',
                'less' => [
                    'RCBC' => '0.00',
                    'NORF' => '200.00',
                    'Rice' => '310.00'
                ],
                'regular_savings_deposit' => '2,740.00'
            ],
            [
                'name' => '3. Elisa Ulbata',
                'member_id' => '157309',
                'duty_hours' => '52.00',
                'rate' => '62.50',
                'transpo_allowance' => '3,250.00',
                'less' => [
                    'RCBC' => '1,000.00',
                    'NORF' => '200.00',
                    'Rice' => '310.00'
                ],
                'regular_savings_deposit' => '1,740.00'
            ]
        ]
    ]
];
>>>>>>> 8c885949b4ce84294e4980923f686a94029e28f7

include 'header.php';

if (file_exists('db_connectio.php')) {
    include 'db_connection.php';
}
?>

<div class="main-content-container">
    <div class="card card-custom">
        <div class="card-body">
            <form action="committee_management.php" method="post" class="report-form">

               <!-- Date Range Row -->
<div class="form-row">
        <div class="form-group">
            <label for="dateFrom">Date From</label>
            <input type="date" class="form-control date-input" id="dateFrom" name="dateFrom" required>
        </div>
        <div class="form-group">
            <label for="dateTo">Date To</label>
            <input type="date" class="form-control date-input" id="dateTo" name="dateTo" required>
        </div>
    </div>

    <!-- PB#/Member ID -->
    <div class="form-group">
        <label>PB#/Member ID</label>
        <input type="text" class="form-control" name="memberId" placeholder="Enter PB#/Member ID" required>
    </div>

    <!-- Report Selection -->
    <div class="form-group">
        <label>Report</label>
        <select class="form-control" name="reportType" required>
            <option value="" selected disabled>Select Report</option>
            <option value="monthly">Monthly Summary</option>
            <option value="quarterly">Quarterly Analysis</option>
            <option value="annual">Annual Report</option>
            <option value="custom">Custom Report</option>
        </select>
    </div>

    <!-- Generate Button -->
    <button type="submit" class="generate-btn">
        <i class="fas fa-file-export"></i> Generate
    </button>
</form>
        </div>
    </div>
</div>
<<<<<<< HEAD

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="assets/css/report.css">
=======
 
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const moveToTopButton = document.getElementById('moveToTopButton');
    const tbody = document.querySelector('#usersTable tbody');
    
    // Search functionality
    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = tbody.querySelectorAll('tr');
        
        rows.forEach(row => {
            if (row.classList.contains('committee-header')) {
                // Always show committee headers
                row.style.display = '';
                return;
            }
            
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchTerm) ? '' : 'none';
        });
    }
    
    // Move last row to top functionality
    function moveLastToTop() {
        const rows = tbody.querySelectorAll('tr');
        if (rows.length > 1) {
            const lastRow = rows[rows.length - 1];
            tbody.insertBefore(lastRow, rows[0]);
        }
    }
    
    // Event listeners
    searchInput.addEventListener('keyup', filterUsers);
    searchButton.addEventListener('click', filterUsers);
    moveToTopButton.addEventListener('click', moveLastToTop);
});
</script>
>>>>>>> 8c885949b4ce84294e4980923f686a94029e28f7

<?php include 'footer.php'; ?>
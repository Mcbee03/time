<?php
$pageTitle = "Report Management";
<<<<<<< HEAD

$pageTitle = "Reports";

$pageTitle = "Committee Management";

=======
>>>>>>> 02fe2482d41500ea0a254c4dcf41f272cd9686ee
$activePage = "report";

// Sample data structured to match the image
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

include '../includes/header.php';
?>

<div class="main-content-container">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0" id="usersTable">
                    <thead class="green-header">
                        <tr>
                            <th rowspan="2">Name</th>
                            <th rowspan="2">Member ID</th>
                            <th rowspan="2">Duty Hours</th>
                            <th rowspan="2">Rate</th>
                            <th rowspan="2">Transpo Allowance</th>
                            <th colspan="3" class="text-center">Less</th>
                            <th rowspan="2">Regular Savings Deposit</th>
                        </tr>
                        <tr>
                            <th>RCBC</th>
                            <th>NORF</th>
                            <th>Rice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($committees as $committee): ?>
                            <tr class="committee-header bg-light">
                                <td colspan="9" class="font-weight-bold"><?= htmlspecialchars($committee['name']) ?></td>
                            </tr>
                            <?php foreach ($committee['users'] as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['member_id']) ?></td>
                                    <td><?= htmlspecialchars($user['duty_hours']) ?></td>
                                    <td><?= htmlspecialchars($user['rate']) ?></td>
                                    <td><?= htmlspecialchars($user['transpo_allowance']) ?></td>
                                    <td><?= htmlspecialchars($user['less']['RCBC']) ?></td>
                                    <td><?= htmlspecialchars($user['less']['NORF']) ?></td>
                                    <td><?= htmlspecialchars($user['less']['Rice']) ?></td>
                                    <td><?= htmlspecialchars($user['regular_savings_deposit']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const moveToTopButton = document.getElementById('moveToTopButton');
    const tbody = document.querySelector('#usersTable tbody');

    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = tbody.querySelectorAll('tr');

        rows.forEach(row => {
            if (row.classList.contains('committee-header')) {
                row.style.display = '';
                return;
            }

            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchTerm) ? '' : 'none';
        });
    }

    function moveLastToTop() {
        const rows = Array.from(tbody.querySelectorAll('tr')).reverse();
        for (const row of rows) {
            if (!row.classList.contains('committee-header')) {
                tbody.insertBefore(row, tbody.firstChild);
                break;
            }
        }
    }

    if (searchInput && searchButton) {
        searchInput.addEventListener('keyup', filterUsers);
        searchButton.addEventListener('click', filterUsers);
    }
    if (moveToTopButton) {
        moveToTopButton.addEventListener('click', moveLastToTop);
    }
});
</script>

<?php include 'footer.php'; ?>

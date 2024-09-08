<?php
// Include the database connection file
include 'includes/db.php';

// Pagination settings
$limit = 10; // Number of entries per page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch total number of records
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM people");
$totalRecords = $totalResult->fetch_assoc()['total'];

// Fetch records for the current page
$sql = "SELECT * FROM people ORDER BY id LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .table th,
        .table td {
            vertical-align: middle;
        }

        .btn-group-sm>.btn,
        .btn-sm {
            padding: .25rem .5rem;
        }

        @media (max-width: 767px) {
            .table-responsive {
                overflow-x: auto;
            }

            .table th,
            .table td {
                white-space: nowrap;
            }

            .btn-sm {
                padding: .2rem .4rem;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 575px) {
            .navbar-brand {
                font-size: 1rem;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">ANMOUL INFOMATICS PRIVATE LIMITED</a>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="text-3xl font-semibold">People Details</h2>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPersonModal">
                    <i class="fas fa-plus"></i> Add New Person
                </button>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by name">
                            <button class="btn btn-secondary " id="clearSearch" type="button">
                                <i class="fas fa-times"></i> </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0"><strong>Total Records:</strong> <span
                                id="totalRecords"><?php echo $totalRecords; ?></span></p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="peopleTable">
                            <?php
                            if ($result->num_rows > 0) {
                                $serial_number = $offset + 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $serial_number . "</td>";
                                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['email_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['contact_number']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['state']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['city']) . "</td>";
                                    echo "<td>
                                            <div class='btn-group btn-group-sm' role='group'>
                                                <button class='btn btn-warning edit-btn' data-id='" . htmlspecialchars($row['id']) . "'>Edit</button>
                                                <button class='btn btn-danger delete-btn' data-id='" . htmlspecialchars($row['id']) . "'>Delete</button>
                                            </div>
                                          </td>";
                                    echo "</tr>";
                                    $serial_number++;
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center flex-wrap" id="pagination">
                        <?php
                        $total_pages = ceil($totalRecords / $limit);

                        for ($i = 1; $i <= $total_pages; $i++) {
                            $active = ($i == $page) ? 'active' : '';
                            echo "<li class='page-item $active'><a class='page-link' href='index.php?page=$i'>$i</a></li>";
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Add Person Modal -->
    <div class="modal fade" id="addPersonModal" tabindex="-1" aria-labelledby="addPersonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addPersonModalLabel">Add New Person</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPersonForm" method="POST" action="views/add.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_id" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_id" name="email_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contact_number" name="contact_number"
                                pattern="\+?\d{10,15}" title="Please enter a valid contact number." required>
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select" id="state" name="state" required>
                                <option value="">Select State</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <select class="form-select" id="city" name="city" required>
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Add Person</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Person Modal -->
    <div class="modal fade" id="editPersonModal" tabindex="-1" aria-labelledby="editPersonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editPersonModalLabel">Edit Person</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPersonForm" method="POST">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email_id" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email_id" name="email_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_contact_number" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="edit_contact_number" name="contact_number">
                        </div>
                        <div class="mb-3">
                            <label for="edit_state" class="form-label">State</label>
                            <select class="form-select" id="edit_state" name="state" required>
                                <option value="">Select State</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_city" class="form-label">City</label>
                            <select class="form-select" id="edit_city" name="city" required>
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Person</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
        $(document).ready(function () {
            const apiKey = "THRWd1oxT1pKcmRKVEtXR2Y1WGdxakFiNzhSSHZOUW81VUJXWGlXMQ==";

            function populateStates() {
                $.ajax({
                    url: "https://api.countrystatecity.in/v1/countries/IN/states",
                    method: "GET",
                    headers: {
                        "X-CSCAPI-KEY": apiKey
                    },
                }).done(function (response) {
                    $('#state, #edit_state').empty().append('<option value="">Select State</option>');
                    response.sort((a, b) => a.name.localeCompare(b.name));
                    response.forEach(function (state) {
                        $('#state, #edit_state').append(`<option value="${state.name}" data-code="${state.iso2}">${state.name}</option>`);
                    });
                }).fail(function () {
                    console.error('Failed to fetch states.');
                });
            }


            function populateCities(stateCode, citySelector) {
                $.ajax({
                    url: `https://api.countrystatecity.in/v1/countries/IN/states/${stateCode}/cities`,
                    method: "GET",
                    headers: {
                        "X-CSCAPI-KEY": apiKey
                    },
                }).done(function (response) {
                    $(citySelector).empty().append('<option value="">Select City</option>');
                    response.sort((a, b) => a.name.localeCompare(b.name));
                    response.forEach(function (city) {
                        $(citySelector).append(`<option value="${city.name}">${city.name}</option>`);
                    });
                }).fail(function () {
                    console.error('Failed to fetch cities.');
                });
            }

            populateStates();
            $('#state, #edit_state').on('change', function () {
                const stateCode = $(this).find('option:selected').data('code');
                const citySelector = $(this).attr('id') === 'state' ? '#city' : '#edit_city';
                if (stateCode) {
                    populateCities(stateCode, citySelector);
                } else {
                    $(citySelector).empty().append('<option value="">Select City</option>');
                }
            });
            $('#addPersonForm').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: 'views/add.php',
                    type: 'POST',
                    data: formData,
                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Person added successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to add person.', 'error');
                    }
                });
            });

            $('#editPersonForm').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: 'views/update.php',
                    type: 'POST',
                    data: formData,
                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Person updated successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to update person.', 'error');
                    }
                });
            });

            const searchInput = $('#searchInput');
    const clearButton = $('#clearSearch');

    // Function to toggle clear button visibility
    function toggleClearButton() {
        if (searchInput.val().length > 0) {
            clearButton.show();
        } else {
            clearButton.hide();
        }
    }

    // Initially hide the clear button
    clearButton.hide();

    // Debounced search function
    const debouncedSearch = debounce(function() {
        var searchTerm = searchInput.val();
        $.ajax({
            url: 'views/search.php',
            type: 'GET',
            data: { search: searchTerm },
            success: function (response) {
                $('#peopleTable').html(response);
            },
            error: function () {
                console.error('Failed to fetch search results.');
            }
        });
    }, 300); // 300ms delay

    // Search input event handler
    searchInput.on('input', function () {
        toggleClearButton();
        debouncedSearch();
    });

    // Clear search input and results
    clearButton.on('click', function () {
        searchInput.val('');
        toggleClearButton();
        $.ajax({
            url: 'views/search.php',
            type: 'GET',
            data: { search: '' },
            success: function (response) {
                $('#peopleTable').html(response);
            },
            error: function () {
                console.error('Failed to fetch search results.');
            }
        });
    });
            $(document).on('click', '.edit-btn', function () {
                const id = $(this).data('id');
                $.ajax({
                    url: 'views/edit.php',
                    type: 'GET',
                    data: { id: id },
                    success: function (response) {
                        const data = JSON.parse(response);
                        $('#edit_id').val(data.id);
                        $('#edit_name').val(data.name);
                        $('#edit_email_id').val(data.email_id);
                        $('#edit_contact_number').val(data.contact_number);
                        $('#edit_state').val(data.state).trigger('change');
                        setTimeout(() => {
                            $('#edit_city').val(data.city);
                        }, 500);
                        $('#editPersonModal').modal('show');
                    },
                    error: function () {
                        console.error('Failed to fetch person details.');
                    }
                });
            });

            $(document).on('click', '.delete-btn', function () {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'views/delete.php',
                            type: 'POST',
                            data: { id: id },
                            success: function () {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Person has been deleted.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function () {
                                Swal.fire('Error', 'Failed to delete person.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
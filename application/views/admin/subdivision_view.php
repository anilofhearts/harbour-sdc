<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token-name" content="<?= $this->security->get_csrf_token_name(); ?>">
    <meta name="csrf-token" content="<?= $this->security->get_csrf_hash(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subdivision Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Sub Divisions</h5>
                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Root</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($subdivision)) {
                                $i = 1;
                                foreach ($subdivision as $data) {
                                    echo "<tr>
                                        <td>$i</td>
                                        <td>$data->subdivision</td>
                                        <td>$data->circle &raquo; $data->division &raquo; $data->subdivision</td>
                                        <td><button class='btn btn-sm btn-primary' onclick=\"view_data('subdivision','$data->subdivision_id','$data->subdivision')\">Edit</button></td>
                                        <td>
                                                <button 
                                                    class='btn btn-sm btn-danger' 
                                                    data-table='subdivision' 
                                                    data-id='$data->subdivision_id' 
                                                    onclick='handleDelete(this)''>
                                                    Delete
                                                </button>

                                            </td>
                                    </tr>";
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="edit_student" tabindex="-1" aria-labelledby="editStudentModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Content will be dynamically loaded here -->
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootbox (for prompt dialogs) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

    <script>
        function handleDelete(button) {
    const table = button.getAttribute('data-table');
    const id = button.getAttribute('data-id');
    const csrfTokenName = '<?= $this->security->get_csrf_token_name(); ?>';
    const csrfTokenValue = '<?= $this->security->get_csrf_hash(); ?>';

    remove_branch(table, id, csrfTokenName, csrfTokenValue);
}

        // Remove branch function
    function remove_branch(table, id, csrfTokenName, csrfTokenValue) {
    bootbox.prompt({
        title: "Remove this item. Type 'delete' to confirm:",
        centerVertical: true,
        callback: function (result) {
            if (result === 'delete') {
                // Construct form data
                const formData = new URLSearchParams();
                formData.append('table', table);
                formData.append('id', id);
                formData.append(csrfTokenName, csrfTokenValue); // Add CSRF token to payload

                fetch("<?= site_url('remove_node'); ?>", {
    method: "POST",
    headers: {
        "Content-Type": "application/x-www-form-urlencoded",
    },
    body: formData.toString(),
})
    .then((response) => response.text()) // Fetch raw response
    .then((text) => {
        try {
            const data = JSON.parse(text.trim()); // Parse JSON and trim whitespace
            if (data.success) {
                alert(data.message); // Success message
                location.reload();   // Reload page
            } else {
                alert(data.message || "An error occurred."); // Error message
            }
        } catch (error) {
            console.error("JSON Parse Error:", error, "Response Text:", text);
            alert("Failed to process the response from the server.");
        }
    })
    .catch((error) => {
        console.error("Fetch Error:", error);
        alert("Failed to delete. Please try again.");
    });
            }
        }
    });
}

        function view_data(table, id, name) {
            fetch(`view_branch?id=${id}&table=${table}&name=${name}`)
                .then(response => response.text())
                .then(result => {
                    document.querySelector("#edit_student .modal-content").innerHTML = result;
                    const modal = new bootstrap.Modal(document.getElementById("edit_student"));
                    modal.show();
                })
                .catch(error => console.error("Error loading data:", error));
        }
    </script>
</body>
</html>

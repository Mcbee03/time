<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="delete_allowance.php" id="deleteForm">
                <input type="hidden" name="delete_id" id="delete_id" value="">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #2b7d62; color: white;">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the allowance for <span id="delete_allowance_name" class="font-weight-bold"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn" style="background-color: #2b7d62; color: white;">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>